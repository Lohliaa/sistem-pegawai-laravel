<?php

// Script sementara: validasi controller & helper.
require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\BahanPenilaian;
use App\Models\DataMou;
use App\Models\DataSk;
use App\Models\PejabatPenilai;
use App\Models\Pegawai;
use App\Models\PenilaianKinerja;
use App\Models\PeriodePenilaian;
use App\Models\StatusKepegawaian;
use App\Models\User;
use App\Support\ExcelHelper;

echo 'Excel formatDate: '.ExcelHelper::formatDate('2026-09-01').PHP_EOL;
echo 'Excel normalizeDate(d/m/Y): '.ExcelHelper::normalizeDate('15/01/1990').PHP_EOL;
echo 'Excel normalizeDate(serial): '.ExcelHelper::normalizeDate(32874).PHP_EOL;
echo 'Excel normalizeMoney: '.ExcelHelper::normalizeMoney('Rp 3.000.000').PHP_EOL;

$headings = ['No', 'Nama', 'Nominal'];
$rows = [[1, 'Uji Coba', '3.000.000'], [2, 'Kedua', '1.250.000']];
$spreadsheet = ExcelHelper::spreadsheet($headings, $rows, 'Uji');
$path = __DIR__.'/storage/app/uji_export.xlsx';
(new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save($path);
echo 'Export xlsx size: '.filesize($path).' bytes'.PHP_EOL;

$readBack = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
echo 'Re-read rows (termasuk header): '
    .count($readBack->load($path)->getActiveSheet()->toArray()).PHP_EOL;
unlink($path);

echo '--- Model helpers ---'.PHP_EOL;
echo 'PenilaianKinerja fillable: '.count((new PenilaianKinerja())->getFillable()).' kolom'.PHP_EOL;
echo 'hitungNilaiTotal([4,3,2,null,null,null]): '.PenilaianKinerja::hitungNilaiTotal([
    'nilai_orientasi_pelayanan' => 4,
    'nilai_integritas' => 3,
    'nilai_komitmen' => 2,
    'nilai_disiplin' => null,
    'nilai_kerjasama' => null,
    'nilai_kepemimpinan' => null,
]).PHP_EOL;
echo 'hitungNilaiTotal(semua null): '.var_export(PenilaianKinerja::hitungNilaiTotal([
    'nilai_orientasi_pelayanan' => null,
    'nilai_integritas' => null,
    'nilai_komitmen' => null,
    'nilai_disiplin' => null,
    'nilai_kerjasama' => null,
    'nilai_kepemimpinan' => null,
]), true).PHP_EOL;
echo 'predikat(3.6) = '.PenilaianKinerja::hitungPredikat(3.6)['label'].PHP_EOL;
echo 'PeriodePenilaian label: '.(new PeriodePenilaian(['nama_kuartal' => 'Q1', 'periode_bulan' => 'Januari-Maret', 'tahun' => 2026]))->label.PHP_EOL;
echo 'PejabatPenilai statuses: '.implode(', ', array_keys(PejabatPenilai::STATUSES)).PHP_EOL;
echo 'User roles: '.implode(', ', array_keys(User::ROLES)).PHP_EOL;
echo 'BahanPenilaian fillable: '.implode(', ', (new BahanPenilaian())->getFillable()).PHP_EOL;
echo 'StatusKepegawaian fillable: '.implode(', ', (new StatusKepegawaian())->getFillable()).PHP_EOL;

echo '--- Query counts (schema nyata) ---'.PHP_EOL;
echo 'pegawai='.Pegawai::count().' periode='.PeriodePenilaian::count()
    .' pejabat='.PejabatPenilai::count().' bahan='.BahanPenilaian::count()
    .' penilaian='.PenilaianKinerja::count().' status='.StatusKepegawaian::count()
    .' mou='.DataMou::count().' sk='.DataSk::count().' users='.User::count().PHP_EOL;

echo '--- Relasi ---'.PHP_EOL;
$pegawai = Pegawai::with('user', 'penilaian', 'pejabatPenilai')->first();
echo 'Pegawai: '.($pegawai?->nama ?? 'tidak ada data').PHP_EOL;
$penilaian = PenilaianKinerja::with('pegawai', 'periode', 'pejabatPenilai')->first();
echo 'Penilaian relasi: '.($penilaian ? 'ADA' : 'kosong (belum ada data)').PHP_EOL;
$pejabat = PejabatPenilai::with('pegawai')->withCount('penilaian')->first();
echo 'Pejabat: '.($pejabat?->nama ?? 'tidak ada data').PHP_EOL;

echo '--- Filter scope ---'.PHP_EOL;
echo 'filter kosong: '.PenilaianKinerja::filter([])->count().PHP_EOL;
echo 'filter periode 1: '.PenilaianKinerja::filter(['periode_id' => 1])->count().PHP_EOL;

echo 'SEMUA UJI SELESAI'.PHP_EOL;