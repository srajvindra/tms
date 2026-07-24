<?php

namespace Modules\YouTube\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class YoutubeChannelVideo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'channel_id',
        'video_id',
        'title',
        'description',
        'thumbnail',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(YoutubeSubscription::class, 'channel_id', 'channel_id');
    }
}
