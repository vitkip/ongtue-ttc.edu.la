<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // This app is hosted under a subdirectory (XAMPP htdocs without a
        // dedicated vhost, e.g. /SanghaCollegeOngtue/public), not the domain
        // root. Without forcing the root URL, Laravel/Livewire/Filament's
        // url()-style helpers generate root-relative paths (e.g. "/livewire/
        // livewire.js") that resolve outside the app entirely.
        if (config('app.url')) {
            URL::forceRootUrl(config('app.url'));
        }
    }
}
