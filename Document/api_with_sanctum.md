# Laravel Sanctum Setup Guide

This guide explains how to set up Laravel Sanctum for API authentication.

## Requirements

- PHP 8.2+
- Composer
- Laravel 11 or Laravel 12
- MySQL/PostgreSQL

---

# Step 1: Install Laravel Sanctum

composer require laravel/sanctum

---

# Step 2: Publish Sanctum Configuration

php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

This publishes:

- config/sanctum.php
- Sanctum migration

---

# Step 3: Run Migration

php artisan migrate

This creates the personal_access_tokens table.

---

# Step 4: Add HasApiTokens Trait

Open app/Models/User.php

<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;

    // ...
}

---

# Step 5: Configure API Authentication

Laravel 11/12 uses bootstrap/app.php.

Ensure the API middleware is enabled if needed.

---

# Step 6: Create Authentication Controller

php artisan make:controller Api/AuthController

Example:

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }
}

---

# Step 7: Configure API Routes

routes/api.php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [AuthController::class, 'profile']);

    Route::post('/logout', [AuthController::class, 'logout']);

});

---

# Step 8: Ensure User Model Fillable

protected $fillable = [
    'name',
    'email',
    'password',
];

---

# Step 9: API Requests

## Register

POST /api/register

Body

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
}

---

## Login

POST /api/login

Body

{
    "email": "john@example.com",
    "password": "password123"
}

Response

{
    "user": {
        "id": 1,
        "name": "John Doe"
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxx"
}

---

## Access Protected Route

Headers

Authorization: Bearer YOUR_TOKEN
Accept: application/json

Example

GET /api/profile

---

## Logout

POST /api/logout

Headers

Authorization: Bearer YOUR_TOKEN

---

# Using Postman

### Register

POST /api/register

### Login

POST /api/login

Copy the returned token.

### Protected Request

Headers