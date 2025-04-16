<?php

use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DataAdminController;
use App\Http\Controllers\DataDampinganController;
use App\Http\Controllers\DataFasilitatorController;
use App\Http\Controllers\DataMasyarakatController;
use App\Http\Controllers\KegiatanDampinganController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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

//Logout route
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

//Dashboard admin route
Route::get('/admin', [DashboardAdminController::class, 'index'])
    ->middleware(['auth', 'verified', EnsureUserRole::class . ':superadmin,admin'])
    ->name('dashboard');

//Data fasilitator route
Route::middleware(['auth', 'verified', EnsureUserRole::class . ':superadmin,admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/data-fasilitator', [DataFasilitatorController::class, 'index'])->name('fasilitator.index');
        Route::get('/data-fasilitator/create', [DataFasilitatorController::class, 'create'])->name('fasilitator.create');
        Route::post('/data-fasilitator', [DataFasilitatorController::class, 'store'])->name('fasilitator.store');
        Route::get('/data-fasilitator/{id}/edit', [DataFasilitatorController::class, 'edit'])->name('fasilitator.edit');
        Route::put('/data-fasilitator/{id}', [DataFasilitatorController::class, 'update'])->name('fasilitator.update');
        Route::delete('/data-fasilitator/{id}', [DataFasilitatorController::class, 'destroy'])->name('fasilitator.destroy');
    });

//Data admin route
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
Route::middleware(['auth', 'verified', EnsureUserRole::class . ':superadmin,admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/data-dampingan', [DataDampinganController::class, 'index'])->name('dampingan.index');
        Route::get('/data-dampingan/create', [DataDampinganController::class, 'create'])->name('dampingan.create');
        Route::post('/data-dampingan', [DataDampinganController::class, 'store'])->name('dampingan.store');
        Route::get('/data-dampingan/{id}/edit', [DataDampinganController::class, 'edit'])->name('dampingan.edit');
        Route::put('/data-dampingan/{id}', [DataDampinganController::class, 'update'])->name('dampingan.update');
        Route::delete('/data-dampingan/{id}', [DataDampinganController::class, 'destroy'])->name('dampingan.destroy');
    });

//Kegiatan dampingan route
Route::get('/admin/kegiatan-dampingan', [KegiatanDampinganController::class, 'index'])
    ->middleware(['auth', 'verified', EnsureUserRole::class . ':superadmin,admin'])
    ->name('kegiatan-dampingan');

//Dampingan route
Route::get('/api/dampingan-list', function () {
    return \App\Models\Bidang::all()->map(function ($bidang) {
        return [
            'value' => $bidang->id_bidang,
            'label' => $bidang->nama_bidang,
        ];
    });
});

//Coba filter
Route::get('/dropdown-data', [AdminController::class, 'getDropdownData']);
Route::get('/api/provinsi', [WilayahController::class, 'getProvinsi']);
Route::get('/api/kabupaten/{kode_provinsi}', [WilayahController::class, 'getKabupaten']);
Route::get('/api/kecamatan/{kode_kabupaten}', [WilayahController::class, 'getKecamatan']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
