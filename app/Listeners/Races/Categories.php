<?php

namespace App\Listeners\Races;

use App\Events\Races\CategoriesUpdated;
use Illuminate\Events\Dispatcher;

/**
 * NOTE: These ARE NOT queued. The cache for the categories list should be cleared immediately.
 */
class Categories
{
    private $categories;
    public function __construct(\App\Services\Cached\Races\Categories $categories)
    {
        $this->categories = $categories;
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            CategoriesUpdated::class,
            'App\Listeners\Races\Categories@clearCache'
        );
    }

    /**
     * Clear categories cache
     */
    public function clearCache()
    {
        $this->categories->clearListCache();
    }

}