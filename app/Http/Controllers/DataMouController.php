<?php

namespace App\Http\Controllers;

use App\Models\DataMou;
use App\Models\StatusKepegawaian;
use App\Support\ExcelHelper;
use Illuminate\Http\Request;
use Throwable;

class DataMouController extends Controller
{
    /**
     * Urutan kolom untuk import & export Excel (kolom database => judul kolom).
     */
    private const KOLOM_EXCEL = [
        'no_sk' => 'No. SK',
        'no_tambahan' => 'No. Tambahan',
        'status_kepegawaian' => 'Status Kepegawaian',
        'status_detail' => 'Status Detail',
        'nama' => 'Nama',
        'gelar' => 'Gelar',
        'hari_kerja' => 'Hari Kerja',
        'jam_kerja' => 'Jam Kerja',
        'alamat' => 'Alamat',
        'hari' => 'Hari MOU',
        'tgl_mou' => 'Tanggal MOU',
        'tempat_lahir' => 'Tempat Lahir',
        'tanggal_lahir' => 'Tanggal Lahir',
        'unit_kerja' => 'Unit Kerja',
        'gaji_pokok' => 'Gaji Pokok',
        'tunjangan_jabatan' => 'Tunjangan Jabatan',
        'tunjangan_transport' => 'Tunjangan Transport',
        'tunjangan_kinerja' => 'Tunjangan Kinerja',
        'tunjangan_fungsional' => 'Tunjangan Fungsional',
        'thp' => 'THP',
        'terbilang' => 'Terbilang',
        'tgl_mulai' => 'Tanggal Mulai',
        'berlaku' => 'Berlaku',
        'tanggal_akhir' => 'Tanggal Akhir',
        'saksi1' => 'Saksi 1',
        'saksi2' => 'Saksi 2',
    ];

    /**
     * Kolom yang disimpan sebagai tanggal (Y-m-d).
     */
    private const KOLOM_TANGGAL = ['tanggal_lahir', 'tgl_mulai', 'tanggal_akhir'];

    /**
     * Format tanggal yang diterima dari form/import (Excel memakai d/m/Y).
     */
    private const FORMAT_TANGGAL = 'Y-m-d,d/m/Y,d-m-Y,d.m.Y';

    /**
     * Kolom nominal rupiah.
     */
    private const KOLOM_RUPIAH = [
        'gaji_pokok',
        'tunjangan_jabatan',
        'tunjangan_transport',
        'tunjangan_kinerja',
        'tunjangan_fungsional',
        'thp',
    ];

    public function index(Request $request)
    {
        $mous = $this->query($request)->paginate(10)->withQueryString();
        $statusList = StatusKepegawaian::orderBy('nama_status')->get();
        $filters = $request->only(['search', 'status_kepegawaian']);

        return view('data-mou.index', compact('mous', 'statusList', 'filters'));
    }

    public function create()
    {
        $statusList = StatusKepegawaian::orderBy('nama_status')->get();

        return view('data-mou.create', compact('statusList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages(), $this->attributes());

        DataMou::create($this->prepareData($validated));

        return redirect()->route('data-mou.index')
            ->with('success', 'Data MOU berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $mou = DataMou::findOrFail($id);

        return view('data-mou.show', compact('mou'));
    }

    public function edit(string $id)
    {
        $mou = DataMou::findOrFail($id);
        $statusList = StatusKepegawaian::orderBy('nama_status')->get();

        return view('data-mou.edit', compact('mou', 'statusList'));
    }

    public function update(Request $request, string $id)
    {
        $mou = DataMou::findOrFail($id);

        $validated = $request->validate($this->rules(), $this->messages(), $this->attributes());

        $mou->update($this->prepareData($validated));

        return redirect()->route('data-mou.index')
            ->with('success', 'Data MOU berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $mou = DataMou::findOrFail($id);
        $mou->delete();

        return redirect()->route('data-mou.index')
            ->with('success', 'Data MOU berhasil dihapus!');
    }

    /**
     * Import data MOU dari file Excel/CSV.
     */
    public function import(Request $request)
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

        $kolom = array_keys(self::KOLOM_EXCEL);
        $berhasil = 0;
        $dilewati = 0;

        foreach (array_slice($rows, 1) as $row) {
            if ($this->barisKosong($row)) {
                $dilewati++;

                continue;
            }

            $data = [];

            foreach ($kolom as $index => $field) {
                $data[$field] = $row[$index] ?? null;
            }

            $data = $this->prepareImportData($data);

            if ($data['nama'] === null || $data['nama'] === '') {
                $dilewati++;

                continue;
            }

            DataMou::create($data);
            $berhasil++;
        }

        if ($berhasil === 0) {
            return back()->with('error', "Import selesai, namun tidak ada data yang tersimpan ({$dilewati} baris dilewati).");
        }

        return redirect()->route('data-mou.index')
            ->with('success', "Import selesai! {$berhasil} data ditambahkan, {$dilewati} baris dilewati.");
    }

    /**
     * Export data MOU ke Excel sesuai filter yang aktif.
     */
    public function export(Request $request)
    {
        $mous = $this->query($request)->reorder()->orderBy('id')->get();

        $headings = array_values(self::KOLOM_EXCEL);
        $rows = [];

        foreach ($mous as $mou) {
            $row = [];

            foreach (array_keys(self::KOLOM_EXCEL) as $kolom) {
                $nilai = $mou->{$kolom};

                if (in_array($kolom, self::KOLOM_TANGGAL, true)) {
                    $nilai = ExcelHelper::formatDate($nilai);
                } elseif (in_array($kolom, self::KOLOM_RUPIAH, true)) {
                    $nilai = is_numeric($nilai) ? number_format((float) $nilai, 0, ',', '.') : $nilai;
                }

                $row[] = $nilai;
            }

            $rows[] = $row;
        }

        $spreadsheet = ExcelHelper::spreadsheet($headings, $rows, 'Data MOU');

        return ExcelHelper::download($spreadsheet, 'data_mou_'.date('Y-m-d_His').'.xlsx');
    }

    /**
     * Unduh template Excel kosong sesuai format import Data MOU.
     */
    public function template()
    {
        $spreadsheet = ExcelHelper::spreadsheet(array_values(self::KOLOM_EXCEL), [], 'Template Data MOU');

        return ExcelHelper::download($spreadsheet, 'template_data_mou.xlsx');
    }

    /**
     * Query data MOU beserta filter pencarian.
     */
    private function query(Request $request)
    {
        return DataMou::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($sub) use ($search) {
                    $sub->where('nama', 'like', '%'.$search.'%')
                        ->orWhere('no_sk', 'like', '%'.$search.'%')
                        ->orWhere('unit_kerja', 'like', '%'.$search.'%');
                });
            })
            ->when($request->filled('status_kepegawaian'), function ($query) use ($request) {
                $query->where('status_kepegawaian', $request->input('status_kepegawaian'));
            })
            ->latest('id');
    }

    /**
     * Aturan validasi seluruh kolom data MOU.
     *
     * @return array<string, mixed>
     */
    private function rules(): array
    {
        $rules = [];

        foreach (array_keys(self::KOLOM_EXCEL) as $kolom) {
            $rules[$kolom] = match (true) {
                $kolom === 'nama' => 'required|string|max:255',
                in_array($kolom, self::KOLOM_TANGGAL, true) => ['nullable', 'date_format:'.self::FORMAT_TANGGAL],
                in_array($kolom, self::KOLOM_RUPIAH, true) => ['nullable', 'string', 'max:255', 'regex:/^\s*(rp\.?\s*)?[0-9][0-9.,\s]*$/i'],
                in_array($kolom, ['alamat', 'terbilang'], true) => 'nullable|string',
                default => 'nullable|string|max:255',
            };
        }

        return $rules;
    }

    /**
     * Nama atribut untuk pesan validasi.
     *
     * @return array<string, string>
     */
    private function attributes(): array
    {
        $attributes = [];

        foreach (self::KOLOM_EXCEL as $kolom => $judul) {
            $attributes[$kolom] = strtolower($judul);
        }

        return $attributes;
    }

    /**
     * Pesan validasi khusus kolom nominal.
     *
     * @return array<string, string>
     */
    private function messages(): array
    {
        $messages = [];

        foreach (self::KOLOM_RUPIAH as $kolom) {
            $messages[$kolom.'.regex'] = 'Kolom :attribute hanya boleh berisi angka.';
        }

        foreach (self::KOLOM_TANGGAL as $kolom) {
            $messages[$kolom.'.date_format'] = 'Kolom :attribute harus berupa tanggal yang valid (contoh: 31/12/2026).';
        }

        return $messages;
    }

    /**
     * Normalisasi data dari form sebelum disimpan.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function prepareData(array $data): array
    {
        foreach (self::KOLOM_RUPIAH as $kolom) {
            $data[$kolom] = isset($data[$kolom]) && $data[$kolom] !== ''
                ? ExcelHelper::normalizeMoney($data[$kolom])
                : null;
        }

        foreach (self::KOLOM_TANGGAL as $kolom) {
            $data[$kolom] = isset($data[$kolom]) && $data[$kolom] !== ''
                ? ExcelHelper::normalizeDate($data[$kolom])
                : null;
        }

        // tgl_mou tersimpan sebagai teks: konversi ke format Y-m-d bila memang bertipe tanggal.
        if (isset($data['tgl_mou']) && $data['tgl_mou'] !== '') {
            $data['tgl_mou'] = ExcelHelper::normalizeDate($data['tgl_mou']) ?? $data['tgl_mou'];
        }

        return $data;
    }

    /**
     * Normalisasi data hasil import Excel.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function prepareImportData(array $data): array
    {
        $hasil = [];

        foreach (array_keys(self::KOLOM_EXCEL) as $kolom) {
            $nilai = $data[$kolom] ?? null;

            if (in_array($kolom, self::KOLOM_TANGGAL, true)) {
                $nilai = ExcelHelper::normalizeDate($nilai);
            } elseif (in_array($kolom, self::KOLOM_RUPIAH, true)) {
                $nilai = ExcelHelper::normalizeMoney($nilai);
            } elseif ($kolom === 'tgl_mou') {
                $nilai = ExcelHelper::normalizeDate($nilai) ?? $this->teks($nilai);
            } else {
                $nilai = $this->teks($nilai);
            }

            $hasil[$kolom] = $nilai;
        }

        return $hasil;
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