<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use App\Support\ExcelHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfilePegawaiController extends Controller
{
    public function index(Request $request)
    {
        $pegawais = Pegawai::with('user')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($sub) use ($search) {
                    $sub->where('nama', 'like', '%'.$search.'%')
                        ->orWhere('unit', 'like', '%'.$search.'%')
                        ->orWhere('jabatan', 'like', '%'.$search.'%')
                        ->orWhere('tempat', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('profile-pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        $users = $this->usersBelumTerhubung();

        return view('profile-pegawai.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), [], $this->attributes());

        Pegawai::create($validated);

        return redirect()->route('profile-pegawai.index')
            ->with('success', 'Data pegawai berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $pegawai = Pegawai::with(['user', 'penilaian.periode', 'penilaian.pejabatPenilai'])->findOrFail($id);

        return view('profile-pegawai.show', compact('pegawai'));
    }

    public function edit(string $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $users = $this->usersBelumTerhubung($pegawai->user_id);

        return view('profile-pegawai.edit', compact('pegawai', 'users'));
    }

    public function update(Request $request, string $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $validated = $request->validate($this->rules($pegawai), [], $this->attributes());

        $pegawai->update($validated);

        return redirect()->route('profile-pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        if ($pegawai->penilaian()->exists()) {
            return back()->with('error', 'Data pegawai tidak dapat dihapus karena sudah memiliki penilaian kinerja!');
        }

        if ($pegawai->pejabatPenilai()->exists()) {
            return back()->with('error', 'Data pegawai tidak dapat dihapus karena terdaftar sebagai pejabat penilai!');
        }

        $pegawai->delete();

        return redirect()->route('profile-pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus!');
    }

    /**
     * Export detail profil pegawai ke Excel.
     */
    public function export(string $id)
    {
        $pegawai = Pegawai::with('user')->findOrFail($id);

        $headings = ['No', 'Keterangan', 'Data'];

        $rows = [
            [1, 'Nama', $pegawai->nama],
            [2, 'Username', $pegawai->user?->username ?? '-'],
            [3, 'Role', $pegawai->user ? strtoupper($pegawai->user->role) : '-'],
            [4, 'Tempat Lahir', $pegawai->tempat ?? '-'],
            [5, 'Tanggal Lahir', $pegawai->tanggal_lahir ? $pegawai->tanggal_lahir->format('d/m/Y') : '-'],
            [6, 'Jenis Kelamin', $pegawai->gender === 'L' ? 'Laki-laki' : ($pegawai->gender === 'P' ? 'Perempuan' : '-')],
            [7, 'Alamat', $pegawai->alamat ?? '-'],
            [8, 'Unit', $pegawai->unit ?? '-'],
            [9, 'Jabatan', $pegawai->jabatan ?? '-'],
            [10, 'TMT', $pegawai->tanggal_tmt ? $pegawai->tanggal_tmt->format('d/m/Y') : '-'],
            [11, 'Jumlah Penilaian', $pegawai->penilaian()->count()],
        ];

        $spreadsheet = ExcelHelper::spreadsheet($headings, $rows, 'Profil Pegawai');

        return ExcelHelper::download($spreadsheet, 'profil_pegawai_'.$pegawai->id.'_'.date('Y-m-d').'.xlsx');
    }

    /**
     * Aturan validasi data pegawai.
     *
     * @return array<string, mixed>
     */
    private function rules(?Pegawai $pegawai = null): array
    {
        return [
            'user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
                Rule::unique('pegawai', 'user_id')->ignore($pegawai?->id),
            ],
            'nama' => 'required|string|max:255',
            'tempat' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'gender' => ['nullable', Rule::in(['L', 'P'])],
            'alamat' => 'nullable|string',
            'unit' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'tanggal_tmt' => 'nullable|date',
        ];
    }

    /**
     * Nama atribut untuk pesan validasi.
     *
     * @return array<string, string>
     */
    private function attributes(): array
    {
        return [
            'user_id' => 'akun user',
            'nama' => 'nama',
            'tanggal_lahir' => 'tanggal lahir',
            'tanggal_tmt' => 'tanggal TMT',
        ];
    }

    /**
     * Daftar akun user yang belum terhubung ke data pegawai.
     */
    private function usersBelumTerhubung(int|string|null $userId = null)
    {
        $terpakai = Pegawai::query()->whereNotNull('user_id')->pluck('user_id');

        return User::whereNotIn('id', $terpakai)
            ->when($userId !== null, fn ($query) => $query->orWhere('id', $userId))
            ->orderBy('username')
            ->get();
    }
}