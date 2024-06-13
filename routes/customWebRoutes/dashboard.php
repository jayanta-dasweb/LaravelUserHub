<?php
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Auth\LoginController;

Route::middleware(['auth', 'PreventBackHistory'])->group(function () {
    // Logout route
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {
        Route::controller(HomeController::class)->group(function () {
            Route::get('', 'index')->name('home');
        });
    });

});

