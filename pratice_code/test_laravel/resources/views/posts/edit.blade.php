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