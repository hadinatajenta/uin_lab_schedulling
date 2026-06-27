<?php




use App\Domains\Profile\Controllers\ProfileController;
use App\Http\Controllers\RuanganController;


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

    // Management alat (Equipment)
    Route::get('/manajemen-alat', [\App\Domains\Equipment\Controllers\EquipmentController::class, 'index'])->name('alat');
    Route::get('/tambah-alat', [\App\Domains\Equipment\Controllers\EquipmentController::class, 'create'])->name('add.alat');
    Route::post('/tambah-alat', [\App\Domains\Equipment\Controllers\EquipmentController::class, 'store'])->name('post.alat');
    Route::delete('/hapus-alat/{id}', [\App\Domains\Equipment\Controllers\EquipmentController::class, 'destroy'])->name('hapus.alat');
    Route::get('/detail-alat/{id}', [\App\Domains\Equipment\Controllers\EquipmentController::class, 'show'])->name('detailAlat');
    Route::get('/edit-alat/{id}', [\App\Domains\Equipment\Controllers\EquipmentController::class, 'edit'])->name('editAlat');
    Route::put('/update-alat/{id}', [\App\Domains\Equipment\Controllers\EquipmentController::class, 'update'])->name('perbarui');
    //Peminjaman
    Route::get('pinjam-alat/{id}', [\App\Domains\Borrowing\Controllers\BorrowingController::class, 'pinjamAlat'])->name('pinjamAlat');
    Route::get('laporan-peminjaman', [\App\Domains\Borrowing\Controllers\BorrowingController::class, 'laporanPeminjaman'])->name('laporanPeminjaman');
    Route::post('ajukan-peminjaman/{id}', [\App\Domains\Borrowing\Controllers\BorrowingController::class, 'ajukanPeminjaman'])->name('ajukanPeminjaman');
    // Laporan
    Route::get('/laporan', [\App\Domains\Report\Controllers\ReportController::class, 'laporanView'])->name('laporanView');
    // Limbah (Wastes)
    Route::resource('wastes', \App\Domains\Waste\Controllers\WasteController::class);

    // About LAB
    Route::get('/tentang-lab', [\App\Domains\AboutLab\Controllers\AboutLabController::class, 'aboutLabView'])->name('tentangLab');
    Route::get('/edit-tentang-lab', [\App\Domains\AboutLab\Controllers\AboutLabController::class, 'editInfoView'])->name('editInfoLab');
    Route::put('/update-aboutlab', [\App\Domains\AboutLab\Controllers\AboutLabController::class, 'editInfo'])->name('editInfo');
    Route::post('/ubah-struktur', [\App\Domains\AboutLab\Controllers\AboutLabController::class, 'ubahStruktur'])->name('ubahStruktur');

    // SUPER ADMIN EXCLUSIVE ROUTES
    Route::middleware([\App\Http\Middleware\CheckJabatan::class])->group(function () {
        Route::resource('rooms', \App\Domains\Room\Controllers\RoomController::class);
        Route::put('rooms/{room}/deactivate', [\App\Domains\Room\Controllers\RoomController::class, 'deactivate'])->name('rooms.deactivate');
        Route::post('rooms/{room}/transfer-schedules', [\App\Domains\Room\Controllers\RoomController::class, 'transferSchedules'])->name('rooms.transfer_schedules');
        
        Route::post('rooms/{room}/maintenance', [\App\Domains\Room\Controllers\RoomController::class, 'scheduleMaintenance'])->name('rooms.maintenance.store');
        Route::put('rooms/maintenance/{maintenance}/complete', [\App\Domains\Room\Controllers\RoomController::class, 'completeMaintenance'])->name('rooms.maintenance.complete');
        Route::put('rooms/maintenance/{maintenance}/cancel', [\App\Domains\Room\Controllers\RoomController::class, 'cancelMaintenance'])->name('rooms.maintenance.cancel');

        Route::get('/departments', [\App\Domains\Department\Controllers\DepartmentController::class, 'index'])->name('departments.index');
    });

    // Activity Logs
    Route::get('/activity-logs', [\App\Domains\ActivityLog\Controllers\ActivityLogController::class, 'index'])->name('activity.logs');

    // Profile Settings
    Route::get('/profile-settings', [\App\Domains\Profile\Controllers\ProfileSettingsController::class, 'index'])->name('profile.settings');
    Route::put('/profile-settings', [\App\Domains\Profile\Controllers\ProfileSettingsController::class, 'update'])->name('profile.settings.update');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/chart-data', [PeminjamanConttroller::class, 'getChartData']);
    Route::get('/pie-chart-data', [PeminjamanConttroller::class, 'getPieChartData']);
});

require __DIR__ . '/auth.php';
