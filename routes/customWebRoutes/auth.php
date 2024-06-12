<?php
use App\Http\Controllers\Auth\LoginController;

Route::prefix('auth')->controller(LoginController::class)->name('auth.')->group(function () {
    Route::get('/login', 'index')->name('login'); 
    Route::post('/login', 'authenticate')->name('login.submit'); 
});
