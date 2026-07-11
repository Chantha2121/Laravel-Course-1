<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;
    const TABLE = 'students';
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const EMAIL = 'email';
    const DATE_OF_BIRTH = 'date_of_birth';
    const IS_ACTIVE = 'is_active';

    protected $table = self::TABLE;
    protected $fillable = [
        self::FIRST_NAME,
        self::LAST_NAME,
        self::EMAIL,
        self::DATE_OF_BIRTH,
        self::IS_ACTIVE
    ];

    protected $casts = [
        self::DATE_OF_BIRTH => 'date',
        self::IS_ACTIVE => 'boolean',
    ];
}
