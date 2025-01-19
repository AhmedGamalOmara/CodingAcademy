<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public function index()
{
    $images = Image::all();
    return response()->json($images, 200);
    }

    public function store(Request $request)
    {
        $message = [
            'image.required' => 'الصورة مطلوبة.',
            'image.image' => 'الملف يجب أن يكون صورة.',
            'image.mimes' => 'يجب أن تكون الصورة بصيغة jpeg, png, jpg, gif.',
            'image.max' => 'يجب ألا يزيد حجم الصورة عن 2 ميجابايت.',
        ];
        
        $validator = Validator::make($request->all(), [
            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ],  $message);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 422);
        };


        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $imageFile) {
                $imageName = time() . '_' . $imageFile->getClientOriginalName();
                $imageFile->move(public_path('images'), $imageName);
                $imagePath = env('APP_URL') . '/public/images/' . $imageName;


                Image::create([
                    'image' => $imagePath,
                ]);
            }
        }

        return response()->json(['message' => 'تم رفع الصور بنجاح'], 200);
    }

    public function show($id){
        $image = Image::find($id);
        return response()->json($image);
    }

    public function update(Request $request, $id)
{
    $message = [
        'image.required' => 'الصورة مطلوبة.',
        'image.image' => 'الملف يجب أن يكون صورة.',
        'image.mimes' => 'يجب أن تكون الصورة بصيغة jpeg, png, jpg, gif.',
        'image.max' => 'يجب ألا يزيد حجم الصورة عن 2 ميجابايت.',
    ];

    $validator = Validator::make($request->all(), [
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
    ],  $message);

    if ($validator->fails()) {
        return response()->json([
            'error' => $validator->errors()->first(),
        ], 422);
    };

    $image = Image::find($id);

    if (!$image) {
        return response()->json(['error' => 'الصورة غير موجودة'], 404);
    }

    $oldImagePath = public_path(str_replace(env('APP_URL') . '/public/', '', $image->image));
    if (file_exists($oldImagePath)) {
        unlink($oldImagePath);
    }

    $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
    $request->file('image')->move(public_path('images'), $imageName);
    $imagePath = env('APP_URL') . '/public/images/' . $imageName;

    $image->update(['image' => $imagePath]);

    return response()->json(['message' => 'تم تحديث الصورة بنجاح', 'image' => $image], 200);
    }

    public function destroy($id)
{
    $image = Image::find($id);
    if (!$image) {
        return response()->json(['error' => 'الصورة غير موجودة'], 404);
    }

    $imagePath = public_path(str_replace(env('APP_URL') . '/public/', '', $image->image));
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    $image->delete();
    return response()->json(['message' => 'تم حذف الصورة بنجاح'], 200);
    }
}
