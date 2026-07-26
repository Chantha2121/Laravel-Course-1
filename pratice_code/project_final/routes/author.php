<?php

use App\Http\Controllers\Api\AuthorController;
use Illuminate\Support\Facades\Route;

Route::apiResource('authors', AuthorController::class);
