<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DataMou;
use App\Models\DataSk;
use App\Models\Pegawai;
use App\Models\PejabatPenilai;
use App\Models\PenilaianKinerja;
use App\Models\PeriodePenilaian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

$fail = 0;

function cek(string $label, callable $fn): void
{
    global $fail;

    try {
        echo '[OK]   '.$label.' => '.$fn().PHP_EOL;
    } catch (Throwable $e) {
        $fail++;
        echo '[FAIL] '.$label.' => '.$e->getMessage().PHP_EOL;
    }
}

/**
 * Panggil method controller dengan data request POST lalu tangkap hasilnya.
 */
function jalankan(object $controller, string $method, array $data): array
{
    $request = Request::create('/', 'POST', $data);
    app()->instance('request', $request);

    try {
        $response = $controller->{$method}($request);
        $session = method_exists($response, 'getSession') ? $response->getSession() : null;

        if ($session?->has('success')) {
            return ['status' => 'success', 'msg' => $session->get('success')];
        }

        if ($session?->has('error')) {
            return ['status' => 'error', 'msg' => $session->get('error')];
        }

        return ['status' => 'redirect', 'msg' => '-'];
    } catch (Illuminate\Validation\ValidationException $e) {
        return ['status' => 'validation', 'msg' => implode(' | ', array_map(fn ($m) => $m[0], $e->errors()))];
    }
}

function ctrl(string $class, string $method, array $data): array
{
    return jalankan(app($class), $method, $data);
}

DB::beginTransaction();

try {
    $user = User::create(['username' => 'uji_controller_'.uniqid(), 'password' => 'rahasia123', 'role' => 'kanit']);
    $pegawai = Pegawai::create(['nama' => 'Pegawai Uji', 'unit' => 'Unit Uji', 'jabatan' => 'Staf Uji']);
    $periode = PeriodePenilaian::create(['nama_kuartal' => 'Q1', 'periode_bulan' => 'Januari-Maret', 'tahun' => 2026, 'status_aktif' => 'aktif']);
    $pejabat = PejabatPenilai::create(['pegawai_id' => $pegawai->id, 'nama' => $pegawai->nama, 'jabatan' => $pegawai->jabatan, 'unit' => $pegawai->unit, 'status_aktif' => 'aktif']);

    cek('ManajemenUserController@store', function () {
        $h = ctrl(App\Http\Controllers\ManajemenUserController::class, 'store', [
            'username' => 'staf_baru_'.uniqid(), 'password' => 'rahasia123', 'role' => 'staf',
        ]);

        return $h['status'].' - '.$h['msg'];
    });

    cek('ManajemenUserController@store (username duplikat ditolak)', function () use ($user) {
        $h = ctrl(App\Http\Controllers\ManajemenUserController::class, 'store', [
            'username' => $user->username, 'password' => 'rahasia123', 'role' => 'staf',
        ]);

        return $h['status'].' - '.$h['msg'];
    });

    cek('KinerjaPejabatController@store (pegawai sudah jadi pejabat ditolak)', function () use ($pegawai) {
        $h = ctrl(App\Http\Controllers\KinerjaPejabatController::class, 'store', [
            'pegawai_id' => $pegawai->id, 'keterangan' => 'Uji', 'status_aktif' => 'aktif',
        ]);

        return $h['status'].' - '.$h['msg'];
    });

    cek('KinerjaPeriodeController@store (duplikat Q1 2026 ditolak)', function () {
        $h = ctrl(App\Http\Controllers\KinerjaPeriodeController::class, 'store', [
            'nama_kuartal' => 'Q1', 'periode_bulan' => 'Januari-Maret', 'tahun' => 2026,
        ]);

        return $h['status'].' - '.$h['msg'];
    });

    cek('KinerjaStatusController@store (status baru)', function () {
        $h = ctrl(App\Http\Controllers\KinerjaStatusController::class, 'store', ['nama_status' => 'Kontrak Uji']);

        return $h['status'].' - '.$h['msg'];
    });
} catch (Throwable $e) {
    $fail++;
    echo '[FAIL] blok A => '.$e->getMessage().PHP_EOL;
}

try {
    cek('FormPenilaianController@store (tanpa nilai aspek ditolak)', function () use ($pegawai, $periode, $pejabat) {
        $h = ctrl(App\Http\Controllers\FormPenilaianController::class, 'store', [
            'pegawai_id' => $pegawai->id, 'periode_id' => $periode->id,
            'pejabat_penilai_id' => $pejabat->id, 'status' => 'draft', 'catatan' => 'Catatan uji',
        ]);

        return $h['status'].' - '.$h['msg'];
    });

    cek('FormPenilaianController@store (nilai lengkap)', function () use ($pegawai, $periode, $pejabat) {
        $h = ctrl(App\Http\Controllers\FormPenilaianController::class, 'store', [
            'pegawai_id' => $pegawai->id, 'periode_id' => $periode->id, 'pejabat_penilai_id' => $pejabat->id,
            'status' => 'submitted', 'catatan' => 'Catatan uji',
            'nilai_orientasi_pelayanan' => 4, 'nilai_integritas' => 3, 'nilai_komitmen' => 3,
            'nilai_disiplin' => 4, 'nilai_kerjasama' => 4, 'nilai_kepemimpinan' => 3,
        ]);

        $p = PenilaianKinerja::first();

        return $h['status'].' - '.$h['msg'].' | total='.$p?->nilai_total.' predikat='.$p?->predikat;
    });

    cek('FormPenilaianController@store (duplikat pegawai+periode ditolak)', function () use ($pegawai, $periode, $pejabat) {
        $h = ctrl(App\Http\Controllers\FormPenilaianController::class, 'store', [
            'pegawai_id' => $pegawai->id, 'periode_id' => $periode->id,
            'pejabat_penilai_id' => $pejabat->id, 'status' => 'draft', 'nilai_integritas' => 5,
        ]);

        return $h['status'].' - '.$h['msg'];
    });

    cek('LaporanPenilaian join+filter query', function () use ($pegawai, $periode, $pejabat) {
        $rows = PenilaianKinerja::with(['pegawai', 'periode', 'pejabatPenilai'])
            ->filter(['pegawai_id' => $pegawai->id, 'periode_id' => $periode->id, 'pejabat_penilai_id' => $pejabat->id, 'status' => 'submitted'])
            ->join('pegawai', 'pegawai.id', '=', 'penilaian_kinerja.pegawai_id')
            ->orderBy('pegawai.nama')
            ->select('penilaian_kinerja.*')
            ->get();

        return 'rows='.$rows->count().' nama='.$rows->first()?->pegawai?->nama.' periode='.$rows->first()?->periode?->label;
    });

    cek('DataMouController@store (nama kosong + nominal salah ditolak)', function () {
        $h = ctrl(App\Http\Controllers\DataMouController::class, 'store', ['no_sk' => '001/MOU/2026', 'gaji_pokok' => 'bukan angka']);

        return $h['status'].' - '.$h['msg'];
    });

    cek('DataMouController@store (format ribuan diterima)', function () {
        $h = ctrl(App\Http\Controllers\DataMouController::class, 'store', [
            'no_sk' => '002/MOU/2026', 'nama' => 'Nama MOU', 'gelar' => 'S.Pd.', 'status_kepegawaian' => 'PT',
            'unit_kerja' => 'Yayasan', 'hari' => 'Senin', 'tgl_mou' => '2026-09-01', 'tanggal_lahir' => '1990-01-15',
            'tgl_mulai' => '2026-09-01', 'tanggal_akhir' => '2027-08-31', 'gaji_pokok' => '3.000.000',
            'thp' => 'Rp 4.400.000', 'terbilang' => 'Empat Juta',
        ]);

        $m = DataMou::first();

        return $h['status'].' - '.$h['msg'].' | gaji='.$m?->gaji_pokok.' thp='.$m?->thp.' tgl_mou='.$m?->tgl_mou;
    });

    cek('DataSkController@store (tanggal format d/m/Y diterima)', function () {
        $h = ctrl(App\Http\Controllers\DataSkController::class, 'store', [
            'no_sk' => '001/SK/2026', 'nama' => 'Nama SK', 'status_kepegawaian' => 'PT', 'unit_kerja' => 'Yayasan',
            'jabatan' => 'Guru', 'tgl_mulai' => '2026-01-01', 'tanggal_lahir' => '1990-01-15',
            'gaji_pokok' => '3.000.000', 'thp' => '4.400.000',
        ]);

        $sk = DataSk::first();

        return $h['status'].' - '.$h['msg'].' | gaji='.$sk?->gaji_pokok.' lahir='.$sk?->tanggal_lahir?->format('Y-m-d');
    });

    cek('ProfilePegawaiController@store + relasi user', function () use ($user) {
        $h = ctrl(App\Http\Controllers\ProfilePegawaiController::class, 'store', [
            'user_id' => $user->id, 'nama' => 'Pegawai Profil', 'tempat' => 'Surabaya',
            'tanggal_lahir' => '1990-01-15', 'gender' => 'L', 'unit' => 'Unit Uji',
            'jabatan' => 'Staf', 'tanggal_tmt' => '2020-02-01',
        ]);

        $p = Pegawai::where('nama', 'Pegawai Profil')->first();

        return $h['status'].' - '.$h['msg'].' | user='.($p?->user?->username ?? '-').' lahir='.$p?->tanggal_lahir?->format('d/m/Y');
    });

    cek('KinerjaBahanController@store (link tidak valid ditolak)', function () {
        $h = ctrl(App\Http\Controllers\KinerjaBahanController::class, 'store', ['nama_bahan' => 'SKP 2026', 'link' => 'bukan-url']);

        return $h['status'].' - '.$h['msg'];
    });

    cek('KinerjaBahanController@store (link valid)', function () {
        $h = ctrl(App\Http\Controllers\KinerjaBahanController::class, 'store', [
            'nama_bahan' => 'SKP 2026', 'link' => 'https://example.com/skp-2026', 'keterangan' => 'Bahan uji',
        ]);

        return $h['status'].' - '.$h['msg'];
    });
} catch (Throwable $e) {
    $fail++;
    echo '[FAIL] blok B => '.$e->getMessage().PHP_EOL;
}

DB::rollBack();

cek('Rollback bersih', fn () => 'user='.User::count().' pegawai='.Pegawai::count().' penilaian='.PenilaianKinerja::count().' mou='.DataMou::count().' sk='.DataSk::count());

echo PHP_EOL.($fail === 0 ? 'SEMUA CEK CONTROLLER LULUS' : "{$fail} CEK GAGAL").PHP_EOL;