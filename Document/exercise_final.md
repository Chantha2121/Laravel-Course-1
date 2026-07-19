# Final Exercise: Secure API with Laravel Sanctum

Welcome to the Final Exercise! In this project, you will build a secure RESTful API using Laravel for a Library Management System. Instead of building our own authentication system from scratch, we will use **Laravel Sanctum**, the official and industry-standard way to handle API tokens in Laravel.

## 🎯 Objectives
- Install and configure Laravel Sanctum.
- Implement User Registration and Login to generate secure Sanctum API Tokens.
- Create 5 interconnected Models and their corresponding Migrations.
- Protect your API Routes using Sanctum's built-in middleware.

---

## 🗄️ Database Structure (The 5 Models)

You will create the following 5 models to manage the library data:
1. **User**: Represents the library members or admins (comes built-in with Laravel).
2. **Author**: Represents the writer of a book.
3. **Category**: Represents the genre of a book (e.g., Fiction, Science).
4. **Book**: Represents the actual book in the library.
5. **BorrowRecord**: Tracks which user borrowed which book and when.

---

## 🚀 Step-by-Step Guide with Demo Code

### Step 1: Install and Set Up Laravel Sanctum

If you are using a newer version of Laravel (11+), you might need to install the API routing and Sanctum first. 

Run the following command in your terminal:
```bash
php artisan install:api
```
*(This command will install Sanctum, create the `api.php` routes file, and run the necessary database migrations for personal access tokens).*

Next, ensure your `User` model uses the `HasApiTokens` trait. Open `app/Models/User.php`:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// 1. Import HasApiTokens
use Laravel\Sanctum\HasApiTokens; 

class User extends Authenticatable
{
    // 2. Add HasApiTokens to the use statement inside the class
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }
}
```

### Step 2: Create the Auth Controller (Login & Register)

We need a controller to handle registering users and logging them in so they can receive their Sanctum token.

```bash
php artisan make:controller Api/AuthController
```

**`app/Http/Controllers/Api/AuthController.php`**
```php
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate a Sanctum Token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully!',
            'access_token' => $token,
            'token_type' => 'Bearer',
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
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }

        // Generate a new Sanctum Token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Successfully logged out']);
    }
}
```

---

### Step 3: Create The Library Resources (Models & Migrations)

Run the following commands in your terminal to create the models along with their migration files (`-m` flag):

```bash
php artisan make:model Author -m
php artisan make:model Category -m
php artisan make:model Book -m
php artisan make:model BorrowRecord -m
```

**1. Update the Migrations (`database/migrations/...`)**

*Authors Table:*
```php
public function up(): void
{
    Schema::create('authors', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('bio')->nullable();
        $table->timestamps();
    });
}
```

*Categories Table:*
```php
public function up(): void
{
    Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });
}
```

*Books Table:*
```php
public function up(): void
{
    Schema::create('books', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->foreignId('author_id')->constrained('authors')->onDelete('cascade');
        $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        $table->integer('published_year');
        $table->timestamps();
    });
}
```

*Borrow Records Table:*
```php
public function up(): void
{
    Schema::create('borrow_records', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
        $table->date('borrowed_at');
        $table->date('returned_at')->nullable();
        $table->timestamps();
    });
}
```
Run `php artisan migrate`.

**2. Update the Models (`app/Models/...`)**

*Author Model (`app/Models/Author.php`):*
```php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = ['name', 'bio'];
    public function books() { return $this->hasMany(Book::class); }
}
```

*Category Model (`app/Models/Category.php`):*
```php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];
    public function books() { return $this->hasMany(Book::class); }
}
```

*Book Model (`app/Models/Book.php`):*
```php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'author_id', 'category_id', 'published_year'];
    public function author() { return $this->belongsTo(Author::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function borrowRecords() { return $this->hasMany(BorrowRecord::class); }
}
```

*BorrowRecord Model (`app/Models/BorrowRecord.php`):*
```php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BorrowRecord extends Model
{
    protected $fillable = ['user_id', 'book_id', 'borrowed_at', 'returned_at'];
    public function user() { return $this->belongsTo(User::class); }
    public function book() { return $this->belongsTo(Book::class); }
}
```

---

### Step 4: Create the Resource Controllers

Let's create basic Controllers to manage our data.

```bash
php artisan make:controller Api/BookController --api
php artisan make:controller Api/AuthorController --api
php artisan make:controller Api/CategoryController --api
php artisan make:controller Api/BorrowRecordController --api
```

**Example 1: `BookController.php`**
```php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index() {
        return response()->json(Book::with(['author', 'category'])->get());
    }
    
    public function store(Request $request) {
        $book = Book::create($request->all());
        return response()->json(['message' => 'Book created', 'data' => $book]);
    }

    public function show($id) {
        return response()->json(Book::with(['author', 'category'])->findOrFail($id));
    }

    public function update(Request $request, $id) {
        $book = Book::findOrFail($id);
        $book->update($request->all());
        return response()->json(['message' => 'Book updated', 'data' => $book]);
    }

    public function destroy($id) {
        Book::findOrFail($id)->delete();
        return response()->json(['message' => 'Book deleted']);
    }
}
```
*(Exercise: Build out `AuthorController`, `CategoryController`, and `BorrowRecordController` using these exact same patterns!)*

---

### Step 5: Define Your Routes

Open `routes/api.php` and define public routes (login/register) and protected routes using Sanctum's built-in `auth:sanctum` middleware.

```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BorrowRecordController;

// ================= Public Routes (No Token Needed) =================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ================= Protected Routes (Token Required) =================
// Notice we use the built-in 'auth:sanctum' middleware here!
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth route to logout
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Library Routes (Handles GET, POST, GET {id}, PUT, DELETE automatically)
    Route::apiResource('books', BookController::class);
    Route::apiResource('authors', AuthorController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('borrows', BorrowRecordController::class);
    
});
```

---

## 🎉 Your Tasks to Complete the Exercise

1. **Follow the Steps Above**: Copy the code above to set up Sanctum, AuthController, and your Library Models/Controllers.
2. **Complete the other Controllers**: Finish writing the code for `AuthorController`, `CategoryController`, and `BorrowRecordController`.
3. **Test Register & Login**: Open Postman, make a `POST` request to `/api/register` with `name`, `email`, and `password`. Copy the `access_token` you get back in the response.
4. **Test Protected Routes**: Make a `GET` request to `/api/books`. 
   - First, try without a token. You should get an unauthorized error or be redirected.
   - Now, go to the **Authorization** tab in Postman.
   - Select **Bearer Token** as the type.
   - Paste your copied `access_token` into the Token field.
   - Send the request again, and it should work!
5. **Create Data**: Make `POST` requests to `/api/authors`, `/api/categories`, and `/api/books` (while passing your Bearer token) to populate your library database!

Good luck, and have fun coding! 🚀
