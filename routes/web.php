<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HakaksesController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\KelolaBarangController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PersetujuanController;
use App\Http\Controllers\KerusakanController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\StatistikController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // ==========================================
    // FITUR UMUM (Semua Role)
    // ==========================================
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');

    // MENGELOLA NOTIFIKASI UMUM (Grup Notifikasi)
    Route::prefix('notifications')->name('notifications.')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/unread-count', 'unreadCount')->name('unread-count');
        Route::get('/recent', 'recent')->name('recent');
        Route::post('/mark-all-read', 'markAllAsRead')->name('mark-all-read');
        Route::delete('/destroy-all', 'destroyAll')->name('destroy-all'); 
        Route::get('/send-test', 'sendTest')->name('send-test');
        Route::post('/{id}/mark-as-read', 'markAsRead')->name('mark-as-read');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    // ==========================================
    // FITUR KHUSUS SUPERADMIN
    // ==========================================
    Route::middleware('role:superadmin')->group(function () {
        
        // AKSI NOTIFIKASI ADMIN (Grup Notifikasi Khusus Superadmin)
        Route::prefix('notifications')->name('notifications.')->controller(NotificationController::class)->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/send', 'send')->name('send');
        });

        // Hak Akses
        Route::get('/hakakses', [HakaksesController::class, 'index'])->name('hakakses.index');
        Route::get('/hakakses/edit/{id}', [HakaksesController::class, 'edit'])->name('hakakses.edit');
        Route::put('/hakakses/update/{id}', [HakaksesController::class, 'update'])->name('hakakses.update');
        Route::delete('/hakakses/delete/{id}', [HakaksesController::class, 'destroy'])->name('hakakses.delete');
        Route::get('/hakakses/create', [HakaksesController::class, 'create'])->name('hakakses.create');
        Route::post('/hakakses', [HakaksesController::class, 'store'])->name('hakakses.store');

        // Kelola Data Barang (CRUD)
        Route::resource('kelola-barang', KelolaBarangController::class);

        // Activity logs
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/activity-logs/{id}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
        Route::delete('/activity-logs/{id}', [ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
        Route::delete('/activity-logs', [ActivityLogController::class, 'clear'])->name('activity-logs.clear');

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
        Route::post('/settings/reset', [SettingController::class, 'reset'])->name('settings.reset');

        // Manage Device
        Route::get('/persetujuan-peminjaman', [PersetujuanController::class, 'index'])->name('persetujuan-peminjaman.index');
        Route::get('/persetujuan-peminjaman/{id}', [PersetujuanController::class, 'show'])->name('persetujuan-peminjaman.show');
        Route::put('/persetujuan-peminjaman/{id}', [PersetujuanController::class, 'update'])->name('persetujuan-peminjaman.update');

        // Laporan Statistik
        Route::get('/statistik', [StatistikController::class, 'index'])->name('statistik.index');
    });

    // ==========================================
    // FITUR GURU & SISWA
    // ==========================================
    Route::middleware('role:teacher|student')->group(function () {

        // Katalog Barang
        Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
        Route::get('/katalog/{id}', [KatalogController::class, 'show'])->name('katalog.show');

        // Peminjaman (Ajukan pinjaman, lihat status)
        Route::resource('peminjaman-saya', PeminjamanController::class);
        Route::post('/peminjaman-saya/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman-saya.kembalikan');
        Route::get('/peminjaman-saya', [PeminjamanController::class, 'index'])->name('peminjaman-saya.index');
        Route::get('/peminjaman-saya/create', [PeminjamanController::class, 'create'])->name('peminjaman-saya.create');
        Route::post('/peminjaman-saya', [PeminjamanController::class, 'store'])->name('peminjaman-saya.store');
        Route::put('/peminjaman-saya/{id}/batalkan', [PeminjamanController::class, 'batalkan'])->name('peminjaman-saya.batalkan');

        // Pelaporan Kerusakan
        Route::resource('lapor-kerusakan', KerusakanController::class)->except(['destroy']);
    });

    // ==========================================
    // FITUR TEKNISI & ADMIN
    // ==========================================
    Route::middleware(['role:superadmin|technician'])->group(function () {

        // Memantau Daftar Perbaikan
        Route::get('/kelola-perbaikan', [PerbaikanController::class, 'index'])->name('kelola-perbaikan.index');
        Route::get('/kelola-perbaikan/{id}', [PerbaikanController::class, 'show'])->name('kelola-perbaikan.show');
        
        // Teknisi memperbarui status (Admin hanya bisa melihat)
        Route::put('/kelola-perbaikan/{id}/status', [PerbaikanController::class, 'updateStatus'])->name('kelola-perbaikan.status');
    });
});