<?php

namespace App\Http\Controllers;

use App\Models\PeriodePenilaian;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KinerjaPeriodeController extends Controller
{
    public function index()
    {
        $periodes = PeriodePenilaian::withCount('penilaian')
            ->ordered()
            ->paginate(10);

        return view('kinerja-periode.index', compact('periodes'));
    }

    public function create()
    {
        $kuartal = PeriodePenilaian::KUARTAL;

        return view('kinerja-periode.create', compact('kuartal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), [], $this->attributes());

        PeriodePenilaian::create($validated);

        return redirect()->route('kinerja-periode.index')
            ->with('success', 'Periode penilaian berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $periode = PeriodePenilaian::withCount('penilaian')->findOrFail($id);

        return view('kinerja-periode.show', compact('periode'));
    }

    public function edit(string $id)
    {
        $periode = PeriodePenilaian::findOrFail($id);
        $kuartal = PeriodePenilaian::KUARTAL;

        return view('kinerja-periode.edit', compact('periode', 'kuartal'));
    }

    public function update(Request $request, string $id)
    {
        $periode = PeriodePenilaian::findOrFail($id);

        $validated = $request->validate($this->rules($periode), [], $this->attributes());

        $periode->update($validated);

        return redirect()->route('kinerja-periode.index')
            ->with('success', 'Periode penilaian berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $periode = PeriodePenilaian::findOrFail($id);

        if ($periode->penilaian()->exists()) {
            return back()->with('error', 'Periode tidak dapat dihapus karena sudah dipakai pada data penilaian!');
        }

        $periode->delete();

        return redirect()->route('kinerja-periode.index')
            ->with('success', 'Periode penilaian berhasil dihapus!');
    }

    /**
     * Aturan validasi periode penilaian.
     *
     * @return array<string, mixed>
     */
    private function rules(?PeriodePenilaian $periode = null): array
    {
        return [
            'nama_kuartal' => ['required', 'string', Rule::in(PeriodePenilaian::KUARTAL)],
            'periode_bulan' => 'required|string|max:20',
            'tahun' => [
                'required',
                'integer',
                'between:2000,2100',
                Rule::unique('periode_penilaian', 'tahun')
                    ->where(fn ($query) => $query->where('nama_kuartal', request('nama_kuartal')))
                    ->ignore($periode?->id),
            ],
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
            'nama_kuartal' => 'kuartal',
            'periode_bulan' => 'periode bulan',
            'tahun' => 'tahun',
        ];
    }
}