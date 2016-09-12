<?php

Breadcrumbs::register('assetto-corsa.championship.entrant.index', function($breadcrumbs, \App\Models\AssettoCorsa\AcChampionship $championship) {
    $breadcrumbs->parent('assetto-corsa.championship.show', $championship);
    $breadcrumbs->push('Entrants', route('assetto-corsa.championship.entrant.index', $championship));
});

Breadcrumbs::register('assetto-corsa.championship.entrant.create', function($breadcrumbs, \App\Models\AssettoCorsa\AcChampionship $championship) {
    $breadcrumbs->parent('assetto-corsa.championship.entrant.index', $championship);
    $breadcrumbs->push('Create', route('assetto-corsa.championship.entrant.create', $championship));
});

Breadcrumbs::register('assetto-corsa.championship.entrant.edit', function($breadcrumbs, \App\Models\AssettoCorsa\AcChampionship $championship, \App\Models\AssettoCorsa\AcChampionshipEntrant $entrant) {
    $breadcrumbs->parent('assetto-corsa.championship.entrant.index', $championship);
    $breadcrumbs->push('Update', route('assetto-corsa.championship.entrant.edit', [$championship, $entrant]));
});
