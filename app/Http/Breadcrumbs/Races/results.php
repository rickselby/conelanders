<?php

Breadcrumbs::register('races.results.championship',
    function($breadcrumbs, \App\Models\Races\RacesCategory $category, \App\Models\Races\RacesChampionship $championship) {
        $breadcrumbs->parent('races.index', $category);
        $breadcrumbs->push($championship->name, route('races.results.championship', [$category, $championship]));
    }
);

Breadcrumbs::register('races.results.event',
    function($breadcrumbs, $categorySlug, $champSlug, $raceSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('races.results.championship', $event->championship->category, $event->championship);
        $breadcrumbs->push($event->name, route('races.results.event', [$event->championship->category, $event->championship, $event]));
    }
);