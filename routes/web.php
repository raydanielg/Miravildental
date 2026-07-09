<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [App\Http\Controllers\Auth\LoginController::class, 'login']);

Auth::routes(['login' => false, 'middleware' => 'throttle:5,1']);

Route::middleware(['auth', 'role:admin,reception'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
});

Route::get('/home', function () {
    return redirect()->route('dashboard');
});
