# Laravel MVC Exercise 2: Task Manager with Category Relation

## Goal
Build a Task Manager (Todo List) that implements a **One-to-Many relationship**. Every task must belong to a Category.

---

## Difficulty: Easy-Intermediate (Focus: Eloquent Relationships)

## Database Fields

### 1. Categories Table
- `id` (Primary Key)
- `name` (String)
- `color` (String, e.g., hex code or bootstrap color classes like `primary`, `danger`, `success`)

### 2. Tasks Table
- `id` (Primary key)
- `category_id` (Foreign Key referencing `categories.id` with cascade on delete)
- `title` (String)
- `description` (Text, Nullable)
- `due_date` (Date)
- `is_completed` (Boolean, Default: false)
- `created_at` / `updated_at` (Timestamps)

---

## Implementation Guide

### Step 1: Models & Migrations
Create the models and migrations:
```bash
php artisan make:model Category -m
php artisan make:model Task -m
```

#### Category Migration (`database/migrations/xxxx_xx_xx_xxxxxx_create_categories_table.php`):
```php
public function up(): void
{
    Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('color')->default('secondary');
        $table->timestamps();
    });
}
```

#### Task Migration (`database/migrations/xxxx_xx_xx_xxxxxx_create_tasks_table.php`):
```php
public function up(): void
{
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->string('title');
        $table->text('description')->nullable();
        $table->date('due_date');
        $table->boolean('is_completed')->default(false);
        $table->timestamps();
    });
}
```

Run database migrations:
```bash
php artisan migrate
```

---

### Step 2: Relationships Definition (Models)

#### Category Model (`app/Models/Category.php`):
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color'];

    // One-to-Many Relationship (A Category has many Tasks)
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
```

#### Task Model (`app/Models/Task.php`):
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'title', 'description', 'due_date', 'is_completed'];

    // Date casting for automatic carbon helper formatting
    protected $casts = [
        'due_date' => 'date',
    ];

    // Inverse Relationship (A Task belongs to a Category)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
```

---

### Step 3: Routing (`routes/web.php`)
```php
use App\Http\Controllers\TaskController;

Route::resource('tasks', TaskController::class);
Route::post('tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])->name('tasks.toggle');
```

---

### Step 4: Controller (`app/Http/Controllers/TaskController.php`)
```php
<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        // Eager load category to prevent N+1 query issue
        $tasks = Task::with('category')->orderBy('due_date', 'asc')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('tasks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task added successfully.');
    }

    public function edit(Task $task)
    {
        $categories = Category::all();
        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'due_date' => 'required|date',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    // Toggle tasks status between Pending and Completed
    public function toggleStatus(Task $task)
    {
        $task->update([
            'is_completed' => !$task->is_completed
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task status updated.');
    }
}
```

---

### Step 5: Database Seeding for Categories
Since Tasks require categories to exist, we should seed some categories into the database.

Generate category seeder:
```bash
php artisan make:seeder CategorySeeder
```

Open `database/seeders/CategorySeeder.php`:
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create(['name' => 'Work', 'color' => 'primary']);
        Category::create(['name' => 'Personal', 'color' => 'success']);
        Category::create(['name' => 'Urgent', 'color' => 'danger']);
        Category::create(['name' => 'Studies', 'color' => 'info']);
    }
}
```

Run seed:
```bash
php artisan db:seed --class=CategorySeeder
```

---

### Step 6: Views (Blade Files)

#### 1. Layout View (`resources/views/layouts/todo.blade.php`):
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
    <div class="container" style="max-width: 800px;">
        @yield('content')
    </div>
</body>
</html>
```

#### 2. Index View (`resources/views/tasks/index.blade.php`):
```html
@extends('layouts.todo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold">My Tasks</h1>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">+ New Task</a>
</div>

@if(session('success'))
    <div class="alert alert-success py-2">{{ session('success') }}</div>
@endif

<div class="card shadow-sm border-0">
    <ul class="list-group list-group-flush">
        @forelse($tasks as $task)
            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                <div class="d-flex align-items-center">
                    <!-- Status Checkbox Form -->
                    <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="me-3">
                        @csrf
                        <button type="submit" class="border-0 bg-transparent">
                            <input type="checkbox" class="form-check-input" {{ $task->is_completed ? 'checked' : '' }} style="cursor: pointer;">
                        </button>
                    </form>
                    
                    <div>
                        <span class="fw-semibold {{ $task->is_completed ? 'text-decoration-line-through text-muted' : '' }}">
                            {{ $task->title }}
                        </span>
                        <div class="small text-muted mt-1">
                            <span class="badge bg-{{ $task->category->color }} me-2">{{ $task->category->name }}</span>
                            Due: {{ $task->due_date->format('M d, Y') }}
                        </div>
                    </div>
                </div>
                
                <div class="d-flex">
                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-secondary me-2">Edit</a>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Delete task?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </div>
            </li>
        @empty
            <li class="list-group-item text-center py-4 text-muted">
                No tasks scheduled. Relax or create a new task!
            </li>
        @endforelse
    </ul>
</div>
@endsection
```

#### 3. Create View (`resources/views/tasks/create.blade.php`):
```html
@extends('layouts.todo')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h4 class="mb-0 fw-bold">Create a New Task</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Task Name</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}">
                @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Description (Optional)</label>
                <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Save Task</button>
            </div>
        </form>
    </div>
</div>
@endsection
```

#### 4. Edit View (`resources/views/tasks/edit.blade.php`):
```html
@extends('layouts.todo')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h4 class="mb-0 fw-bold">Edit Task</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Task Name</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $task->title) }}">
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $task->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', $task->due_date->format('Y-m-d')) }}">
                @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control">{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Task</button>
            </div>
        </form>
    </div>
</div>
@endsection
```
