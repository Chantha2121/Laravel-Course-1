<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('/tasks', [TaskController::class, 'store']);
Route::get('/tasks', [TaskController::class, 'index']);
Route::get('/tasks/{id}', [TaskController::class, 'getTaskById']);
Route::put('/tasks/{id}', [TaskController::class, 'update']);
Route::patch('/tasks/update_status/{id}', [TaskController::class, 'updateStatus']);
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);