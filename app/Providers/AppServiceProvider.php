<?php

namespace App\Providers;

use Illuminate\Cache\TaggableStore;
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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*
         * We can't check if the cache is taggable when register() is processed,
         * so we must defer till later and check when the interface is requested.
         */
        $this->app->bind(\App\Interfaces\AssettoCorsa\ChampionshipInterface::class, function() {
            return $this->checkCacheTaggable(\App\Services\Cached\AssettoCorsa\Championships::Class, \App\Services\AssettoCorsa\Championships::class);
        });
        $this->app->bind(\App\Interfaces\AssettoCorsa\ConstructorStandingsInterface::class, function() {
            return $this->checkCacheTaggable(\App\Services\Cached\AssettoCorsa\ConstructorStandings::class, \App\Services\AssettoCorsa\ConstructorStandings::class);
        });
        $this->app->bind(\App\Interfaces\AssettoCorsa\DriverStandingsInterface::class, function() {
            return $this->checkCacheTaggable(\App\Services\Cached\AssettoCorsa\DriverStandings::class, \App\Services\AssettoCorsa\DriverStandings::class);
        });
        $this->app->bind(\App\Interfaces\AssettoCorsa\EventInterface::class, function() {
            return $this->checkCacheTaggable(\App\Services\Cached\AssettoCorsa\Event::class, \App\Services\AssettoCorsa\Event::class);
        });
        $this->app->bind(\App\Interfaces\AssettoCorsa\ResultsInterface::class,  function() {
            return $this->checkCacheTaggable(\App\Services\Cached\AssettoCorsa\Results::class, \App\Services\AssettoCorsa\Results::class);
        });
        $this->app->bind(\App\Interfaces\DirtRally\ResultsInterface::class, function() {
            return $this->checkCacheTaggable(\App\Services\Cached\DirtRally\Results::class, \App\Services\DirtRally\Results::class);
        });
        $this->app->bind(\App\Interfaces\DirtRally\DriverPointsInterface::class, function() {
            return $this->checkCacheTaggable(\App\Services\Cached\DirtRally\DriverPoints::class, \App\Services\DirtRally\DriverPoints::class);
        });
        $this->app->bind(\App\Interfaces\DirtRally\NationPointsInterface::class, function() {
            return $this->checkCacheTaggable(\App\Services\Cached\DirtRally\NationPoints::class, \App\Services\DirtRally\NationPoints::class);
        });
        $this->app->bind(\App\Interfaces\DirtRally\TimesInterface::class, function() {
            return $this->checkCacheTaggable(\App\Services\Cached\DirtRally\Times::class, \App\Services\DirtRally\Times::class);
        });
    }

    /**
     * Check if the cache implementation is taggable. If it is, return the first class; if not, return the second.
     * @param string $ifCacheTaggable Fully Qualified Class Name
     * @param string $ifCacheNotTaggable Fully Qualified Class Name
     */
    protected function checkCacheTaggable($ifCacheTaggable, $ifCacheNotTaggable)
    {
        if (\Cache::getStore() instanceof TaggableStore) {
            return app($ifCacheTaggable);
        } else {
            return app($ifCacheNotTaggable);
        }
    }
}
