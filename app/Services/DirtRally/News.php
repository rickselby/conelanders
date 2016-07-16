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

    public function getNews(Carbon $start, Carbon $end)
    {
        $newsList = [];
        $news = [
            $this->events->getNews($start, $end),
            $this->seasons->getNews($start, $end),
            $this->championships->getNews($start, $end),
        ];
        foreach($news AS $source) {
            foreach($source AS $time => $item) {
                if (!isset($newsList[$time])) {
                    $newsList[$time] = [
                        'type' => 'Dirt Rally',
                        'content' => [],
                    ];
                }
                $newsList[$time]['content'][] = $item;
            }
        }
        return $newsList;        
    }
}