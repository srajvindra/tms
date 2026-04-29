<?php

use Illuminate\Support\Facades\Route;
use Modules\Tasks\Http\Controllers\TasksController;
use Modules\Tasks\Http\Controllers\JsonReaderController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('tasks', TasksController::class)->names('tasks');
    
    // JSON Reader routes
    Route::prefix('json-reader')->name('json-reader.')->group(function () {
        Route::get('/', [JsonReaderController::class, 'index'])->name('index');
        Route::post('/read', [JsonReaderController::class, 'read'])->name('read');
        Route::get('/files', [JsonReaderController::class, 'listFiles'])->name('files');
        Route::post('/upload', [JsonReaderController::class, 'upload'])->name('upload');
    });
});
