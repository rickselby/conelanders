<?php

Breadcrumbs::register('assetto-corsa.championship.event.show',
    function($breadcrumbs, $champSlug, $eventSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('assetto-corsa.championship.show', $event->championship);
        $breadcrumbs->push($event->name, route('assetto-corsa.championship.event.show', [$event->championship, $event]));
    }
);

Breadcrumbs::register('assetto-corsa.championship.event.edit',
    function($breadcrumbs, $champSlug, $eventSlug, $event = null) {
        if (!$event) {
            $event = \Request::get('event');
        }
        $breadcrumbs->parent('assetto-corsa.championship.event.show', '', '', $event);
        $breadcrumbs->push('Update', route('assetto-corsa.championship.event.edit', [$event->championship, $event]));
    }
);
