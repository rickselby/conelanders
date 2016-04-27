<?php

Breadcrumbs::register('dirt-rally.standings.index', function($breadcrumbs) {
    $breadcrumbs->parent('dirt-rally.index');
    $breadcrumbs->push('Driver Standings', route('dirt-rally.standings.index'));
});

Breadcrumbs::register('dirt-rally.standings.system', function($breadcrumbs, \App\Models\DirtRally\DirtPointsSystem $system) {
    $breadcrumbs->parent('dirt-rally.standings.index');
    $breadcrumbs->push($system->name, route('dirt-rally.standings.system', $system));
});

Breadcrumbs::register('dirt-rally.standings.championship',
    function($breadcrumbs, \App\Models\DirtRally\DirtPointsSystem $system, \App\Models\DirtRally\DirtChampionship $championship) {
        $breadcrumbs->parent('dirt-rally.standings.system', $system);
        $breadcrumbs->push($championship->name, route('dirt-rally.standings.championship', [$system, $championship]));
    }
);

Breadcrumbs::register('dirt-rally.standings.overview',
    function($breadcrumbs, \App\Models\DirtRally\DirtPointsSystem $system, \App\Models\DirtRally\DirtChampionship $championship) {
        $breadcrumbs->parent('dirt-rally.standings.championship', $system, $championship);
        $breadcrumbs->push("Overview", route('dirt-rally.standings.overview', [$system, $championship]));
    }
);

Breadcrumbs::register('dirt-rally.standings.season',
    function($breadcrumbs, \App\Models\DirtRally\DirtPointsSystem $system, $champSlug, $seasonSlug, $season = null) {
        if (!$season) {
            $season = \Request::get('season');
        }
        $breadcrumbs->parent('dirt-rally.standings.championship', $system, $season->championship);
        $breadcrumbs->push($season->name, route('dirt-rally.standings.season', [$system, $season->championship, $season]));
    }
);

Breadcrumbs::register('dirt-rally.standings.event',
    function($breadcrumbs, \App\Models\DirtRally\DirtPointsSystem $system, $champSlug, $seasonSlug, $eventSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('dirt-rally.standings.season', $system, '', '', $event->season);
        $breadcrumbs->push($event->name, route('dirt-rally.standings.event', [$system, $event->season->championship, $event->season, $event]));
    }
);

Breadcrumbs::register('dirt-rally.standings.stage',
    function($breadcrumbs, \App\Models\DirtRally\DirtPointsSystem $system, $champSlug, $seasonSlug, $eventSlug, $stageSlug, $stage = null) {
        if (!$stage) {
            $stage = \Request::get('stage');
        }
        $breadcrumbs->parent('dirt-rally.standings.event', $system, '', '', '', $stage->event);
        $breadcrumbs->push($stage->name, route('dirt-rally.standings.stage', [$system, $stage->event->season->championship, $stage->event->season, $stage->event, $stage]));
    }
);
