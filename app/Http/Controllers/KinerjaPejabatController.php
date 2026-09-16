<?php

namespace App\Http\Controllers;

use App\Models\PejabatPenilai;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KinerjaPejabatController extends Controller
{
    public function index()
    {
        $pejabats = PejabatPenilai::with('pegawai')
            ->withCount('penilaian')
            ->orderBy('nama')
            ->paginate(10);

        return view('kinerja-pejabat.index', compact('pejabats'));
    }

    public function create()
    {
        $pegawais = $this->pegawaiTersedia();
        $statuses = PejabatPenilai::STATUSES;

        return view('kinerja-pejabat.create', compact('pegawais', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id' => [
                'required',
                'integer',
                'exists:pegawai,id',
                Rule::unique('pejabat_penilai', 'pegawai_id'),
            ],
            'keterangan' => 'nullable|string|max:255',
            'status_aktif' => ['required', Rule::in(array_keys(PejabatPenilai::STATUSES))],
        ], [
            'pegawai_id.unique' => 'Pegawai tersebut sudah terdaftar sebagai pejabat penilai!',
        ], [
            'pegawai_id' => 'pegawai',
            'status_aktif' => 'status',
        ]);

        $pegawai = Pegawai::findOrFail($validated['pegawai_id']);

        PejabatPenilai::create([
            'pegawai_id' => $pegawai->id,
            'nama' => mb_substr($pegawai->nama, 0, 100),
            'jabatan' => mb_substr((string) ($pegawai->jabatan ?? ''), 0, 50),
            'unit' => mb_substr((string) ($pegawai->unit ?? ''), 0, 20),
            'keterangan' => $validated['keterangan'] ?? null,
            'status_aktif' => $validated['status_aktif'],
        ]);

        return redirect()->route('kinerja-pejabat.index')
            ->with('success', 'Pejabat penilai berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $pejabat = PejabatPenilai::with(['pegawai', 'penilaian.periode'])->findOrFail($id);

        return view('kinerja-pejabat.show', compact('pejabat'));
    }

    public function edit(string $id)
    {
        $pejabat = PejabatPenilai::findOrFail($id);
        $statuses = PejabatPenilai::STATUSES;

        return view('kinerja-pejabat.edit', compact('pejabat', 'statuses'));
    }

    public function update(Request $request, string $id)
    {
        $pejabat = PejabatPenilai::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jabatan' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string|max:255',
            'status_aktif' => ['required', Rule::in(array_keys(PejabatPenilai::STATUSES))],
        ], [], [
            'nama' => 'nama',
            'jabatan' => 'jabatan',
            'unit' => 'unit',
            'status_aktif' => 'status',
        ]);

        $pejabat->update($validated);

        return redirect()->route('kinerja-pejabat.index')
            ->with('success', 'Pejabat penilai berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $pejabat = PejabatPenilai::findOrFail($id);

        if ($pejabat->penilaian()->exists()) {
            return back()->with('error', 'Pejabat tidak dapat dihapus karena sudah memiliki data penilaian!');
        }

        $pejabat->delete();

        return redirect()->route('kinerja-pejabat.index')
            ->with('success', 'Pejabat penilai berhasil dihapus!');
    }

    /**
     * Daftar pegawai yang belum terdaftar sebagai pejabat penilai.
     */
    private function pegawaiTersedia()
    {
        return Pegawai::whereNotIn('id', PejabatPenilai::query()->select('pegawai_id'))
            ->orderBy('nama')
            ->get();
    }
}