<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\EnsureUserRole;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Auth/Login');
});
Route::post('/', function () {
    return Inertia::render('Auth/Login');
});

Route::get('/admin', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', EnsureUserRole::class.':superadmin,admin'])->name('dashboard');

Route::get('/admin/data-admin', function () {
    return Inertia::render('admin/DataAdminView');
})->middleware(['auth', 'verified', EnsureUserRole::class.':superadmin,admin'])->name('data-admin');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
