<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/*-------------------Administrator----------------------*/
Route::controller(RegisterController::class)->prefix('admin')->group(function () {
    Route::get('register', 'create')->name('admin.create');
    Route::post('register', 'store')->name('admin.store');
});

Route::controller(LoginController::class)->prefix('admin')->group(function () {
    Route::get('/', 'index')->name('admin.login');
    Route::post('/', 'login')->name('admin.login-access');
    Route::get('logout', 'destroy')->name('admin.logout');
});

Route::get('admin/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard-admin');
/*-------------------------End--------------------------*/