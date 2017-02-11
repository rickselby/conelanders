<?php

namespace App\Services\Cached\AcHotlap;

use App\Models\AcHotlap\AcHotlapSession;
use Illuminate\Cache\TaggableStore;
use Illuminate\Contracts\Cache\Repository;

class Handler
{
    /**
     * @var Repository
     */
    protected $cache;

    protected $cacheKey = 'ac.hotlap.tag.';

    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Get the tag for a session
     * @param AcHotlapSession $session
     * @return string
     */
    public function sessionTag(AcHotlapSession $session)
    {
        return $this->cacheKey.'session.'.$session->id;
    }

    /**
     * Clear the cache for a session, and clear parents
     * @param AcHotlapSession $session
     */
    public function clearSessionCache(AcHotlapSession $session)
    {
        if ($this->checkCacheStoreSupportsTags()) {
            \Log::info('Clearing Assetto Corsa Hotlap Session Cache', ['session' => $session->id]);
            $this->cache->tags($this->sessionTag($session))->flush();
        }

        $this->clearSummaryCache();
    }

    public function summaryName()
    {
        return $this->cacheKey.'full-summary';
    }

    public function clearSummaryCache()
    {
        $this->cache->forget($this->summaryName());
    }

    private function checkCacheStoreSupportsTags()
    {
        return $this->cache->getStore() instanceof TaggableStore;
    }

}