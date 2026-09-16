<?php

/**
 * Script verifikasi sementara untuk menguji seluruh controller.
 * Jalankan: php smoke_test.php  (semua perubahan data di-rollback)
 */

putenv('APP_ENV=testing');
$_ENV['APP_ENV'] = 'testing';
$_SERVER['APP_ENV'] = 'testing';

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\DataMou;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Guard auth butuh request ter-bind di container sebelum login
$app->instance('request', Request::create('/', 'GET'));

Auth::loginUsingId(1);

$hasil = [];
$gagal = 0;

function cek(string $label, callable $fn): void
{
    global $hasil, $gagal;

    try {
        $fn();
        $hasil[] = "OK   {$label}";
    } catch (Throwable $e) {
        $gagal++;
        $hasil[] = "FAIL {$label} :: ".get_class($e).' - '.$e->getMessage();
    }
}

function assertTrue(bool $kondisi, string $pesan): void
{
    if (! $kondisi) {
        throw new RuntimeException($pesan);
    }
}

function minta(string $method, string $uri, array $data = []): Symfony\Component\HttpFoundation\Response
{
    global $kernel;

    $request = Request::create($uri, $method, $data);
    $request->headers->set('Accept', 'text/html');

    return $kernel->handle($request);
}

function isiResponse(Symfony\Component\HttpFoundation\Response $response): string
{
    ob_start();

    if ($response instanceof Symfony\Component\HttpFoundation\StreamedResponse) {
        $response->sendContent();
    } else {
        $response->send();
    }

    return (string) ob_get_clean();
}

/**
 * Ambil seluruh atribut model sebagai array siap kirim ke form.
 */
function payload(Illuminate\Database\Eloquent\Model $model): array
{
    $data = [];

    foreach (array_keys($model->getAttributes()) as $kolom) {
        $nilai = $model->{$kolom};

        $data[$kolom] = $nilai instanceof DateTimeInterface ? $nilai->format('Y-m-d') : $nilai;
    }

    return $data;
}

DB::beginTransaction();

// 1. Profile Pegawai
cek('profile-pegawai store (tambah pegawai)', function () {
    $res = minta('POST', '/profile-pegawai', [
        'nama' => 'Aisyah Nur',
        'tempat' => 'Surabaya',
        'tanggal_lahir' => '1990-01-15',
        'gender' => 'P',
        'alamat' => 'Jl. Contoh No. 1',
        'unit' => 'SDIT',
        'jabatan' => 'Guru',
        'tanggal_tmt' => '2015-07-01',
    ]);

    assertTrue($res->getStatusCode() === 302, 'status bukan 302: '.$res->getStatusCode());

    $pegawai = App\Models\Pegawai::where('nama', 'Aisyah Nur')->first();
    assertTrue($pegawai !== null, 'pegawai tidak tersimpan');
    assertTrue($pegawai->tanggal_lahir->format('Y-m-d') === '1990-01-15', 'tanggal_lahir salah');
});

cek('profile-pegawai menolak nama kosong', function () {
    $res = minta('POST', '/profile-pegawai', ['nama' => '']);

    assertTrue($res->getStatusCode() === 302, 'harusnya redirect dengan error validasi');
    assertTrue(! App\Models\Pegawai::where('nama', '')->exists(), 'data kosong tersimpan');
});

// 2. Manajemen User
cek('manajemen-user store + password ter-hash + export excel', function () {
    $res = minta('POST', '/manajemen-user', [
        'username' => 'tester_smoke',
        'password' => 'rahasia123',
        'role' => 'staf',
    ]);

    assertTrue($res->getStatusCode() === 302, 'status bukan 302: '.$res->getStatusCode());

    $user = App\Models\User::where('username', 'tester_smoke')->first();
    assertTrue($user !== null, 'user tidak tersimpan');
    assertTrue(Illuminate\Support\Facades\Hash::check('rahasia123', $user->password), 'password tidak ter-hash');

    $export = minta('GET', '/manajemen-user/export/excel');
    assertTrue($export->getStatusCode() === 200, 'export status '.$export->getStatusCode());
    $isi = isiResponse($export);
    assertTrue(str_starts_with($isi, 'PK'), 'file export bukan xlsx');
    assertTrue(strlen($isi) > 3000, 'file export terlalu kecil: '.strlen($isi));
});

cek('manajemen-user menolak username duplikat', function () {
    $res = minta('POST', '/manajemen-user', ['username' => 'admin', 'password' => 'rahasia123', 'role' => 'staf']);

    assertTrue($res->getStatusCode() === 302, 'harusnya redirect error validasi');
    assertTrue(App\Models\User::where('username', 'admin')->count() === 1, 'user duplikat terbentuk');
});

// 3. Data MOU
cek('data-mou store (normalisasi rupiah & tanggal)', function () {
    $res = minta('POST', '/data-mou', [
        'no_sk' => '001/ABC/2026',
        'no_tambahan' => 'A1',
        'status_kepegawaian' => 'PT',
        'status_detail' => 'PKWTT',
        'nama' => 'Contoh Pegawai',
        'gelar' => 'S.Pd.',
        'hari_kerja' => 'Senin-Jumat',
        'jam_kerja' => '08.00-16.00',
        'alamat' => 'Jl. Contoh No. 2',
        'hari' => 'Senin',
        'tgl_mou' => '2026-09-01',
        'tempat_lahir' => 'Surabaya',
        'tanggal_lahir' => '1990-01-15',
        'unit_kerja' => 'Yayasan',
        'gaji_pokok' => '3.000.000',
        'tunjangan_jabatan' => '500.000',
        'tunjangan_transport' => '200000',
        'tunjangan_kinerja' => '300000',
        'tunjangan_fungsional' => '400000',
        'thp' => '4.400.000',
        'terbilang' => 'Empat Juta Empat Ratus Ribu',
        'tgl_mulai' => '2026-09-01',
        'berlaku' => '1 Tahun',
        'tanggal_akhir' => '2027-08-31',
        'saksi1' => 'Hendra',
        'saksi2' => 'Wati',
    ]);

    assertTrue($res->getStatusCode() === 302, 'status bukan 302: '.$res->getStatusCode());

    $mou = DataMou::where('nama', 'Contoh Pegawai')->first();
    assertTrue($mou !== null, 'data MOU tidak tersimpan');
    assertTrue((int) $mou->gaji_pokok === 3000000, 'gaji_pokok salah: '.$mou->gaji_pokok);
    assertTrue((int) $mou->thp === 4400000, 'thp salah: '.$mou->thp);
    assertTrue($mou->tanggal_akhir->format('Y-m-d') === '2027-08-31', 'tanggal_akhir salah');
});

cek('data-mou update', function () {
    $mou = DataMou::where('nama', 'Contoh Pegawai')->first();
    $data = payload($mou);
    $data['nama'] = 'Contoh Pegawai Update';

    $res = minta('PUT', '/data-mou/'.$mou->id, $data);

    assertTrue($res->getStatusCode() === 302, 'status bukan 302: '.$res->getStatusCode());
    assertTrue($mou->fresh()->nama === 'Contoh Pegawai Update', 'nama tidak terupdate');
});

// 4. Halaman index (butuh view)
cek('GET /dashboard', function () {
    assertTrue(minta('GET', '/dashboard')->getStatusCode() === 200, 'dashboard tidak 200');
});

cek('GET /data-mou', function () {
    $res = minta('GET', '/data-mou');

    assertTrue($res->getStatusCode() === 200, 'data-mou tidak 200: '.$res->getStatusCode());
});

cek('GET /kinerja-status', function () {
    assertTrue(minta('GET', '/kinerja-status')->getStatusCode() === 200, 'kinerja-status tidak 200');
});

// 5. Halaman kinerja-periode (view lengkap)
cek('kinerja-periode store + index/create/show/edit', function () {
    $res = minta('POST', '/kinerja-periode', [
        'nama_kuartal' => 'Q4',
        'periode_bulan' => 'Oktober-Desember',
        'tahun' => 2027,
    ]);

    assertTrue($res->getStatusCode() === 302, 'store tidak 302: '.$res->getStatusCode());

    $periode = App\Models\PeriodePenilaian::where('tahun', 2027)->first();
    assertTrue($periode !== null, 'periode tidak tersimpan');

    foreach ([
        'index' => '/kinerja-periode',
        'create' => '/kinerja-periode/create',
        'show' => '/kinerja-periode/'.$periode->id,
        'edit' => '/kinerja-periode/'.$periode->id.'/edit',
    ] as $nama => $uri) {
        $status = minta('GET', $uri)->getStatusCode();
        assertTrue($status === 200, "halaman {$nama} status {$status}");
    }
});

// 6. Halaman kinerja-pejabat & kinerja-bahan
cek('kinerja-pejabat store + index/create/show/edit', function () {
    $pegawai = App\Models\Pegawai::create(['nama' => 'Calon Pejabat', 'jabatan' => 'Kabid', 'unit' => 'SDM']);

    $res = minta('POST', '/kinerja-pejabat', [
        'pegawai_id' => $pegawai->id,
        'status_aktif' => 'aktif',
        'keterangan' => 'Pejabat uji',
    ]);

    assertTrue($res->getStatusCode() === 302, 'store tidak 302: '.$res->getStatusCode());

    $pejabat = App\Models\PejabatPenilai::where('pegawai_id', $pegawai->id)->first();
    assertTrue($pejabat !== null, 'pejabat tidak tersimpan');
    assertTrue($pejabat->jabatan === 'Kabid', 'jabatan tidak tersalin: '.$pejabat->jabatan);

    foreach ([
        'index' => '/kinerja-pejabat',
        'create' => '/kinerja-pejabat/create',
        'show' => '/kinerja-pejabat/'.$pejabat->id,
        'edit' => '/kinerja-pejabat/'.$pejabat->id.'/edit',
    ] as $nama => $uri) {
        $status = minta('GET', $uri)->getStatusCode();
        assertTrue($status === 200, "halaman {$nama} status {$status}");
    }
});

cek('kinerja-bahan store + index/create/show/edit', function () {
    $res = minta('POST', '/kinerja-bahan', [
        'nama_bahan' => 'SKP Uji',
        'link' => 'https://example.com/skp-uji',
        'keterangan' => 'Bahan uji',
    ]);

    assertTrue($res->getStatusCode() === 302, 'store tidak 302: '.$res->getStatusCode());

    $bahan = App\Models\BahanPenilaian::where('nama_bahan', 'SKP Uji')->first();
    assertTrue($bahan !== null, 'bahan tidak tersimpan');

    foreach ([
        'index' => '/kinerja-bahan',
        'create' => '/kinerja-bahan/create',
        'show' => '/kinerja-bahan/'.$bahan->id,
        'edit' => '/kinerja-bahan/'.$bahan->id.'/edit',
    ] as $nama => $uri) {
        $status = minta('GET', $uri)->getStatusCode();
        assertTrue($status === 200, "halaman {$nama} status {$status}");
    }
});

DB::rollBack();

echo implode(PHP_EOL, $hasil).PHP_EOL;
echo PHP_EOL.'Total gagal: '.$gagal.PHP_EOL;
echo 'Rollback -> pegawai='.App\Models\Pegawai::count().' mou='.DataMou::count()
    .' user='.App\Models\User::count().' penilaian='.App\Models\PenilaianKinerja::count().PHP_EOL;
echo $gagal === 0 ? 'SMOKE TEST LULUS'.PHP_EOL : 'SMOKE TEST GAGAL'.PHP_EOL;