<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/auth/login');

// Include custom web routes
require __DIR__ . '/customWebRoutes/dashboard.php';
require __DIR__ . '/customWebRoutes/auth.php';
