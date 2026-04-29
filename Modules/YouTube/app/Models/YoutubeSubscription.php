<?php

namespace Modules\YouTube\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Modules\YouTube\Database\Factories\YoutubeSubscriptionFactory;

class YoutubeSubscription extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'account_id',
        'subscription_id',
        'channel_id',
        'title',
        'description',
        'thumbnail',
        'subscribed_at',
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
    ];

    public function videos(): HasMany
    {
        return $this->hasMany(YoutubeChannelVideo::class, 'channel_id', 'channel_id');
    }

    // protected static function newFactory(): YoutubeSubscriptionFactory
    // {
    //     // return YoutubeSubscriptionFactory::new();
    // }
}
