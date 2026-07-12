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
        $this->due_date = $data[self::DUE_DATE] ?? now();
        $this->status = $data[self::STATUS] ?? 'pending';
        $this->priority = $data[self::PRIORITY] ?? 'medium';
    }

    public function lists($filter = [])
    {
        return self::when(isset($filter['status']), function ($q) use ($filter) {
            $q->where(self::STATUS, $filter['status']);
        })
            ->when(isset($filter['priority']), function ($q) use ($filter) {
                $q->where(self::PRIORITY, $filter['priority']);
            })
            ->when(isset($filter['title']), function ($q) use ($filter) {
                $q->where(self::TITLE, 'like', '%' . $filter['title'] . '%');
            });
    }
}
