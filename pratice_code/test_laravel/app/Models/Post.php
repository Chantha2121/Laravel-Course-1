<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'content', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // Automatically generate slug before saving
    protected static function booted()
    {
        static::saving(function ($post) {
            $post->slug = Str::slug($post->title);
        });
    }
}
