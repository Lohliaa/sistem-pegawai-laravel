<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use App\Models\StatusKepegawaian;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfilePegawaiController extends Controller
{
    public function index(Request $request)
    {
        $totalPegawai = Pegawai::count();
        $pegawaiPerUnit = Pegawai::select('unit', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('unit')
            ->pluck('total', 'unit');
        $pegawaiPerGender = Pegawai::select('gender', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->pluck('total', 'gender');
        $pegawaiPerJabatan = Pegawai::select('jabatan', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('jabatan')
            ->pluck('total', 'jabatan');
        $pegawaiPerStatusKepegawaian = Pegawai::with('statusKepegawaian')
            ->get()
            ->groupBy(fn($p) => $p->statusKepegawaian?->nama_status ?? 'Tidak Ada Status')
            ->map->count();
        $pegawaiPerBpi = Pegawai::select('data_bpi', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('data_bpi')
            ->pluck('total', 'data_bpi');
        $pegawaiPerPendidikan = Pegawai::select('pendidikan_terakhir', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('pendidikan_terakhir')
            ->pluck('total', 'pendidikan_terakhir');
        $totalUsers = User::count();

        $pegawais = Pegawai::with('user')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where(function ($sub) use ($search) {
                    $sub->where('nama', 'like', '%'.$search.'%')
                        ->orWhere('unit', 'like', '%'.$search.'%')
                        ->orWhere('jabatan', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('profile-pegawai.index', compact(
            'pegawais',
            'totalPegawai',
            'pegawaiPerUnit',
            'pegawaiPerGender',
            'pegawaiPerJabatan',
            'pegawaiPerStatusKepegawaian',
            'pegawaiPerBpi',
            'pegawaiPerPendidikan',
            'totalUsers'
        ));
    }

    public function create()
    {
        $users = $this->usersBelumTerhubung();
        $statusList = StatusKepegawaian::orderBy('nama_status')->get();

        return view('profile-pegawai.create', compact('users', 'statusList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), [], $this->attributes());

        $pegawai = Pegawai::create($validated);
        $this->handleFileUploads($request, $pegawai);

        return redirect()->route('profile-pegawai.index')
            ->with('success', 'Data pegawai berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $pegawai = Pegawai::with(['user', 'penilaian.periode', 'penilaian.pejabatPenilai', 'statusKepegawaian'])->findOrFail($id);

        return view('profile-pegawai.show', compact('pegawai'));
    }

    public function edit(string $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $users = $this->usersBelumTerhubung($pegawai->user_id);
        $statusList = StatusKepegawaian::orderBy('nama_status')->get();

        return view('profile-pegawai.edit', compact('pegawai', 'users', 'statusList'));
    }

    public function update(Request $request, string $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $validated = $request->validate($this->rules($pegawai), [], $this->attributes());

        $pegawai->update($validated);
        $this->handleFileUploads($request, $pegawai);

        return redirect()->route('profile-pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        // ... (cleanup files if needed)
        $pegawai->delete();
        return redirect()->route('profile-pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus!');
    }

    private function handleFileUploads(Request $request, Pegawai $pegawai)
    {
        $files = ['foto', 'dokumen_ktp', 'dokumen_kk', 'dokumen_ijazah', 'dokumen_sk', 'dokumen_mou', 'dokumen_sk_jabatan', 'dokumen_skck', 'dokumen_sertifikat'];
        foreach ($files as $fileKey) {
            if ($request->hasFile($fileKey)) {
                if ($pegawai->$fileKey) Storage::delete($pegawai->$fileKey);
                $path = $request->file($fileKey)->store('pegawai_files', 'public');
                $pegawai->update([$fileKey => $path]);
            }
        }
    }

    private function rules(?Pegawai $pegawai = null): array
    {
        return [
            'user_id' => ['nullable', 'integer', 'exists:users,id', Rule::unique('pegawai', 'user_id')->ignore($pegawai?->id)],
            'nama' => 'required|string|max:255',
            'tempat' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'gender' => ['nullable', Rule::in(['L', 'P'])],
            'status_pernikahan' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'unit' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'jenis_tenaga' => 'nullable|string|max:100',
            'status_kepegawaian_id' => 'nullable|exists:status_kepegawaian,id',
            'tanggal_tmt' => 'nullable|date',
            'golongan_ruang' => 'nullable|string|max:50',
            'atasan_langsung' => 'nullable|string|max:255',
            'nomor_sk' => 'nullable|string|max:100',
            'tanggal_sk' => 'nullable|date',
            'masa_kerja' => 'nullable|string|max:100',
            'status_aktif' => 'nullable|string|max:20',
            'pendidikan_terakhir' => 'nullable|string|max:50',
            'jurusan' => 'nullable|string|max:100',
            'institusi' => 'nullable|string|max:255',
            'tahun_lulus' => 'nullable|string|max:4',
            'sertifikasi' => 'nullable|string',
            'pelatihan' => 'nullable|string',
            'data_bpi' => 'nullable|string',
            'data_presensi' => 'nullable|string',
            'data_cuti' => 'nullable|string',
            'riwayat_jabatan' => 'nullable|string',
            'riwayat_mutasi' => 'nullable|string',
            'riwayat_status_kepegawaian' => 'nullable|string',
        ];
    }

    private function attributes(): array { return ['user_id' => 'akun user']; }
    private function usersBelumTerhubung(int|string|null $userId = null) {
        $terpakai = Pegawai::query()->whereNotNull('user_id')->pluck('user_id');
        return User::whereNotIn('id', $terpakai)->when($userId !== null, fn ($query) => $query->orWhere('id', $userId))->get();
    }
}