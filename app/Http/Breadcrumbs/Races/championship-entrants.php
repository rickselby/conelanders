<?php

Breadcrumbs::register('races.championship.entrant.index', function($breadcrumbs, \App\Models\Races\RacesChampionship $championship) {
    $breadcrumbs->parent('races.category.championship.show', $championship->category, $championship);
    $breadcrumbs->push('Entrants', route('races.championship.entrant.index', $championship));
});

Breadcrumbs::register('races.championship.entrant.create', function($breadcrumbs, \App\Models\Races\RacesChampionship $championship) {
    $breadcrumbs->parent('races.championship.entrant.index', $championship);
    $breadcrumbs->push('Create', route('races.championship.entrant.create', $championship));
});

Breadcrumbs::register('races.championship.entrant.edit', function($breadcrumbs, \App\Models\Races\RacesChampionship $championship, \App\Models\Races\RacesChampionshipEntrant $entrant) {
    $breadcrumbs->parent('races.championship.entrant.index', $championship);
    $breadcrumbs->push('Update', route('races.championship.entrant.edit', [$championship, $entrant]));
});
