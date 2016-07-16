<?php

namespace App\Services\AssettoCorsa;

use Carbon\Carbon;

class News
{
    /**
     * @var Event
     */
    protected $event;

    /**
     * @var Championships
     */
    protected $championships;

    public function __construct(Event $event, Championships $championships)
    {
        $this->event = $event;
        $this->championships = $championships;
    }

    public function getNews(Carbon $start, Carbon $end)
    {
        $newsList = [];
        $news = [
            $this->event->getNews($start, $end),
            $this->championships->getNews($start, $end),
        ];
        foreach($news AS $source) {
            foreach($source AS $time => $item) {
                if (!isset($newsList[$time])) {
                    $newsList[$time] = [
                        'type' => 'Assetto Corsa',
                        'content' => [],
                    ];
                }
                $newsList[$time]['content'][] = $item;
            }
        }
        return $newsList;        
    }
}