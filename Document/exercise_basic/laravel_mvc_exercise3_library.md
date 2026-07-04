# Laravel MVC Exercise 3: Book Library System with Search & Pagination

## Goal
Build a Book Library Management System that implements **Searching, Filtering**, and **Pagination** using Eloquent local query scopes.

---

## Difficulty: Intermediate (Focus: Query Scopes, Search, and Pagination)

## Database Fields
- `id` (Primary Key)
- `title` (String)
- `author` (String)
- `isbn` (String, Unique)
- `published_year` (Integer)
- `genre` (String)
- `created_at` / `updated_at` (Timestamps)

---

## Implementation Guide

### Step 1: Model & Migration
Create the model and migration:
```bash
php artisan make:model Book -m
```

#### Migration (`database/migrations/xxxx_xx_xx_xxxxxx_create_books_table.php`):
```php
public function up(): void
{
    Schema::create('books', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('author');
        $table->string('isbn')->unique();
        $table->integer('published_year');
        $table->string('genre');
        $table->timestamps();
    });
}
```

Run database migrations:
```bash
php artisan migrate
```

#### Model (`app/Models/Book.php`):
We will use **Local Query Scopes** to modularize the search and filter query logic.
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author', 'isbn', 'published_year', 'genre'];

    // Local Query Scope for Search
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function($q) use ($searchTerm) {
            $q->where('title', 'like', '%' . $searchTerm . '%')
              ->orWhere('author', 'like', '%' . $searchTerm . '%')
              ->orWhere('isbn', 'like', '%' . $searchTerm . '%');
        });
    }

    // Local Query Scope for Filtering by Genre
    public function scopeGenre($query, $genre)
    {
        return $query->where('genre', $genre);
    }
}
```

---

### Step 2: Routing (`routes/web.php`)
```php
use App\Http\Controllers\BookController;

Route::resource('books', BookController::class);
```

---

### Step 3: Controller (`app/Http/Controllers/BookController.php`)
```php
<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        // Start building Eloquent Query
        $query = Book::query();

        // Apply Search Scope if search input is provided
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Apply Genre Scope if genre dropdown selection is active
        if ($request->filled('genre')) {
            $query->genre($request->genre);
        }

        // Fetch paginated results (5 per page)
        $books = $query->latest()->paginate(5);
        
        // Fetch unique genres from db for filter dropdown
        $genres = Book::select('genre')->distinct()->pluck('genre');

        return view('books.index', compact('books', 'genres'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'published_year' => 'required|integer|min:1000|max:' . date('Y'),
            'genre' => 'required|string|max:100',
        ]);

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Book added successfully!');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,' . $book->id,
            'published_year' => 'required|integer|min:1000|max:' . date('Y'),
            'genre' => 'required|string|max:100',
        ]);

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
    }
}
```

---

### Step 4: Views (Blade Files)

#### 1. Common Layout View (`resources/views/layouts/library.blade.php`):
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
```

#### 2. Index View (`resources/views/books/index.blade.php`):
**CRITICAL**: When using pagination with search filters, you must call `appends(request()->query())` on pagination links so they don't reset the search filters when navigating to page 2.
```html
@extends('layouts.library')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold mb-0">Library Catalogue</h1>
    <a href="{{ route('books.create') }}" class="btn btn-primary btn-sm">+ Add New Book</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Search & Filter Form -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('books.index') }}" method="GET" class="row g-3">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search by title, author, or ISBN..." value="{{ request('search') }}">
            </div>
            
            <div class="col-md-3">
                <select name="genre" class="form-select">
                    <option value="">All Genres</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>
                            {{ $genre }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100">Filter</button>
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Books List -->
<div class="card shadow-sm border-0">
    <div class="card-body py-0">
        @if($books->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ISBN</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Genre</th>
                            <th>Published</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                            <tr>
                                <td class="text-monospace">{{ $book->isbn }}</td>
                                <td class="fw-semibold">{{ $book->title }}</td>
                                <td>{{ $book->author }}</td>
                                <td><span class="badge bg-secondary">{{ $book->genre }}</span></td>
                                <td>{{ $book->published_year }}</td>
                                <td class="text-end">
                                    <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove book?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-muted mb-0">No books found in catalogue matching criteria.</p>
            </div>
        @endif
    </div>
</div>

<!-- Pagination Links preserving search query context -->
<div class="mt-4">
    {{ $books->appends(request()->query())->links() }}
</div>
@endsection
```

#### 3. Create View (`resources/views/books/create.blade.php`):
```html
@extends('layouts.library')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0 fw-bold">Add Book to Inventory</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('books.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Book Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Author Name</label>
                            <input type="text" name="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author') }}">
                            @error('author') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ISBN</label>
                            <input type="text" name="isbn" class="form-control @error('isbn') is-invalid @enderror" value="{{ old('isbn') }}" placeholder="978-3-16-148410-0">
                            @error('isbn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Genre</label>
                            <input type="text" name="genre" class="form-control @error('genre') is-invalid @enderror" value="{{ old('genre') }}" placeholder="Fiction, Tech, Science">
                            @error('genre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Published Year</label>
                            <input type="number" name="published_year" class="form-control @error('published_year') is-invalid @enderror" value="{{ old('published_year') }}">
                            @error('published_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('books.index') }}" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-success">Save Book</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

#### 4. Edit View (`resources/views/books/edit.blade.php`):
```html
@extends('layouts.library')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0 fw-bold">Edit Book Details</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('books.update', $book->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Book Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $book->title) }}">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Author Name</label>
                            <input type="text" name="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author', $book->author) }}">
                            @error('author') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ISBN</label>
                            <input type="text" name="isbn" class="form-control @error('isbn') is-invalid @enderror" value="{{ old('isbn', $book->isbn) }}">
                            @error('isbn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Genre</label>
                            <input type="text" name="genre" class="form-control @error('genre') is-invalid @enderror" value="{{ old('genre', $book->genre) }}">
                            @error('genre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Published Year</label>
                            <input type="number" name="published_year" class="form-control @error('published_year') is-invalid @enderror" value="{{ old('published_year', $book->published_year) }}">
                            @error('published_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('books.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Book</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```
