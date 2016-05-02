<?php

Breadcrumbs::register('dirt-rally.points-system.index', function($breadcrumbs) {
    $breadcrumbs->parent('dirt-rally.index');
    $breadcrumbs->push('Points Systems', route('dirt-rally.points-system.index'));
});

Breadcrumbs::register('dirt-rally.points-system.create', function($breadcrumbs) {
    $breadcrumbs->parent('dirt-rally.points-system.index');
    $breadcrumbs->push('New', route('dirt-rally.points-system.create'));
});

Breadcrumbs::register('dirt-rally.points-system.show', function($breadcrumbs, \App\Models\DirtRally\DirtPointsSystem $system) {
    $breadcrumbs->parent('dirt-rally.points-system.index');
    $breadcrumbs->push($system->name, route('dirt-rally.points-system.show', $system));
});

Breadcrumbs::register('dirt-rally.points-system.edit', function($breadcrumbs, \App\Models\DirtRally\DirtPointsSystem $system) {
    $breadcrumbs->parent('dirt-rally.points-system.show', $system);
    $breadcrumbs->push('Update', route('dirt-rally.points-system.edit', $system));
});
