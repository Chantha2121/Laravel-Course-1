<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    const TABLE_NAME = 'posts';
    const COLUMN_ID = 'id';
    const COLUMN_TITLE = 'title';
    const COLUMN_CONTENT = 'content';

    protected $table = self::TABLE_NAME;
    protected $fillable = [
        self::COLUMN_TITLE,
        self::COLUMN_CONTENT,
    ];
}
