<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
// use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (config('app.env') === 'production') {
            // https://stackoverflow.com/questions/73294167/livewire-image-upload-fails-on-production-server
            \Illuminate\Http\Request::macro('hasValidSignature', function ($absolute = true) {
                if('livewire/upload-file' == request()->path()) {
                    return true;
                }
                return \Illuminate\Support\Facades\URL::hasValidSignature($this, $absolute);
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if (config('app.env') === 'production') \Illuminate\Support\Facades\URL::forceScheme('https');

        // Carbon::setLocale(app()->getLocale());
        // dd(now()->translatedFormat('l, d \d\e F \d\e Y'));

        Gate::before(function ($user, $ability) {
            return $user->hasRole('super admin') ? true : null;
        });
    }
}
