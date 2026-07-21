<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    const TABLE = 'books';
    const ID = 'id';
    const TITLE = 'title';
    const AUTHOR_ID = 'author_id';
    const CATEGORY_ID = 'category_id';
    const PUBLISHED_YEAR = 'published_year';

    protected $table = self::TABLE;
    protected $fillable = [
        self::TITLE,
        self::AUTHOR_ID,
        self::CATEGORY_ID,
        self::PUBLISHED_YEAR,
    ];

    public function author(){
        return $this->belongsTo(Author::class, self::AUTHOR_ID, Author::ID);
    }

    public function category(){
        return $this->belongsTo(Category::class, self::CATEGORY_ID, Category::ID);
    }
}
