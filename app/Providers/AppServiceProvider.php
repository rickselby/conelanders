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
        view()->share('standings', PointsSystem::all());
        view()->share('championships', Championship::all()->sortBy('closes'));
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
