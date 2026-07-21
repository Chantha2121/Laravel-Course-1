<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const TABLE = 'categories';
    const ID = 'id';
    const NAME = 'name';

    protected $table = self::TABLE;
    protected $fillable = [
        self::NAME,
    ];

    public function books(){
        return $this->hasMany(Book::class, 'category_id', self::ID);
    }
}
