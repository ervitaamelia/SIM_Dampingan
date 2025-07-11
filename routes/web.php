<?php
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardFasilitatorController;
use App\Http\Controllers\DataAdminController;
use App\Http\Controllers\DataDampinganController;
use App\Http\Controllers\DataFasilitatorController;
use App\Http\Controllers\DataKegiatanController;
use App\Http\Controllers\DataMasyarakatController;
use App\Http\Controllers\KegiatanDampinganController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\KelolaKegiatanController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Middleware\EnsureUserRole;
use App\Http\Middleware\CheckMustChangePassword;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Login route
Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

Route::post('/', [AuthenticatedSessionController::class, 'store']);

//Dashboard fasilitator route
Route::middleware(['auth', 'role:fasilitator', CheckMustChangePassword::class])
    ->get('/fasilitator', [DashboardFasilitatorController::class, 'index'])
    ->name('fasilitator.dashboard');

//Dashboard admin route
Route::get('/admin', [DashboardAdminController::class, 'index'])
    ->middleware(['auth', 'verified', EnsureUserRole::class . ':superadmin,admin-provinsi,admin-kabupaten,admin-kecamatan', CheckMustChangePassword::class])
    ->name('admin.dashboard');

//Ubah password route
Route::middleware('auth')->get('/ubah-password', [PasswordChangeController::class, 'edit'])->name('password.change.edit');
Route::middleware('auth')->post('/ubah-password', [PasswordChangeController::class, 'update'])->name('password.change.update');

//Logout route
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

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
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::post('/fasilitator/{id}/reset-password', [DataFasilitatorController::class, 'resetPassword'])->name('fasilitator.reset-password');
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
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::post('/admin/{id}/reset-password', [DataAdminController::class, 'resetPassword'])->name('admin.reset-password');
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
Route::get('/artikel/{id}', [KegiatanDampinganController::class, 'show']);

Route::middleware(['auth', 'verified'])->prefix('admin/kelola-kegiatan')->name('admin.kegiatan.')->group(function () {
    // Halaman utama (bisa berupa komponen Vue langsung atau via controller)
    Route::get('/', [KelolaKegiatanController::class, 'index'])->name('index');

    // Validasi kegiatan
    Route::put('/{id}/validasi', [KelolaKegiatanController::class, 'validasi'])->name('validasi');

    // Tolak kegiatan
    Route::put('/{id}/tolak', [KelolaKegiatanController::class, 'tolak'])->name('tolak');
});

//Data kegiatan route
Route::middleware(['auth', 'verified', EnsureUserRole::class . ':fasilitator'])
    ->prefix('fasilitator')
    ->group(function () {
        Route::get('/data-kegiatan', [DataKegiatanController::class, 'index'])->name('kegiatan.index');
        Route::get('/data-kegiatan/create', [DataKegiatanController::class, 'create'])->name('kegiatan.create');
        Route::post('/data-kegiatan', [DataKegiatanController::class, 'store'])->name('kegiatan.store');
        Route::get('/data-kegiatan/{id}/edit', [DataKegiatanController::class, 'edit'])->name('kegiatan.edit');
        Route::post('/data-kegiatan/{id}', [DataKegiatanController::class, 'update'])->name('kegiatan.update');
        Route::delete('/data-kegiatan/{id}', [DataKegiatanController::class, 'destroy'])->name('kegiatan.destroy');
    });

//Dampingan route
Route::get('/api/dampingan-list', function () {
    return \App\Models\Bidang::all()->map(function ($bidang) {
        return [
            'value' => $bidang->id_bidang,
            'label' => $bidang->nama_bidang,
        ];
    });
});

Route::post('/simpan-nomor-telepon', [KontakController::class, 'simpan']);
Route::get('/nomor-telepon', function () {
    return DB::table('kontak')->value('nomor_telepon');
});

//api

Route::get('/api/provinsi', [WilayahController::class, 'getProvinsi']);
Route::get('/api/kabupaten/{kode_provinsi}', [WilayahController::class, 'getKabupaten']);
Route::get('/api/kecamatan/{kode_kabupaten}', [WilayahController::class, 'getKecamatan']);
Route::get('/api/check-nama-grup', [DataDampinganController::class, 'checkNamaGrup']);
Route::get('/api/check-username', [DataFasilitatorController::class, 'checkUsername']);



require __DIR__ . '/auth.php';
