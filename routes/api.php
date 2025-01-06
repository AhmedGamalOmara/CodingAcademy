<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LecturerController;

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


Route::group(['prefix'=> 'user','middleware'=>'auth:sanctum'], function () {
    Route::get('/', [UserController::class,'index']);
    Route::post('/store', [UserController::class,'store']);
    Route::get('/{id}', [Usercontroller::class,'edit']);
    Route::post('/{id}', [Usercontroller::class,'update']);
    Route::delete('/{id}', [Usercontroller::class,'destroy']); 
});

Route::group(['prefix'=> 'course'], function () {
    Route::get('/', [CourseController::class,'index']);
    Route::post('/store', [CourseController::class,'store']);
    Route::get('/{id}', [CourseController::class,'edit']);
    Route::post('/{id}', [CourseController::class,'update']);
    Route::delete('/{id}', [CourseController::class,'destroy']); 
});

Route::group(['prefix'=> 'lecturer'], function () {
    Route::get('/', [LecturerController::class,'index']);
    Route::post('/store', [LecturerController::class,'store']);
    Route::get('/{id}', [LecturerController::class,'edit']);
    Route::post('/{id}', [LecturerController::class,'update']);
    Route::delete('/{id}', [LecturerController::class,'destroy']); 
});
