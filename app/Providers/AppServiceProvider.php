<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// 1. PASTIKAN BARIS INI ADA DI BAGIAN ATAS FILE:
use Illuminate\Support\Facades\URL; 

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
        // 2. Memaksa HTTPS jika aplikasi berjalan di production (Railway)
        if (config('app.env') === 'production' || env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}