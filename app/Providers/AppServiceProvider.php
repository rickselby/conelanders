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
        $this->app->bind(\App\Interfaces\Races\CategoriesInterface::class, function() {
            # Hey, this one doesn't use tags!
            return app(\App\Services\Cached\Races\Categories::Class);
        });
        $this->app->bind(\App\Interfaces\Races\ChampionshipInterface::class, function() {
            return $this->checkCacheTaggable(\App\Services\Cached\Races\Championships::Class, \App\Services\Races\Championships::class);
        });
        $this->app->bind(\App\Interfaces\Races\ConstructorStandingsInterface::class, function() {
            return $this->checkCacheTaggable(\App\Services\Cached\Races\ConstructorStandings::class, \App\Services\Races\ConstructorStandings::class);
        });
        $this->app->bind(\App\Interfaces\Races\DriverStandingsInterface::class, function() {
            return $this->checkCacheTaggable(\App\Services\Cached\Races\DriverStandings::class, \App\Services\Races\DriverStandings::class);
        });
        $this->app->bind(\App\Interfaces\Races\EventInterface::class, function() {
            return $this->checkCacheTaggable(\App\Services\Cached\Races\Event::class, \App\Services\Races\Event::class);
        });
        $this->app->bind(\App\Interfaces\Races\ResultsInterface::class,  function() {
            return $this->checkCacheTaggable(\App\Services\Cached\Races\Results::class, \App\Services\Races\Results::class);
        });
        $this->app->bind(\App\Interfaces\Races\TeamStandingsInterface::class, function() {
            return $this->checkCacheTaggable(\App\Services\Cached\Races\TeamStandings::class, \App\Services\Races\TeamStandings::class);
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
