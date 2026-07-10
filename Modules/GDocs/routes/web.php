<?php

use Illuminate\Support\Facades\Route;
use Modules\GDocs\Http\Controllers\DownloadFilesController;
use Modules\GDocs\Http\Controllers\GDocsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('gdocs/download', DownloadFilesController::class)->name('gdocs.download');
    Route::resource('gdocs', GDocsController::class)->names('gdocs');
});
