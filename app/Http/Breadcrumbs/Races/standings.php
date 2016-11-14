<?php

Breadcrumbs::register('races.standings.drivers',
    function($breadcrumbs, \App\Models\Races\RacesCategory $category, \App\Models\Races\RacesChampionship $championship) {
        $breadcrumbs->parent('races.results.championship', $category, $championship);
        $breadcrumbs->push('Driver Standings', route('races.standings.drivers', [$category, $championship]));
    }
);

Breadcrumbs::register('races.standings.constructors',
    function($breadcrumbs, \App\Models\Races\RacesCategory $category, \App\Models\Races\RacesChampionship $championship) {
        $breadcrumbs->parent('races.results.championship', $category, $championship);
        $breadcrumbs->push('Constructor Standings', route('races.standings.constructors', [$category, $championship]));
    }
);

Breadcrumbs::register('races.standings.teams',
    function($breadcrumbs, \App\Models\Races\RacesCategory $category, \App\Models\Races\RacesChampionship $championship) {
        $breadcrumbs->parent('races.results.championship', $category, $championship);
        $breadcrumbs->push('Team Standings', route('races.standings.teams', [$category, $championship]));
    }
);
