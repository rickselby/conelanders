<?php

Breadcrumbs::register('assetto-corsa.championship.index', function($breadcrumbs) {
    $breadcrumbs->parent('assetto-corsa.index');
    $breadcrumbs->push('Results', route('assetto-corsa.championship.index'));
});

Breadcrumbs::register('assetto-corsa.championship.create', function($breadcrumbs) {
    $breadcrumbs->parent('assetto-corsa.championship.index');
    $breadcrumbs->push('Create', route('assetto-corsa.championship.create'));
});

Breadcrumbs::register('assetto-corsa.championship.show', function($breadcrumbs, \App\Models\AssettoCorsa\AcChampionship $championship) {
    $breadcrumbs->parent('assetto-corsa.championship.index');
    $breadcrumbs->push($championship->name, route('assetto-corsa.championship.show', $championship));
});

Breadcrumbs::register('assetto-corsa.championship.edit', function($breadcrumbs, \App\Models\AssettoCorsa\AcChampionship $championship) {
    $breadcrumbs->parent('assetto-corsa.championship.show', $championship);
    $breadcrumbs->push('Update', route('assetto-corsa.championship.edit', $championship));
});

Breadcrumbs::register('assetto-corsa.championship.season.create', function($breadcrumbs, \App\Models\AssettoCorsa\AcChampionship $championship) {
    $breadcrumbs->parent('assetto-corsa.championship.show', $championship);
    $breadcrumbs->push('Create Season', route('assetto-corsa.championship.season.create', $championship));
});
