<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Course;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class CourseController extends Controller
{
    public function index(Request $request)
    {
        // قراءة عدد العناصر في الصفحة من الطلب مع قيمة افتراضية
        $perPage = $request->get('per_page', 10); // عدد العناصر الافتراضي 10
        $page = $request->get('page', 1); // الصفحة الافتراضية هي 1

        // جلب البيانات مع تحديد الإزاحة وعدد العناصر
        $query = Course::query();
        $total = $query->count(); // العدد الإجمالي للعناصر
        $data = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        // حساب العدد الإجمالي للصفحات
        $totalPages = ceil($total / $perPage);

        // بناء الاستجابة
        return response()->json([
            'data' => $data,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => $totalPages,
        ]);
    }

    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'الاسم مطلوب.',
            'name.string' => 'يجب أن يكون الاسم نصًا.',
            'name.max' => 'يجب ألا يزيد الاسم عن 255 حرفًا.',
            'contant.required' => 'المحتوى مطلوب.',
            'description.required' => 'الوصف مطلوب.',
            'description.string' => 'يجب أن يكون الوصف نصًا.',
            'price.required' => 'السعر مطلوب.',
            'price.numeric' => 'يجب أن يكون السعر رقمًا.',
            'time.required' => 'مدة الدراسة مطلوبة.',
            'time.integer' => 'مدة الدراسة يجب أن تكون عددًا صحيحًا.',
            'time.min' => 'مدة الدراسة يجب أن تكون على الأقل شهرًا واحدًا.',
            'time.max' => 'مدة الدراسة لا يمكن أن تتجاوز 6 شهور .',
            'image.image' => 'يجب أن تكون الصورة من نوع صورة.',
            'image.mimes' => 'يجب أن تكون الصورة بصيغة jpeg, png, jpg, gif.',
            'image.max' => 'يجب ألا يزيد حجم الصورة عن 2 ميجابايت.',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contant' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'time' => 'required|integer|min:1|max:6',
            'user_add_id' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 422);
        };


        // التحقق من وجود صورة مرفوعة
        if ($request->hasFile('image')) {
            // رفع الصورة وتخزينها في مجلد التخزين
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images'), $imageName);
            $imagePath = env('APP_URL') . '/public/images/' . $imageName;
        } else {
            $imagePath = url('public/images/def.png'); // رابط الصورة الافتراضية بالكامل 
        }


        $course = Course::create([
            'name' => $request->name,
            'contant' => $request->contant,
            'description' => $request->description,
            'price' => $request->price,
            'time' => $request->time,
            'user_add_id' => $request->user_add_id,
            'image' => $imagePath,
        ]);

        return response()->json($course);
    }

    public function edit($id)
    {
        $course = Course::find($id);
        return response()->json($course);
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'name.required' => 'الاسم مطلوب.',
            'name.string' => 'يجب أن يكون الاسم نصًا.',
            'name.max' => 'يجب ألا يزيد الاسم عن 255 حرفًا.',
            'contant.required' => 'المحتوى مطلوب.',
            'description.required' => 'الوصف مطلوب.',
            'description.string' => 'يجب أن يكون الوصف نصًا.',
            'price.required' => 'السعر مطلوب.',
            'price.numeric' => 'يجب أن يكون السعر رقمًا.',
            'time.required' => 'مدة الدراسة مطلوبة.',
            'time.integer' => 'مدة الدراسة يجب أن تكون عددًا صحيحًا.',
            'time.min' => 'مدة الدراسة يجب أن تكون على الأقل شهرًا واحدًا.',
            'time.max' => 'مدة الدراسة لا يمكن أن تتجاوز 6 شهور .',
            'image.image' => 'يجب أن تكون الصورة من نوع صورة.',
            'image.mimes' => 'يجب أن تكون الصورة بصيغة jpeg, png, jpg, gif.',
            'image.max' => 'يجب ألا يزيد حجم الصورة عن 2 ميجابايت.',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contant' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'time' => 'required|integer|min:1|max:6',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 422);
        };

        $course = Course::findOrFail($id);

        $data = [
            'name' => $request->name,
            'contant' => $request->contant,
            'description' => $request->description,
            'price' => $request->price,
            'time' => $request->time,
        ];

        // التحقق من وجود صورة مرفوعة
        if ($request->hasFile('image')) {
            // رفع الصورة وتخزينها في مجلد التخزين
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images'), $imageName);
            $data['image'] = env('APP_URL') . '/public/images/' . $imageName;
        }

        $course->update($data);

        return response()->json($course);
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return response()->json([
            'succec' => 'بنجاح [ ' . $course->name . ' ] تم حذف كورس ',
        ]);
    }
}
