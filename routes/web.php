<?php

use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardFasilitatorController;
use App\Http\Controllers\DataAdminController;
use App\Http\Controllers\DataDampinganController;
use App\Http\Controllers\DataFasilitatorController;
use App\Http\Controllers\DataKegiatanController;
use App\Http\Controllers\DataMasyarakatController;
use App\Http\Controllers\KegiatanDampinganController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Middleware\EnsureUserRole;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Login route
Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

Route::post('/', [AuthenticatedSessionController::class, 'store']);

//Dashboard Fasilitator
Route::middleware(['auth', 'role:fasilitator'])->get('/fasilitator', [DashboardFasilitatorController::class, 'index']);

//Logout route
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

//Dashboard admin route
Route::get('/admin', [DashboardAdminController::class, 'index'])
    ->middleware(['auth', 'verified', EnsureUserRole::class . ':superadmin,admin-provinsi,admin-kabupaten,admin-kecamatan'])
    ->name('dashboard');

// Route::get('/fasilitator', [DashboardFasilitatorController::class, 'index'])
//     ->middleware(['auth', 'verified', 'role:fasilitator', EnsureUserRole::class . ':fasilitator'])
//     ->name('dashboard');

//Bidang route
Route::get('/bidang', [BidangController::class, 'index'])->name('bidang.index');
Route::post('/bidang', [BidangController::class, 'store'])->name('bidang.store');
Route::delete('/bidang/{id}', [BidangController::class, 'destroy'])->name('bidang.destroy');

//Data fasilitator route
Route::middleware(['auth', 'verified', EnsureUserRole::class . ':superadmin,admin-provinsi,admin-kabupaten,admin-kecamatan'])
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
Route::middleware(['auth', 'verified', EnsureUserRole::class . ':superadmin,admin-provinsi,admin-kabupaten,admin-kecamatan'])
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
Route::middleware(['auth', 'verified', EnsureUserRole::class . ':superadmin,admin-provinsi,admin-kabupaten,admin-kecamatan'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/data-masyarakat', [DataMasyarakatController::class, 'index'])->name('masyarakat.index');
        Route::get('/data-masyarakat/create', [DataMasyarakatController::class, 'create'])->name('masyarakat.create');
        Route::post('/data-masyarakat', [DataMasyarakatController::class, 'store'])->name('masyarakat.store');
        Route::get('/data-masyarakat/{id}/edit', [DataMasyarakatController::class, 'edit'])->name('masyarakat.edit');
        Route::put('/data-masyarakat/{id}', [DataMasyarakatController::class, 'update'])->name('masyarakat.update');
        Route::delete('/data-masyarakat/{id}', [DataMasyarakatController::class, 'destroy'])->name('masyarakat.destroy');
    });

//Data dampingan route
Route::middleware(['auth', 'verified', EnsureUserRole::class . ':superadmin,admin-provinsi,admin-kabupaten,admin-kecamatan'])
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
    ->middleware(['auth', 'verified', EnsureUserRole::class . ':superadmin,admin-provinsi,admin-kabupaten,admin-kecamatan'])
    ->name('kegiatan-dampingan');

//Data kegiatan route
Route::middleware(['auth', 'verified', EnsureUserRole::class . ':fasilitator'])
    ->prefix('fasilitator')
    ->group(function () {
        Route::get('/data-kegiatan', [DataKegiatanController::class, 'index'])->name('kegiatan.index');
        Route::get('/data-kegiatan/create', [DataKegiatanController::class, 'create'])->name('kegiatan.create');
        Route::post('/data-kegiatan', [DataKegiatanController::class, 'store'])->name('kegiatan.store');
        Route::get('/data-kegiatan/{id}/edit', [DataKegiatanController::class, 'edit'])->name('kegiatan.edit');
        Route::put('/data-kegiatan/{id}', [DataKegiatanController::class, 'update'])->name('kegiatan.update');
        Route::post('/data-kegiatan/{id}', [DataKegiatanController::class, 'update'])->name('kegiatan.update');
        Route::delete('/data-kegiatan/{id}', [DataKegiatanController::class, 'destroy'])->name('kegiatan.destroy');
    });

// Route::middleware(['auth', 'role:fasilitator'])->get('/fasilitator/data-kegiatan', function () {
//     return Inertia::render('fasilitator/DataKegiatan');
// });
// Route::middleware(['auth', 'role:fasilitator'])->get('/fasilitator/FormKegiatan', function () {
//     return Inertia::render('fasilitator/FormKegiatan');
// });

//Dampingan route
Route::get('/api/dampingan-list', function () {
    return \App\Models\Bidang::all()->map(function ($bidang) {
        return [
            'value' => $bidang->id_bidang,
            'label' => $bidang->nama_bidang,
        ];
    });
});

//api
Route::get('/dropdown-data', [AdminController::class, 'getDropdownData']);
Route::get('/api/provinsi', [WilayahController::class, 'getProvinsi']);
Route::get('/api/kabupaten/{kode_provinsi}', [WilayahController::class, 'getKabupaten']);
Route::get('/api/kecamatan/{kode_kabupaten}', [WilayahController::class, 'getKecamatan']);
Route::get('/api/check-nama-grup', [DataDampinganController::class, 'checkNamaGrup']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
