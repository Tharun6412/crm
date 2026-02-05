<?php

use App\Http\Middleware\ModuleAccess;
use Illuminate\Support\Facades\Route;

// Guest access
Route::middleware('guest')->group(function(){
    Route::get('login', [App\Http\Controllers\Auth\Authentication::class, 'login'])->name('login');
    Route::post('login', [App\Http\Controllers\Auth\Authentication::class, 'store']);
    // Registration
    Route::get('register', [App\Http\Controllers\Auth\UserRegistration::class, 'index'])->name('register');
    Route::post('register-verify', [App\Http\Controllers\Auth\UserRegistration::class, 'verify']);
    Route::post('resendEmail/{id}', [App\Http\Controllers\Auth\UserRegistration::class, 'resendEmail']);
    Route::post('validateRegisterOtp/{id}', [App\Http\Controllers\Auth\UserRegistration::class, 'validateRegisterOtp']);
    Route::get('generatePassword', [App\Http\Controllers\Auth\UserRegistration::class, 'generatePassword']);
    Route::get('cancelRegistration', [App\Http\Controllers\Auth\UserRegistration::class, 'cancelRegistration']);
    Route::post('storePassword', [App\Http\Controllers\Auth\UserRegistration::class, 'storePassword']);
    // Forgot password
    Route::get('forgotPassword', [App\Http\Controllers\Auth\ForgotPassword::class, 'index']);
    Route::post('userVerify', [App\Http\Controllers\Auth\ForgotPassword::class, 'userVerify']);
    Route::post('forgotPassword/resendEmail/{id}', [App\Http\Controllers\Auth\ForgotPassword::class, 'resendEmail']);
    Route::post('validateUserOtp/{id}', [App\Http\Controllers\Auth\ForgotPassword::class, 'validateUserOtp']);
    Route::get('reGeneratePassword', [App\Http\Controllers\Auth\ForgotPassword::class, 'reGeneratePassword']);
    Route::post('updatePassword/{id}', [App\Http\Controllers\Auth\ForgotPassword::class, 'updatePassword']);
    Route::get('cancelReset', [App\Http\Controllers\Auth\ForgotPassword::class, 'cancelReset']);
});
// Authenticated
Route::middleware('auth')->group(function(){
    Route::get('profile', [App\Http\Controllers\Auth\UserProfile::class, 'index'])->name('profile');
    Route::get('profile/edit', [App\Http\Controllers\Auth\UserProfile::class, 'edit']);
    Route::get('changePassword', [App\Http\Controllers\Auth\ChangePassword::class, 'index']);
    Route::post('changePassword', [App\Http\Controllers\Auth\ChangePassword::class, 'store']);
    Route::post('logout', [App\Http\Controllers\Auth\Authentication::class, 'destroy']);
});