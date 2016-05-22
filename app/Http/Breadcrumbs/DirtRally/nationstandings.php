<?php

Breadcrumbs::register('dirt-rally.nationstandings.championship',
    function($breadcrumbs, \App\Models\DirtRally\DirtChampionship $championship) {
        $breadcrumbs->parent('dirt-rally.championship', $championship);
        $breadcrumbs->push('Nation Standings', route('dirt-rally.nationstandings.championship', $championship));
    }
);

Breadcrumbs::register('dirt-rally.nationstandings.overview',
    function($breadcrumbs, \App\Models\DirtRally\DirtChampionship $championship) {
        $breadcrumbs->parent('dirt-rally.nationstandings.championship', $championship);
        $breadcrumbs->push("Overview", route('dirt-rally.nationstandings.overview', $championship));
    }
);

Breadcrumbs::register('dirt-rally.nationstandings.season',
    function($breadcrumbs, $champSlug, $seasonSlug, $season = null) {
        if (!$season) {
            $season = \Request::get('season');
        }
        $breadcrumbs->parent('dirt-rally.nationstandings.championship', $season->championship);
        $breadcrumbs->push($season->name, route('dirt-rally.nationstandings.season', [$season->championship, $season]));
    }
);

Breadcrumbs::register('dirt-rally.nationstandings.event',
    function($breadcrumbs, $champSlug, $seasonSlug, $eventSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('dirt-rally.nationstandings.season', '', '', $event->season);
        $breadcrumbs->push($event->name, route('dirt-rally.nationstandings.event', [$event->season->championship, $event->season, $event]));
    }
);


Breadcrumbs::register('dirt-rally.nationstandings.detail',
    function($breadcrumbs, $champSlug, $seasonSlug, $eventSlug, \App\Models\Nation $nation) {
        $event = \Request::get('event');
        $breadcrumbs->parent('dirt-rally.nationstandings.event', '', '', '');
        $breadcrumbs->push($nation->name, route('dirt-rally.nationstandings.detail', [$event->season->championship, $event->season, $event, $nation]));
    }
);
