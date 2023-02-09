<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentDetailController;
use App\Http\Controllers\TeacherDetailController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//sign up process
Route::post('user/sign-up', [UserController::class, 'store']);

// add and update subjects
Route::post('subjects', [SubjectController::class, 'store']);
Route::put('subjects/{id}', [SubjectController::class, 'update']);

//home page
Route::get('user/home', [UserController::class, 'index']);

//login
Route::get('user/login', [UserController::class, 'login']);

// admin can block the user 

Route::put('user/update-user-status/{id}', [UserController::class, 'update']);

// Teacher to view Student details
Route::get('/view-student-details/{id}', [StudentDetailController::class, 'getStudentDetails']);

// teacher can update marks for the students
Route::post('update-student-marks', [StudentDetailController::class, 'updateStudentDetails']);


//teacher can upload assignements
Route::post('update-teacher-subject-details', [TeacherDetailController::class, 'store']);

//students can view subject details , marks and ranks
Route::get('/view-subject-details/{id}', [StudentDetailController::class, 'index']);

// student can add his favorite subjects from the subjects that he has chosen
Route::post('update-subject-details-by-student', [StudentController::class, 'update']);
