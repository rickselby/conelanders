<?php

Breadcrumbs::register('dirt-rally.championship.season.show',
    function($breadcrumbs, $champSlug, $seasonSlug, $season = null) {
        if (!$season) {
            $season = \Request::get('season');
        }
        $breadcrumbs->parent('dirt-rally.championship.show', $season->championship);
        $breadcrumbs->push($season->name, route('dirt-rally.championship.season.show', [$season->championship, $season]));
    }
);

Breadcrumbs::register('dirt-rally.championship.season.edit',
    function($breadcrumbs, $champSlug, $seasonSlug, $season = null) {
        if (!$season) {
            $season = \Request::get('season');
        }
        $breadcrumbs->parent('dirt-rally.championship.season.show', '', '', $season);
        $breadcrumbs->push('Update', route('dirt-rally.championship.season.edit', [$season->championship, $season]));
    }
);

Breadcrumbs::register('dirt-rally.championship.season.event.create',
    function($breadcrumbs, $champSlug, $seasonSlug, $season = null) {
        if (!$season) {
            $season = \Request::get('season');
        }
        $breadcrumbs->parent('dirt-rally.championship.season.show', '', '', $season);
        $breadcrumbs->push('Create Event', route('dirt-rally.championship.season.event.create', [$season->championship, $season]));
    }
);
