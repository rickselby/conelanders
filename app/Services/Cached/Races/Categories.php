<?php

namespace App\Services\Cached\Races;

use App\Interfaces\Races\CategoriesInterface;
use Illuminate\Contracts\Cache\Repository;

class Categories implements CategoriesInterface
{
    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var \App\Services\Races\Categories
     */
    protected $categoriesService;

    /**
     * @var string
     */
    protected $cacheKey = 'categories.';

    public function __construct(Repository $cache, \App\Services\Races\Categories $categories)
    {
        $this->cache = $cache;
        $this->categoriesService = $categories;
    }

    /**
     * @inheritdoc
     */
    public function getList()
    {
        return $this->cache->rememberForever(
            $this->cacheKey.'list',
            function() {
                return $this->categoriesService->getList();
            }
        );
    }

    public function clearListCache()
    {
        $this->cache->forget($this->cacheKey.'list');
    }
}
