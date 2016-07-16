<?php

namespace App\Services\DirtRally;

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

    public function getNews()
    {
        $newsList = [];
        $news = [
            $this->events->getNews(),
            $this->seasons->getNews(),
            $this->championships->getNews(),
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