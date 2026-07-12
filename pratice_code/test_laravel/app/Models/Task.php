<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    const TABLE = 'tasks';
    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const DUE_DATE = 'due_date';
    const STATUS = 'status';
    const PRIORITY = 'priority';

    protected $table = self::TABLE;
    protected $fillable = [
        self::TITLE,
        self::DESCRIPTION,
        self::DUE_DATE,
        self::STATUS,
        self::PRIORITY
    ];

    public function setData(array $data): void
    {
        $this->title = $data[self::TITLE] ?? null;
        $this->description = $data[self::DESCRIPTION] ?? null;
        $this->due_date = $data[self::DUE_DATE] ?? null;
        $this->status = $data[self::STATUS] ?? 'pending';
        $this->priority = $data[self::PRIORITY] ?? 'medium';
    }
}
