<?php
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;


Route::middleware(['auth', 'PreventBackHistory'])->group(function () {
    /* =================== LOGOUT ROUTE =======================*/
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::controller(HomeController::class)->group(function () {
            Route::get('', 'index')->name('home');
        });

        /* =================== NEW USERS ROUTES =======================*/ 
        Route::group(['middleware' => ['can:view new users']], function () {
            Route::get('/new/users', [UserController::class, 'index'])->name('user.view.new');
            Route::get('/new/users/count', [UserController::class, 'countUsersWithoutRoles'])->name('user.count');
        });

        Route::group(['middleware' => ['can:edit new user']], function () {
            Route::get('/new/user/{id}', [UserController::class, 'getWithoutRoleUserData'])->name('user.new.data');
            Route::post('/new/user/edit/{id}', [UserController::class, 'updateWithoutRoleUserData'])->name('user.new.edit');
         });

        Route::group(['middleware' => ['can:delete new user']], function () {
            Route::delete('/new/user/delete/{id}', [UserController::class, 'destroyWithoutRoleUserData'])->name('user.new.delete');
        });

        Route::group(['middleware' => ['can:assign role']], function () {
            Route::get('/roles', [UserController::class, 'getRoles'])->name('roles.list');
            Route::post('/new/user/assign-role/{id}', [UserController::class, 'assignRole'])->name('user.new.assign.role');
        });

        /* =================== USERS ROUTES =======================*/
        Route::group(['middleware' => ['can:view users']], function () {
            Route::get('/users', [UserController::class, 'loadUserView'])->name('user.view');
        });

        Route::group(['middleware' => ['can:edit user']], function () {
            Route::get('/user/{id}', [UserController::class, 'getUserData'])->name('user.data');
            Route::get('/user/roles', [UserController::class, 'getRolesForUser'])->name('roles.list.user');
            Route::post('/user/edit/{id}', [UserController::class, 'updateUserData'])->name('user.edit');
        });

        Route::group(['middleware' => ['can:delete user']], function () {
            Route::delete('/user/delete/{id}', [UserController::class, 'destroyUserData'])->name('user.delete');
        });
        

    });



});

