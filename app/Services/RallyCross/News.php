<?php

namespace App\Services\RallyCross;

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

    public function getPastNews(Carbon $start, Carbon $end)
    {
        return $this->parseNews([
            $this->event->getPastNews($start, $end),
            $this->championships->getPastNews($start, $end),
        ]);
    }

    public function getUpcomingNews(Carbon $start, Carbon $end)
    {
        return $this->parseNews([
            $this->event->getUpcomingNews($start, $end),
        ]);
    }

    protected function parseNews($news)
    {
        $newsList = [];
        foreach($news AS $source) {
            foreach($source AS $time => $item) {
                if (!isset($newsList[$time])) {
                    $newsList[$time] = [
                        'type' => 'RallyCross',
                        'content' => [],
                    ];
                }
                $newsList[$time]['content'][] = $item;
            }
        }
        return $newsList;
    }
}