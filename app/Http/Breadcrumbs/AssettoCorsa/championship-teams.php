<?php

Breadcrumbs::register('assetto-corsa.championship.team.index', function($breadcrumbs, \App\Models\AssettoCorsa\AcChampionship $championship) {
    $breadcrumbs->parent('assetto-corsa.championship.show', $championship);
    $breadcrumbs->push('Teams', route('assetto-corsa.championship.team.index', $championship));
});

Breadcrumbs::register('assetto-corsa.championship.team.create', function($breadcrumbs, \App\Models\AssettoCorsa\AcChampionship $championship) {
    $breadcrumbs->parent('assetto-corsa.championship.team.index', $championship);
    $breadcrumbs->push('Create', route('assetto-corsa.championship.team.create', $championship));
});

Breadcrumbs::register('assetto-corsa.championship.team.edit', function($breadcrumbs, \App\Models\AssettoCorsa\AcChampionship $championship, \App\Models\AssettoCorsa\AcTeam $team) {
    $breadcrumbs->parent('assetto-corsa.championship.team.index', $championship);
    $breadcrumbs->push('Update', route('assetto-corsa.championship.team.edit', [$championship, $team]));
});
