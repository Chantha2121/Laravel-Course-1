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