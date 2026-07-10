<?php

namespace Modules\DataAnalyser\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keyword extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'domain_category_id',
        'pattern',
        'type',
        'sort_order',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(DomainCategory::class, 'domain_category_id');
    }

    public function scopeActive($query): Builder
    {
        return $query->where('is_active', true);
    }
}
