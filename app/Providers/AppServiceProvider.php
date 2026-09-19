<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\PejabatPenilai;
use App\Models\PejabatYayasan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('print.penilaian', function ($view) {
            $view->with('kabidSdm', PejabatPenilai::where('jabatan', 'LIKE', '%Kepala Bidang SDM%')->first());
            $view->with('ketuaYayasan', PejabatYayasan::first());
        });
    }
}

