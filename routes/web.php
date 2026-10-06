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
use App\Http\Controllers\SopKepegawaianController;
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
    
    // Approval Routes
    Route::middleware('role:kanit,kabid')->group(function () {
        Route::post('/pengajuan/{pengajuan}/approve', [PengajuanController::class, 'approve'])->name('pengajuan.approve');
        Route::post('/pengajuan/{pengajuan}/reject', [PengajuanController::class, 'reject'])->name('pengajuan.reject');
    });

    // Admin Process / Complete Routes
    Route::middleware('role:admin')->group(function () {
        Route::post('/pengajuan/{pengajuan}/process', [PengajuanController::class, 'processAdmin'])->name('pengajuan.process');
        Route::post('/pengajuan/{pengajuan}/complete', [PengajuanController::class, 'completeAdmin'])->name('pengajuan.complete');
        Route::get('/pengajuan-export', [PengajuanController::class, 'export'])->name('pengajuan.export');
    });

    Route::delete('/pengajuan/{pengajuan}', [PengajuanController::class, 'destroy'])->name('pengajuan.destroy');


    Route::get('/pengajuan/riwayat', [PengajuanController::class, 'riwayat'])->name('pengajuan.riwayat');
    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])->name('pengajuan.show');


    // Profile Pegawai (Accessible to all authenticated users)
    Route::get('profile-pegawai', [ProfilePegawaiController::class, 'index'])->name('profile-pegawai.index');
    Route::get('profile-pegawai/{profile_pegawai}', [ProfilePegawaiController::class, 'show'])->name('profile-pegawai.show');

    // Admin Only Routes
    Route::middleware('role:admin')->group(function () {
        // Profile Pegawai Management
        Route::get('profile-pegawai/create', [ProfilePegawaiController::class, 'create'])->name('profile-pegawai.create');
        Route::post('profile-pegawai', [ProfilePegawaiController::class, 'store'])->name('profile-pegawai.store');
        Route::get('profile-pegawai/{profile_pegawai}/edit', [ProfilePegawaiController::class, 'edit'])->name('profile-pegawai.edit');
        Route::put('profile-pegawai/{profile_pegawai}', [ProfilePegawaiController::class, 'update'])->name('profile-pegawai.update');
        Route::delete('profile-pegawai/{profile_pegawai}', [ProfilePegawaiController::class, 'destroy'])->name('profile-pegawai.destroy');
        Route::get('profile-pegawai/{id}/export', [ProfilePegawaiController::class, 'export'])->name('profile-pegawai.export');
        
        // Manajemen User
        Route::get('manajemen-user/template', [ManajemenUserController::class, 'templateExcel'])->name('manajemen-user.template');
        Route::resource('manajemen-user', ManajemenUserController::class);
        Route::get('manajemen-user/export/excel', [ManajemenUserController::class, 'exportExcel'])->name('manajemen-user.export');
        Route::post('manajemen-user/upload-excel', [ManajemenUserController::class, 'importExcel'])->name('manajemen-user.import');
        
        // Data MOU
        Route::get('data-mou/pembaruan', [DataMouController::class, 'pembaruanMou'])->name('data-mou.pembaruan');
        Route::get('data-mou/pembaruan/export', [DataMouController::class, 'exportPembaruan'])->name('data-mou.pembaruan.export');
        Route::get('data-mou/template', [DataMouController::class, 'template'])->name('data-mou.template');
        Route::resource('data-mou', DataMouController::class);
        Route::post('data-mou/import', [DataMouController::class, 'import'])->name('data-mou.import');
        Route::get('data-mou/export/excel', [DataMouController::class, 'export'])->name('data-mou.export');
        
        // Data SK
        Route::get('data-sk/pembaruan', [DataSkController::class, 'pembaruan'])->name('data-sk.pembaruan');
        Route::get('data-sk/pembaruan/export', [DataSkController::class, 'exportPembaruan'])->name('data-sk.pembaruan.export');
        Route::get('data-sk/template', [DataSkController::class, 'template'])->name('data-sk.template');
        Route::get('data-sk/export/excel', [DataSkController::class, 'export'])->name('data-sk.export');
        Route::post('data-sk/import', [DataSkController::class, 'import'])->name('data-sk.import');
        Route::resource('data-sk', DataSkController::class);

    });

    // Penilaian Kinerja Routes (Read-only for all, Full for Admin/Kanit/Kabid)
    Route::get('kinerja-status', [KinerjaStatusController::class, 'index'])->name('kinerja-status.index');
    Route::get('kinerja-periode', [KinerjaPeriodeController::class, 'index'])->name('kinerja-periode.index');
    Route::get('kinerja-pejabat', [KinerjaPejabatController::class, 'index'])->name('kinerja-pejabat.index');
    Route::get('kinerja-bahan', [KinerjaBahanController::class, 'index'])->name('kinerja-bahan.index');
    
    // Form Penilaian (Read access for all, Full access for Admin/Kanit/Kabid + Staf self-service)
    Route::get('form-penilaian', [FormPenilaianController::class, 'index'])->name('form-penilaian.index');
    Route::get('form-penilaian/create', [FormPenilaianController::class, 'create'])->name('form-penilaian.create');
    Route::post('form-penilaian', [FormPenilaianController::class, 'store'])->name('form-penilaian.store');
    Route::get('form-penilaian/{id}', [FormPenilaianController::class, 'show'])->name('form-penilaian.show');
    Route::get('form-penilaian/{id}/edit', [FormPenilaianController::class, 'edit'])->name('form-penilaian.edit');
    Route::put('form-penilaian/{id}', [FormPenilaianController::class, 'update'])->name('form-penilaian.update');
    Route::delete('form-penilaian/{id}', [FormPenilaianController::class, 'destroy'])->name('form-penilaian.destroy');

    Route::middleware('role:admin,kanit,kabid')->group(function () {
        // Status Kepegawaian
        Route::resource('kinerja-status', KinerjaStatusController::class)->except(['index']);
        
        // Periode Penilaian
        Route::resource('kinerja-periode', KinerjaPeriodeController::class)->except(['index']);
        
        // Pejabat Penilai
        Route::resource('kinerja-pejabat', KinerjaPejabatController::class)->except(['index']);
        
        // Bahan Penilaian
        Route::resource('kinerja-bahan', KinerjaBahanController::class)->except(['index']);
        
        // Laporan Penilaian
        Route::get('laporan-penilaian', [LaporanPenilaianController::class, 'index'])->name('laporan-penilaian.index');
        Route::get('laporan-penilaian/cetak-semua', [LaporanPenilaianController::class, 'cetakSemua'])->name('laporan-penilaian.cetak-semua');
        Route::get('laporan-penilaian/detail/{id}', [LaporanPenilaianController::class, 'detail'])->name('laporan-penilaian.detail');
        Route::get('laporan-penilaian/export', [LaporanPenilaianController::class, 'export'])->name('laporan-penilaian.export')->middleware('role:admin');
    });

    // SOP Kepegawaian Routes - Read access for all authenticated users
    Route::get('sop-kepegawaian', [SopKepegawaianController::class, 'index'])->name('sop-kepegawaian.index');

    // SOP Kepegawaian CRUD - Admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('sop-kepegawaian/create', [SopKepegawaianController::class, 'create'])->name('sop-kepegawaian.create');
        Route::post('sop-kepegawaian', [SopKepegawaianController::class, 'store'])->name('sop-kepegawaian.store');
        Route::get('sop-kepegawaian/{sop_kepegawaian}/edit', [SopKepegawaianController::class, 'edit'])->name('sop-kepegawaian.edit');
        Route::put('sop-kepegawaian/{sop_kepegawaian}', [SopKepegawaianController::class, 'update'])->name('sop-kepegawaian.update');
        Route::delete('sop-kepegawaian/{sop_kepegawaian}', [SopKepegawaianController::class, 'destroy'])->name('sop-kepegawaian.destroy');
    });

    Route::get('sop-kepegawaian/{sop_kepegawaian}', [SopKepegawaianController::class, 'show'])->name('sop-kepegawaian.show');
    Route::get('sop-kepegawaian/{sop_kepegawaian}/download', [SopKepegawaianController::class, 'downloadPdf'])->name('sop-kepegawaian.download');
});

Route::get('/print/penilaian/{id}', [\App\Http\Controllers\PrintController::class, 'cetak'])->name('print.penilaian');
