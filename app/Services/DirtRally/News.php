<?php

namespace App\Services\DirtRally;

use Carbon\Carbon;

class News
{
    /**
     * @var Events
     */
    protected $events;

    /**
     * @var Seasons
     */
    protected $seasons;
    
    /**
     * @var Championships
     */
    protected $championships;

    public function __construct(Events $events, Seasons $seasons, Championships $championships)
    {
        $this->events = $events;
        $this->seasons = $seasons;
        $this->championships = $championships;
    }

    public function getPastNews(Carbon $start, Carbon $end)
    {
        return $this->parseNews([
            $this->events->getPastNews($start, $end),
            $this->seasons->getPastNews($start, $end),
            $this->championships->getPastNews($start, $end),
        ]);
    }

    public function getUpcomingNews(Carbon $start, Carbon $end)
    {
        return $this->parseNews([
            $this->events->getUpcomingNews($start, $end),
        ]);
    }
    
    public function getCurrentNews()
    {
        return $this->parseNews([
            $this->events->getCurrentNews()
        ]);
    }

    protected function parseNews($news)
    {
        $newsList = [];
        foreach($news AS $source) {
            if (count($source)) {
                foreach ($source AS $time => $item) {
                    if ($item) {
                        if (!isset($newsList[$time])) {
                            $newsList[$time] = [
                                'type' => 'Dirt Rally',
                                'content' => [],
                            ];
                        }
                        $newsList[$time]['content'][] = $item;
                    }
                }
            }
        }
        return $newsList;
    }
}