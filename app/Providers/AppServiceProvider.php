<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot()
    {
        if (str_contains(request()->getHost(), 'ngrok')) {
            \URL::forceScheme('https');
        }
        Carbon::setLocale('es');
    }
}

