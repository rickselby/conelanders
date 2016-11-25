<?php

Breadcrumbs::register('rallycross.championship.event.show',
    function($breadcrumbs, $champSlug, $eventSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('rallycross.championship.show', $event->championship);
        $breadcrumbs->push($event->name, route('rallycross.championship.event.show', [$event->championship, $event]));
    }
);

Breadcrumbs::register('rallycross.championship.event.edit',
    function($breadcrumbs, $champSlug, $eventSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('rallycross.championship.event.show', '', '', $event);
        $breadcrumbs->push('Update', route('rallycross.championship.event.edit', [$event->championship, $event]));
    }
);


Breadcrumbs::register('rallycross.championship.event.entrant.index',
    function($breadcrumbs, $champSlug, $eventSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('rallycross.championship.event.show', '', '', $event);
        $breadcrumbs->push('Entrants', route('rallycross.championship.event.entrant.index', [$event->championship, $event]));
    }
);


Breadcrumbs::register('rallycross.championship.event.entrant.create',
    function($breadcrumbs, $champSlug, $eventSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('rallycross.championship.event.entrant.index', '', '', $event);
        $breadcrumbs->push('Create', route('rallycross.championship.event.entrant.create', [$event->championship, $event]));
    }
);