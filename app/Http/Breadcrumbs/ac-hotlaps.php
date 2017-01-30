<?php

Breadcrumbs::register('assetto-corsa.hotlaps.index', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push("Assetto Corsa Hotlaps", route('assetto-corsa.hotlaps.index'));
});


Breadcrumbs::register('assetto-corsa.hotlaps.session', function($breadcrumbs, \App\Models\AcHotlap\AcHotlapSession $session) {
    $breadcrumbs->parent('assetto-corsa.hotlaps.index');
    $breadcrumbs->push($session->name.': '.$session->cars->pluck('short_name')->implode(', '), route('assetto-corsa.hotlaps.session', $session));
});


Breadcrumbs::register('assetto-corsa.hotlaps.session.index', function($breadcrumbs) {
    # temporary, till there's a rallycross index
    $breadcrumbs->parent('assetto-corsa.hotlaps.index');
    $breadcrumbs->push('Session Management', route('assetto-corsa.hotlaps.session.index'));
});

Breadcrumbs::register('assetto-corsa.hotlaps.session.create', function($breadcrumbs) {
    $breadcrumbs->parent('assetto-corsa.hotlaps.session.index');
    $breadcrumbs->push('Create', route('assetto-corsa.hotlaps.session.create'));
});

Breadcrumbs::register('assetto-corsa.hotlaps.session.show', function($breadcrumbs, \App\Models\AcHotlap\AcHotlapSession $session) {
    $breadcrumbs->parent('assetto-corsa.hotlaps.session.index');
    $breadcrumbs->push($session->name, route('assetto-corsa.hotlaps.session.show', $session));
});

Breadcrumbs::register('assetto-corsa.hotlaps.session.edit', function($breadcrumbs, \App\Models\AcHotlap\AcHotlapSession $session) {
    $breadcrumbs->parent('assetto-corsa.hotlaps.session.show', $session);
    $breadcrumbs->push('Update', route('assetto-corsa.hotlaps.session.edit', $session));
});
