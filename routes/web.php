<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Auth\DirectPasswordResetController;
use App\Http\Controllers\Admin\SettingController;

// Root redirect
Route::get('/', function () {
    return redirect()->route('weather.index');
});

// -------------------------------------------------------
// MAINTENANCE MIDDLEWARE — berlaku untuk semua route
// kecuali login (agar admin bisa masuk saat maintenance)
// -------------------------------------------------------
Route::middleware([\App\Http\Middleware\MaintenanceMiddleware::class])->group(function () {

    // Direct Password Reset
    Route::middleware('guest')->group(function () {
        Route::get('/forgot-password', [DirectPasswordResetController::class, 'create'])
            ->name('password.request');
        Route::post('/forgot-password', [DirectPasswordResetController::class, 'store'])
            ->name('password.direct.store');
    });

    // Auth users
    Route::middleware(['auth'])->group(function () {
        Route::get('/weather', [WeatherController::class, 'index'])->name('weather.index');
        Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
        Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/dashboard', function () {
            return redirect()->route('weather.index');
        })->name('dashboard');
    });

    // Admin only
    Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::post('/admin/settings', [SettingController::class, 'update'])->name('admin.settings.update');
        Route::get('/admin/api-check', [SettingController::class, 'checkApi'])->name('admin.api.check');

    // User management
        Route::patch('/admin/users/{user}/toggle-role', [UserController::class, 'toggleRole'])->name('admin.users.toggle-role');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });


});

require __DIR__.'/auth.php';
