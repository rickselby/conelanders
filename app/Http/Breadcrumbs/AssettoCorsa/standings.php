<?php

Breadcrumbs::register('assetto-corsa.standings.index', function($breadcrumbs) {
    $breadcrumbs->parent('assetto-corsa.index');
    $breadcrumbs->push('Driver Standings', route('assetto-corsa.standings.index'));
});

Breadcrumbs::register('assetto-corsa.standings.system', function($breadcrumbs, \App\Models\AssettoCorsa\AcPointsSystem $system) {
    $breadcrumbs->parent('assetto-corsa.standings.index');
    $breadcrumbs->push($system->name, route('assetto-corsa.standings.system', $system));
});

Breadcrumbs::register('assetto-corsa.standings.championship',
    function($breadcrumbs, \App\Models\AssettoCorsa\AcPointsSystem $system, \App\Models\AssettoCorsa\AcChampionship $championship) {
        $breadcrumbs->parent('assetto-corsa.standings.system', $system);
        $breadcrumbs->push($championship->name, route('assetto-corsa.standings.championship', [$system, $championship]));
    }
);

Breadcrumbs::register('assetto-corsa.standings.race',
    function($breadcrumbs, \App\Models\AssettoCorsa\AcPointsSystem $system, $champSlug, $raceSlug, $race = null) {
        if (!$race) {
            $race = \Request::get('race');
        }
        $breadcrumbs->parent('assetto-corsa.standings.championship', $system, $race->championship);
        $breadcrumbs->push($race->name, route('assetto-corsa.standings.race', [$system, $race->championship, $race]));
    }
);