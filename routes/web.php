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
->middleware(['auth', 'verified', EnsureUserRole::class.':superadmin,admin'])
->name('dashboard');

//Data fasilitator route
Route::get('/admin/data-fasilitator', [DataFasilitatorController::class, 'index'])
->middleware(['auth', 'verified', EnsureUserRole::class.':superadmin,admin'])
->name('data-fasilitator');

//Data admin route
Route::get('/admin/data-admin', [DataAdminController::class, 'index'])
->middleware(['auth', 'verified', EnsureUserRole::class.':superadmin,admin'])
->name('data-admin');

//Data masyarakat route
Route::get('/admin/data-masyarakat', [DataMasyarakatController::class, 'index'])
->middleware(['auth', 'verified', EnsureUserRole::class.':superadmin,admin'])
->name('data-masyarakat');

//Data dampingan route
Route::get('/admin/data-dampingan', [DataDampinganController::class, 'index'])
->middleware(['auth', 'verified', EnsureUserRole::class.':superadmin,admin'])
->name('data-dampingan');

//Kegiatan dampingan route
Route::get('/admin/kegiatan-dampingan', [KegiatanDampinganController::class, 'index'])
->middleware(['auth', 'verified', EnsureUserRole::class.':superadmin,admin'])
->name('kegiatan-dampingan');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
