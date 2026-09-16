<?php

// Script sementara: uji alur controller MOU, SK, User, Profile, Status, Bahan (rollback di akhir).
require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\DataMouController;
use App\Http\Controllers\DataSkController;
use App\Http\Controllers\FormPenilaianController;
use App\Http\Controllers\KinerjaBahanController;
use App\Http\Controllers\KinerjaStatusController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\ProfilePegawaiController;
use App\Models\BahanPenilaian;
use App\Models\DataMou;
use App\Models\DataSk;
use App\Models\Pegawai;
use App\Models\StatusKepegawaian;
use App\Models\User;
use App\Support\ExcelHelper;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

DB::beginTransaction();

$gagal = 0;

function jalankan(callable $aksi, string $label): void
{
    global $gagal;

    try {
        $aksi();
        echo "OK  : {$label}".PHP_EOL;
    } catch (Throwable $e) {
        $gagal++;
        echo "GAGAL: {$label} => ".get_class($e).': '.$e->getMessage().PHP_EOL;
    }
}

/**
 * Buat request POST lengkap dengan file upload.
 */
function permintaan(array $data, array $files = []): Request
{
    $request = Request::create('/', 'POST', $data, [], $files);
    app()->instance('request', $request);

    return $request;
}

/**
 * Tulis spreadsheet uji ke file temporer lalu bungkus menjadi UploadedFile.
 *
 * @param  array<int, string>  $headings
 * @param  array<int, array<int, mixed>>  $rows
 */
function fileExcel(array $headings, array $rows): UploadedFile
{
    $path = tempnam(sys_get_temp_dir(), 'uji').'.xlsx';
    $spreadsheet = ExcelHelper::spreadsheet($headings, $rows, 'Uji');
    (new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save($path);
    $spreadsheet->disconnectWorksheets();

    return new UploadedFile($path, 'uji.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
}

/**
 * Jalankan aksi; bedakan kegagalan controller dengan view yang belum dibuat.
 */
function jalankanView(callable $aksi, string $label, string $view): void
{
    global $gagal;

    try {
        $aksi();
        echo "OK  : {$label}".PHP_EOL;
    } catch (InvalidArgumentException $e) {
        if (Illuminate\Support\Facades\View::exists($view)) {
            $gagal++;
            echo "GAGAL: {$label} => ".get_class($e).': '.$e->getMessage().PHP_EOL;
        } else {
            echo "LEWAT: {$label} (view {$view} belum dibuat)".PHP_EOL;
        }
    } catch (Throwable $e) {
        $gagal++;
        echo "GAGAL: {$label} => ".get_class($e).': '.$e->getMessage().PHP_EOL;
    }
}

echo '=== DATA MOU ==='.PHP_EOL;

jalankan(fn () => app(DataMouController::class)->store(permintaan([
    'no_sk' => '001/MOU/2026',
    'no_tambahan' => 'A1',
    'status_kepegawaian' => 'PT',
    'status_detail' => 'PKWTT',
    'nama' => 'Ahmad Fauzi',
    'gelar' => 'S.Pd.',
    'hari_kerja' => 'Senin-Jumat',
    'jam_kerja' => '08.00-16.00',
    'alamat' => 'Jl. Contoh No. 1',
    'hari' => 'Senin',
    'tgl_mou' => '01/09/2026',
    'tempat_lahir' => 'Surabaya',
    'tanggal_lahir' => '1990-01-15',
    'unit_kerja' => 'Yayasan',
    'gaji_pokok' => '3.000.000',
    'tunjangan_jabatan' => '500.000',
    'tunjangan_transport' => '200.000',
    'tunjangan_kinerja' => '300.000',
    'tunjangan_fungsional' => '400.000',
    'thp' => '4.400.000',
    'terbilang' => 'Empat Juta Empat Ratus Ribu',
    'tgl_mulai' => '01/09/2026',
    'berlaku' => '1 Tahun',
    'tanggal_akhir' => '31/08/2027',
    'saksi1' => 'Hendra',
    'saksi2' => 'Wati',
])), 'DataMouController@store');

$mou = DataMou::first();
echo 'MOU: '.$mou?->nama.' | gaji='.$mou?->gaji_pokok.' | thp='.$mou?->thp
    .' | tgl_mou='.$mou?->tgl_mou.' (input d/m/Y -> ISO) | tgl_mulai='.($mou?->tgl_mulai?->format('Y-m-d') ?? '-')
    .' | tanggal_akhir='.($mou?->tanggal_akhir?->format('Y-m-d') ?? '-').PHP_EOL;

jalankanView(fn () => app(DataMouController::class)->index(permintaan([])), 'DataMouController@index', 'data-mou.index');

jalankan(function () {
    $status = app(DataMouController::class)->export(permintaan([]))->getStatusCode();

    if ($status !== 200) {
        throw new RuntimeException('Status export: '.$status);
    }
}, 'DataMouController@export');

$headingsMou = ['No. SK', 'No. Tambahan', 'Status Kepegawaian', 'Status Detail', 'Nama', 'Gelar', 'Hari Kerja', 'Jam Kerja', 'Alamat', 'Hari MOU', 'Tanggal MOU', 'Tempat Lahir', 'Tanggal Lahir', 'Unit Kerja', 'Gaji Pokok', 'Tunjangan Jabatan', 'Tunjangan Transport', 'Tunjangan Kinerja', 'Tunjangan Fungsional', 'THP', 'Terbilang', 'Tanggal Mulai', 'Berlaku', 'Tanggal Akhir', 'Saksi 1', 'Saksi 2'];

$fileImport = fileExcel($headingsMou, [
    ['002/MOU/2026', 'A2', 'PT', 'PKWTT', 'Siti Aminah', 'S.Ag.', 'Senin-Jumat', '08.00-16.00', 'Jl. Kedua', 'Selasa', '02/09/2026', 'Malang', '1985-05-20', 'Unit SD', '4500000', '500000', '250000', '350000', '150000', '5750000', 'Lima Juta', '02/09/2026', '1 Tahun', '01/09/2027', 'Budi', 'Rina'],
    ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
    ['003/MOU/2026', 'A3', 'PT', 'PKWT', 'Dewi Lestari', '', 'Senin-Jumat', '08.00-16.00', 'Jl. Ketiga', 'Rabu', '03/09/2026', 'Gresik', '1992-12-01', 'Unit SMP', '4000000', '400000', '200000', '300000', '100000', '5000000', 'Lima Juta', '03/09/2026', '1 Tahun', '02/09/2027', 'Andi', 'Sari'],
]);

jalankan(fn () => app(DataMouController::class)->import(permintaan([], ['file_excel' => $fileImport])), 'DataMouController@import');
echo 'Total MOU setelah import: '.DataMou::count().PHP_EOL;
echo 'Baris ke-3 (dilewati karena kosong): '.DataMou::where('no_sk', '003/MOU/2026')->first()?->nama.PHP_EOL;
echo 'Import tanggal d/m/Y vs serial: '.DataMou::where('no_sk', '002/MOU/2026')->first()?->tanggal_lahir?->format('Y-m-d').PHP_EOL;

jalankan(fn () => app(DataMouController::class)->update(permintaan([
    'nama' => 'Ahmad Fauzi Updated',
    'gaji_pokok' => '3.500.000',
    'tanggal_lahir' => '1990-01-15',
]), (string) $mou->id), 'DataMouController@update');

$mou->refresh();
echo 'Setelah update: '.$mou->nama.' | gaji='.$mou->gaji_pokok.' | tgl_lahir='.$mou->tanggal_lahir?->format('Y-m-d').PHP_EOL;

// Nominal dengan prefix "Rp" boleh (dinormalisasi), teks murni harus ditolak
try {
    app(DataMouController::class)->update(permintaan(['nama' => 'X', 'gaji_pokok' => 'abc']), (string) $mou->id);
    echo 'GAGAL: nominal "abc" tidak tertolak'.PHP_EOL;
    $gagal++;
} catch (Illuminate\Validation\ValidationException $e) {
    echo 'OK  : nominal "abc" ditolak ('.implode(' ', $e->validator->errors()->all()).')'.PHP_EOL;
}

app(DataMouController::class)->update(permintaan(['nama' => 'X', 'gaji_pokok' => 'Rp 3.500.000']), (string) $mou->id);
echo 'OK  : nominal "Rp 3.500.000" diterima -> tersimpan sebagai '.DataMou::find($mou->id)->gaji_pokok.PHP_EOL;

jalankan(fn () => app(DataMouController::class)->destroy((string) DataMou::latest('id')->first()->id), 'DataMouController@destroy');
echo 'Total MOU akhir: '.DataMou::count().PHP_EOL;

echo PHP_EOL.'=== DATA SK ==='.PHP_EOL;

jalankan(fn () => app(DataSkController::class)->store(permintaan([
    'no_sk' => 'SK/001/2026',
    'nama' => 'Siti Aminah',
    'gelar' => 'S.Pd.',
    'status_kepegawaian' => 'PT',
    'unit_kerja' => 'Yayasan',
    'jabatan' => 'Guru',
    'tanggal_lahir' => '15/03/1992',
    'tgl_mulai' => '01/01/2026',
    'gaji_pokok' => '2.500.000',
    'thp' => '3.250.000',
])), 'DataSkController@store');

$sk = DataSk::where('no_sk', 'SK/001/2026')->first();
echo 'SK: '.$sk?->nama.' | gaji='.($sk?->gaji_pokok ?? '-').' | tgl_lahir='.($sk?->tanggal_lahir?->format('Y-m-d') ?? '-')
    .' | tgl_mulai='.($sk?->tgl_mulai?->format('Y-m-d') ?? '-').PHP_EOL;

jalankanView(fn () => app(DataSkController::class)->index(permintaan([])), 'DataSkController@index', 'data-sk.index');
jalankan(fn () => app(DataSkController::class)->export(permintaan([])), 'DataSkController@export');

$fileSk = fileExcel(
    ['No. SK', 'Status Kepegawaian', 'Nama', 'Gelar', 'Alamat', 'Tempat Lahir', 'Tanggal Lahir', 'Unit Kerja', 'Jabatan', 'Tanggal Mulai', 'Gaji Pokok', 'Tunjangan Jabatan', 'Tunjangan Transport', 'Tunjangan Kinerja', 'Tunjangan Fungsional', 'THP', 'Terbilang', 'Saksi 1', 'Saksi 2'],
    [
        ['SK/002/2026', 'PT', 'Budi Santoso', 'S.Kom.', 'Jl. Melati 2', 'Malang', '20/07/1988', 'IT', 'Programmer', 45000, 3000000, 0, 0, 0, 0, 0, 'Tiga Juta', 'Saksi A', 'Saksi B'],
        ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
        ['SK/003/2026', 'PT', 'Citra Dewi', '', '', 'Bandung', '1990-09-09', 'HRD', 'HRD', '2026-02-01', 2800000, 0, 0, 0, 0, 0, '', '', ''],
    ]
);

jalankan(fn () => app(DataSkController::class)->import(permintaan([], ['file_excel' => $fileSk])), 'DataSkController@import');

echo 'Total SK setelah import: '.DataSk::count().PHP_EOL;
echo 'Import serial date -> '.DataSk::where('no_sk', 'SK/002/2026')->first()?->tanggal_lahir?->format('Y-m-d').PHP_EOL;

jalankan(fn () => app(DataSkController::class)->update(permintaan([
    'nama' => 'Siti Aminah Updated',
    'gaji_pokok' => 'Rp 2.750.000',
    'tgl_mulai' => '2026-01-01',
]), (string) $sk->id), 'DataSkController@update');

echo 'Setelah update: '.DataSk::find($sk->id)->nama.' | gaji='.DataSk::find($sk->id)->gaji_pokok.PHP_EOL;

jalankan(fn () => app(DataSkController::class)->destroy((string) DataSk::latest('id')->first()->id), 'DataSkController@destroy');
echo 'Total SK akhir: '.DataSk::count().PHP_EOL;

echo PHP_EOL.'=== KINERJA STATUS ==='.PHP_EOL;

jalankan(fn () => app(KinerjaStatusController::class)->store(permintaan([
    'nama_status' => 'UJI-STATUS',
])), 'KinerjaStatusController@store');

jalankanView(fn () => app(KinerjaStatusController::class)->index(), 'KinerjaStatusController@index', 'kinerja-status.index');

$status = StatusKepegawaian::where('nama_status', 'UJI-STATUS')->first();
echo 'Status tersimpan: '.($status?->nama_status ?? '-').PHP_EOL;

try {
    app(KinerjaStatusController::class)->store(permintaan(['nama_status' => 'UJI-STATUS']));
    echo 'GAGAL: status duplikat tidak tertolak'.PHP_EOL;
    $gagal++;
} catch (Illuminate\Validation\ValidationException $e) {
    echo 'OK  : status duplikat ditolak'.PHP_EOL;
}

jalankan(fn () => app(KinerjaStatusController::class)->update(permintaan([
    'nama_status' => 'UJI-STATUS-2',
]), (string) $status->id), 'KinerjaStatusController@update');
echo 'Setelah update: '.StatusKepegawaian::find($status->id)->nama_status.PHP_EOL;

jalankan(fn () => app(KinerjaStatusController::class)->destroy((string) $status->id), 'KinerjaStatusController@destroy');
echo 'Status uji terhapus: '.(StatusKepegawaian::where('nama_status', 'UJI-STATUS-2')->exists() ? 'belum' : 'ya').PHP_EOL;

echo PHP_EOL.'=== KINERJA BAHAN ==='.PHP_EOL;

jalankan(fn () => app(KinerjaBahanController::class)->store(permintaan([
    'nama_bahan' => 'SKP 2026',
    'link' => 'https://drive.google.com/skp-2026',
    'keterangan' => 'Bahan uji coba',
])), 'KinerjaBahanController@store');

jalankanView(fn () => app(KinerjaBahanController::class)->index(permintaan(['search' => 'SKP'])), 'KinerjaBahanController@index', 'kinerja-bahan.index');

$bahan = BahanPenilaian::where('nama_bahan', 'SKP 2026')->first();
echo 'Bahan tersimpan: '.($bahan?->nama_bahan ?? '-').' | '.($bahan?->link ?? '-').PHP_EOL;

try {
    app(KinerjaBahanController::class)->store(permintaan(['nama_bahan' => 'X', 'link' => 'bukan-url']));
    echo 'GAGAL: link bukan URL tidak tertolak'.PHP_EOL;
    $gagal++;
} catch (Illuminate\Validation\ValidationException $e) {
    echo 'OK  : link bukan URL ditolak'.PHP_EOL;
}

jalankan(fn () => app(KinerjaBahanController::class)->update(permintaan([
    'nama_bahan' => 'SKP 2026 Revisi',
    'link' => 'https://drive.google.com/skp-2026-revisi',
    'keterangan' => 'Diperbarui',
]), (string) $bahan->id), 'KinerjaBahanController@update');
echo 'Setelah update: '.BahanPenilaian::find($bahan->id)->nama_bahan.PHP_EOL;

jalankan(fn () => app(KinerjaBahanController::class)->destroy((string) $bahan->id), 'KinerjaBahanController@destroy');
echo 'Bahan uji terhapus: '.(BahanPenilaian::where('id', $bahan->id)->exists() ? 'belum' : 'ya').PHP_EOL;

echo PHP_EOL.'=== MANAJEMEN USER ==='.PHP_EOL;

jalankan(fn () => app(ManajemenUserController::class)->store(permintaan([
    'username' => 'uji_user',
    'password' => 'rahasia123',
    'role' => 'staf',
])), 'ManajemenUserController@store');

$user = User::where('username', 'uji_user')->first();
echo 'User tersimpan: '.($user?->username ?? '-').' | role='.($user?->role ?? '-')
    .' | password ter-hash: '.(Hash::check('rahasia123', (string) $user?->password) ? 'ya' : 'TIDAK').PHP_EOL;

try {
    app(ManajemenUserController::class)->store(permintaan([
        'username' => 'uji_user',
        'password' => 'rahasia123',
        'role' => 'staf',
    ]));
    echo 'GAGAL: username duplikat tidak tertolak'.PHP_EOL;
    $gagal++;
} catch (Illuminate\Validation\ValidationException $e) {
    echo 'OK  : username duplikat ditolak'.PHP_EOL;
}

try {
    app(ManajemenUserController::class)->store(permintaan([
        'username' => 'uji_role_salah',
        'password' => 'rahasia123',
        'role' => 'superadmin',
    ]));
    echo 'GAGAL: role tidak dikenal tidak tertolak'.PHP_EOL;
    $gagal++;
} catch (Illuminate\Validation\ValidationException $e) {
    echo 'OK  : role tidak dikenal ditolak'.PHP_EOL;
}

jalankanView(fn () => app(ManajemenUserController::class)->index(permintaan(['search' => 'uji'])), 'ManajemenUserController@index', 'manajemen-user.index');
jalankan(fn () => app(ManajemenUserController::class)->exportExcel(), 'ManajemenUserController@exportExcel');

jalankan(fn () => app(ManajemenUserController::class)->update(permintaan([
    'username' => 'uji_user_ubah',
    'password' => 'passwordbaru',
    'role' => 'kanit',
]), (string) $user->id), 'ManajemenUserController@update');

$user->refresh();
echo 'Setelah update: '.$user->username.' | role='.$user->role
    .' | password baru valid: '.(Hash::check('passwordbaru', (string) $user->password) ? 'ya' : 'TIDAK').PHP_EOL;

jalankan(fn () => app(ManajemenUserController::class)->update(permintaan([
    'username' => 'uji_user_ubah',
    'role' => 'kanit',
]), (string) $user->id), 'ManajemenUserController@update (password dikosongkan)');
echo 'Password tetap saat dikosongkan: '.(Hash::check('passwordbaru', (string) $user->fresh()->password) ? 'ya' : 'TIDAK').PHP_EOL;

// Guard: user tidak boleh menghapus akunnya sendiri
Auth::login($user);
app(ManajemenUserController::class)->destroy((string) $user->id);
echo (User::where('id', $user->id)->exists() ? 'OK  : akun sendiri tidak terhapus' : 'GAGAL: akun sendiri terhapus').PHP_EOL;
Auth::logout();

jalankan(fn () => app(ManajemenUserController::class)->destroy((string) $user->id), 'ManajemenUserController@destroy');
echo 'User uji terhapus: '.(User::where('id', $user->id)->exists() ? 'belum' : 'ya').PHP_EOL;

echo PHP_EOL.'=== PROFILE PEGAWAI ==='.PHP_EOL;

$akun = User::create(['username' => 'pegawai_uji', 'password' => 'rahasia123', 'role' => 'staf']);

jalankan(fn () => app(ProfilePegawaiController::class)->store(permintaan([
    'user_id' => $akun->id,
    'nama' => 'Pegawai Uji',
    'tempat' => 'Surabaya',
    'tanggal_lahir' => '10/10/1995',
    'gender' => 'L',
    'alamat' => 'Jl. Uji No. 1',
    'unit' => 'Yayasan',
    'jabatan' => 'Staf',
    'tanggal_tmt' => '01/02/2020',
])), 'ProfilePegawaiController@store');

$pegawai = Pegawai::where('nama', 'Pegawai Uji')->first();
echo 'Pegawai tersimpan: '.($pegawai?->nama ?? '-').' | user_id='.($pegawai?->user_id ?? '-')
    .' | lahir='.($pegawai?->tanggal_lahir?->format('Y-m-d') ?? '-')
    .' | tmt='.($pegawai?->tanggal_tmt?->format('Y-m-d') ?? '-').PHP_EOL;

try {
    app(ProfilePegawaiController::class)->store(permintaan([
        'user_id' => $akun->id,
        'nama' => 'Duplikat Akun',
    ]));
    echo 'GAGAL: user_id duplikat tidak tertolak'.PHP_EOL;
    $gagal++;
} catch (Illuminate\Validation\ValidationException $e) {
    echo 'OK  : user_id duplikat ditolak'.PHP_EOL;
}

try {
    app(ProfilePegawaiController::class)->store(permintaan([
        'gender' => 'X',
        'nama' => 'Gender Salah',
    ]));
    echo 'GAGAL: gender tidak dikenal tidak tertolak'.PHP_EOL;
    $gagal++;
} catch (Illuminate\Validation\ValidationException $e) {
    echo 'OK  : gender tidak dikenal ditolak'.PHP_EOL;
}

jalankanView(fn () => app(ProfilePegawaiController::class)->index(permintaan(['search' => 'Uji'])), 'ProfilePegawaiController@index', 'profile-pegawai.index');
jalankan(fn () => app(ProfilePegawaiController::class)->export((string) $pegawai->id), 'ProfilePegawaiController@export');

jalankan(fn () => app(ProfilePegawaiController::class)->update(permintaan([
    'user_id' => $akun->id,
    'nama' => 'Pegawai Uji Updated',
    'unit' => 'SDM',
]), (string) $pegawai->id), 'ProfilePegawaiController@update');

$pegawai->refresh();
echo 'Setelah update: '.$pegawai->nama.' | unit='.$pegawai->unit.PHP_EOL;

// Guard: pegawai yang sudah jadi pejabat penilai tidak boleh dihapus
$periodeUji = App\Models\PeriodePenilaian::create(['nama_kuartal' => 'Q3', 'periode_bulan' => 'Juli-September', 'tahun' => 2026]);
$pejabatUji = App\Models\PejabatPenilai::create([
    'pegawai_id' => $pegawai->id,
    'nama' => $pegawai->nama,
    'jabatan' => 'Staf',
    'status_aktif' => 'aktif',
]);
app(FormPenilaianController::class)->store(permintaan([
    'pegawai_id' => $pegawai->id,
    'periode_id' => $periodeUji->id,
    'pejabat_penilai_id' => $pejabatUji->id,
    'nilai_orientasi_pelayanan' => 4,
    'status' => 'draft',
]));

app(ProfilePegawaiController::class)->destroy((string) $pegawai->id);
echo (Pegawai::where('id', $pegawai->id)->exists() ? 'OK  : pegawai dengan penilaian tidak terhapus' : 'GAGAL: pegawai terhapus').PHP_EOL;

// Setelah penilaian & pejabat dihapus, pegawai boleh dihapus
App\Models\PenilaianKinerja::where('pegawai_id', $pegawai->id)->delete();
$pejabatUji->delete();

jalankan(fn () => app(ProfilePegawaiController::class)->destroy((string) $pegawai->id), 'ProfilePegawaiController@destroy');
echo 'Pegawai uji terhapus: '.(Pegawai::where('id', $pegawai->id)->exists() ? 'belum' : 'ya').PHP_EOL;

DB::rollBack();

echo PHP_EOL.'=== RINGKASAN ==='.PHP_EOL;
echo 'Total kegagalan: '.$gagal.PHP_EOL;
echo 'Rollback -> pegawai='.Pegawai::count().' penilaian='.App\Models\PenilaianKinerja::count()
    .' periode='.App\Models\PeriodePenilaian::count().' pejabat='.App\Models\PejabatPenilai::count()
    .' mou='.DataMou::count().' sk='.DataSk::count().' bahan='.BahanPenilaian::count()
    .' status='.StatusKepegawaian::count().' users='.User::count().PHP_EOL;
echo $gagal === 0 ? 'SEMUA UJI CRUD SELESAI TANPA KEGAGALAN'.PHP_EOL : 'MASIH ADA KEGAGALAN'.PHP_EOL;