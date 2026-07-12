<?php

use App\Http\Controllers\StudentsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


include __DIR__ . '/student/student.php';
include __DIR__ . '/task/task.php';
// include('student/student.php');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
