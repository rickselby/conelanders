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

Breadcrumbs::register('assetto-corsa.championship.race.create', function($breadcrumbs, \App\Models\AssettoCorsa\AcChampionship $championship) {
    $breadcrumbs->parent('assetto-corsa.championship.show', $championship);
    $breadcrumbs->push('Create Race', route('assetto-corsa.championship.race.create', $championship));
});

Breadcrumbs::register('assetto-corsa.championship.entrants.index', function($breadcrumbs, \App\Models\AssettoCorsa\AcChampionship $championship) {
    $breadcrumbs->parent('assetto-corsa.championship.show', $championship);
    $breadcrumbs->push('Update Entrants', route('assetto-corsa.championship.entrants.index', $championship));
});