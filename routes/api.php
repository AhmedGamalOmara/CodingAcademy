<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\GetController;
use App\Http\Controllers\TeachController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\QuestionController;

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
    Route::post('/', [CourseController::class,'store']);
    Route::patch('edit/{id}', [CourseController::class,'update']);
    Route::delete('/{id}', [CourseController::class,'destroy']); 
});
Route::get('/course/edit/{id}', [CourseController::class,'show']);


Route::group(['prefix'=> 'teach','middleware'=>'auth:sanctum'], function () {
    Route::post('/add', [TeachController::class, 'addLecturerToCourse']);
    Route::put('/update/{id}', [TeachController::class, 'updateLecturerOrCourse']);
    Route::get('/course/{id}', [TeachController::class, 'getLecturersForCourse']);
    Route::delete('/remove/{id}', [TeachController::class, 'removeLecturerFromCourse']);
    Route::delete('/course/delete/{id}', [TeachController::class, 'deleteCourse']);
});

Route::group(['prefix'=> 'lecturer'], function () {
    Route::get('/', [LecturerController::class,'index']);
    Route::post('/', [LecturerController::class,'store']);
    Route::get('edit/{id}', [LecturerController::class,'show']);
    Route::patch('edit/{id}', [LecturerController::class,'update']);
    Route::delete('/{id}', [LecturerController::class,'destroy']); 
});

Route::group(['prefix'=> 'team'], function () {
    Route::get('/', [TeamController::class,'index']);
    Route::post('/', [TeamController::class,'store']);
    Route::get('/edit/{id}', [TeamController::class,'show']);
    Route::patch('/edit/{id}', [TeamController::class,'update']);
    Route::delete('/{id}', [TeamController::class,'destroy']); 
});

Route::group(['prefix'=> 'image'], function () {
    Route::get('/', [ImageController::class, 'index']); 
    Route::post('/', [ImageController::class, 'store']); 
    Route::get('/edit/{id}', [ImageController::class,'show']);
    Route::patch('edit/{id}', [ImageController::class, 'update']); 
    Route::delete('/{id}', [ImageController::class, 'destroy']); 
});

Route::group(['prefix'=> 'question'], function () {
    Route::get('/', [QuestionController::class, 'index']); 
    Route::post('/', [QuestionController::class, 'store']); 
    Route::get('/edit/{id}', [QuestionController::class,'show']);
    Route::patch('edit/{id}', [QuestionController::class, 'update']); 
    Route::delete('/{id}', [QuestionController::class, 'destroy']); 
});



