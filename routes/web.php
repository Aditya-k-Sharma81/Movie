<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminSignupController;

Route::prefix('admin')->name('admin.')->group(function () {

    // Login Page
    Route::get('/login', function () {
        return view('admin.login');
    })->name('login');

    // AJAX Login Logic
    Route::post('/login', [AdminSignupController::class, 'login'])->name('login.post');

    // Protected Admin Routes
    Route::middleware(['admin.auth'])->group(function () {
        // Dashboard Page
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Profile Page
        Route::get('/profile', function () {
            $admin = \App\Models\AdminSignupModel::find(session('admin_id'));
            return view('admin.profile', compact('admin'));
        })->name('profile');

        // Profile Update Logic
        Route::post('/profile/update', [AdminSignupController::class, 'updateProfile'])->name('profile.update');

        // Movie Management
        Route::prefix('movies')->name('movies.')->group(function () {
            Route::get('/', [\App\Http\Controllers\MovieController::class, 'index'])->name('index');
            Route::get('/add', [\App\Http\Controllers\MovieController::class, 'create'])->name('add');
            Route::post('/store', [\App\Http\Controllers\MovieController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [\App\Http\Controllers\MovieController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [\App\Http\Controllers\MovieController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [\App\Http\Controllers\MovieController::class, 'destroy'])->name('delete');
        });

        // Screens / Seating Layout Builder
        Route::get('/screens/seating', function () {
            return view('admin.screens.seating');
        })->name('screens.seating');

        // Logout
        Route::get('/logout', [AdminSignupController::class, 'logout'])->name('logout');
    });
});
