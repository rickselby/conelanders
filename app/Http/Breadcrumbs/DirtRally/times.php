<?php

Breadcrumbs::register('dirt-rally.times.championship',
    function($breadcrumbs, \App\Models\DirtRally\DirtChampionship $championship) {
        $breadcrumbs->parent('dirt-rally.championship', $championship);
        $breadcrumbs->push('Total Times', route('dirt-rally.times.championship', $championship));
    }
);

Breadcrumbs::register('dirt-rally.times.season',
    function($breadcrumbs, $champSlug, $seasonSlug, $season = null) {
        if (!$season) {
            $season = \Request::get('season');
        }
        $breadcrumbs->parent('dirt-rally.times.championship', $season->championship);
        $breadcrumbs->push($season->name, route('dirt-rally.times.season', [$season->championship, $season]));
    }
);

Breadcrumbs::register('dirt-rally.times.event',
    function($breadcrumbs, $champSlug, $seasonSlug, $eventSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('dirt-rally.times.season', '', '', $event->season);
        $breadcrumbs->push($event->name, route('dirt-rally.times.event', [$event->season->championship, $event->season, $event]));
    }
);

Breadcrumbs::register('dirt-rally.times.stage',
    function($breadcrumbs, $champSlug, $seasonSlug, $eventSlug, $stageSlug, $stage = null) {
        if (!$stage) {
            $stage = \Request::get('stage');
        }
        $breadcrumbs->parent('dirt-rally.times.event', '', '', '', $stage->event);
        $breadcrumbs->push($stage->name, route('dirt-rally.times.stage', [$stage->event->season->championship, $stage->event->season, $stage->event, $stage]));
    }
);
