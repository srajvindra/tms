<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'what',
        'source',
        'action',
        'type',
        'category',
        'category_ii',
        'priority',
        'comments',
        'status',
        'is_recurring',
        'recurring_type',
    ];

    protected function casts(): array
    {
        return [
            'is_recurring' => 'boolean',
        ];
    }
}