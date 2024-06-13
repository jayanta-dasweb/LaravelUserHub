<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\registerController;

Route::prefix('auth')->middleware('guest')->name('auth.')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'index')->name('login');
        Route::post('/login', 'authenticate')->name('login.submit');
    });

    Route::controller(registerController::class)->group(function () {
        Route::get('/register', 'index')->name('register');
        Route::post('/register', 'create')->name('register.submit');
    });

});


