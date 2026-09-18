<?php

namespace App\Http\Controllers;

use App\Models\PenilaianKinerja;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function cetak(string $id)
    {
        $penilaian = PenilaianKinerja::with(['pegawai', 'periode', 'pejabatPenilai'])->findOrFail($id);
        $aspek = PenilaianKinerja::ASPEK;
        $predikat = PenilaianKinerja::hitungPredikat($penilaian->nilai_total);

        return view('print.penilaian', compact('penilaian', 'aspek', 'predikat'));
    }
}
