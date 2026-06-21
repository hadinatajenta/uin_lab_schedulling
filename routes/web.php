<?php

use App\Http\Controllers\AlatController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\LimbahController;
use App\Http\Controllers\AboutLabController;
use App\Http\Controllers\PeminjamanConttroller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Admin - dosen
Route::prefix('/admin')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Management user
    Route::get('/users', [UsersController::class, 'usersView'])->name('users.index');
    Route::post('/add-user', [UsersController::class, 'addUser'])->name('add.users');
    Route::put('/update-users/{id}', [UsersController::class, 'usersUpdate'])->name('update.users');
    Route::delete('/hapus/{id}', [UsersController::class, 'deleteUser'])->name('delete.users');
    // Management lab
    Route::get('/list-jadwal', [JadwalController::class, 'jadwalView'])->name('lab');
    Route::get('/jadwal-baru', [JadwalController::class, 'addJadwalView'])->name('addJadwalView');
    Route::post('/jadwal-baru', [JadwalController::class, 'addJadwal'])->name('addJadwal');

    // Edit jadwal route
    Route::get('/update-jadwal/{id}', [JadwalController::class, 'updateJadwal'])->name('updateJadwal');
    Route::put('/edit-jadwal/{id}', [JadwalController::class, 'editjadwal'])->name('editJadwal');

    // Hapus jadwal route
    Route::delete('/hapus-jadwal/{id}', [JadwalController::class, 'hapusJadwal'])->name('hapusJadwal');

    // Status Management Routes
    Route::put('/cancel-jadwal/{id}', [JadwalController::class, 'cancelJadwal'])->name('cancelJadwal');
    Route::put('/complete-early/{id}', [JadwalController::class, 'completeEarly'])->name('completeEarly');

    // Management alat
    Route::get('/manajemen-alat', [AlatController::class, 'alatView'])->name('alat');
    Route::get('/tambah-alat', [AlatController::class, 'tambahAlatView'])->name('add.alat');
    Route::post('/tambah-alat', [AlatController::class, 'addAlat'])->name('post.alat');
    Route::delete('/hapus-alat/{id}', [AlatController::class, 'deleteAlat'])->name('hapus.alat');
    Route::get('/detail-alat/{id}', [AlatController::class, 'alat'])->name('detailAlat');
    Route::post('/detail-alat/{id}', [AlatController::class, 'detailAlat'])->name('updateAlat');
    Route::get('/edit-alat/{id}', [AlatController::class, 'editAlat'])->name('editAlat');
    Route::put('/update-alat/{id}', [AlatController::class, 'updateAlat'])->name('perbarui');
    //Peminjaman
    Route::get('pinjam-alat/{id}', [PeminjamanConttroller::class, 'pinjamAlat'])->name('pinjamAlat');
    Route::get('laporan-peminjaman', [PeminjamanConttroller::class, 'laporanPeminjaman'])->name('laporanPeminjaman');
    Route::post('ajukan-peminjaman/{id}', [PeminjamanConttroller::class, 'ajukanPeminjaman'])->name('ajukanPeminjaman');
    // Laporan
    Route::get('/laporan', [LaporanController::class, 'laporanView'])->name('laporanView');
    // Limbah
    Route::get('/limbah', [LimbahController::class, 'limbahView'])->name('limbah');
    Route::get('/tambah-limbah', [LimbahController::class, 'tambahLimbahView'])->name('tambahLimbah');
    Route::post('/create-limbah', [LimbahController::class, 'create'])->name('limbah.store');
    Route::get('/detail-limbah/{id}', [LimbahController::class, 'detailLimbah'])->name('detailLimbah');
    Route::delete('/hapus-limbah/{id}', [LimbahController::class, 'hapusLimbah'])->name('hapusLimbah');
    // Jaslab
    Route::get('/pengaturan-jaslab', [UsersController::class, 'jaslabView'])->name('jaslabView');
    Route::put('/jaslab-ubah/{id}', [UsersController::class, 'ubahJaslab'])->name('ubahJaslab');
    // about lab
    Route::get('/tentang-lab', [AboutLabController::class, 'aboutLabView'])->name('tentangLab');
    Route::get('/edit-tentang-lab', [AboutLabController::class, 'editInfoView'])->name('editInfoLab');
    Route::put('/update-aboutlab', [AboutLabController::class, 'editInfo'])->name('editInfo');

    // Activity Logs
    Route::get('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity.logs');

    // Profile Settings
    Route::get('/profile-settings', [\App\Http\Controllers\ProfileSettingsController::class, 'index'])->name('profile.settings');
    Route::put('/profile-settings', [\App\Http\Controllers\ProfileSettingsController::class, 'update'])->name('profile.settings.update');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/chart-data', [PeminjamanConttroller::class, 'getChartData']);
    Route::get('/pie-chart-data', [PeminjamanConttroller::class, 'getPieChartData']);
});

require __DIR__ . '/auth.php';
