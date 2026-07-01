<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Barang;
use App\Models\LaporanKerusakan;
use App\Observers\BarangObserver;
use App\Observers\LaporanKerusakanObserver;

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
        Barang::observe(BarangObserver::class);
        LaporanKerusakan::observe(LaporanKerusakanObserver::class);
    }
}