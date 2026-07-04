<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return "<h1>About Page</h1>";
});

Route::get('/users', [UserController::class, 'index']);
