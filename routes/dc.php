<?php

use App\Http\Middleware\RouteAccess;
use Illuminate\Support\Facades\Route;

// DocumentCentre routes
Route::middleware(['auth', RouteAccess::class])->group(function() {
    Route::resource('documents', App\Http\Controllers\DocumentCentre\DocumentController::class);
    // Document browser
    Route::resource('browse', App\Http\Controllers\DocumentCentre\DocumentBrowser::class);
    Route::get('sessionFiles', [App\Http\Controllers\DocumentCentre\DocumentBrowser::class, 'sessionFiles']);
    Route::get('deleteFile', [App\Http\Controllers\DocumentCentre\DocumentBrowser::class, 'deleteFile']);
    Route::get('search', [App\Http\Controllers\DocumentCentre\DocumentBrowser::class, 'search']);
    Route::post('selectFiles', [App\Http\Controllers\DocumentCentre\DocumentBrowser::class, 'selectFiles']);
});