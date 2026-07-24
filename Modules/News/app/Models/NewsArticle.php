<?php

namespace Modules\News\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsArticle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'url',
        'source',
        'author',
        'description',
        'category',
        'published_at',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }
}
