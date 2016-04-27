<?php

Breadcrumbs::register('dirt-rally.championship.index', function($breadcrumbs) {
    $breadcrumbs->parent('dirt-rally.index');
    $breadcrumbs->push('Results', route('dirt-rally.championship.index'));
});

Breadcrumbs::register('dirt-rally.championship.create', function($breadcrumbs) {
    $breadcrumbs->parent('dirt-rally.championship.index');
    $breadcrumbs->push('Create', route('dirt-rally.championship.create'));
});

Breadcrumbs::register('dirt-rally.championship.show', function($breadcrumbs, \App\Models\DirtRally\DirtChampionship $championship) {
    $breadcrumbs->parent('dirt-rally.championship.index');
    $breadcrumbs->push($championship->name, route('dirt-rally.championship.show', $championship));
});

Breadcrumbs::register('dirt-rally.championship.edit', function($breadcrumbs, \App\Models\DirtRally\DirtChampionship $championship) {
    $breadcrumbs->parent('dirt-rally.championship.show', $championship);
    $breadcrumbs->push('Update', route('dirt-rally.championship.edit', $championship));
});

Breadcrumbs::register('dirt-rally.championship.season.create', function($breadcrumbs, \App\Models\DirtRally\DirtChampionship $championship) {
    $breadcrumbs->parent('dirt-rally.championship.show', $championship);
    $breadcrumbs->push('Create Season', route('dirt-rally.championship.season.create', $championship));
});
