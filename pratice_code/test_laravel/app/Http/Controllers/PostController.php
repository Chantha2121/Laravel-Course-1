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
