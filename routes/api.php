<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LearningMaterialController;
use App\Http\Controllers\ExamController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/login-admin', [AuthController::class, 'loginAdmin']);

Route::post('/auth/send-otp-forgot-password', [AuthController::class, 'sendOtpForgotPassword']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/learning-material', [LearningMaterialController::class, 'index']);
    Route::get('/learning-material/{id}', [LearningMaterialController::class, 'show']);
    Route::post('/learning-material', [LearningMaterialController::class, 'store']);
    Route::put('/learning-material/{id}', [LearningMaterialController::class, 'update']);
    Route::delete('/learning-material/{id}', [LearningMaterialController::class, 'destroy']);

    Route::get('/exam', [ExamController::class,'getListExam']);
    Route::post('/exam/start', [ExamController::class, 'startExam']);
    Route::post('/exam/finish', [ExamController::class,'finishExam']);
});
