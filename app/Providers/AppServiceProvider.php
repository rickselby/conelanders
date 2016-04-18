<?php

namespace App\Providers;

use App\Models\Championship;
use App\Models\PointsSystem;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // These are only for web pages...
        if (!app()->runningInConsole()) {
            view()->share('championships', Championship::all()->sortBy('closes'));
            view()->share('defaultPointsSystem', PointsSystem::where('default', true)->first());
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
