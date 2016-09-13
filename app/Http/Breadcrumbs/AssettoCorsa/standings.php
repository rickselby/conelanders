<?php

Breadcrumbs::register('assetto-corsa.standings.drivers',
    function($breadcrumbs, \App\Models\AssettoCorsa\AcChampionship $championship) {
        $breadcrumbs->parent('assetto-corsa.results.championship', $championship);
        $breadcrumbs->push('Driver Standings', route('assetto-corsa.standings.drivers', $championship));
    }
);

Breadcrumbs::register('assetto-corsa.standings.constructors',
    function($breadcrumbs, \App\Models\AssettoCorsa\AcChampionship $championship) {
        $breadcrumbs->parent('assetto-corsa.results.championship', $championship);
        $breadcrumbs->push('Constructor Standings', route('assetto-corsa.standings.constructors', $championship));
    }
);

Breadcrumbs::register('assetto-corsa.standings.teams',
    function($breadcrumbs, \App\Models\AssettoCorsa\AcChampionship $championship) {
        $breadcrumbs->parent('assetto-corsa.results.championship', $championship);
        $breadcrumbs->push('Team Standings', route('assetto-corsa.standings.teams', $championship));
    }
);
