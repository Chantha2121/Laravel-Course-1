# Laravel API with Sanctum - Practice Exercises

## Course Topic
**Building REST APIs in Laravel using Laravel Sanctum**

---

# Exercise 1 - Setup Laravel API with Sanctum Authentication

## Objective
Students will learn how to:

- Create a Laravel project
- Install Sanctum
- Configure API authentication
- Test API using Postman

---

## Step 1: Create Laravel Project

```bash
composer create-project laravel/laravel laravel-api
```

Move into project

```bash
cd laravel-api
```

---

## Step 2: Install Sanctum

```bash
composer require laravel/sanctum
```

Publish Sanctum

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

Run migration

```bash
php artisan migrate
```

---

## Step 3: Configure User Model

app/Models/User.php

```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
}
```

---

## Step 4: Create API Routes

routes/api.php

```php
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
```

---

## Step 5: Create Controller

```bash
php artisan make:controller AuthController
```

---

## Step 6: Register Function

```php
public function register(Request $request)
{
    $request->validate([
        'name'=>'required',
        'email'=>'required|email|unique:users',
        'password'=>'required|min:6'
    ]);

    $user = User::create([
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>bcrypt($request->password)
    ]);

    return response()->json([
        'message'=>'Register Success',
        'user'=>$user
    ]);
}
```

---

## Step 7: Login Function

```php
public function login(Request $request)
{
    if(!Auth::attempt($request->only('email','password'))){
        return response()->json([
            'message'=>'Invalid Credentials'
        ],401);
    }

    $user = User::where('email',$request->email)->first();

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'token'=>$token,
        'user'=>$user
    ]);
}
```

---

## Test using Postman

### Register

POST

```
http://localhost:8000/api/register
```

Body

```json
{
    "name":"John",
    "email":"john@gmail.com",
    "password":"123456"
}
```

---

### Login

POST

```
http://localhost:8000/api/login
```

Body

```json
{
    "email":"john@gmail.com",
    "password":"123456"
}
```

---

### Expected Result

Students should receive

```json
{
    "token":"1|abcdefg...."
}
```

---

# Practice Exercise

Create another API endpoint

```
POST /api/register-admin
```

Requirements

- Register admin
- Return JSON response
- Generate Sanctum token automatically

---

# Exercise 2 - Protect API Using Sanctum Middleware

## Objective

Students learn how middleware protects API endpoints.

---

## Step 1

Create Product Controller

```bash
php artisan make:controller ProductController
```

---

## Step 2

Create Routes

```php
use App\Http\Controllers\ProductController;

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/products',[ProductController::class,'index']);

});
```

---

## Step 3

Product Controller

```php
public function index()
{
    return response()->json([
        [
            "id"=>1,
            "name"=>"Macbook"
        ],
        [
            "id"=>2,
            "name"=>"iPhone"
        ]
    ]);
}
```

---

## Step 4

Login first

```
POST /api/login
```

Copy token

Example

```
1|Rtkldsjfslkdfjslfj....
```

---

## Step 5

Open Postman

Headers

```
Authorization

Bearer YOUR_TOKEN
```

Example

```
Bearer 1|Rtkldsjfslkdfjslfj....
```

---

## Call API

GET

```
/api/products
```

---

## Without Token

Expected Response

```json
{
    "message":"Unauthenticated."
}
```

---

## With Token

Expected Response

```json
[
    {
        "id":1,
        "name":"Macbook"
    },
    {
        "id":2,
        "name":"iPhone"
    }
]
```

---

# Practice Exercise

Create these protected routes

```
GET /students

GET /teachers

GET /courses
```

All routes must require

```
auth:sanctum
```

Return sample JSON.

---

# Exercise 3 - Build Complete CRUD API with Sanctum Protection

## Objective

Students create a secured CRUD API.

---

## Step 1

Create Model

```bash
php artisan make:model Product -mcr
```

---

## Migration

```php
Schema::create('products', function (Blueprint $table) {

    $table->id();

    $table->string('name');

    $table->double('price');

    $table->timestamps();

});
```

Run migration

```bash
php artisan migrate
```

---

## API Routes

```php
use App\Http\Controllers\ProductController;

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('products', ProductController::class);

});
```

---

## Store

```php
public function store(Request $request)
{
    $product = Product::create([
        'name'=>$request->name,
        'price'=>$request->price
    ]);

    return response()->json($product);
}
```

---

## Index

```php
public function index()
{
    return Product::all();
}
```

---

## Show

```php
public function show(Product $product)
{
    return $product;
}
```

---

## Update

```php
public function update(Request $request, Product $product)
{
    $product->update([
        'name'=>$request->name,
        'price'=>$request->price
    ]);

    return response()->json([
        'message'=>'Updated',
        'product'=>$product
    ]);
}
```

---

## Delete

```php
public function destroy(Product $product)
{
    $product->delete();

    return response()->json([
        'message'=>'Deleted Successfully'
    ]);
}
```

---

## Test Endpoints

### Login

```
POST /api/login
```

---

### Create

```
POST /api/products
```

---

### Get All

```
GET /api/products
```

---

### Get One

```
GET /api/products/1
```

---

### Update

```
PUT /api/products/1
```

---

### Delete

```
DELETE /api/products/1
```

---

# Final Challenge

Build a **Student Management REST API** using Laravel Sanctum.

Requirements:

- User Registration
- User Login
- User Logout
- Sanctum Authentication
- CRUD Student API
- Validation
- JSON Responses
- Protected Routes using `auth:sanctum`

### Student Table

| Field | Type |
|--------|------|
| id | bigint |
| name | string |
| email | string |
| phone | string |
| address | string |
| created_at | timestamp |
| updated_at | timestamp |

### Required Endpoints

| Method | Endpoint |
|----------|------------------|
| POST | /api/register |
| POST | /api/login |
| POST | /api/logout |
| GET | /api/students |
| POST | /api/students |
| GET | /api/students/{id} |
| PUT | /api/students/{id} |
| DELETE | /api/students/{id} |

### Learning Outcomes

After completing these exercises, students will be able to:

- Install Laravel Sanctum
- Generate API Tokens
- Authenticate users
- Protect API routes using middleware
- Build secure REST APIs
- Implement CRUD operations
- Test APIs with Postman
- Understand token-based authentication workflows