<?php

namespace App\Services\AssettoCorsa;

class News
{
    public function getNews()
    {
        $newsList = [];
        $news = [
            \ACEvent::getNews(),
            \ACChampionships::getNews(),
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