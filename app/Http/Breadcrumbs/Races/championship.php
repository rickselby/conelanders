<?php

Breadcrumbs::register('races.championship.index', function($breadcrumbs) {
    $breadcrumbs->parent('races.index');
    $breadcrumbs->push('Championship Management', route('races.championship.index'));
});

Breadcrumbs::register('races.championship.create', function($breadcrumbs) {
    $breadcrumbs->parent('races.championship.index');
    $breadcrumbs->push('Create', route('races.championship.create'));
});

Breadcrumbs::register('races.championship.show', function($breadcrumbs, \App\Models\Races\RacesChampionship $championship) {
    $breadcrumbs->parent('races.championship.index');
    $breadcrumbs->push($championship->name, route('races.championship.show', $championship));
});

Breadcrumbs::register('races.championship.edit', function($breadcrumbs, \App\Models\Races\RacesChampionship $championship) {
    $breadcrumbs->parent('races.championship.show', $championship);
    $breadcrumbs->push('Update', route('races.championship.edit', $championship));
});

Breadcrumbs::register('races.championship.event.create', function($breadcrumbs, \App\Models\Races\RacesChampionship $championship) {
    $breadcrumbs->parent('races.championship.show', $championship);
    $breadcrumbs->push('Create Race', route('races.championship.event.create', $championship));
});
