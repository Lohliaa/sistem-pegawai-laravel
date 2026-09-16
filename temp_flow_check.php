<?php

// Script sementara: uji alur controller penilaian kinerja end-to-end (rollback di akhir).
require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\FormPenilaianController;
use App\Http\Controllers\KinerjaPejabatController;
use App\Http\Controllers\KinerjaPeriodeController;
use App\Http\Controllers\LaporanPenilaianController;
use App\Models\Pegawai;
use App\Models\PenilaianKinerja;
use App\Models\PeriodePenilaian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

DB::beginTransaction();

function jalankan(callable $aksi, string $label): void
{
    try {
        $aksi();
        echo "OK  : {$label}".PHP_EOL;
    } catch (Throwable $e) {
        $pesan = $e->getMessage();

        if (str_contains($pesan, 'View [') && str_contains($pesan, 'not found')) {
            echo "OK  : {$label} (query jalan, view belum dibuat)".PHP_EOL;

            return;
        }

        echo "GAGAL: {$label} => ".get_class($e).': '.$pesan.PHP_EOL;
    }
}

function permintaan(array $data): Request
{
    $request = Request::create('/', 'POST', $data);
    app()->instance('request', $request);

    return $request;
}

try {
    // 1. Periode penilaian
    jalankan(fn () => app(KinerjaPeriodeController::class)->store(permintaan([
        'nama_kuartal' => 'Q1',
        'periode_bulan' => 'Januari-Maret',
        'tahun' => 2026,
    ])), 'KinerjaPeriodeController@store');

    $periode = PeriodePenilaian::first();

    // 2. Pegawai + user
    $user = User::create(['username' => 'uji_flow', 'password' => 'rahasia123', 'role' => 'admin']);
    $pegawai = Pegawai::create(['user_id' => $user->id, 'nama' => 'Pegawai Uji', 'jabatan' => 'Guru', 'unit' => 'SD']);
    $penilai = Pegawai::create(['nama' => 'Pejabat Uji', 'jabatan' => 'Kepala Bidang SDM', 'unit' => 'Yayasan']);

    echo 'Set up: periode='.$periode->id.' pegawai='.$pegawai->id.' penilai='.$penilai->id.PHP_EOL;

    // 3. Pejabat penilai (kopi nama/jabatan/unit dari pegawai)
    jalankan(fn () => app(KinerjaPejabatController::class)->store(permintaan([
        'pegawai_id' => $penilai->id,
        'keterangan' => 'Penilai utama',
        'status_aktif' => 'aktif',
    ])), 'KinerjaPejabatController@store');

    $pejabat = App\Models\PejabatPenilai::first();
    echo 'Pejabat tersimpan: '.($pejabat?->nama ?? '-').' | unit: '.($pejabat?->unit ?? '-').PHP_EOL;

    // Duplikasi pejabat harus ditolak validasi
    try {
        app(KinerjaPejabatController::class)->store(permintaan([
            'pegawai_id' => $penilai->id,
            'status_aktif' => 'aktif',
        ]));
        echo 'GAGAL: duplikat pejabat tidak tertolak'.PHP_EOL;
    } catch (Illuminate\Validation\ValidationException $e) {
        echo 'OK  : duplikat pejabat ditolak ('.implode(' ', $e->validator->errors()->all()).')'.PHP_EOL;
    }

    // 4. Form penilaian
    jalankan(fn () => app(FormPenilaianController::class)->store(permintaan([
        'pegawai_id' => $pegawai->id,
        'periode_id' => $periode->id,
        'pejabat_penilai_id' => $pejabat->id,
        'nilai_orientasi_pelayanan' => 4,
        'nilai_integritas' => 3,
        'nilai_komitmen' => 4,
        'nilai_disiplin' => 3,
        'nilai_kerjasama' => 4,
        'nilai_kepemimpinan' => 2,
        'catatan' => 'Catatan uji',
        'status' => 'submitted',
    ])), 'FormPenilaianController@store');

    $penilaian = PenilaianKinerja::with(['pegawai', 'periode'])->first();
    echo 'Penilaian: id='.$penilaian?->id.' total='.$penilaian?->nilai_total.' predikat='.$penilaian?->predikat
        .' status='.$penilaian?->status.PHP_EOL;
    echo 'Relasi -> pegawai: '.($penilaian?->pegawai?->nama ?? '-')
        .' | periode label: '.($penilaian?->periode?->label ?? '-').PHP_EOL;

    // 5. Index & detail controller (view belum ada, yang penting query tidak error)
    jalankan(fn () => app(FormPenilaianController::class)->index(permintaan(['pegawai_id' => $pegawai->id])), 'FormPenilaianController@index');
    jalankan(fn () => app(FormPenilaianController::class)->show((string) $penilaian->id), 'FormPenilaianController@show');
    jalankan(fn () => app(LaporanPenilaianController::class)->index(permintaan(['periode_id' => $periode->id])), 'LaporanPenilaianController@index');
    jalankan(fn () => app(LaporanPenilaianController::class)->detail((string) $penilaian->id), 'LaporanPenilaianController@detail');
    jalankan(fn () => app(LaporanPenilaianController::class)->export(permintaan(['periode_id' => $periode->id]))->getStatusCode(), 'LaporanPenilaianController@export');

    // 6. Update penilaian
    jalankan(fn () => app(FormPenilaianController::class)->update(permintaan([
        'pegawai_id' => $pegawai->id,
        'periode_id' => $periode->id,
        'pejabat_penilai_id' => $pejabat->id,
        'nilai_orientasi_pelayanan' => 3,
        'nilai_integritas' => 3,
        'catatan' => 'Catatan update',
        'status' => 'approved',
    ]), (string) $penilaian->id), 'FormPenilaianController@update');

    $penilaian->refresh();
    echo 'Setelah update: total='.$penilaian->nilai_total.' status='.$penilaian->status
        .' (rata 3 aspek bernilai)'.PHP_EOL;

    // 7. Guard hapus periode yang sudah dipakai
    jalankan(fn () => app(KinerjaPeriodeController::class)->destroy((string) $periode->id), 'KinerjaPeriodeController@destroy (guard)');
    echo 'Periode masih ada setelah guard: '.(PeriodePenilaian::find($periode->id) ? 'ya - OK' : 'tidak - SALAH').PHP_EOL;
} finally {
    DB::rollBack();
    echo 'Rollback. penilaian='.PenilaianKinerja::count().' periode='.PeriodePenilaian::count().PHP_EOL;
    echo 'SELESAI'.PHP_EOL;
}