<?php

Breadcrumbs::register('races.championship.event.session.show',
    function($breadcrumbs, $champSlug, $eventSlug, $sessionSlug, $session = null) {
        if (!$session) {
            $session = \Request::get('session');
        }
        $breadcrumbs->parent('races.championship.event.show', '', '', $session->event);
        $breadcrumbs->push($session->name, route('races.championship.event.session.show', [$session->event->championship, $session->event, $session]));
    }
);

Breadcrumbs::register('races.championship.event.session.edit',
    function($breadcrumbs, $champSlug, $eventSlug, $sessionSlug, $session = null) {
        if (!$session) {
            $session = \Request::get('session');
        }
        $breadcrumbs->parent('races.championship.event.session.show', '', '', '', $session);
        $breadcrumbs->push('Update', route('races.championship.event.session.edit', [$session->event->championship, $session->event, $session]));
    }
);

Breadcrumbs::register('races.championship.event.session.entrants.create',
    function($breadcrumbs, $champSlug, $eventSlug, $sessionSlug, $session = null) {
        if (!$session) {
            $session = \Request::get('session');
        }
        $breadcrumbs->parent('races.championship.event.session.show', '', '', '', $session);
        $breadcrumbs->push('Entrant Details', route('races.championship.event.session.entrants.create', [$session->event->championship, $session->event, $session]));
    }
);
