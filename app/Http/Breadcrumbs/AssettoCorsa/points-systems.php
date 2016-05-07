<?php

Breadcrumbs::register('assetto-corsa.points-system.index', function($breadcrumbs) {
    $breadcrumbs->parent('assetto-corsa.index');
    $breadcrumbs->push('Points Systems', route('assetto-corsa.points-system.index'));
});

Breadcrumbs::register('assetto-corsa.points-system.create', function($breadcrumbs) {
    $breadcrumbs->parent('assetto-corsa.points-system.index');
    $breadcrumbs->push('New', route('assetto-corsa.points-system.create'));
});

Breadcrumbs::register('assetto-corsa.points-system.show', function($breadcrumbs, \App\Models\AssettoCorsa\AcPointsSystem $system) {
    $breadcrumbs->parent('assetto-corsa.points-system.index');
    $breadcrumbs->push($system->name, route('assetto-corsa.points-system.show', $system));
});

Breadcrumbs::register('assetto-corsa.points-system.edit', function($breadcrumbs, \App\Models\AssettoCorsa\AcPointsSystem $system) {
    $breadcrumbs->parent('assetto-corsa.points-system.show', $system);
    $breadcrumbs->push('Update', route('assetto-corsa.points-system.edit', $system));
});
