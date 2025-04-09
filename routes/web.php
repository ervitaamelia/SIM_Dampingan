<?php

use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DataAdminController;
use App\Http\Controllers\DataDampinganController;
use App\Http\Controllers\DataFasilitatorController;
use App\Http\Controllers\DataMasyarakatController;
use App\Http\Controllers\KegiatanDampinganController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureUserRole;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Login route
Route::get('/', function () {
    return Inertia::render('Auth/Login');
});
Route::post('/', function () {
    return Inertia::render('Auth/Login');
});

//Dashboard admin route
Route::get('/admin', [DashboardAdminController::class, 'index'])
    ->middleware(['auth', 'verified', EnsureUserRole::class . ':superadmin,admin'])
    ->name('dashboard');

//Data fasilitator route
Route::get('/admin/data-fasilitator', [DataFasilitatorController::class, 'index'])
    ->middleware(['auth', 'verified', EnsureUserRole::class . ':superadmin,admin'])
    ->name('data-fasilitator');

//Data admin route
// Grup route untuk pengelolaan admin
Route::middleware(['auth', 'verified', EnsureUserRole::class . ':superadmin,admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/data-admin', [DataAdminController::class, 'index'])->name('admin.index'); // List Admin
        Route::get('/data-admin/create', [DataAdminController::class, 'create'])->name('admin.create'); // Form Tambah Admin
        Route::post('/data-admin', [DataAdminController::class, 'store'])->name('admin.store'); // Simpan Admin Baru
        Route::get('/data-admin/{id}/edit', [DataAdminController::class, 'edit'])->name('admin.edit'); // Form Edit Admin
        Route::put('/data-admin/{id}', [DataAdminController::class, 'update'])->name('admin.update'); // Update Admin
        Route::delete('/data-admin/{id}', [DataAdminController::class, 'destroy'])->name('admin.destroy'); // Hapus Admin
    });

//Data masyarakat route
Route::get('/admin/data-masyarakat', [DataMasyarakatController::class, 'index'])
    ->middleware(['auth', 'verified', EnsureUserRole::class . ':superadmin,admin'])
    ->name('data-masyarakat');

//Data dampingan route
Route::get('/admin/data-dampingan', [DataDampinganController::class, 'index'])
    ->middleware(['auth', 'verified', EnsureUserRole::class . ':superadmin,admin'])
    ->name('data-dampingan');

//Kegiatan dampingan route
Route::get('/admin/kegiatan-dampingan', [KegiatanDampinganController::class, 'index'])
    ->middleware(['auth', 'verified', EnsureUserRole::class . ':superadmin,admin'])
    ->name('kegiatan-dampingan');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
