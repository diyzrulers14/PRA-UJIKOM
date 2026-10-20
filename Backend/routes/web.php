<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WEB\AuthController;
use App\Http\Controllers\WEB\AdminController;
use App\Http\Controllers\WEB\PetugasController;
use App\Http\Controllers\WEB\PeminjamController;
use App\Http\Controllers\WEB\LogAktivitasController;

// ====================
// HALAMAN AWAL
// ====================

Route::get('/', function () {
    return redirect()->route('login');
});

// ====================
// AUTH
// ====================

Route::middleware('guest')->group(function () {

    // Halaman Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');

    // Halaman Registrasi
    Route::get('/register', [AuthController::class, 'showRegisterForm'])
        ->name('register');

    // Proses Registrasi
    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.store');

    // Proses Login
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// ====================
// ADMIN
// ====================

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ====================
        // DASHBOARD
        // ====================

        Route::get('/dashboard', [AdminController::class, 'index'])
            ->name('dashboard');


        // ====================
        // CRUD ALAT
        // ====================

        Route::get('/alat', [AdminController::class, 'indexAlat'])
            ->name('alat.index');

        Route::get('/alat/create', [AdminController::class, 'createAlat'])
            ->name('alat.create');

        Route::post('/alat', [AdminController::class, 'storeAlat'])
            ->name('alat.store');

        Route::get('/alat/{id}/edit', [AdminController::class, 'editAlat'])
            ->name('alat.edit');

        Route::put('/alat/{id}', [AdminController::class, 'updateAlat'])
            ->name('alat.update');

        Route::delete('/alat/{id}', [AdminController::class, 'destroyAlat'])
            ->name('alat.destroy');


        // ====================
        // CRUD USER
        // ====================

        Route::get('/users', [AdminController::class, 'indexUser'])
            ->name('user.index');

        Route::get('/users/create', [AdminController::class, 'createUser'])
            ->name('user.create');

        Route::post('/users', [AdminController::class, 'storeUser'])
            ->name('user.store');

        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])
            ->name('user.edit');

        Route::put('/users/{id}', [AdminController::class, 'updateUser'])
            ->name('user.update');

        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])
            ->name('user.destroy');


        // ====================
        // CRUD KATEGORI
        // ====================

        Route::get('/kategori', [AdminController::class, 'indexKategori'])
            ->name('kategori.index');

        Route::get('/kategori/create', [AdminController::class, 'createKategori'])
            ->name('kategori.create');

        Route::post('/kategori', [AdminController::class, 'storeKategori'])
            ->name('kategori.store');

        Route::get('/kategori/{id}/edit', [AdminController::class, 'editKategori'])
            ->name('kategori.edit');

        Route::put('/kategori/{id}', [AdminController::class, 'updateKategori'])
            ->name('kategori.update');

        Route::delete('/kategori/{id}', [AdminController::class, 'destroyKategori'])
            ->name('kategori.destroy');


        // ====================
        // CRUD PEMINJAMAN
        // ====================

        Route::get('/peminjaman', [AdminController::class, 'indexPeminjaman'])
            ->name('peminjaman.index');

        Route::get('/peminjaman/create', [AdminController::class, 'createPeminjaman'])
            ->name('peminjaman.create');

        Route::post('/peminjaman', [AdminController::class, 'storePeminjaman'])
            ->name('peminjaman.store');

        Route::put('/peminjaman/{id}/status', [AdminController::class, 'updateStatusPeminjaman'])
            ->name('peminjaman.updateStatus');

        Route::delete('/peminjaman/{id}', [AdminController::class, 'destroyPeminjaman'])
            ->name('peminjaman.destroy');


        // ====================
        // KELOLA PENGEMBALIAN
        // ====================

        Route::get('/pengembalian', [AdminController::class, 'indexPengembalian'])
            ->name('pengembalian.index');

        Route::get('/pengembalian/{id}/create', [AdminController::class, 'createPengembalian'])
            ->name('pengembalian.create');

        Route::put('/pengembalian/{id}', [AdminController::class, 'kembalikan'])
            ->name('pengembalian.kembalikan');

        // Menampilkan daftar riwayat pengembalian
        Route::get('/pengembalian', [AdminController::class, 'indexPengembalian'])
            ->name('pengembalian.index');
        
        // Memproses pengembalian (saat tombol diklik)
        Route::post('/pengembalian/{id}/proses', [AdminController::class, 'kembalikan'])
            ->name('pengembalian.proses');


        // ====================
        // LOG AKTIVITAS
        // ====================

        Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])
            ->name('logAktivitas.index');
    });


// ====================
// PETUGAS
// ====================

Route::middleware(['auth', 'role:petugas,admin'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {

        // Daftar peminjaman
        Route::get('/peminjaman', [PetugasController::class, 'indexPeminjaman'])
            ->name('peminjaman.index');

        // Menyetujui peminjaman
        Route::post('/peminjaman/{id}/setujui', [PetugasController::class, 'setujuiPeminjaman'])
            ->name('peminjaman.setujui');

        // Menolak peminjaman (Direvisi agar prefix tidak double & disesuaikan ke PetugasController)
        Route::post('/peminjaman/{id}/tolak', [PetugasController::class, 'tolakPeminjaman'])
            ->name('peminjaman.tolak');

        // Pemantauan / Daftar Pengembalian
        Route::get('/pengembalian', [PetugasController::class, 'indexPengembalian'])
            ->name('pengembalian.index');

        // Proses pengembalian (Aksi form post)
        Route::post('/pengembalian/{id}', [PetugasController::class, 'prosesPengembalian'])
            ->name('pengembalian.proses');

        // Cetak Laporan (Duplikasi dihapus, disatukan di sini)
        Route::get('/laporan', [PetugasController::class, 'laporan'])
            ->name('laporan.index');
            
        Route::get('/laporan/cetak', [PetugasController::class, 'cetakLaporan'])
            ->name('laporan.cetak');

    });


// ====================
// PEMINJAM
// ====================

Route::middleware(['auth', 'role:peminjam'])
    ->prefix('peminjam')
    ->name('peminjam.')
    ->group(function () {

        // Katalog alat
        Route::get(
            '/katalog',
            [PeminjamController::class, 'katalogAlat']
        )->name('katalog');

        Route::post(
            '/ajukan',
            [PeminjamController::class, 'ajukanPeminjaman']
        )->name('ajukan');

        // Riwayat peminjaman
        Route::get(
            '/riwayat',
            [PeminjamController::class, 'riwayatPeminjam']
        )->name('riwayat');
    }); 