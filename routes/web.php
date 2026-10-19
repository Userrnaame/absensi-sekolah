<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PortalController;

/*
|--------------------------------------------------------------------------
| PORTAL SEKOLAH (Public — tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', [PortalController::class, 'index'])->name('portal.index');
Route::get('/portal/kelas/{kodeKelas}', [PortalController::class, 'kelas'])->name('portal.kelas');

/*
|--------------------------------------------------------------------------
| AUTH (belum login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| BUTUH LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Absensi
    Route::get('/absensi', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/absensi', [\App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/riwayat', [\App\Http\Controllers\AttendanceController::class, 'history'])->name('attendance.history');

    // Pengajuan izin / sakit / cuti
    Route::middleware('role:siswa,guru')->group(function () {
        Route::get('/izin', [\App\Http\Controllers\LeaveRequestController::class, 'index'])->name('izin.index');
        Route::get('/izin/buat', [\App\Http\Controllers\LeaveRequestController::class, 'create'])->name('izin.create');
        Route::post('/izin', [\App\Http\Controllers\LeaveRequestController::class, 'store'])->name('izin.store');
        Route::delete('/izin/{leave_request}', [\App\Http\Controllers\LeaveRequestController::class, 'destroy'])->name('izin.destroy');
    });

    // Profil
    Route::get('/profil', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Admin only
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/generate-nis', [UserController::class, 'generateNisAjax'])
            ->name('users.generate-nis');

        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])
            ->name('settings.index');
        Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])
            ->name('settings.update');

        Route::get('izin', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'index'])
            ->name('izin.index');
        Route::get('izin/{leave_request}', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'show'])
            ->name('izin.show');
        Route::post('izin/{leave_request}/setujui', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'approve'])
            ->name('izin.approve');
        Route::post('izin/{leave_request}/tolak', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'reject'])
            ->name('izin.reject');
    });
});