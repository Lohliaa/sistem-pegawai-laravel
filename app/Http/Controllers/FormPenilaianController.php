<?php

namespace App\Http\Controllers;

use App\Models\PejabatPenilai;
use App\Models\Pegawai;
use App\Models\PenilaianKinerja;
use App\Models\PeriodePenilaian;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FormPenilaianController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['pegawai_id', 'periode_id', 'pejabat_penilai_id', 'status']);

        $penilaians = PenilaianKinerja::with(['pegawai', 'periode', 'pejabatPenilai'])
            ->filter($filters)
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('form-penilaian.index', [
            'penilaians' => $penilaians,
            'filters' => $filters,
            'pegawais' => Pegawai::orderBy('nama')->get(),
            'periodes' => PeriodePenilaian::ordered()->get(),
            'pejabats' => PejabatPenilai::orderBy('nama')->get(),
            'statuses' => PenilaianKinerja::STATUSES,
        ]);
    }

    public function create()
    {
        return view('form-penilaian.create', $this->dataForm());
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages(), $this->attributes());

        $nilai = $this->nilaiAspek($validated);

        if (PenilaianKinerja::hitungNilaiTotal($nilai) === null) {
            return back()->withInput()->withErrors([array_key_first(PenilaianKinerja::ASPEK) => 'Minimal satu aspek penilaian harus diberi nilai.']);
        }

        PenilaianKinerja::create([
            'pegawai_id' => $validated['pegawai_id'],
            'periode_id' => $validated['periode_id'],
            'pejabat_penilai_id' => $validated['pejabat_penilai_id'],
            ...$nilai,
            'nilai_total' => PenilaianKinerja::hitungNilaiTotal($nilai),
            'catatan' => $validated['catatan'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('form-penilaian.index')
            ->with('success', 'Penilaian kinerja berhasil disimpan!');
    }

    public function show(string $id)
    {
        $penilaian = PenilaianKinerja::with(['pegawai', 'periode', 'pejabatPenilai'])->findOrFail($id);

        return view('form-penilaian.show', [
            'penilaian' => $penilaian,
            'aspek' => PenilaianKinerja::ASPEK,
        ]);
    }

    public function edit(string $id)
    {
        $penilaian = PenilaianKinerja::findOrFail($id);

        return view('form-penilaian.edit', $this->dataForm($penilaian));
    }

    public function update(Request $request, string $id)
    {
        $penilaian = PenilaianKinerja::findOrFail($id);

        $validated = $request->validate($this->rules($penilaian), $this->messages(), $this->attributes());

        $nilai = $this->nilaiAspek($validated);

        if (PenilaianKinerja::hitungNilaiTotal($nilai) === null) {
            return back()->withInput()->withErrors([array_key_first(PenilaianKinerja::ASPEK) => 'Minimal satu aspek penilaian harus diberi nilai.']);
        }

        $penilaian->update([
            'pegawai_id' => $validated['pegawai_id'],
            'periode_id' => $validated['periode_id'],
            'pejabat_penilai_id' => $validated['pejabat_penilai_id'],
            ...$nilai,
            'nilai_total' => PenilaianKinerja::hitungNilaiTotal($nilai),
            'catatan' => $validated['catatan'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('form-penilaian.index')
            ->with('success', 'Penilaian kinerja berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $penilaian = PenilaianKinerja::findOrFail($id);
        $penilaian->delete();

        return redirect()->route('form-penilaian.index')
            ->with('success', 'Penilaian kinerja berhasil dihapus!');
    }

    /**
     * Data pendukung form penilaian.
     *
     * @return array<string, mixed>
     */
    private function dataForm(?PenilaianKinerja $penilaian = null): array
    {
        return [
            'penilaian' => $penilaian,
            'pegawais' => Pegawai::orderBy('nama')->get(),
            'periodes' => PeriodePenilaian::ordered()->get(),
            'pejabats' => PejabatPenilai::where('status_aktif', 'aktif')->orderBy('nama')->get(),
            'statuses' => PenilaianKinerja::STATUSES,
            'aspek' => PenilaianKinerja::ASPEK,
        ];
    }

    /**
     * Ambil pasangan kolom nilai aspek => nilai dari input.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, int|null>
     */
    private function nilaiAspek(array $validated): array
    {
        $nilai = [];

        foreach (array_keys(PenilaianKinerja::ASPEK) as $kolom) {
            $nilai[$kolom] = isset($validated[$kolom]) && $validated[$kolom] !== ''
                ? (int) $validated[$kolom]
                : null;
        }

        return $nilai;
    }

    /**
     * Aturan validasi penilaian kinerja.
     *
     * @return array<string, mixed>
     */
    private function rules(?PenilaianKinerja $penilaian = null): array
    {
        $rules = [
            'periode_id' => 'required|integer|exists:periode_penilaian,id',
            'pejabat_penilai_id' => 'required|integer|exists:pejabat_penilai,id',
            'catatan' => 'nullable|string',
            'status' => ['required', Rule::in(array_keys(PenilaianKinerja::STATUSES))],
        ];

        foreach (array_keys(PenilaianKinerja::ASPEK) as $kolom) {
            $rules[$kolom] = 'nullable|integer|min:0|max:4';
        }

        $rules['pegawai_id'] = [
            'required',
            'integer',
            'exists:pegawai,id',
            Rule::unique('penilaian_kinerja', 'pegawai_id')
                ->where('periode_id', request()->input('periode_id'))
                ->ignore($penilaian?->id),
        ];

        return $rules;
    }

    /**
     * Pesan validasi khusus.
     *
     * @return array<string, string>
     */
    private function messages(): array
    {
        $messages = [
            'pegawai_id.unique' => 'Pegawai tersebut sudah dinilai pada periode yang dipilih.',
        ];

        foreach (array_keys(PenilaianKinerja::ASPEK) as $kolom) {
            $messages[$kolom.'.max'] = 'Nilai setiap aspek maksimal 4.';
            $messages[$kolom.'.min'] = 'Nilai setiap aspek minimal 0.';
        }

        return $messages;
    }

    /**
     * Nama atribut untuk pesan validasi.
     *
     * @return array<string, string>
     */
    private function attributes(): array
    {
        return [
            'pegawai_id' => 'pegawai',
            'periode_id' => 'periode',
            'pejabat_penilai_id' => 'pejabat penilai',
        ];
    }
}