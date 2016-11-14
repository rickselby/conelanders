<?php

Breadcrumbs::register('races.standings.drivers',
    function($breadcrumbs, \App\Models\Races\RacesChampionship $championship) {
        $breadcrumbs->parent('races.results.championship', $championship);
        $breadcrumbs->push('Driver Standings', route('races.standings.drivers', $championship));
    }
);

Breadcrumbs::register('races.standings.constructors',
    function($breadcrumbs, \App\Models\Races\RacesChampionship $championship) {
        $breadcrumbs->parent('races.results.championship', $championship);
        $breadcrumbs->push('Constructor Standings', route('races.standings.constructors', $championship));
    }
);

Breadcrumbs::register('races.standings.teams',
    function($breadcrumbs, \App\Models\Races\RacesChampionship $championship) {
        $breadcrumbs->parent('races.results.championship', $championship);
        $breadcrumbs->push('Team Standings', route('races.standings.teams', $championship));
    }
);
