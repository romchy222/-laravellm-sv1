<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\UserController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User Profile & Data
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::get('/my-courses', [UserController::class, 'enrolledCourses']);
    Route::get('/my-achievements', [UserController::class, 'achievements']);
    Route::get('/my-certificates', [UserController::class, 'certificates']);
    Route::get('/my-wallet', [UserController::class, 'wallet']);

    // Courses
    Route::apiResource('courses', CourseController::class);
    Route::post('/courses/{course}/publish', [CourseController::class, 'publish']);
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll']);

    // Lessons
    Route::get('/lessons/{lesson}', [LessonController::class, 'show']);
    Route::post('/lessons/{lesson}/start', [LessonController::class, 'start']);
    Route::post('/lessons/{lesson}/complete', [LessonController::class, 'complete']);
    Route::post('/lessons/{lesson}/progress', [LessonController::class, 'updateProgress']);
    Route::post('/lessons/{lesson}/submit-answers', [LessonController::class, 'submitAnswers']);
});

// Public endpoints
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{course}', [CourseController::class, 'show']);
