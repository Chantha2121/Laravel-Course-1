# Laravel MVC Basics with Practice Code

Laravel is a powerful PHP framework that follows the **Model-View-Controller (MVC)** architectural pattern. This pattern helps separate the application concerns (data, user interface, and control logic), making the application easier to develop, maintain, and scale.

---

## Detailed Overview of MVC in Laravel

### 1. Model (Data & Database Logic)
- **Role**: Manages database queries, table relationships, and the business logic of the data.
- **Location**: `app/Models/` (e.g., `app/Models/Student.php`)
- **Eloquent ORM**: Laravel uses Eloquent, an Active Record implementation. Every database table has a corresponding "Model" used to interact with it.
- **Key Concepts**:
  - **Migrations**: Blueprints for building database tables (`database/migrations/`).
  - **Mass Assignment**: Specifying `$fillable` (allowed columns) or `$guarded` (restricted columns) to prevent security vulnerabilities.

### 2. View (Presentation Layer)
- **Role**: The User Interface. It displays data to the user and captures user interaction.
- **Location**: `resources/views/` (e.g., `resources/views/students/index.blade.php`)
- **Blade Templating Engine**: Laravel uses Blade, a lightweight, powerful templating engine.
  - **Layouts**: `@extends()`, `@section()`, `@yield()` allow for template inheritance and reusable layouts.
  - **Directives**: `@if`, `@foreach`, `@empty`, `@auth` simplify PHP logic inside HTML.
  - **CSRF Protection**: The `@csrf` directive generates a token to prevent Cross-Site Request Forgery on forms.

### 3. Controller (Traffic Cop / Request Handler)
- **Role**: Coordinates the application flow. It receives HTTP requests, interacts with the Model to retrieve/manipulate data, and passes that data to a View.
- **Location**: `app/Http/Controllers/` (e.g., `app/Http/Controllers/StudentController.php`)
- **Resource Controllers**: Controllers that handle standard CRUD actions (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`) automatically.

---

## Detailed Directory Structure

```text
bootstrap/              # Application bootstrapping and configuration
config/                 # Configuration files (database, mail, session, etc.)
database/
├── migrations/        # Database schema files
└── seeders/           # Database seed files for dummy data
app/
├── Http/
│   ├── Controllers/   # Controller files
│   └── Requests/      # Custom Form Request validation classes
└── Models/            # Eloquent Models
resources/
└── views/             # Blade template files
routes/
└── web.php            # Routes for web interface requests
```

---

## The Request Lifecycle (MVC Flow)

```text
    +------------------+
    |     Browser      | <------------------------------------+
    +------------------+                                      |
       |             ^                                        |
       | HTTP        | HTML                                   |
       | Request     | Response                               |
       v             |                                        |
    +------------------+                                      |
    |      Routes      |                                      |
    | (routes/web.php) |                                      |
    +------------------+                                      |
       |                                                      |
       | Triggers Action                                      |
       v                                                      |
    +----------------------+                                  |
    |      Controller      |                                  |
    | (App\Http\Controller)|                                  |
    +-----------+----------+                                  |
       |        ^                                             |
       | Query  | Data                                        |
       v        |                                             |
    +-----------+----------+       +------------------+       |
    |        Model         | <---> |     Database     |       |
    |   (App\Models\*)     |       +------------------+       |
    +-----------+----------+                                  |
       |                                                      |
       | Passes Data to View                                  |
       v                                                      |
    +----------------------+                                  |
    |         View         | ---------------------------------+
    |  (resources/views)   |
    +----------------------+
```

---

## Comprehensive Example: Student Management CRUD System

To understand how MVC fits together, we will build a complete **Student Management System** from scratch. 

### Step 1: Database Setup and Model Creation

Run the Artisan command to create the model along with a migration file:
```bash
php artisan make:model Student -m
```
*(The `-m` flag automatically creates a database migration file under `database/migrations/`)*.

#### 1. The Migration File (`database/migrations/xxxx_xx_xx_xxxxxx_create_students_table.php`)
Open the migration file and define the fields:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamps(); // creates created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
```

Run the migration to create the table in your database:
```bash
php artisan migrate
```

#### 2. The Model File (`app/Models/Student.php`)
Define the `$fillable` property to allow mass assignment when inserting data.
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    // Define table name (optional if it follows Laravel naming conventions: Student -> students)
    protected $table = 'students';

    // Fields that can be mass-assigned securely
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];
}
```

---

### Step 2: Routing Setup (`routes/web.php`)

Instead of writing individual routes for index, create, store, edit, update, and destroy, Laravel provides `Route::resource` which registers all these routes automatically.

Open `routes/web.php` and append:
```php
use App\Http\Controllers\StudentController;

// Simple Home Redirect
Route::get('/', function () {
    return redirect()->route('students.index');
});

// Single Resource route registers all CRUD routes
Route::resource('students', StudentController::class);
```

#### Registered Routes Table:
| Verb | URI | Action (Method) | Route Name | Purpose |
|------|-----|-----------------|------------|---------|
| GET | `/students` | `index` | `students.index` | Display list of students |
| GET | `/students/create` | `create` | `students.create` | Show form to add student |
| POST | `/students` | `store` | `students.store` | Save new student to database |
| GET | `/students/{student}` | `show` | `students.show` | View details of a student |
| GET | `/students/{student}/edit` | `edit` | `students.edit` | Show form to edit student |
| PUT/PATCH | `/students/{student}` | `update` | `students.update` | Update student in database |
| DELETE | `/students/{student}` | `destroy` | `students.destroy` | Delete student from database |

---

### Step 3: Controller Creation and Implementation

Generate a resource controller:
```bash
php artisan make:controller StudentController --resource
```
*(The `--resource` flag pre-generates methods for all standard CRUD actions)*.

Open `app/Http/Controllers/StudentController.php` and implement the logic:
```php
<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all students from the database (paginated for performance)
        $students = Student::latest()->paginate(10);
        
        // Pass data to the views folder: resources/views/students/index.blade.php
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate Form Input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
        ]);

        // 2. Insert into database using Model
        Student::create($validatedData);

        // 3. Redirect back to list page with success message
        return redirect()->route('students.index')
                         ->with('success', 'Student added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        // Route Model Binding automatically fetches the Student instance
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        // Route Model Binding handles finding the student
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        // 1. Validate Form Input (Excluding the current student's email for unique check)
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
        ]);

        // 2. Update database entry
        $student->update($validatedData);

        // 3. Redirect with success message
        return redirect()->route('students.index')
                         ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        // Delete student from database
        $student->delete();

        // Redirect with success message
        return redirect()->route('students.index')
                         ->with('success', 'Student deleted successfully.');
    }
}
```

---

### Step 4: Views (Blade Templates) with Styling

To create a clean interface, let's create a main layout first, and then make our specific views inherit from it.

#### 1. Common Layout file (`resources/views/layouts/app.blade.php`)
This file contains the boilerplate and global styling (using clean Bootstrap CDN for presentation).
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Management System')</title>
    <!-- Simple Modern Styling using Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">

    <div class="container">
        <!-- Application Title -->
        <header class="mb-4 text-center">
            <h1 class="fw-bold text-primary">Student Registry Portal</h1>
            <p class="text-muted">A Laravel MVC CRUD Application</p>
        </header>

        <!-- Dynamic Content Section -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

#### 2. Index Page (`resources/views/students/index.blade.php`)
This template renders the student listing table, success alerts, pagination links, and delete actions.
```html
@extends('layouts.app')

@section('title', 'Student List')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">All Registered Students</h5>
        <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">+ Add New Student</a>
    </div>
    
    <div class="card-body">
        <!-- Success Alert -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($students->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Created At</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td class="fw-semibold">{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->phone ?? 'N/A' }}</td>
                                <td>{{ $student->created_at->format('Y-M-d H:i') }}</td>
                                <td class="text-end">
                                    <!-- Edit Link -->
                                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm me-1">Edit</a>
                                    
                                    <!-- Delete Button Form -->
                                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this student?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="mt-3">
                {{ $students->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-muted mb-0">No students found. Click "Add New Student" to add one.</p>
            </div>
        @endif
    </div>
</div>
@endsection
```

#### 3. Create Page (`resources/views/students/create.blade.php`)
This form sends a `POST` request to `route('students.store')` and displays validation errors using `@error`.
```html
@extends('layouts.app')

@section('title', 'Add New Student')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Add New Student</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('students.store') }}" method="POST">
                    @csrf <!-- CSRF Token for security -->

                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="John Doe">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="john@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone Field -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="+855 12 345 678">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">Back to List</a>
                        <button type="submit" class="btn btn-success">Save Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

#### 4. Edit Page (`resources/views/students/edit.blade.php`)
This form sends a `PUT` request to update data, pre-populating fields with current model data.
```html
@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Edit Student Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('students.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Spoofs a PUT method since HTML forms only support GET/POST -->

                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $student->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $student->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone Field -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $student->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## Best Practices for Laravel MVC

1. **Keep Controllers Skinny**: Put business logic in Models, Services, or Form Requests. Keep controllers simple and readable (only handling input collection, flow control, and output responses).
2. **Always Use CSRF Protection**: Include `@csrf` in forms that post data to protect your application from Cross-Site Request Forgery.
3. **Validate All Incoming Data**: Never trust user inputs. Always validate inputs in the Controller or using dedicated Form Requests.
4. **Use Route Model Binding**: Instead of manually querying (e.g. `Student::find($id)`), type-hint the model in the controller signature (`public function show(Student $student)`) to fetch the database object automatically or throw a 404 page if not found.
5. **Use Database Seeders & Factories**: Set up database factories to seed dummy records quickly for testing and local development.

---

## Hands-On Practice Exercises

To deepen your understanding of Laravel MVC, complete the following four structured, progressive coding exercises:

1. **[Exercise 1: Blog Post Management System (Beginner)](file:///Users/choeurnchantha/Course/Laravel/Satur-Sun-8/Document/laravel_mvc_exercise1_blog.md)**
   - **Focus**: Basic CRUD, Route Resources, validation rules, and dynamic Blade templates.
2. **[Exercise 2: Category-based Todo Task Manager (Easy-Intermediate)](file:///Users/choeurnchantha/Course/Laravel/Satur-Sun-8/Document/laravel_mvc_exercise2_todo.md)**
   - **Focus**: Eloquent One-to-Many Relationships, database foreign key constraints, relationship loading, dropdown lists, database seeders, and status-toggling controllers.
3. **[Exercise 3: Book Library System with Search & Pagination (Intermediate)](file:///Users/choeurnchantha/Course/Laravel/Satur-Sun-8/Document/laravel_mvc_exercise3_library.md)**
   - **Focus**: Query Scopes, pagination logic, search/filter queries, and retaining search variables between pages.
4. **[Exercise 4: Product Inventory with Image Upload & Form Requests (Advanced-Intermediate)](file:///Users/choeurnchantha/Course/Laravel/Satur-Sun-8/Document/laravel_mvc_exercise4_inventory.md)**
   - **Focus**: File uploads, image handling/deletion using Laravel Storage, separated Form Request validation classes, and forms with multipart headers.

