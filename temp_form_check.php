<?php

// Script sementara: uji FormPenilaianController tanpa nilai aspek (harus ditolak).
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pegawai;
use App\Models\PejabatPenilai;
use App\Models\PenilaianKinerja;
use App\Models\PeriodePenilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

DB::beginTransaction();

$pegawai = Pegawai::create(['nama' => 'Uji Aspek', 'unit' => 'Unit', 'jabatan' => 'Staf']);
$periode = PeriodePenilaian::create(['nama_kuartal' => 'Q2', 'periode_bulan' => 'April-Juni', 'tahun' => 2026]);
$pejabat = PejabatPenilai::create(['pegawai_id' => $pegawai->id, 'nama' => $pegawai->nama, 'jabatan' => 'Staf', 'status_aktif' => 'aktif']);

$request = Request::create('/', 'POST', [
    'pegawai_id' => $pegawai->id,
    'periode_id' => $periode->id,
    'pejabat_penilai_id' => $pejabat->id,
    'status' => 'draft',
]);
app()->instance('request', $request);

$response = app(App\Http\Controllers\FormPenilaianController::class)->store($request);

echo 'Response class : '.get_class($response).PHP_EOL;
echo 'Errors         : '.json_encode($response->getSession()->get('errors')?->getBag('default')->all()).PHP_EOL;
echo 'Tersimpan      : '.PenilaianKinerja::count().PHP_EOL;
echo PenilaianKinerja::count() === 0 ? 'OK: penilaian tanpa nilai aspek ditolak'.PHP_EOL : 'GAGAL: data tersimpan'.PHP_EOL;

DB::rollBack();
echo 'Rollback: penilaian='.PenilaianKinerja::count().' pegawai='.Pegawai::count().PHP_EOL;