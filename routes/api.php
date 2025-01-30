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
    Route::post('edit/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

Route::group(['prefix'=> 'get','middleware'=>'auth:sanctum'], function () {
    Route::get('/', [GetController::class, 'index']); 
    Route::post('/', [GetController::class, 'subscribe']); 
    Route::post('/unsubscribe', [GetController::class, 'unsubscribe']); 
    Route::post('/update', [GetController::class, 'updateSubscription']); 
    Route::get('/user-courses/{user_id}', [GetController::class, 'getUserCourses']);
    Route::get('/course-users/{courses_id}', [GetController::class, 'getCourseUsers']); 
});

Route::group(['prefix'=> 'course','middleware'=>'auth:sanctum'], function () {
    Route::get('/', [CourseController::class,'index']);
    Route::post('/', [CourseController::class,'store']);
    Route::post('edit/{id}', [CourseController::class,'update']);
    Route::delete('/{id}', [CourseController::class,'destroy']); 
});
Route::get('/course/edit/{id}', [CourseController::class,'show']);


Route::group(['prefix'=> 'teach','middleware'=>'auth:sanctum'], function () {
    Route::post('/add', [TeachController::class, 'addLecturerToCourse']);
    Route::post('/update/{id}', [TeachController::class, 'updateLecturerOrCourse']);
    Route::get('/course/{id}', [TeachController::class, 'getLecturersForCourse']);
    Route::delete('/remove/{id}', [TeachController::class, 'removeLecturerFromCourse']);
    Route::delete('/course/delete/{id}', [TeachController::class, 'deleteCourse']);
});

Route::group(['prefix'=> 'lecturer','middleware'=>'auth:sanctum'], function () {
    Route::get('/', [LecturerController::class,'index']);
    Route::post('/', [LecturerController::class,'store']);
    Route::get('edit/{id}', [LecturerController::class,'show']);
    Route::post('edit/{id}', [LecturerController::class,'update']);
    Route::delete('/{id}', [LecturerController::class,'destroy']); 
});

Route::group(['prefix'=> 'team','middleware'=>'auth:sanctum'], function () {
    Route::post('/', [TeamController::class,'store']);
    Route::get('/edit/{id}', [TeamController::class,'show']);
    Route::post('/edit/{id}', [TeamController::class,'update']);
    Route::delete('/{id}', [TeamController::class,'destroy']); 
});
Route::get('team/', [TeamController::class,'index']);


Route::group(['prefix'=> 'image','middleware'=>'auth:sanctum'], function () {
    Route::post('/', [ImageController::class, 'store']); 
    Route::get('/edit/{id}', [ImageController::class,'show']);
    Route::post('edit/{id}', [ImageController::class, 'update']); 
    Route::delete('/{id}', [ImageController::class, 'destroy']); 
});
Route::get('image/', [ImageController::class, 'index']); 


Route::group(['prefix'=> 'question','middleware'=>'auth:sanctum'], function () {
    Route::post('/', [QuestionController::class, 'store']); 
    Route::get('/edit/{id}', [QuestionController::class,'show']);
    Route::post('edit/{id}', [QuestionController::class, 'update']); 
    Route::delete('/{id}', [QuestionController::class, 'destroy']); 
});
Route::get('question/', [QuestionController::class, 'index']); 



