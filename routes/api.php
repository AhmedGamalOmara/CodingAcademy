<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\GetController;
use App\Http\Controllers\TeachController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class,'logout']);


Route::group(['prefix' => 'user', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('edit/{id}', [UserController::class, 'show']);
    Route::patch('edit/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

Route::group(['prefix'=> 'get','middleware'=>'auth:sanctum'], function () {
    Route::post('/subscribe', [GetController::class, 'subscribe']); // الاشتراك في كورس
    Route::post('/unsubscribe', [GetController::class, 'unsubscribe']); // إلغاء الاشتراك
    Route::get('/user-courses/{user_id}', [GetController::class, 'getUserCourses']); // عرض الكورسات
    Route::get('/course-users/{courses_id}', [GetController::class, 'getCourseUsers']); // عرض المسخدمين
});

Route::group(['prefix'=> 'course','middleware'=>'auth:sanctum'], function () {
    Route::get('/', [CourseController::class,'index']);
    Route::post('/store', [CourseController::class,'store']);
    Route::get('/{id}', [CourseController::class,'edit']);
    Route::post('/{id}', [CourseController::class,'update']);
    Route::delete('/{id}', [CourseController::class,'destroy']); 
});

Route::group(['prefix'=> 'teach','middleware'=>'auth:sanctum'], function () {
    Route::post('/add', [TeachController::class, 'addLecturerToCourse']);
    Route::put('/update/{id}', [TeachController::class, 'updateLecturerOrCourse']);
    Route::get('/course/{id}', [TeachController::class, 'getLecturersForCourse']);
    Route::delete('/remove/{id}', [TeachController::class, 'removeLecturerFromCourse']);
    Route::delete('/course/delete/{id}', [TeachController::class, 'deleteCourse']);
});

Route::group(['prefix'=> 'lecturer'], function () {
    Route::get('/', [LecturerController::class,'index']);
    Route::post('/store', [LecturerController::class,'store']);
    Route::get('edit/{id}', [LecturerController::class,'show']);
    Route::patch('edit/{id}', [LecturerController::class,'update']);
    Route::delete('/{id}', [LecturerController::class,'destroy']); 
});
