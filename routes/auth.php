<?php

use Illuminate\Support\Facades\Route;

// Login
Route::get('login', function () {
    return view('auth.login');
});
