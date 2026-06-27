<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Daftarkan View Composer untuk floating button
        View::composer(
            'modules.landing.partials.floating-btn',
            \App\Http\View\Composers\FloatingButtonComposer::class
        );
    }
}