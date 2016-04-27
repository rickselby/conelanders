<?php

Breadcrumbs::register('dirt-rally.nationstandings.index', function($breadcrumbs) {
    $breadcrumbs->parent('dirt-rally.index');
    $breadcrumbs->push('Nation Standings', route('dirt-rally.nationstandings.index'));
});

Breadcrumbs::register('dirt-rally.nationstandings.system', function($breadcrumbs, \App\Models\DirtRally\PointsSystem $system) {
    $breadcrumbs->parent('dirt-rally.nationstandings.index');
    $breadcrumbs->push($system->name, route('dirt-rally.nationstandings.system', $system));
});

Breadcrumbs::register('dirt-rally.nationstandings.championship',
    function($breadcrumbs, \App\Models\DirtRally\PointsSystem $system, \App\Models\DirtRally\Championship $championship) {
        $breadcrumbs->parent('dirt-rally.nationstandings.system', $system);
        $breadcrumbs->push($championship->name, route('dirt-rally.nationstandings.championship', [$system, $championship]));
    }
);

Breadcrumbs::register('dirt-rally.nationstandings.season',
    function($breadcrumbs, \App\Models\DirtRally\PointsSystem $system, $champSlug, $seasonSlug, $season = null) {
        if (!$season) {
            $season = \Request::get('season');
        }
        $breadcrumbs->parent('dirt-rally.nationstandings.championship', $system, $season->championship);
        $breadcrumbs->push($season->name, route('dirt-rally.nationstandings.season', [$system, $season->championship, $season]));
    }
);

Breadcrumbs::register('dirt-rally.nationstandings.event',
    function($breadcrumbs, \App\Models\DirtRally\PointsSystem $system, $champSlug, $seasonSlug, $eventSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('dirt-rally.nationstandings.season', $system, '', '', $event->season);
        $breadcrumbs->push($event->name, route('dirt-rally.nationstandings.event', [$system, $event->season->championship, $event->season, $event]));
    }
);
