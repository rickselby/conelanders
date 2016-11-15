<?php

Breadcrumbs::register('races.championship.team.index', function($breadcrumbs, \App\Models\Races\RacesChampionship $championship) {
    $breadcrumbs->parent('races.category.championship.show', $championship->category, $championship);
    $breadcrumbs->push('Teams', route('races.championship.team.index', $championship));
});

Breadcrumbs::register('races.championship.team.create', function($breadcrumbs, \App\Models\Races\RacesChampionship $championship) {
    $breadcrumbs->parent('races.championship.team.index', $championship);
    $breadcrumbs->push('Create', route('races.championship.team.create', $championship));
});

Breadcrumbs::register('races.championship.team.edit', function($breadcrumbs, \App\Models\Races\RacesChampionship $championship, \App\Models\Races\RacesTeam $team) {
    $breadcrumbs->parent('races.championship.team.index', $championship);
    $breadcrumbs->push('Update', route('races.championship.team.edit', [$championship, $team]));
});
