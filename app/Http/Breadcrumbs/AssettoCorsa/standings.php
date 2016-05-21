<?php

Breadcrumbs::register('assetto-corsa.standings.index', function($breadcrumbs) {
    $breadcrumbs->parent('assetto-corsa.index');
    $breadcrumbs->push('Driver Standings', route('assetto-corsa.standings.index'));
});

Breadcrumbs::register('assetto-corsa.standings.championship',
    function($breadcrumbs, \App\Models\AssettoCorsa\AcChampionship $championship) {
        $breadcrumbs->parent('assetto-corsa.standings.index');
        $breadcrumbs->push($championship->name, route('assetto-corsa.standings.championship', $championship));
    }
);

Breadcrumbs::register('assetto-corsa.standings.race',
    function($breadcrumbs, $champSlug, $raceSlug, $race = null) {
        if (!$race) {
            $race = \Request::get('race');
        }
        $breadcrumbs->parent('assetto-corsa.standings.championship', $race->championship);
        $breadcrumbs->push($race->name, route('assetto-corsa.standings.race', [$race->championship, $race]));
    }
);