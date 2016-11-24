<?php

Breadcrumbs::register('rallycross.results.championship',
    function($breadcrumbs, \App\Models\RallyCross\RxChampionship $championship) {
        $breadcrumbs->parent('rallycross.index');
        $breadcrumbs->push($championship->name, route('rallycross.results.championship', $championship));
    }
);

Breadcrumbs::register('rallycross.results.event',
    function($breadcrumbs, $champSlug, $raceSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('rallycross.results.championship', $event->championship);
        $breadcrumbs->push($event->name, route('rallycross.results.event', [$event->championship, $event]));
    }
);