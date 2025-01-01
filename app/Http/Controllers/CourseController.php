<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Course;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return response()->json([
            "Courses : "=> $courses,
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
            'Reservations.in' => 'حالة الحجز يجب أن تكون إما booked أو no.',
            'image.image' => 'يجب أن تكون الصورة من نوع صورة.',
            'image.mimes' => 'يجب أن تكون الصورة بصيغة jpeg, png, jpg, gif.',
            'image.max' => 'يجب ألا يزيد حجم الصورة عن 2 ميجابايت.',
        ];

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'contant' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'time' => 'required|integer|min:1|max:6', 
            'Reservations' => 'in:booked,nobooked', 
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ],$messages);

        if ($validator->fails()) {
            return response()->json([
                'error'=> $validator->errors()->first(),
            ], 422);
        };

        $imagePath = 'images/default.png';
        if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('images', 'public');
    }

        $course = Course::create([
            'name' => $request->name,
            'contant' => $request->contant,
            'description' => $request->description,
            'price' => $request->price,
            'time' => $request->time, 
            'Reservations' => $request->Reservations,
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
            'Reservations.in' => 'حالة الحجز يجب أن تكون إما booked أو no.',
            'image.image' => 'يجب أن تكون الصورة من نوع صورة.',
            'image.mimes' => 'يجب أن تكون الصورة بصيغة jpeg, png, jpg, gif.',
            'image.max' => 'يجب ألا يزيد حجم الصورة عن 2 ميجابايت.',
        ];

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'contant' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'time' => 'required|integer|min:1|max:6', 
            'Reservations' => 'in:booked,nobooked', 
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ],$messages);

        if ($validator->fails()) {
            return response()->json([
                'error'=> $validator->errors()->first(),
            ], 422);
        };

        $imagePath = 'images/default.png';
        if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('images', 'public');
    }

        $course = Course::findOrFail($id)->update([
            'name' => $request->name,
            'contant' => $request->contant,
            'description' => $request->description,
            'price' => $request->price,
            'time' => $request->time, 
            'Reservations' => $request->Reservations,
            'image' => $imagePath,
        ]);

        return response()->json($course);

    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return response()->json([
            'succec'=>'بنجاح [ ' .$course->name. ' ] تم حذف كورس ',
        ]);
    }
}
