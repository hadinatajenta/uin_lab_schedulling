<?php

use App\Http\Controllers\AlatController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\LimbahController;
use App\Http\Controllers\AboutLabController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Admin - dosen
Route::prefix('/admin')->group(function () {
    // Management user
    Route::get('/dashboard', [UsersController::class, 'usersView'])->name('dashboard');
    Route::post('/add-user', [UsersController::class, 'addUser'])->name('add.users');
    Route::put('/update-users/{id}', [UsersController::class, 'usersUpdate'])->name('update.users');
    Route::delete('/hapus/{id}', [UsersController::class, 'deleteUser'])->name('delete.users');
    // Management lab
    Route::get('/list-jadwal', [JadwalController::class, 'jadwalView'])->name('lab');
    Route::get('/jadwal-baru', [JadwalController::class, 'addJadwalView'])->name('addJadwalView');
    Route::get('/update-jadwal/{id}', [JadwalController::class, 'updateJadwal'])->name('updateJadwal');
    Route::post('/jadwal-baru', [JadwalController::class, 'addJadwal'])->name('addJadwal');
    Route::put('/edit-jadwal/{id}', [JadwalController::class, 'editjadwal'])->name('editJadwal');
    Route::delete('/hapus-jadwal/{id}', [JadwalController::class, 'hapusJadwal'])->name('hapusJadwal');
    // Management alat
    Route::get('/manajemen-alat', [AlatController::class, 'alatView'])->name('alat');
    Route::get('/tambah-alat', [AlatController::class, 'tambahAlatView'])->name('add.alat');
    Route::post('/tambah-alat', [AlatController::class, 'addAlat'])->name('post.alat');
    Route::delete('/hapus-alat/{id}', [AlatController::class, 'deleteAlat'])->name('hapus.alat');
    Route::get('/detail-alat/{id}', [AlatController::class, 'alat'])->name('detailAlat');
    Route::post('/detail-alat/{id}', [AlatController::class, 'detailAlat'])->name('updateAlat');
    // Laporan
    Route::get('/laporan', [LaporanController::class, 'laporanView'])->name('laporanView');
    // Limbah
    Route::get('/limbah', [LimbahController::class, 'limbahView'])->name('limbah');
    Route::get('/tambah-limbah', [LimbahController::class, 'tambahLimbahView'])->name('tambahLimbah');
    Route::post('/create-limbah', [LimbahController::class, 'create'])->name('limbah.store');
    Route::get('/detail-limbah/{id}', [LimbahController::class, 'detailLimbah'])->name('detailLimbah');
    Route::delete('/hapus-limbah/{id}', [LimbahController::class, 'hapusLimbah'])->name('hapusLimbah');
    // Jaslab
    Route::put('/jaslab-ubah/{id}', [UsersController::class, 'ubahJaslab'])->name('ubahJaslab');
    // about lab
    Route::get('/tentang-lab', [AboutLabController::class, 'aboutLabView'])->name('tentangLab');
    Route::get('/edit-tentang-lab', [AboutLabController::class, 'editInfoView'])->name('editInfoLab');
    Route::put('/update-aboutlab', [AboutLabController::class, 'editInfo'])->name('editInfo');

});


require __DIR__ . '/auth.php';
