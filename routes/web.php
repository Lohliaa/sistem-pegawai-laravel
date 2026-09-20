<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ProfilePegawaiController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\DataMouController;
use App\Http\Controllers\DataSkController;
use App\Http\Controllers\KinerjaStatusController;
use App\Http\Controllers\KinerjaPeriodeController;
use App\Http\Controllers\KinerjaPejabatController;
use App\Http\Controllers\KinerjaBahanController;
use App\Http\Controllers\FormPenilaianController;
use App\Http\Controllers\LaporanPenilaianController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Require Authentication)
Route::middleware('auth')->group(function () {
    
    // Dashboard - All authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pengajuan Routes
    Route::middleware('role:staf,admin')->group(function () {
        Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
    });

    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])->name('pengajuan.show');
    Route::delete('/pengajuan/{pengajuan}', [PengajuanController::class, 'destroy'])->name('pengajuan.destroy');

    // Approval Routes (Kanit & Kabid)
    Route::middleware('role:kanit,kabid')->group(function () {
        Route::post('/pengajuan/{pengajuan}/approve', [PengajuanController::class, 'approve'])->name('pengajuan.approve');
        Route::post('/pengajuan/{pengajuan}/reject', [PengajuanController::class, 'reject'])->name('pengajuan.reject');
    });

    // Admin Only Routes
    Route::middleware('role:admin')->group(function () {
        // Profile Pegawai
        Route::resource('profile-pegawai', ProfilePegawaiController::class);
        Route::get('profile-pegawai/{id}/export', [ProfilePegawaiController::class, 'export'])->name('profile-pegawai.export');
        
        // Manajemen User
        Route::get('manajemen-user/template', [ManajemenUserController::class, 'templateExcel'])->name('manajemen-user.template');
        Route::resource('manajemen-user', ManajemenUserController::class);
        Route::get('manajemen-user/export/excel', [ManajemenUserController::class, 'exportExcel'])->name('manajemen-user.export');
        Route::post('manajemen-user/upload-excel', [ManajemenUserController::class, 'importExcel'])->name('manajemen-user.import');
        
        // Data MOU
        Route::get('data-mou/template', [DataMouController::class, 'template'])->name('data-mou.template');
        Route::resource('data-mou', DataMouController::class);
        Route::post('data-mou/import', [DataMouController::class, 'import'])->name('data-mou.import');
        Route::get('data-mou/export/excel', [DataMouController::class, 'export'])->name('data-mou.export');
        
        // Data SK
        Route::get('data-sk/template', [DataSkController::class, 'template'])->name('data-sk.template');
        Route::resource('data-sk', DataSkController::class);
        Route::post('data-sk/import', [DataSkController::class, 'import'])->name('data-sk.import');
        Route::get('data-sk/export/excel', [DataSkController::class, 'export'])->name('data-sk.export');
    });

    // Penilaian Kinerja Routes (Admin, Kanit, Kabid) - Full CRUD
    Route::middleware('role:admin,kanit,kabid')->group(function () {
        // Status Kepegawaian
        Route::resource('kinerja-status', KinerjaStatusController::class);
        
        // Periode Penilaian
        Route::resource('kinerja-periode', KinerjaPeriodeController::class);
        
        // Pejabat Penilai
        Route::resource('kinerja-pejabat', KinerjaPejabatController::class);
        
        // Bahan Penilaian
        Route::resource('kinerja-bahan', KinerjaBahanController::class);
        
        // Form Penilaian
        Route::get('form-penilaian', [FormPenilaianController::class, 'index'])->name('form-penilaian.index');
        Route::get('form-penilaian/create', [FormPenilaianController::class, 'create'])->name('form-penilaian.create');
        Route::post('form-penilaian', [FormPenilaianController::class, 'store'])->name('form-penilaian.store');
        Route::get('form-penilaian/{id}', [FormPenilaianController::class, 'show'])->name('form-penilaian.show');
        Route::get('form-penilaian/{id}/edit', [FormPenilaianController::class, 'edit'])->name('form-penilaian.edit');
        Route::put('form-penilaian/{id}', [FormPenilaianController::class, 'update'])->name('form-penilaian.update');
        Route::delete('form-penilaian/{id}', [FormPenilaianController::class, 'destroy'])->name('form-penilaian.destroy');
        
        // Laporan Penilaian
        Route::get('laporan-penilaian', [LaporanPenilaianController::class, 'index'])->name('laporan-penilaian.index');
        Route::get('laporan-penilaian/detail/{id}', [LaporanPenilaianController::class, 'detail'])->name('laporan-penilaian.detail');
        Route::get('laporan-penilaian/export', [LaporanPenilaianController::class, 'export'])->name('laporan-penilaian.export');
    });

    // Penilaian Kinerja Read-Only Routes for Staf (Status, Periode, Pejabat, Bahan)
    Route::middleware('role:staf')->group(function () {
        Route::get('kinerja-status', [KinerjaStatusController::class, 'index'])->name('kinerja-status.index');
        Route::get('kinerja-status/{kinerja_status}', [KinerjaStatusController::class, 'show'])->name('kinerja-status.show');
        
        Route::get('kinerja-periode', [KinerjaPeriodeController::class, 'index'])->name('kinerja-periode.index');
        Route::get('kinerja-periode/{kinerja_periode}', [KinerjaPeriodeController::class, 'show'])->name('kinerja-periode.show');
        
        Route::get('kinerja-pejabat', [KinerjaPejabatController::class, 'index'])->name('kinerja-pejabat.index');
        Route::get('kinerja-pejabat/{kinerja_pejabat}', [KinerjaPejabatController::class, 'show'])->name('kinerja-pejabat.show');
        
        Route::get('kinerja-bahan', [KinerjaBahanController::class, 'index'])->name('kinerja-bahan.index');
        Route::get('kinerja-bahan/{kinerja_bahan}', [KinerjaBahanController::class, 'show'])->name('kinerja-bahan.show');
    });
});

Route::get('/print/penilaian/{id}', [\App\Http\Controllers\PrintController::class, 'cetak'])->name('print.penilaian');
