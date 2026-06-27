<?php

use App\Http\Controllers\AlatController;

use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuanganController;

use App\Http\Controllers\WasteController;
use App\Http\Controllers\AboutLabController;
use App\Http\Controllers\PeminjamanConttroller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Admin - dosen
Route::prefix('/admin')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Domains\Dashboard\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Management user
    Route::get('/users', [\App\Domains\User\Controllers\UserController::class, 'index'])->name('users.index');
    Route::post('/add-user', [\App\Domains\User\Controllers\UserController::class, 'store'])->name('add.users');
    Route::put('/update-users/{id}', [\App\Domains\User\Controllers\UserController::class, 'update'])->name('update.users');
    Route::delete('/hapus/{id}', [\App\Domains\User\Controllers\UserController::class, 'destroy'])->name('delete.users');
    
    // User Import
    Route::get('/users/import', [\App\Domains\User\Controllers\UserImportController::class, 'index'])->name('users.import.view');
    Route::post('/users/validate-bulk', [\App\Domains\User\Controllers\UserImportController::class, 'validateBulk'])->name('users.import.validateBulk');
    Route::post('/users/validate-row', [\App\Domains\User\Controllers\UserImportController::class, 'validateRow'])->name('users.import.validateRow');
    Route::post('/users/process-bulk', [\App\Domains\User\Controllers\UserImportController::class, 'processBulk'])->name('users.import.processBulk');
    
    // Dynamic user route must be below static routes
    Route::get('/users/{id}', [\App\Domains\User\Controllers\UserController::class, 'show'])->name('users.show');
    
    // Management lab (Schedules)
    Route::get('/list-jadwal', [\App\Domains\Schedule\Controllers\ScheduleController::class, 'index'])->name('lab');
    Route::get('/jadwal-baru', [\App\Domains\Schedule\Controllers\ScheduleController::class, 'create'])->name('addJadwalView');
    Route::post('/jadwal-baru', [\App\Domains\Schedule\Controllers\ScheduleController::class, 'store'])->name('addJadwal');

    // Edit jadwal route
    Route::get('/update-jadwal/{id}', [\App\Domains\Schedule\Controllers\ScheduleController::class, 'edit'])->name('updateJadwal');
    Route::put('/edit-jadwal/{id}', [\App\Domains\Schedule\Controllers\ScheduleController::class, 'update'])->name('editJadwal');

    // Hapus jadwal route
    Route::delete('/hapus-jadwal/{id}', [\App\Domains\Schedule\Controllers\ScheduleController::class, 'destroy'])->name('hapusJadwal');

    // Status Management Routes
    Route::put('/cancel-jadwal/{id}', [\App\Domains\Schedule\Controllers\ScheduleController::class, 'cancel'])->name('cancelJadwal');
    Route::put('/complete-early/{id}', [\App\Domains\Schedule\Controllers\ScheduleController::class, 'completeEarly'])->name('completeEarly');

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
    // Limbah (Wastes)
    Route::resource('wastes', WasteController::class);
    // Jaslab
    Route::get('/pengaturan-jaslab', [\App\Http\Controllers\JaslabController::class, 'index'])->name('jaslabView');
    Route::put('/jaslab-ubah/{id}', [\App\Http\Controllers\JaslabController::class, 'update'])->name('ubahJaslab');
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
