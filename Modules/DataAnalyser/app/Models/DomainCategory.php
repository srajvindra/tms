<?php

namespace Modules\DataAnalyser\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DomainCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
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

    /**
     * All active category names, in matching priority order.
     *
     * @return list<string>
     */
    public static function activeNames(): array
    {
        return static::query()
            ->active()
            ->orderBy('sort_order')
            ->pluck('name')
            ->all();
    }

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    public function keywords(): HasMany
    {
        return $this->hasMany(Keyword::class);
    }

    public function scopeActive($query): Builder
    {
        return $query->where('is_active', true);
    }
}
