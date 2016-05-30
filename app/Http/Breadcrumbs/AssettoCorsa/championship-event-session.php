<?php

Breadcrumbs::register('assetto-corsa.championship.event.session.show',
    function($breadcrumbs, $champSlug, $eventSlug, $sessionSlug, $session = null) {
        if (!$session) {
            $session = \Request::get('session');
        }
        $breadcrumbs->parent('assetto-corsa.championship.event.show', '', '', $session->event);
        $breadcrumbs->push($session->name, route('assetto-corsa.championship.event.session.show', [$session->event->championship, $session->event, $session]));
    }
);

Breadcrumbs::register('assetto-corsa.championship.event.session.edit',
    function($breadcrumbs, $champSlug, $eventSlug, $sessionSlug, $session = null) {
        if (!$session) {
            $session = \Request::get('session');
        }
        $breadcrumbs->parent('assetto-corsa.championship.event.session.show', '', '', '', $session);
        $breadcrumbs->push('Update', route('assetto-corsa.championship.event.session.edit', [$session->event->championship, $session->event, $session]));
    }
);

Breadcrumbs::register('assetto-corsa.championship.event.session.entrants.create',
    function($breadcrumbs, $champSlug, $eventSlug, $sessionSlug, $session = null) {
        if (!$session) {
            $session = \Request::get('session');
        }
        $breadcrumbs->parent('assetto-corsa.championship.event.session.show', '', '', '', $session);
        $breadcrumbs->push('Entrant Details', route('assetto-corsa.championship.event.session.entrants.create', [$session->event->championship, $session->event, $session]));
    }
);
