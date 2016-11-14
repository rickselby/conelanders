<?php

Breadcrumbs::register('races.category.championship.show', function($breadcrumbs, \App\Models\Races\RacesCategory $category, \App\Models\Races\RacesChampionship $championship) {
    $breadcrumbs->parent('races.category.show', $category);
    $breadcrumbs->push($championship->name, route('races.category.championship.show', [$category, $championship]));
});

Breadcrumbs::register('races.category.championship.edit', function($breadcrumbs, \App\Models\Races\RacesCategory $category, \App\Models\Races\RacesChampionship $championship) {
    $breadcrumbs->parent('races.category.championship.show', $category, $championship);
    $breadcrumbs->push('Update', route('races.category.championship.edit', [$category, $championship]));
});

Breadcrumbs::register('races.championship.event.create', function($breadcrumbs, \App\Models\Races\RacesCategory $category, \App\Models\Races\RacesChampionship $championship) {
    $breadcrumbs->parent('races.category.championship.show', [$category, $championship]);
    $breadcrumbs->push('Create Race', route('races.championship.event.create', $championship));
});
