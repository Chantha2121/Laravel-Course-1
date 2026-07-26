<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRecord extends Model
{
    const TABLE = 'borrow_records';
    const ID = 'id';
    const USER_ID = 'user_id';
    const BOOK_ID = 'book_id';
    const BORROWED_AT = 'borrowed_at';
    const RETURNED_AT = 'returned_at';

    protected $table = self::TABLE;
    protected $fillable = [
        self::USER_ID,
        self::BOOK_ID,
        self::BORROWED_AT,
        self::RETURNED_AT,
    ];

    public function setData($data)
    {
        $this->user_id = auth()->id();
        $this->book_id = $data['book_id'];
        $this->borrowed_at = $data['borrowed_at'];
        $this->returned_at = $data['returned_at'] ?? null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, self::USER_ID, User::ID);
    }

    public function book()
    {
        return $this->belongsTo(Book::class, self::BOOK_ID, Book::ID);
    }
}
