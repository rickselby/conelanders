<?php

Breadcrumbs::register('assetto-corsa.results.championship',
    function($breadcrumbs, \App\Models\AssettoCorsa\AcChampionship $championship) {
        $breadcrumbs->parent('assetto-corsa.index');
        $breadcrumbs->push($championship->name, route('assetto-corsa.results.championship', $championship));
    }
);

Breadcrumbs::register('assetto-corsa.results.event',
    function($breadcrumbs, $champSlug, $raceSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('assetto-corsa.results.championship', $event->championship);
        $breadcrumbs->push($event->name, route('assetto-corsa.results.event', [$event->championship, $event]));
    }
);