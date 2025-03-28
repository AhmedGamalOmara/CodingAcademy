<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    // {
    //     $users = User::all();
    //     return response()->json([
    //         "users : "=> $users,
    //     ]);
    // }
    // public function index()
    {

        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $search = $request->get('search', '');
        
        
        // $query = User::query();
        $query = User::with('user:id,name');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('id', $search)
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $total = $query->count(); 
        $data = $query->skip(($page - 1) * $perPage)->take($perPage)->get();
    
        $totalPages = ceil($total / $perPage);
    
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
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.string' => 'يجب أن يكون البريد الإلكتروني نصًا.',
            'email.email' => 'يجب أن يكون البريد الإلكتروني بتنسيق صحيح.',
            'email.max' => 'يجب ألا يزيد البريد الإلكتروني عن 255 حرفًا.',
            'email.unique' => 'هذا البريد الإلكتروني مسجل مسبقًا.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.string' => 'يجب أن تكون كلمة المرور نصًا.',
            'password.min' => 'يجب ألا تقل كلمة المرور عن 8 أحرف.',
            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.numeric' => 'يجب أن يحتوي رقم الهاتف على أرقام فقط.',
            'phone.digits_between' => 'يجب أن يكون رقم الهاتف بين 8 و15 رقمًا.',
            'image.image' => 'يجب أن تكون الصورة من نوع صورة.',
            'image.mimes' => 'يجب أن تكون الصورة بصيغة jpeg, png, jpg, gif.',
            'image.max' => 'يجب ألا يزيد حجم الصورة عن 2 ميجابايت.',
            'role.in' => 'يجب أن تكون القيمة المدخلة للدور إما 0 (مستخدم) أو 1 (مشرف).',
        ];

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|numeric|digits_between:8,15',
            'role' => 'nullable|in:0,1',
            'user_add_id' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],$messages);

        if ($validator->fails()) {
            return response()->json([
                'error'=> $validator->errors()->first(),
            ], 422);
        };


        $imagePath = url('coding_academy/public/images/def.png'); // رابط الصورة الافتراضية بالكامل

        // التحقق من وجود صورة مرفوعة
        if ($request->hasFile('image')) {
            // رفع الصورة وتخزينها في مجلد التخزين
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images'), $imageName);
            $imagePath = env('APP_URL') . '/public/images/' . $imageName;
        }

        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
            'phone' => $request->phone,
            'role'=> $request->role,
            'user_add_id'=> $request->user_add_id,
            'image' => $imagePath,
        ]);
        return response()->json($user);
    }

    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);

    }

    public function update(Request $request, $id)
    {
        $messages = [
            'name.required' => 'الاسم مطلوب.',
            'name.string' => 'يجب أن يكون الاسم نصًا.',
            'name.max' => 'يجب ألا يزيد الاسم عن 255 حرفًا.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.string' => 'يجب أن يكون البريد الإلكتروني نصًا.',
            'email.email' => 'يجب أن يكون البريد الإلكتروني بتنسيق صحيح.',
            'email.max' => 'يجب ألا يزيد البريد الإلكتروني عن 255 حرفًا.',
            'email.unique' => 'هذا البريد الإلكتروني مسجل مسبقًا.',
            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.numeric' => 'يجب أن يحتوي رقم الهاتف على أرقام فقط.',
            'phone.digits_between' => 'يجب أن يكون رقم الهاتف بين 10 و15 رقمًا.',
            'image.image' => 'يجب أن تكون الصورة من نوع صورة.',
            'image.mimes' => 'يجب أن تكون الصورة بصيغة jpeg, png, jpg, gif.',
            'image.max' => 'يجب ألا يزيد حجم الصورة عن 2 ميجابايت.',
            'role.in' => 'يجب أن تكون القيمة المدخلة للدور إما 0 (مستخدم) أو 1 (مشرف).',
            'reservations.in' => 'يجب أن تكون القيمة المدخلة للدور إما 0 ( غير محجوز) أو 1 (محجوز).',
        ];

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|numeric|digits_between:10,15',
            'role' => 'nullable|in:0,1',
            'reservations' => 'nullable|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],$messages);

        if ($validator->fails()) {
            return response()->json([
                'error'=> $validator->errors()->first(),
            ], 422);
        };
        

        $user = User::findOrFail($id);

        
        $date=[
            'name'=> $request->name,
            'email'=> $request->email,
            'phone' => $request->phone,
            'role'=> $request->role,

        ];

        
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images'), $imageName);
            $data['image'] = env('APP_URL') . '/public/images/' . $imageName;
        }


        $password = $request->input('password');
        if (!empty($password)) {
            $date['password'] = Hash::make($password);
        }
        
        $reservation = $request->input('reservations');
        if (!empty($reservation)) {
            $date['reservations'] = $reservation;
        }
        
        $user->update($date);


        return response()->json($user);

    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'succec'=>'بنجاح [ ' .$user->name. ' ] تم حذف المستخدم ',
        ]);
    }
}
