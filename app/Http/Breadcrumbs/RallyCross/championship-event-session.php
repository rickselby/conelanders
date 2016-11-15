<?php

Breadcrumbs::register('rallycross.championship.event.session.show',
    function($breadcrumbs, $champSlug, $eventSlug, $sessionSlug, $session = null) {
        if (!$session) {
            $session = \Request::get('session');
        }
        $breadcrumbs->parent('rallycross.championship.event.show', '', '', $session->event);
        $breadcrumbs->push($session->name, route('rallycross.championship.event.session.show', [$session->event->championship, $session->event, $session]));
    }
);

Breadcrumbs::register('rallycross.championship.event.session.edit',
    function($breadcrumbs, $champSlug, $eventSlug, $sessionSlug, $session = null) {
        if (!$session) {
            $session = \Request::get('session');
        }
        $breadcrumbs->parent('rallycross.championship.event.session.show', '', '', '', $session);
        $breadcrumbs->push('Update', route('rallycross.championship.event.session.edit', [$session->event->championship, $session->event, $session]));
    }
);
