<?php

Breadcrumbs::register('dirt-rally.championship.season.event.show',
    function($breadcrumbs, $champSlug, $seasonSlug, $eventSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('dirt-rally.championship.season.show', '', '', $event->season);
        $breadcrumbs->push($event->name, route('dirt-rally.championship.season.event.show', [$event->season->championship, $event->season, $event]));
    }
);

Breadcrumbs::register('dirt-rally.championship.season.event.edit',
    function($breadcrumbs, $champSlug, $seasonSlug, $eventSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('dirt-rally.championship.season.event.show', '', '', '', $event);
        $breadcrumbs->push('Update', route('dirt-rally.championship.season.event.edit', [$event->season->championship, $event->season, $event]));
    }
);

Breadcrumbs::register('dirt-rally.championship.season.event.stage.create',
    function($breadcrumbs, $champSlug, $seasonSlug, $eventSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('dirt-rally.championship.season.event.show', '', '', '', $event);
        $breadcrumbs->push('Create Stage', route('dirt-rally.championship.season.event.stage.create', [$event->season->championship, $event->season, $event]));
    }
);
