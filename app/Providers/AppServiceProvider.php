<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            static $appSlogan = null;
            if ($appSlogan === null) {
                try {
                    $raw = \App\Models\Setting::where('key', 'app_slogan')->value('value');
                    $appSlogan = $raw ?: 'Tangkas di Alam, Adaptif di Zaman, Unggul Berkarakter';
                } catch (\Throwable $e) {
                    $appSlogan = 'Tangkas di Alam, Adaptif di Zaman, Unggul Berkarakter';
                }
            }
            $view->with('appSlogan', $appSlogan);
        });
    }
}
