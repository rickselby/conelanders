<?php

Breadcrumbs::register('dirt-rally.standings.championship',
    function($breadcrumbs, \App\Models\DirtRally\DirtChampionship $championship) {
        $breadcrumbs->parent('dirt-rally.championship', $championship);
        $breadcrumbs->push('Driver Standings', route('dirt-rally.standings.championship', $championship));
    }
);

Breadcrumbs::register('dirt-rally.standings.overview',
    function($breadcrumbs, \App\Models\DirtRally\DirtChampionship $championship) {
        $breadcrumbs->parent('dirt-rally.standings.championship', $championship);
        $breadcrumbs->push("Overview", route('dirt-rally.standings.overview', $championship));
    }
);

Breadcrumbs::register('dirt-rally.standings.season',
    function($breadcrumbs, $champSlug, $seasonSlug, $season = null) {
        if (!$season) {
            $season = \Request::get('season');
        }
        $breadcrumbs->parent('dirt-rally.standings.championship', $season->championship);
        $breadcrumbs->push($season->name, route('dirt-rally.standings.season', [$season->championship, $season]));
    }
);

Breadcrumbs::register('dirt-rally.standings.event',
    function($breadcrumbs, $champSlug, $seasonSlug, $eventSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('dirt-rally.standings.season', '', '', $event->season);
        $breadcrumbs->push($event->name, route('dirt-rally.standings.event', [$event->season->championship, $event->season, $event]));
    }
);

Breadcrumbs::register('dirt-rally.standings.stage',
    function($breadcrumbs, $champSlug, $seasonSlug, $eventSlug, $stageSlug, $stage = null) {
        if (!$stage) {
            $stage = \Request::get('stage');
        }
        $breadcrumbs->parent('dirt-rally.standings.event', '', '', '', $stage->event);
        $breadcrumbs->push('SS'.$stage->order.': '.$stage->name, route('dirt-rally.standings.stage', [$stage->event->season->championship, $stage->event->season, $stage->event, $stage]));
    }
);
