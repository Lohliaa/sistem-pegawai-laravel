<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update routes/web.php
        $webPath = base_path('routes/web.php');
        $webContent = file_get_contents($webPath);
        
        $targetRoute = "Route::get('data-mou/template', [DataMouController::class, 'template'])->name('data-mou.template');";
        $newRoutes = "Route::get('data-mou/pembaruan', [DataMouController::class, 'pembaruanMou'])->name('data-mou.pembaruan');\n        Route::get('data-mou/pembaruan/export', [DataMouController::class, 'exportPembaruan'])->name('data-mou.pembaruan.export');\n        " . $targetRoute;
        
        if (str_contains($webContent, $targetRoute) && !str_contains($webContent, 'data-mou.pembaruan')) {
            $webContent = str_replace($targetRoute, $newRoutes, $webContent);
            file_put_contents($webPath, $webContent);
        }

        // 2. Update DataMouController.php
        $ctrlPath = app_path('Http/Controllers/DataMouController.php');
        $ctrlContent = file_get_contents($ctrlPath);
        
        $pembaruanMethods = '
    public function pembaruanMou(Request $request)
    {
        $bulan = $request->get(\'bulan\', \'semua\');
        $query = DataMou::query();

        if ($bulan !== \'semua\' && is_numeric($bulan)) {
            $query->where(\, \Illuminate\Support\Facades\DB::raw(\'MONTH(tgl_mou)\'), $bulan);
        }

        $mous = $query->get()->map(function ($mou) {
            $mou->bulan_mou = $mou->tgl_mou ? \Illuminate\Support\Carbon::parse($mou->tgl_mou)->format(\'F\') : \'-\';
            return $mou;
        });

        return view(\'data-mou.pembaruan\', compact(\'mous\', \'bulan\'));
    }

    public function exportPembaruan(Request $request)
    {
        $bulan = $request->get(\'bulan\', \'semua\');
        $query = DataMou::query();

        if ($bulan !== \'semua\' && is_numeric($bulan)) {
            $query->where(\Illuminate\Support\Facades\DB::raw(\'MONTH(tgl_mou)\'), $bulan);
        }

        $mous = $query->get();
        $headings = [\'No\', \'Nama\', \'Status Kepegawaian\', \'Unit Kerja\', \'Tanggal MoU\', \'Tanggal Akhir\'];
        $rows = [];

        foreach ($mous as $index => $mou) {
            $rows[] = [
                $index + 1,
                $mou->nama,
                $mou->status_kepegawaian,
                $mou->unit_kerja,
                $mou->tgl_mou ? \Illuminate\Support\Carbon::parse($mou->tgl_mou)->format(\'d-m-Y\') : \'-\',
                $mou->tanggal_akhir ? \Illuminate\Support\Carbon::parse($mou->tanggal_akhir)->format(\'d-m-Y\') : \'-\',
            ];
        }

        $namaBulan = \'Semua\';
        if ($bulan !== \'semua\' && is_numeric($bulan)) {
            $namaBulan = \Illuminate\Support\Carbon::create()->month((int)$bulan)->format(\'F\');
        }

        $spreadsheet = ExcelHelper::spreadsheet($headings, $rows, \'Pembaruan MoU\');
        return ExcelHelper::download($spreadsheet, \'Pembaruan_MoU_\' . $namaBulan . \'_\' . date(\'Y-m-d\') . \'.xlsx\');
    }
';

        if (!str_contains($ctrlContent, 'pembaruanMou')) {
            $ctrlContent = preg_replace('/}\s*$/', $pembaruanMethods . "\n}", $ctrlContent);
            file_put_contents($ctrlPath, $ctrlContent);
        }

        // 3. Update resources/views/data-mou/index.blade.php
        $indexPath = resource_path('views/data-mou/index.blade.php');
        $indexContent = file_get_contents($indexPath);
        
        $targetBtn = '<a href="{{ route(\'data-mou.create\') }}" class="btn btn-primary" title="Tambah">';
        $replacementBtn = '<a href="{{ route(\'data-mou.pembaruan\') }}" class="btn btn-secondary" title="Pembaruan MoU">
                        <i class="bi bi-arrow-repeat"></i> Pembaruan MoU
                    </a>
                    <a href="{{ route(\'data-mou.create\') }}" class="btn btn-primary" title="Tambah">';
                    
        if (str_contains($indexContent, $targetBtn) && !str_contains($indexContent, 'data-mou.pembaruan')) {
            $indexContent = str_replace($targetBtn, $replacementBtn, $indexContent);
            file_put_contents($indexPath, $indexContent);
        }
    }

    public function down(): void
    {
        //
    }
};
