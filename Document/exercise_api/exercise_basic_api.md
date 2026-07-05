# Laravel API CRUD Practice Exercises

This document contains 5 progressive exercises for learning and practicing RESTful API CRUD (Create, Read, Update, Delete) development in Laravel, complete with step-by-step implementation code.

---

## Exercise 1: Product Management API (Difficulty: Easy)

### Objective
Learn how to create a basic API CRUD with standard data types, request validation, and clean JSON responses.

### Database Schema
**Model**: `Product`
- `id` (Primary Key)
- `name` (String, Required)
- `description` (Text, Nullable)
- `price` (Decimal: 8, 2, Required)
- `stock` (Integer, Default: 0)
- `timestamps` (`created_at`, `updated_at`)

### API Endpoints
| HTTP Method | URI | Controller Action | Description |
|---|---|---|---|
| `GET` | `/api/products` | `ProductController@index` | Get all products |
| `GET` | `/api/products/{id}` | `ProductController@show` | Get single product detail |
| `POST` | `/api/products` | `ProductController@store` | Create a new product |
| `PUT` | `/api/products/{id}` | `ProductController@update` | Update a product (Full update) |
| `DELETE` | `/api/products/{id}` | `ProductController@destroy` | Delete a product |

---

### Solution Code

#### 1. Migration (`database/migrations/xxxx_xx_xx_create_products_table.php`)
```php
public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->decimal('price', 8, 2);
        $table->integer('stock')->default(0);
        $table->timestamps();
    });
}
```

#### 2. Model (`app/Models/Product.php`)
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'stock'];
}
```

#### 3. Routes (`routes/api.php`)
```php
use App\Http\Controllers\ProductController;

Route::apiResource('products', ProductController::class);
```

#### 4. Controller (`app/Http/Controllers/ProductController.php`)
```php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'message' => 'Products retrieved successfully.',
            'data' => $products
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Product created successfully.',
            'data' => $product
        ], 201);
    }

    public function show(Product $product)
    {
        return response()->json([
            'message' => 'Product retrieved successfully.',
            'data' => $product
        ], 200);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully.',
            'data' => $product
        ], 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully.'
        ], 200);
    }
}
```

---

## Exercise 2: Student Registry API with Soft Deletes (Difficulty: Medium)

### Objective
Learn about route model binding, unique field validation (with exception on update), and implementing Soft Deletes.

### Database Schema
**Model**: `Student`
- `id` (Primary Key)
- `first_name` (String, Required)
- `last_name` (String, Required)
- `email` (String, Unique, Required)
- `date_of_birth` (Date, Required)
- `is_active` (Boolean, Default: true)
- `deleted_at` (Timestamp for Soft Deletes)

### API Endpoints
| HTTP Method | URI | Controller Action | Description |
|---|---|---|---|
| `GET` | `/api/students` | `StudentController@index` | Get all students (only non-deleted) |
| `GET` | `/api/students/trashed` | `StudentController@onlyTrashed` | Get all soft-deleted students |
| `GET` | `/api/students/{student}` | `StudentController@show` | View a single student (Route Model Binding) |
| `POST` | `/api/students` | `StudentController@store` | Register a new student |
| `PUT` | `/api/students/{student}` | `StudentController@update` | Update student details |
| `DELETE` | `/api/students/{student}` | `StudentController@destroy` | Soft delete a student |
| `PATCH` | `/api/students/{id}/restore` | `StudentController@restore` | Restore a soft-deleted student |

---

### Solution Code

#### 1. Migration (`database/migrations/xxxx_xx_xx_create_students_table.php`)
```php
public function up(): void
{
    Schema::create('students', function (Blueprint $table) {
        $table->id();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('email')->unique();
        $table->date('date_of_birth');
        $table->boolean('is_active')->default(true);
        $table->softDeletes(); // Adds 'deleted_at' column
        $table->timestamps();
    });
}
```

#### 2. Model (`app/Models/Student.php`)
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['first_name', 'last_name', 'email', 'date_of_birth', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'date_of_birth' => 'date',
    ];
}
```

#### 3. Routes (`routes/api.php`)
```php
use App\Http\Controllers\StudentController;

Route::get('/students/trashed', [StudentController::class, 'onlyTrashed']);
Route::patch('/students/{id}/restore', [StudentController::class, 'restore']);
Route::apiResource('students', StudentController::class);
```

#### 4. Controller (`app/Http/Controllers/StudentController.php`)
```php
namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        return response()->json(Student::all(), 200);
    }

    public function onlyTrashed()
    {
        // Retrieve only soft-deleted models
        $trashed = Student::onlyTrashed()->get();
        return response()->json($trashed, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:students,email',
            'date_of_birth' => 'required|date|before:today',
            'is_active' => 'boolean'
        ]);

        $student = Student::create($validated);
        return response()->json($student, 201);
    }

    public function show(Student $student)
    {
        return response()->json($student, 200);
    }

    public function update(Request $request, Student $student)
    {
        // Exclude the current student ID from unique email check
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'date_of_birth' => 'required|date|before:today',
            'is_active' => 'boolean'
        ]);

        $student->update($validated);
        return response()->json($student, 200);
    }

    public function destroy(Student $student)
    {
        $student->delete(); // Soft deletes the student
        return response()->json(['message' => 'Student soft-deleted successfully.'], 200);
    }

    public function restore($id)
    {
        $student = Student::onlyTrashed()->findOrFail($id);
        $student->restore();

        return response()->json(['message' => 'Student restored successfully.', 'data' => $student], 200);
    }
}
```

---

## Exercise 3: Task Manager API with Search & Pagination (Difficulty: Medium)

### Objective
Implement search query parameters, filtering, sorting, custom status patch updates, and pagination.

### Database Schema
**Model**: `Task`
- `id` (Primary Key)
- `title` (String, Required)
- `description` (Text, Nullable)
- `due_date` (Date, Required)
- `status` (Enum: `pending`, `in_progress`, `completed`, Default: `pending`)
- `priority` (Enum: `low`, `medium`, `high`, Default: `medium`)

### API Endpoints
| HTTP Method | URI | Controller Action | Description |
|---|---|---|---|
| `GET` | `/api/tasks` | `TaskController@index` | List tasks with sorting, filters, and pagination |
| `POST` | `/api/tasks` | `TaskController@store` | Create a task |
| `GET` | `/api/tasks/{task}` | `TaskController@show` | View a task |
| `PUT` | `/api/tasks/{task}` | `TaskController@update` | Full update |
| `PATCH` | `/api/tasks/{task}/status` | `TaskController@updateStatus` | Custom endpoint to update task status only |
| `DELETE` | `/api/tasks/{task}` | `TaskController@destroy` | Delete a task |

---

### Solution Code

#### 1. Migration (`database/migrations/xxxx_xx_xx_create_tasks_table.php`)
```php
public function up(): void
{
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();
        $table->date('due_date');
        $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
        $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
        $table->timestamps();
    });
}
```

#### 2. Model (`app/Models/Task.php`)
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'due_date', 'status', 'priority'];
}
```

#### 3. Routes (`routes/api.php`)
```php
use App\Http\Controllers\TaskController;

Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
Route::apiResource('tasks', TaskController::class);
```

#### 4. Controller (`app/Http/Controllers/TaskController.php`)
```php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();

        // 1. Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 2. Exact status match
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Exact priority match
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // 4. Custom sorting
        $sortBy = $request->get('sort_by', 'due_date'); // Default sort field
        $sortOrder = $request->get('sort_order', 'asc'); // Default sort order
        
        $allowedFields = ['due_date', 'priority', 'status', 'created_at'];
        if (in_array($sortBy, $allowedFields)) {
            $query->orderBy($sortBy, $sortOrder === 'desc' ? 'desc' : 'asc');
        }

        // 5. Dynamic pagination limit
        $perPage = (int) $request->get('per_page', 10);

        return response()->json($query->paginate($perPage), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'nullable|in:pending,in_progress,completed',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        $task = Task::create($validated);
        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        return response()->json($task, 200);
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,medium,high',
        ]);

        $task->update($validated);
        return response()->json($task, 200);
    }

    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Status updated successfully.',
            'data' => $task
        ], 200);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully.'], 200);
    }
}
```

---

## Exercise 4: Book & Author API with API Resources (Difficulty: Hard)

### Objective
Build relationships (One-to-Many), configure foreign keys, and utilize Laravel API Resources (`JsonResource` and `ResourceCollection`) to format the returned JSON response and selectively load relationships.

### Database Schema

**Model**: `Author`
- `id` (Primary Key)
- `name` (String, Required)
- `bio` (Text, Nullable)

**Model**: `Book`
- `id` (Primary Key)
- `author_id` (Foreign Key pointing to `authors.id`, Cascade Delete)
- `title` (String, Required)
- `isbn` (String, Unique, Required)
- `published_year` (Integer, Required)

---

### Solution Code

#### 1. Migrations (`database/migrations/xxxx_xx_xx_create_authors_and_books_tables.php`)
```php
// Migration for Authors
Schema::create('authors', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('bio')->nullable();
    $table->timestamps();
});

// Migration for Books
Schema::create('books', function (Blueprint $table) {
    $table->id();
    $table->foreignId('author_id')->constrained()->onDelete('cascade');
    $table->string('title');
    $table->string('isbn')->unique();
    $table->integer('published_year');
    $table->timestamps();
});
```

#### 2. Models & Relationships
**`app/Models/Author.php`**
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = ['name', 'bio'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
```

**`app/Models/Book.php`**
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['author_id', 'title', 'isbn', 'published_year'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
```

#### 3. API Resources
**`app/Http/Resources/AuthorResource.php`**
```php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author_name' => $this->name,
            'bio' => $this->bio,
        ];
    }
}
```

**`app/Http/Resources/BookResource.php`**
```php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'book_title' => $this->title,
            'isbn_code' => $this->isbn,
            'year' => $this->published_year,
            // Only include author when it is eager loaded
            'author' => new AuthorResource($this->whenLoaded('author')),
        ];
    }
}
```

#### 4. Controller (`app/Http/Controllers/BookController.php`)
```php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        // Eager load relationships to prevent N+1 query problem
        $books = Book::with('author')->get();
        return BookResource::collection($books);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'author_id' => 'required|exists:authors,id',
            'title' => 'required|string|max:255',
            // Custom ISBN format check via regex (matches 978- or 979- formats)
            'isbn' => 'required|unique:books,isbn|regex:/^(97[89][- ]?)?[0-9]{1,5}[- ]?[0-9]{1,7}[- ]?[0-9]{1,7}[- ]?[0-9]$/',
            'published_year' => 'required|integer|between:1000,' . date('Y'),
        ]);

        $book = Book::create($validated);
        return new BookResource($book->load('author'));
    }

    public function show(Book $book)
    {
        return new BookResource($book->load('author'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'author_id' => 'required|exists:authors,id',
            'title' => 'required|string|max:255',
            'isbn' => 'required|regex:/^(97[89][- ]?)?[0-9]{1,5}[- ]?[0-9]{1,7}[- ]?[0-9]{1,7}[- ]?[0-9]$/|unique:books,isbn,' . $book->id,
            'published_year' => 'required|integer|between:1000,' . date('Y'),
        ]);

        $book->update($validated);
        return new BookResource($book->load('author'));
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(['message' => 'Book deleted successfully.'], 200);
    }
}
```

---

## Exercise 5: Employee Profile API with File Upload (Difficulty: Hard)

### Objective
Learn to handle file uploads, validate file inputs, store uploaded files in the public directory, serve absolute file URLs, and ensure orphaned files are deleted from the disk on update or delete.

### Database Schema
**Model**: `Employee`
- `id` (Primary Key)
- `name` (String, Required)
- `position` (String, Required)
- `email` (String, Unique, Required)
- `profile_picture` (String, Nullable, stores relative file path in storage)

---

### Solution Code

#### 1. Migration (`database/migrations/xxxx_xx_xx_create_employees_table.php`)
```php
public function up(): void
{
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('position');
        $table->string('email')->unique();
        $table->string('profile_picture')->nullable();
        $table->timestamps();
    });
}
```

#### 2. Model (`app/Models/Employee.php`)
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['name', 'position', 'email', 'profile_picture'];
}
```

#### 3. Routes (`routes/api.php`)
```php
use App\Http\Controllers\EmployeeController;

// Standard API Resource
Route::apiResource('employees', EmployeeController::class);
```

#### 4. Controller (`app/Http/Controllers/EmployeeController.php`)
```php
namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all()->map(function ($employee) {
            return $this->formatEmployee($employee);
        });

        return response()->json($employees, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Max 2MB
        ]);

        if ($request->hasFile('profile_picture')) {
            // Save file inside storage/app/public/avatars folder
            $path = $request->file('profile_picture')->store('avatars', 'public');
            $validated['profile_picture'] = $path;
        }

        $employee = Employee::create($validated);
        return response()->json($this->formatEmployee($employee), 201);
    }

    public function show(Employee $employee)
    {
        return response()->json($this->formatEmployee($employee), 200);
    }

    /**
     * NOTE: For Laravel PUT request with multipart/form-data (files),
     * you should send a POST request with the hidden form field "_method" set to "PUT".
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('profile_picture')) {
            // Delete the old file from disk if it exists
            if ($employee->profile_picture) {
                Storage::disk('public')->delete($employee->profile_picture);
            }

            // Save the new file
            $path = $request->file('profile_picture')->store('avatars', 'public');
            $validated['profile_picture'] = $path;
        }

        $employee->update($validated);
        return response()->json($this->formatEmployee($employee), 200);
    }

    public function destroy(Employee $employee)
    {
        // Delete file from disk if it exists
        if ($employee->profile_picture) {
            Storage::disk('public')->delete($employee->profile_picture);
        }

        $employee->delete();
        return response()->json(['message' => 'Employee deleted successfully.'], 200);
    }

    /**
     * Format the employee data to return an absolute public storage URL for the avatar.
     */
    private function formatEmployee(Employee $employee)
    {
        return [
            'id' => $employee->id,
            'name' => $employee->name,
            'position' => $employee->position,
            'email' => $employee->email,
            'profile_picture_url' => $employee->profile_picture 
                ? asset(Storage::url($employee->profile_picture)) 
                : null
        ];
    }
}
```
