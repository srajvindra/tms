<?php

use Illuminate\Support\Facades\Route;
use Modules\WorldMap\Http\Controllers\WorldMapController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('worldmaps', WorldMapController::class)->names('worldmap');
});
