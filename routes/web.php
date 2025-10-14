<?php

use App\Http\Middleware\RouteAccess;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', RouteAccess::class])->group(function() {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');
});


