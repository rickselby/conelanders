<?php

Breadcrumbs::register('races.championship.event.show',
    function($breadcrumbs, $champSlug, $eventSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('races.championship.show', $event->championship);
        $breadcrumbs->push($event->name, route('races.championship.event.show', [$event->championship, $event]));
    }
);

Breadcrumbs::register('races.championship.event.edit',
    function($breadcrumbs, $champSlug, $eventSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('races.championship.event.show', '', '', $event);
        $breadcrumbs->push('Update', route('races.championship.event.edit', [$event->championship, $event]));
    }
);
