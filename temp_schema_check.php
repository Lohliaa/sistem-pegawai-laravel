<?php

// Script sementara: bandingkan kolom tabel nyata dengan $fillable model & KOLOM_EXCEL controller.
require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\DataMou;
use App\Models\DataSk;
use App\Models\PenilaianKinerja;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$pasangan = [
    'data_mou' => DataMou::class,
    'data_sk' => DataSk::class,
    'penilaian_kinerja' => PenilaianKinerja::class,
];

foreach ($pasangan as $tabel => $model) {
    $kolomDb = collect(Schema::getColumnListing($tabel))
        ->reject(fn ($k) => in_array($k, ['id', 'created_at', 'updated_at'], true));
    $fillable = (new $model())->getFillable();

    $hilangDiFillable = $kolomDb->diff($fillable)->values()->all();
    $berlebihDiFillable = collect($fillable)->diff($kolomDb)->values()->all();

    echo "== {$tabel} ==".PHP_EOL;
    echo '  kolom DB: '.$kolomDb->count().' | fillable: '.count($fillable).PHP_EOL;
    echo '  DB tanpa fillable  : '.(count($hilangDiFillable) ? implode(', ', $hilangDiFillable) : '(kosong - OK)').PHP_EOL;
    echo '  fillable tanpa DB  : '.(count($berlebihDiFillable) ? implode(', ', $berlebihDiFillable) : '(kosong - OK)').PHP_EOL;
}

foreach ([App\Http\Controllers\DataMouController::class => 'data_mou', App\Http\Controllers\DataSkController::class => 'data_sk'] as $controller => $tabel) {
    $reflection = new ReflectionClass($controller);
    $kolomExcel = $reflection->getConstant('KOLOM_EXCEL');
    $kolomDb = Schema::getColumnListing($tabel);

    $tidakAdaDiDb = collect(array_keys($kolomExcel))->diff($kolomDb)->values()->all();
    $tidakDiexport = collect($kolomDb)
        ->reject(fn ($k) => in_array($k, ['id', 'created_at', 'updated_at'], true))
        ->diff(array_keys($kolomExcel))->values()->all();

    echo "== KOLOM_EXCEL ".class_basename($controller)." vs {$tabel} ==".PHP_EOL;
    echo '  kolom tidak ada di tabel : '.(count($tidakAdaDiDb) ? implode(', ', $tidakAdaDiDb) : '(kosong - OK)').PHP_EOL;
    echo '  kolom tabel tak dieskpor : '.(count($tidakDiexport) ? implode(', ', $tidakDiexport) : '(kosong - OK)').PHP_EOL;
}

DB::beginTransaction();
try {
    $mou = DataMou::create([
        'no_sk' => 'UJI/001',
        'nama' => 'Uji Coba Insert',
        'tanggal_lahir' => '1990-01-15',
        'gaji_pokok' => '3000000',
        'thp' => '4400000',
    ]);
    echo 'Insert data_mou OK, id='.$mou->id.PHP_EOL;

    $sk = DataSk::create([
        'no_sk' => 'UJI-SK/001',
        'nama' => 'Uji Coba SK',
        'tanggal_lahir' => '1991-02-20',
        'gaji_pokok' => '2500000',
    ]);
    echo 'Insert data_sk OK, id='.$sk->id.PHP_EOL;
} catch (Throwable $e) {
    echo 'GAGAL INSERT: '.$e->getMessage().PHP_EOL;
} finally {
    DB::rollBack();
    echo 'Rollback selesai. data_mou='.DataMou::count().' data_sk='.DataSk::count().PHP_EOL;
}

echo 'Kolom pejabat_penilai: '.implode(', ', Schema::getColumnListing('pejabat_penilai')).PHP_EOL;
echo 'Kolom periode_penilaian: '.implode(', ', Schema::getColumnListing('periode_penilaian')).PHP_EOL;
echo 'Kolom bahan_penilaian: '.implode(', ', Schema::getColumnListing('bahan_penilaian')).PHP_EOL;
echo 'Kolom status_kepegawaian: '.implode(', ', Schema::getColumnListing('status_kepegawaian')).PHP_EOL;
echo 'SELESAI'.PHP_EOL;