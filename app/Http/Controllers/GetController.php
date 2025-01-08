<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Get;
use Illuminate\Http\Request;

class GetController extends Controller
{
    /**
     * وظيفة الاشتراك في كورس.
     */
    public function subscribe(Request $request)
    {

        $massage = [
            'user_id.required' => 'يجب تحديد المستخدم.',
            'user_id.exists' => 'المستخدم غير موجود.',
            'courses_id.required' => 'يجب تحديد الكورس.',
            'courses_id.exists' => 'الكورس غير موجود.',
        ];

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'courses_id' => 'required|exists:courses,id',
        ], $massage);

        // التحقق من الاشتراك الحالي
        $existingSubscription = Get::where('user_id', $request->user_id)
                                   ->where('courses_id', $request->courses_id)
                                   ->first();

        if ($existingSubscription) {
            return response()->json(['message' => 'المستخدم مشترك بالفعل في هذا الكورس.'], 409);
        }

        // إنشاء اشتراك جديد
        $subscription = Get::create([
            'user_id' => $request->user_id,
            'courses_id' => $request->courses_id,
        ]);

        return response()->json(['message' => 'تم الاشتراك في الكورس بنجاح.', 'subscription' => $subscription], 201);
    }

    /**
     * وظيفة إلغاء الاشتراك من كورس.
     */
    public function unsubscribe(Request $request)
    {
        $massage = [
            'user_id.required' => 'يجب تحديد المستخدم.',
            'user_id.exists' => 'المستخدم غير موجود.',
            'courses_id.required' => 'يجب تحديد الكورس.',
            'courses_id.exists' => 'الكورس غير موجود.',
        ];

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'courses_id' => 'required|exists:courses,id',
        ], $massage);

        // البحث عن الاشتراك
        $subscription = Get::where('user_id', $request->user_id)
                           ->where('courses_id', $request->courses_id)
                           ->first();

        if (!$subscription) {
            return response()->json(['message' => 'المستخدم غير مشترك في هذا الكورس.'], 404);
        }

        // حذف الاشتراك
        $subscription->delete();

        return response()->json(['message' => 'تم إلغاء الاشتراك من الكورس بنجاح.']);
    }

    /**
     * وظيفة عرض قائمة الكورسات التي اشترك بها المستخدم.
     */
    public function getUserCourses($user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return response()->json(['message' => 'المستخدم غير موجود.'], 404);
        }

        // استرجاع الكورسات التي اشترك فيها المستخدم
        $courses = $user->subscriptions()->with('course')->get();

        return response()->json(['message' => 'قائمة الكورسات المشترك بها المستخدم.', 'courses' => $courses]);
    }
}

