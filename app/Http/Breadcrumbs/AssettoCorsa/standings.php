<?php

Breadcrumbs::register('assetto-corsa.standings.index', function($breadcrumbs) {
    $breadcrumbs->parent('assetto-corsa.index');
    $breadcrumbs->push('Driver Standings', route('assetto-corsa.standings.index'));
});

Breadcrumbs::register('assetto-corsa.standings.championship',
    function($breadcrumbs, \App\Models\AssettoCorsa\AcChampionship $championship) {
        $breadcrumbs->parent('assetto-corsa.index');
        $breadcrumbs->push($championship->name, route('assetto-corsa.standings.championship', $championship));
    }
);

Breadcrumbs::register('assetto-corsa.standings.event',
    function($breadcrumbs, $champSlug, $raceSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('assetto-corsa.standings.championship', $event->championship);
        $breadcrumbs->push($event->name, route('assetto-corsa.standings.event', [$event->championship, $event]));
    }
);