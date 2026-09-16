<?php

namespace App\Http\Controllers;

use App\Models\PejabatPenilai;
use App\Models\Pegawai;
use App\Models\PenilaianKinerja;
use App\Models\PeriodePenilaian;
use App\Support\ExcelHelper;
use Illuminate\Http\Request;

class LaporanPenilaianController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['pegawai_id', 'periode_id', 'pejabat_penilai_id']);

        $laporans = PenilaianKinerja::with(['pegawai', 'periode', 'pejabatPenilai'])
            ->filter($filters)
            ->join('pegawai', 'pegawai.id', '=', 'penilaian_kinerja.pegawai_id')
            ->orderBy('pegawai.nama')
            ->orderByDesc('penilaian_kinerja.id')
            ->select('penilaian_kinerja.*')
            ->paginate(10)
            ->withQueryString();

        return view('laporan-penilaian.index', [
            'laporans' => $laporans,
            'filters' => $filters,
            'pegawais' => Pegawai::orderBy('nama')->get(),
            'periodes' => PeriodePenilaian::ordered()->get(),
            'pejabats' => PejabatPenilai::orderBy('nama')->get(),
        ]);
    }

    public function detail(string $id)
    {
        $laporan = PenilaianKinerja::with(['pegawai', 'periode', 'pejabatPenilai'])->findOrFail($id);

        return view('laporan-penilaian.detail', [
            'laporan' => $laporan,
            'aspek' => PenilaianKinerja::ASPEK,
            'predikat' => PenilaianKinerja::hitungPredikat($laporan->nilai_total),
        ]);
    }

    /**
     * Export rekap laporan penilaian kinerja ke Excel.
     */
    public function export(Request $request)
    {
        $laporans = PenilaianKinerja::with(['pegawai', 'periode', 'pejabatPenilai'])
            ->filter($request->only(['pegawai_id', 'periode_id', 'pejabat_penilai_id']))
            ->join('pegawai', 'pegawai.id', '=', 'penilaian_kinerja.pegawai_id')
            ->orderBy('pegawai.nama')
            ->orderByDesc('penilaian_kinerja.id')
            ->select('penilaian_kinerja.*')
            ->get();

        $headings = array_merge(
            ['No', 'Nama Pegawai', 'Jabatan', 'Unit', 'Pejabat Penilai', 'Periode', 'Tanggal Penilaian'],
            array_column(PenilaianKinerja::ASPEK, 'label'),
            ['Nilai Total', 'Predikat', 'Status', 'Catatan']
        );

        $rows = [];

        foreach ($laporans as $index => $laporan) {
            $row = [
                $index + 1,
                $laporan->pegawai?->nama ?? '-',
                $laporan->pegawai?->jabatan ?? '-',
                $laporan->pegawai?->unit ?? '-',
                $laporan->pejabatPenilai?->nama ?? '-',
                $laporan->periode?->label ?? '-',
                $laporan->updated_at ? $laporan->updated_at->format('d/m/Y') : '-',
            ];

            foreach (array_keys(PenilaianKinerja::ASPEK) as $kolom) {
                $row[] = $laporan->{$kolom} ?? '-';
            }

            $row[] = $laporan->nilai_total !== null ? number_format((float) $laporan->nilai_total, 2, ',', '.') : '-';
            $row[] = PenilaianKinerja::hitungPredikat($laporan->nilai_total)['label'];
            $row[] = PenilaianKinerja::STATUSES[$laporan->status] ?? $laporan->status;
            $row[] = $laporan->catatan ?? '-';

            $rows[] = $row;
        }

        $spreadsheet = ExcelHelper::spreadsheet($headings, $rows, 'Laporan Penilaian');

        return ExcelHelper::download($spreadsheet, 'laporan_penilaian_'.date('Y-m-d_His').'.xlsx');
    }
}