<?php

use Illuminate\Support\Facades\Route;
use Modules\YouTube\Http\Controllers\SubscriptionsController;

Route::middleware(['auth', 'verified'])->prefix('youtube')->name('youtube.')->group(function () {
    Route::get('/subscriptions', [SubscriptionsController::class, 'index'])->name('subscriptions.index');
    Route::get('/channel/{channelId}/videos', [SubscriptionsController::class, 'channelVideos'])->name('channel.videos');
});
