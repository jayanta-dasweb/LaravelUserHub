<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Api\NSAPController;


Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);

// NSAP Scheme Open REST API
Route::get('/nsap-schemes', [NSAPController::class, 'index']);
Route::get('/nsap-schemes/{id}', [NSAPController::class, 'show']);
Route::get('/nsap-schemes/code/{scheme_code}', [NSAPController::class, 'getBySchemeCode']);