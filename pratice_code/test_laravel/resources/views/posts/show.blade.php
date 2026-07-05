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