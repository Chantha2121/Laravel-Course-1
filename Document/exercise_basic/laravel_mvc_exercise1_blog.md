# Laravel MVC Exercise 1: Blog Post Management System

## Goal
Build a basic Blog Post Management system where users can view list of posts, view a single post, create new posts, edit existing posts, and delete posts.

---

## Difficulty: Beginner (Focus: Basic CRUD)

## Database Fields
- `id` (Primary Key)
- `title` (String)
- `slug` (String, Unique) - URL-friendly title (e.g. `my-first-post`)
- `content` (Text)
- `is_published` (Boolean, Default: false)
- `created_at` / `updated_at` (Timestamps)

---

## Implementation Guide

### Step 1: Model & Migration
Create the model and migration:
```bash
php artisan make:model Post -m
```

#### Migration (`database/migrations/xxxx_xx_xx_xxxxxx_create_posts_table.php`):
```php
public function up(): void
{
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique();
        $table->text('content');
        $table->boolean('is_published')->default(false);
        $table->timestamps();
    });
}
```

#### Model (`app/Models/Post.php`):
We will use Laravel's `Str::slug` helper to automatically generate the slug in the model using a **booted** method (Model Observer/Event).
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'content', 'is_published'];

    // Automatically generate slug before saving
    protected static function booted()
    {
        static::saving(function ($post) {
            $post->slug = Str::slug($post->title);
        });
    }
}
```

---

### Step 2: Routing (`routes/web.php`)
```php
use App\Http\Controllers\PostController;

Route::resource('posts', PostController::class);
```

---

### Step 3: Controller (`app/Http/Controllers/PostController.php`)
```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(5);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'is_published' => 'nullable|boolean'
        ]);

        $validated['is_published'] = $request->has('is_published');

        Post::create($validated);

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'is_published' => 'nullable|boolean'
        ]);

        $validated['is_published'] = $request->has('is_published');

        $post->update($validated);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }
}
```

---

### Step 4: Views (Blade Files)

#### 1. Common Layout View (`resources/views/layouts/blog.blade.php`):
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog App - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="{{ route('posts.index') }}">Laravel Blog</a>
            <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">Create New Post</a>
        </div>
    </nav>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
```

#### 2. Index View (`resources/views/posts/index.blade.php`):
```html
@extends('layouts.blog')
@section('title', 'All Posts')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse($posts as $post)
            <div class="col-md-12 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h2 class="card-title h4">
                            <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none text-dark">{{ $post->title }}</a>
                        </h2>
                        <span class="badge {{ $post->is_published ? 'bg-success' : 'bg-secondary' }} mb-2">
                            {{ $post->is_published ? 'Published' : 'Draft' }}
                        </span>
                        <p class="text-muted small">Posted {{ $post->created_at->diffForHumans() }}</p>
                        <p class="card-text">{{ Str::limit($post->content, 150) }}</p>
                        
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm me-2">Edit</a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No blog posts found. Create your first post!</p>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $posts->links() }}
    </div>
@endsection
```

#### 3. Create View (`resources/views/posts/create.blade.php`):
```html
@extends('layouts.blog')
@section('title', 'Create Post')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h2 class="card-title h4 mb-4">Create New Blog Post</h2>
        <form action="{{ route('posts.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Post Title</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" rows="6" class="form-control @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="is_published" class="form-check-input" id="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_published">Publish immediately</label>
            </div>

            <button type="submit" class="btn btn-success">Save Post</button>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
```

#### 4. Edit View (`resources/views/posts/edit.blade.php`):
```html
@extends('layouts.blog')
@section('title', 'Edit Post')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h2 class="card-title h4 mb-4">Edit Post</h2>
        <form action="{{ route('posts.update', $post->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Post Title</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}">
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" rows="6" class="form-control @error('content') is-invalid @enderror">{{ old('content', $post->content) }}</textarea>
                @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="is_published" class="form-check-input" id="is_published" value="1" {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_published">Published</label>
            </div>

            <button type="submit" class="btn btn-primary">Update Post</button>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
```

#### 5. Show View (`resources/views/posts/show.blade.php`):
```html
@extends('layouts.blog')
@section('title', $post->title)

@section('content')
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <h1 class="h3 mb-2">{{ $post->title }}</h1>
        <p class="text-muted small">Published: {{ $post->created_at->format('M d, Y H:i') }}</p>
        <span class="badge {{ $post->is_published ? 'bg-success' : 'bg-secondary' }} mb-4">
            {{ $post->is_published ? 'Published' : 'Draft' }}
        </span>
        <hr>
        <div class="blog-post-content" style="white-space: pre-wrap;">
            {{ $post->content }}
        </div>
    </div>
</div>
<a href="{{ route('posts.index') }}" class="btn btn-secondary btn-sm">Back to Blog</a>
@endsection
```
