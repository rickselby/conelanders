<?php

namespace App\Providers;

use App\Interfaces\AssettoCorsa\EventInterface;
use App\Interfaces\AssettoCorsa\ResultsInterface;
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
        $this->app->bind(ResultsInterface::class,  function() {
            return $this->checkCache(\App\Services\Cached\AssettoCorsa\Results::class, \App\Services\AssettoCorsa\Results::class);
        });
        $this->app->bind(EventInterface::class, function() {
            return $this->checkCache(\App\Services\Cached\AssettoCorsa\Event::class, \App\Services\AssettoCorsa\Event::class);
        });
    }

    protected function checkCache($ifCacheTaggable, $ifCacheNotTaggable)
    {
        if (\Cache::getStore() instanceof TaggableStore) {
            return app($ifCacheTaggable);
        } else {
            return app($ifCacheNotTaggable);
        }
    }
}
