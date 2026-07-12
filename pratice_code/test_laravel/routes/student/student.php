<?php

use App\Http\Controllers\StudentsController;
use Illuminate\Support\Facades\Route;

Route::post('/students', [StudentsController::class, 'store']);
Route::get('/students', [StudentsController::class, 'index']);
Route::get('/students/trashed', [StudentsController::class, 'trashed']);
Route::delete('/students', [StudentsController::class, 'destroy']);
Route::get('/students/{id}', [StudentsController::class, 'getStudentById']);
Route::put('/students/{id}', [StudentsController::class, 'update']);
Route::patch('/students/{id}', [StudentsController::class, 'restore']);
