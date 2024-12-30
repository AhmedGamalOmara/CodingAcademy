<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Lecturer;
use Illuminate\Support\Facades\Validator;

class LecturerController extends Controller
{
    public function index()
    {
        $lecturers = Lecturer::all();
        return response()->json([
            "Lecturers : "=> $lecturers,
        ]);
    }

    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'الاسم مطلوب.',
            'name.string' => 'يجب أن يكون الاسم نصًا.',
            'name.max' => 'يجب ألا يزيد الاسم عن 255 حرفًا.',
            'notes.required' => 'الوصف مطلوب.',
            'notes.string' => 'يجب أن يكون الوصف نصًا.',
            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.numeric' => 'يجب أن يحتوي رقم الهاتف على أرقام فقط.',
            'phone.digits_between' => 'يجب أن يكون رقم الهاتف بين 8 و15 رقمًا.',
            'image.image' => 'يجب أن تكون الصورة من نوع صورة.',
            'image.mimes' => 'يجب أن تكون الصورة بصيغة jpeg, png, jpg, gif.',
            'image.max' => 'يجب ألا يزيد حجم الصورة عن 2 ميجابايت.',
        ];

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'notes' => 'required|string',
            'phone' => 'required|numeric|digits_between:8,15',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],$messages);

        if ($validator->fails()) {
            return response()->json([
                'error'=> $validator->errors()->first(),
            ], 422);
        };

        $imagePath = 'images/def.png';
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $lecturer = Lecturer::create([
            'name'=> $request->name,
            'notes'=> $request->notes,
            'phone' => $request->phone,
            'image' => $imagePath,
        ]);
        
        return response()->json($lecturer,200);
    }

    public function edit($id)
    {
        $lecturer = Lecturer::find($id);
        return response()->json($lecturer);

    }

    public function update(Request $request, $id)
    {
        $messages = [
            'name.required' => 'الاسم مطلوب.',
            'name.string' => 'يجب أن يكون الاسم نصًا.',
            'name.max' => 'يجب ألا يزيد الاسم عن 255 حرفًا.',
            'notes.required' => 'الوصف مطلوب.',
            'notes.string' => 'يجب أن يكون الوصف نصًا.',
            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.numeric' => 'يجب أن يحتوي رقم الهاتف على أرقام فقط.',
            'phone.digits_between' => 'يجب أن يكون رقم الهاتف بين 8 و15 رقمًا.',
            'image.image' => 'يجب أن تكون الصورة من نوع صورة.',
            'image.mimes' => 'يجب أن تكون الصورة بصيغة jpeg, png, jpg, gif.',
            'image.max' => 'يجب ألا يزيد حجم الصورة عن 2 ميجابايت.',
        ];

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'notes' => 'required|string',
            'phone' => 'required|numeric|digits_between:8,15',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],$messages);

        if ($validator->fails()) {
            return response()->json([
                'error'=> $validator->errors()->first(),
            ], 422);
        };


        $imagePath = 'images/def.png';
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $lecturer = Lecturer::findOrFail($id)->update([
            'name'=> $request->name,
            'notes'=> $request->notes,
            'phone' => $request->phone,
            'image' => $imagePath,
        ]);

        return response()->json($lecturer);

    }

    public function destroy($id)
    {
        $lecturer = Lecturer::findOrFail($id);
        $lecturer->delete();
    }
}
