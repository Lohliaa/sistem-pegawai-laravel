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
        $filters = $request->only(['pegawai_id', 'periode_id', 'pejabat_penilai_id', 'status', 'kategori']);
        $kategori = $request->get('kategori', 'pegawai');

        $kategoriLabels = [
            'pegawai' => 'Pegawai',
            'guru-alquran' => "Guru Al Qur'an",
            'guru-non-alquran' => "Guru Non Al Qur'an",
            'wali-kelas-reguler' => 'Wali Kelas',
            'koordinator-jenjang' => 'Koordinator Jenjang',
            'koordinator-alquran' => "Koordinator Al Qur'an",
            'leader' => 'Leader',
            'musyrifah' => 'Musyrif/ah',
            'cs' => 'CS',
        ];

        $kategoriNama = $kategoriLabels[$kategori] ?? 'Pegawai';

        $filters['kategori'] = $kategori;

        $penilaians = PenilaianKinerja::with(['pegawai', 'periode', 'pejabatPenilai'])
            ->filter($filters);

        // Pembatasan akses untuk Pejabat Penilai (Kanit / Kabid) pada semua kategori form penilaian
        if (auth()->check() && (auth()->user()->role === 'kanit' || auth()->user()->role === 'kabid')) {
            $pegawai = auth()->user()->pegawai;
            $pejabat = PejabatPenilai::where('pegawai_id', $pegawai?->id)->first();
            if ($pejabat) {
                $penilaians->where('pejabat_penilai_id', $pejabat->id);
            }
        }

        $penilaians = $penilaians->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('form-penilaian.index', [
            'penilaians' => $penilaians,
            'filters' => $filters,
            'kategori' => $kategori,
            'kategoriNama' => $kategoriNama,
            'pegawais' => Pegawai::orderBy('nama')->get(),
            'periodes' => PeriodePenilaian::ordered()->get(),
            'pejabats' => PejabatPenilai::orderBy('nama')->get(),
        ]);
    }

    public function create(Request $request)
    {
        if (auth()->user()->role === 'staf') {
            abort(403, 'Unauthorized access.');
        }

        $kategori = $request->get('kategori', 'pegawai');
        return view('form-penilaian.create', array_merge($this->dataForm(null, $kategori), ['kategori' => $kategori]));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'staf') {
            abort(403, 'Unauthorized access.');
        }

        $kategori = $request->input('kategori', 'pegawai');
        $validated = $request->validate($this->rules(null, $kategori), $this->messages($kategori), $this->attributes());

        $detail = $this->detailNilai($validated, $kategori);

        if ($detail['rata'] === null) {
            return back()->withInput()->withErrors(['nilai' => 'Minimal satu baris penilaian harus diberi nilai.']);
        }

        PenilaianKinerja::create([
            'pegawai_id' => $validated['pegawai_id'],
            'periode_id' => $validated['periode_id'],
            'pejabat_penilai_id' => $validated['pejabat_penilai_id'],
            'status_kepegawaian_id' => $validated['status_kepegawaian_id'],
            'detail_penilaian' => $detail['detail'],
            'nilai_total' => $detail['rata'],
            'jumlah_total' => $detail['jumlah'],
            'catatan' => $validated['catatan'] ?? null,
            'kategori' => $kategori,
        ]);

        return redirect()->route('form-penilaian.index', ['kategori' => $kategori])
            ->with('success', 'Penilaian kinerja berhasil disimpan!');
    }

    public function show(string $id)
    {
        $penilaian = PenilaianKinerja::with(['pegawai', 'periode', 'pejabatPenilai', 'statusKepegawaian'])->findOrFail($id);

        if (auth()->user()->role === 'staf' && $penilaian->pegawai?->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('form-penilaian.show', [
            'penilaian' => $penilaian,
            'aspek' => PenilaianKinerja::ASPEK,
        ]);
    }

    public function edit(Request $request, string $id)
    {
        if (auth()->user()->role === 'staf') {
            abort(403, 'Unauthorized access.');
        }

        $penilaian = PenilaianKinerja::findOrFail($id);
        $kategori = $penilaian->kategori ?? $request->get('kategori', 'pegawai');

        return view('form-penilaian.edit', array_merge($this->dataForm($penilaian, $kategori), ['kategori' => $kategori]));
    }

    public function update(Request $request, string $id)
    {
        if (auth()->user()->role === 'staf') {
            abort(403, 'Unauthorized access.');
        }

        $penilaian = PenilaianKinerja::findOrFail($id);
        $kategori = $penilaian->kategori ?? $request->input('kategori', 'pegawai');

        $validated = $request->validate($this->rules($penilaian, $kategori), $this->messages($kategori), $this->attributes());

        $detail = $this->detailNilai($validated, $kategori);

        if ($detail['rata'] === null) {
            return back()->withInput()->withErrors(['nilai' => 'Minimal satu baris penilaian harus diberi nilai.']);
        }

        $penilaian->update([
            'pegawai_id' => $validated['pegawai_id'],
            'periode_id' => $validated['periode_id'],
            'pejabat_penilai_id' => $validated['pejabat_penilai_id'],
            'status_kepegawaian_id' => $validated['status_kepegawaian_id'],
            'detail_penilaian' => $detail['detail'],
            'nilai_total' => $detail['rata'],
            'jumlah_total' => $detail['jumlah'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('form-penilaian.index', ['kategori' => $kategori])
            ->with('success', 'Penilaian kinerja berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        if (auth()->user()->role === 'staf') {
            abort(403, 'Unauthorized access.');
        }

        $penilaian = PenilaianKinerja::findOrFail($id);

        $kategori = $penilaian->kategori ?? 'pegawai';
        $penilaian->delete();

        return redirect()->route('form-penilaian.index', ['kategori' => $kategori])
            ->with('success', 'Penilaian kinerja berhasil dihapus!');
    }

    /**
     * Data pendukung form penilaian.
     *
     * @return array<string, mixed>
     */
    private function dataForm(?PenilaianKinerja $penilaian = null, string $kategori = 'pegawai'): array
    {
        $kategoriLabels = [
            'pegawai' => 'Pegawai',
            'guru-alquran' => "Guru Al Qur'an",
            'guru-non-alquran' => "Guru Non Al Qur'an",
            'wali-kelas-reguler' => 'Wali Kelas Reguler',
            'wali-kelas-icp' => 'Wali Kelas ICP',
            'koordinator-jenjang' => 'Koordinator Jenjang',
            'koordinator-alquran' => "Koordinator Al Qur'an",
            'leader' => 'Leader',
            'musyrifah' => 'Musyrif/ah',
            'cs' => 'CS',
        ];

        return [
            'penilaian' => $penilaian,
            'kategori' => $kategori,
            'kategoriNama' => $kategoriLabels[$kategori] ?? 'Pegawai',
            'pegawais' => Pegawai::orderBy('nama')->get(),
            'periodes' => PeriodePenilaian::ordered()->get(),
            'pejabats' => PejabatPenilai::orderBy('nama')->get(),
            'statusKepegawaians' => \App\Models\StatusKepegawaian::all(),
            'aspek' => PenilaianKinerja::ASPEK,
            'items' => PenilaianKinerja::getItems($kategori),
        ];
    }

    /**
     * Bentuk rincian nilai per item dari input validasi.
     *
     * Item dengan sub-poin menghitung nilai induknya sebagai rata-rata dari
     * poin-poin yang terisi, dan menyimpan nilai tiap poin di indeks "sub".
     *
     * @param  array<string, mixed>  $validated
     * @return array{detail: array<string, array<string, mixed>>, jumlah: float, rata: float|null}
     */
    private function detailNilai(array $validated, string $kategori = 'pegawai'): array
    {
        $nilaiInput = $validated['nilai'] ?? [];
        $catatanInput = $validated['catatan_baris'] ?? [];
        $catatanPoin = $validated['catatan_poin'] ?? [];

        $detail = [];
        $terisi = [];

        foreach (PenilaianKinerja::getItems($kategori) as $row) {
            if (($row['type'] ?? '') !== 'item') {
                continue;
            }

            $key = $row['key'];

            $catatan = $catatanInput[$key] ?? null;
            $catatan = is_array($catatan) ? null : $catatan; // defensif
            $catatan = $catatan !== null && $catatan !== '' ? $catatan : null;

            if (empty($row['sub'])) {
                $nilai = isset($nilaiInput[$key]) && $nilaiInput[$key] !== ''
                    ? (int) $nilaiInput[$key]
                    : null;

                if ($nilai !== null) {
                    $terisi[] = $nilai;
                }

                $detail[$key] = [
                    'nilai' => $nilai,
                    'catatan' => $catatan,
                ];

                continue;
            }

            $sub = [];
            $jumlahSub = 0;
            $terisiSub = 0;

            foreach ($row['sub'] as $i => $subDef) {
                $subNilai = isset($nilaiInput[$key]['sub'][$i]) && $nilaiInput[$key]['sub'][$i] !== ''
                    ? (int) $nilaiInput[$key]['sub'][$i]
                    : null;

                $subCatatan = $catatanPoin[$key][$i] ?? null;
                $subCatatan = is_array($subCatatan) ? null : $subCatatan; // defensif
                $subCatatan = $subCatatan !== null && $subCatatan !== '' ? $subCatatan : null;

                if ($subNilai !== null) {
                    $jumlahSub += $subNilai;
                    $terisiSub++;
                }

                $sub[] = [
                    'nilai' => $subNilai,
                    'catatan' => $subCatatan,
                ];
            }

            $rata = $terisiSub > 0 ? round($jumlahSub / $terisiSub, 2) : null;

            if ($rata !== null) {
                $terisi[] = $rata;
            }

            $detail[$key] = [
                'nilai' => $rata,
                'catatan' => $catatan,
                'sub' => $sub,
            ];
        }

        $jumlah = array_sum($terisi);
        $rata = $terisi === [] ? null : round($jumlah / count($terisi), 2);

        return [
            'detail' => $detail,
            'jumlah' => $jumlah,
            'rata' => $rata,
        ];
    }

    /**
     * Aturan validasi penilaian kinerja.
     *
     * @return array<string, mixed>
     */
    private function rules(?PenilaianKinerja $penilaian = null, string $kategori = 'pegawai'): array
    {
        $rules = [
            'periode_id' => 'required|integer|exists:periode_penilaian,id',
            'pejabat_penilai_id' => 'required|integer|exists:pejabat_penilai,id',
            'status_kepegawaian_id' => 'required|integer|exists:status_kepegawaian,id',
            'nilai' => 'nullable|array',
            'catatan_baris' => 'nullable|array',
            'catatan_poin' => 'nullable|array',
            'catatan' => 'nullable|string',
        ];

        // Aturan per baris nilai & catatan (termasuk sub-poin).
        foreach (PenilaianKinerja::getItems($kategori) as $row) {
            if (($row['type'] ?? '') !== 'item') {
                continue;
            }

            $key = $row['key'];

            $rules['catatan_baris.'.$key] = 'nullable|string|max:500';

            if (empty($row['sub'])) {
                $rules['nilai.'.$key] = 'nullable|integer|min:0|max:4';
                continue;
            }

            foreach ($row['sub'] as $i => $subDef) {
                $rules['nilai.'.$key.'.sub.'.$i] = 'nullable|integer|min:0|max:4';
                $rules['catatan_poin.'.$key.'.'.$i] = 'nullable|string|max:500';
            }
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
    private function messages(string $kategori = 'pegawai'): array
    {
        $messages = [
            'pegawai_id.unique' => 'Pegawai tersebut sudah dinilai pada periode yang dipilih.',
        ];

        // Pesan min/max untuk setiap baris nilai (induk & sub-poin).
        foreach (PenilaianKinerja::getItems($kategori) as $row) {
            if (($row['type'] ?? '') !== 'item') {
                continue;
            }

            $key = $row['key'];

            if (empty($row['sub'])) {
                $messages['nilai.'.$key.'.max'] = 'Nilai "'.$row['uraian'].'" maksimal 4.';
                $messages['nilai.'.$key.'.min'] = 'Nilai "'.$row['uraian'].'" minimal 0.';
                continue;
            }

            foreach ($row['sub'] as $i => $subDef) {
                $label = $row['uraian'].' ('.chr(97 + $i).'. '.$subDef['uraian'].')';
                $messages['nilai.'.$key.'.sub.'.$i.'.max'] = 'Nilai "'.$label.'" maksimal 4.';
                $messages['nilai.'.$key.'.sub.'.$i.'.min'] = 'Nilai "'.$label.'" minimal 0.';
            }
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
            'status_kepegawaian_id' => 'status kepegawaian',
        ];
    }
}