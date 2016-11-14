<?php

Breadcrumbs::register('races.results.championship',
    function($breadcrumbs, \App\Models\Races\RacesChampionship $championship) {
        $breadcrumbs->parent('races.index');
        $breadcrumbs->push($championship->name, route('races.results.championship', $championship));
    }
);

Breadcrumbs::register('races.results.event',
    function($breadcrumbs, $champSlug, $raceSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('races.results.championship', $event->championship);
        $breadcrumbs->push($event->name, route('races.results.event', [$event->championship, $event]));
    }
);