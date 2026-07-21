<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    const TABLE = 'authors';
    const ID = 'id';
    const NAME = 'name';
    const BIO = 'bio';

    protected $table = self::TABLE;
    protected $fillable = [
        self::NAME,
        self::BIO,
    ];

    public function books(){
        return $this->hasMany(Book::class, 'author_id', self::ID);
    }
}
