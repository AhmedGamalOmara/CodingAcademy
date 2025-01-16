<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\models\Team;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $team  = Team::all();
        return response()->json([
            "team : "=> $team,
        ]);
    }

    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'الاسم مطلوب.',
            'name.string' => 'يجب أن يكون الاسم نصًا.',
            'name.max' => 'يجب ألا يزيد الاسم عن 255 حرفًا.',
            'description.required' => 'الوصف مطلوب.',
            'description.string' => 'يجب أن يكون الوصف نصًا.',
            'image.image' => 'يجب أن تكون الصورة من نوع صورة.',
            'image.mimes' => 'يجب أن تكون الصورة بصيغة jpeg, png, jpg, gif.',
            'image.max' => 'يجب ألا يزيد حجم الصورة عن 2 ميجابايت.',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'facebook' => 'nullable',
            'instagram' => 'nullable',
            'twitter' => 'nullable',
            'linkedin' => 'nullable',
        ], messages: $messages);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 422);
        };

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images'), $imageName);
            $imagePath = env('APP_URL') . '/public/images/' . $imageName;
        } else {
            $imagePath = url('public/images/def.png'); 
        }

        $team = Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
        ]);

        return response()->json($team, 200);
    }

    public function show($id)
    {
        $team = Team::find($id);
        return response()->json($team);
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'name.required' => 'الاسم مطلوب.',
            'name.string' => 'يجب أن يكون الاسم نصًا.',
            'name.max' => 'يجب ألا يزيد الاسم عن 255 حرفًا.',
            'description.required' => 'الوصف مطلوب.',
            'description.string' => 'يجب أن يكون الوصف نصًا.',
            'image.image' => 'يجب أن تكون الصورة من نوع صورة.',
            'image.mimes' => 'يجب أن تكون الصورة بصيغة jpeg, png, jpg, gif.',
            'image.max' => 'يجب ألا يزيد حجم الصورة عن 2 ميجابايت.',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'facebook' => 'nullable',
            'instagram' => 'nullable',
            'twitter' => 'nullable',
            'linkedin' => 'nullable',
        ], messages: $messages);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 422);
        };


        $team = Team::findOrFail($id);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
        ];

        // التحقق من وجود صورة مرفوعة
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images'), $imageName);
            $data['image'] = env('APP_URL') . '/public/images/' . $imageName;
        }

        $team->update($data);

        return response()->json($team);
    }

    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();
        return response()->json([
            'succec' => 'بنجاح [ ' . $team->name . ' ] تم حذف  ',
        ]);
    }
}
