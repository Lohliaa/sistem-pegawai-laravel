<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use App\Support\ExcelHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Throwable;

class ManajemenUserController extends Controller
{
    /**
     * Urutan kolom untuk upload Excel user (kolom database => judul kolom).
     */
    private const KOLOM_EXCEL = [
        'username' => 'Username',
        'password' => 'Password',
        'role' => 'Role',
        'nama' => 'Nama',
        'tempat' => 'Tempat Lahir',
        'tanggal_lahir' => 'Tanggal Lahir',
        'gender' => 'Jenis Kelamin (L/P)',
        'alamat' => 'Alamat',
        'unit' => 'Unit',
        'jabatan' => 'Jabatan',
        'tanggal_tmt' => 'Tanggal TMT',
    ];

    /**
     * Kolom tanggal pada file upload (dinormalisasi ke Y-m-d).
     */
    private const KOLOM_TANGGAL = ['tanggal_lahir', 'tanggal_tmt'];
    public function index(Request $request)
    {
        $users = User::with('pegawai')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($sub) use ($search) {
                    $sub->where('username', 'like', '%'.$search.'%')
                        ->orWhereHas('pegawai', function ($pegawai) use ($search) {
                            $pegawai->where('nama', 'like', '%'.$search.'%')
                                ->orWhere('unit', 'like', '%'.$search.'%');
                        });
                });
            })
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->input('role')))
            ->orderBy('username')
            ->paginate(10)
            ->withQueryString();

        return view('manajemen-user.index', compact('users'));
    }

    public function create()
    {
        $roles = User::ROLES;
        $pegawais = Pegawai::whereNull('user_id')->orderBy('nama')->get();

        return view('manajemen-user.create', compact('roles', 'pegawais'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'nama' => 'nullable|string|max:255',
            'pegawai_id' => 'nullable|integer|exists:pegawai,id',
        ], [], [
            'username' => 'username',
            'password' => 'password',
            'role' => 'role',
            'nama' => 'nama pegawai',
            'pegawai_id' => 'pegawai',
        ]);

        DB::transaction(function () use ($validated) {
            $pegawaiId = $validated['pegawai_id'] ?? null;

            if (empty($pegawaiId) && !empty($validated['nama'])) {
                $pegawai = Pegawai::create([
                    'nama' => $validated['nama'],
                ]);
                $pegawaiId = $pegawai->id;
            }

            $user = User::create([
                'username' => $validated['username'],
                'password' => $validated['password'],
                'role' => $validated['role'],
                'pegawai_id' => $pegawaiId,
            ]);

            if ($pegawaiId) {
                Pegawai::where('id', $pegawaiId)->update(['user_id' => $user->id]);
            }
        });

        return redirect()->route('manajemen-user.index')
            ->with('success', 'User dan profil pegawai berhasil dibuat!');
    }

    public function show(string $id)
    {
        $user = User::with(['pegawai', 'pengajuan'])->findOrFail($id);

        return view('manajemen-user.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = User::ROLES;

        return view('manajemen-user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
        ], [], [
            'username' => 'username',
            'password' => 'password',
            'role' => 'role',
        ]);

        $user->username = $validated['username'];
        $user->role = $validated['role'];

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()->route('manajemen-user.index')
            ->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        if ($user->pengajuan()->exists()) {
            return back()->with('error', 'User tidak dapat dihapus karena masih memiliki data pengajuan!');
        }

        $user->delete();

        return redirect()->route('manajemen-user.index')
            ->with('success', 'User berhasil dihapus!');
    }

    /**
     * Import user dari file Excel/CSV. Setiap baris membuat akun user
     * dan otomatis terhubung ke profil pegawai (profile-pegawai).
     */
    public function importExcel(Request $request)
    {
        set_time_limit(300);

        $request->validate([
            'file_excel' => 'required|file|mimes:xls,xlsx,csv,txt|max:10240',
        ], [
            'file_excel.mimes' => 'Format file harus .xls, .xlsx, atau .csv',
            'file_excel.max' => 'Ukuran file Excel maksimal 10 MB',
        ], [
            'file_excel' => 'file Excel',
        ]);

        try {
            $rows = ExcelHelper::readRows($request->file('file_excel'));
        } catch (Throwable $e) {
            return back()->with('error', 'Gagal membaca file: '.$e->getMessage());
        }

        $kolom = ExcelHelper::mapKolomDariJudul($rows[0] ?? [], self::KOLOM_EXCEL);
        $berhasil = 0;
        $dilewati = 0;
        $profilTersimpan = 0;

        try {
            DB::transaction(function () use ($rows, $kolom, &$berhasil, &$dilewati, &$profilTersimpan) {
                foreach (array_slice($rows, 1) as $row) {
                    if ($this->barisKosong($row)) {
                        $dilewati++;

                        continue;
                    }

                    $data = $this->prepareImportData($this->dataDariBaris($row, $kolom));

                    $username = $data['username'];

                    if ($username === null || $username === '') {
                        $dilewati++;

                        continue;
                    }

                    $user = User::where('username', $username)->first();

                    if ($user === null) {
                        $user = User::create([
                            'username' => $username,
                            'password' => $data['password'],
                            'role' => $data['role'],
                        ]);

                        $berhasil++;
                    } elseif ($user->pegawai !== null) {
                        // Username dan profil pegawainya sudah terdaftar.
                        $dilewati++;

                        continue;
                    }

                    // Tautkan akun user ke profil pegawai (profile-pegawai).
                    if ($data['nama'] !== null && $data['nama'] !== '') {
                        if ($user->pegawai === null) {
                            Pegawai::create([
                                'user_id' => $user->id,
                                'nama' => $data['nama'],
                                'tempat' => $data['tempat'],
                                'tanggal_lahir' => $data['tanggal_lahir'],
                                'gender' => $data['gender'],
                                'alamat' => $data['alamat'],
                                'unit' => $data['unit'],
                                'jabatan' => $data['jabatan'],
                                'tanggal_tmt' => $data['tanggal_tmt'],
                            ]);

                            $profilTersimpan++;
                        }
                    } else {
                        $dilewati++;
                    }
                }
            });
        } catch (Throwable $e) {
            return back()->with('error', 'Import gagal: '.$e->getMessage());
        }

        if ($berhasil === 0 && $profilTersimpan === 0) {
            return back()->with('error', "Import selesai, namun tidak ada data yang tersimpan ({$dilewati} baris dilewati).");
        }

        return redirect()->route('manajemen-user.index')
            ->with('success', "Import selesai! {$berhasil} user baru dibuat, {$profilTersimpan} profil pegawai terhubung, {$dilewati} baris dilewati.");
    }

    /**
     * Export seluruh data user beserta profil pegawainya ke Excel.
     */
    public function exportExcel()
    {
        $users = User::with('pegawai')->orderBy('id')->get();

        $headings = [
            'No', 'ID User', 'Username', 'Role', 'Nama Pegawai', 'Tempat Lahir',
            'Tanggal Lahir', 'Alamat', 'Jabatan', 'Unit', 'Status',
        ];

        $rows = $users->values()->map(function (User $user, int $index) {
            $pegawai = $user->pegawai;

            return [
                $index + 1,
                $user->id,
                $user->username,
                strtoupper($user->role),
                $pegawai?->nama ?? '-',
                $pegawai?->tempat ?? '-',
                $pegawai?->tanggal_lahir ? $pegawai->tanggal_lahir->format('d/m/Y') : '-',
                $pegawai?->alamat ?? '-',
                $pegawai?->jabatan ?? '-',
                $pegawai?->unit ?? '-',
                $pegawai ? 'Terhubung' : 'Belum Terhubung',
            ];
        })->all();

        $spreadsheet = ExcelHelper::spreadsheet($headings, $rows, 'Data User');

        return ExcelHelper::download($spreadsheet, 'data_users_'.date('Y-m-d').'.xlsx');
    }
/**
     * Unduh template Excel kosong sesuai format upload user.
     */
    public function templateExcel()
    {
        $spreadsheet = ExcelHelper::spreadsheet(array_values(self::KOLOM_EXCEL), [], 'Template Upload User');

        return ExcelHelper::download($spreadsheet, 'template_upload_user.xlsx');
    }

    /**
     * Ambil nilai satu baris Excel sesuai kolom yang ditentukan (field => index kolom).
     *
     * @param  array<int, mixed>  $row
     * @param  array<string, int>  $kolom
     * @return array<string, mixed>
     */
    private function dataDariBaris(array $row, array $kolom): array
    {
        $data = [];

        foreach ($kolom as $field => $index) {
            $data[$field] = $row[$index] ?? null;
        }

        return $data;
    }

    /**
     * Normalisasi data hasil upload Excel user.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function prepareImportData(array $data): array
    {
        foreach (self::KOLOM_TANGGAL as $kolom) {
            $data[$kolom] = isset($data[$kolom]) && $data[$kolom] !== ''
                ? ExcelHelper::normalizeDate($data[$kolom])
                : null;
        }

        $role = strtolower(trim((string) ($data['role'] ?? '')));
        $data['role'] = array_key_exists($role, User::ROLES) ? $role : 'staf';
        $data['gender'] = $this->normalizeGender($data['gender'] ?? null);

        return [
            'username' => $this->teks($data['username'] ?? null),
            'password' => $this->teks($data['password'] ?? null) ?? '123456',
            'role' => $data['role'],
            'nama' => $this->teks($data['nama'] ?? null),
            'tempat' => $this->teks($data['tempat'] ?? null),
            'tanggal_lahir' => $data['tanggal_lahir'],
            'gender' => $data['gender'],
            'alamat' => $this->teks($data['alamat'] ?? null),
            'unit' => $this->teks($data['unit'] ?? null),
            'jabatan' => $this->teks($data['jabatan'] ?? null),
            'tanggal_tmt' => $data['tanggal_tmt'],
        ];
    }

    /**
     * Normalisasi kolom jenis kelamin menjadi 'L' / 'P' (null bila tidak dikenal).
     */
    private function normalizeGender(mixed $nilai): ?string
    {
        $teks = strtolower(trim((string) ($nilai ?? '')));

        return match ($teks) {
            'l', 'laki', 'laki-laki', 'pria', 'male' => 'L',
            'p', 'perempuan', 'wanita', 'female' => 'P',
            default => null,
        };
    }

    /**
     * Ubah nilai sel menjadi teks bersih (null bila kosong).
     */
    private function teks(mixed $nilai): ?string
    {
        if ($nilai === null) {
            return null;
        }

        $nilai = trim((string) $nilai);

        return $nilai === '' ? null : $nilai;
    }

    /**
     * Cek apakah baris Excel benar-benar kosong.
     *
     * @param  array<int, mixed>  $row
     */
    private function barisKosong(array $row): bool
    {
        foreach ($row as $nilai) {
            if (trim((string) $nilai) !== '') {
                return false;
            }
        }

        return true;
    }
}