<?php

namespace App\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Event::listen(\Illuminate\Log\Events\MessageLogged::class, function ($event) {
            if (str_contains($event->message, 'Non-numeric score')) {
                // Notifique os desenvolvedores ou tome ação corretiva
            }
        });
    }
}
