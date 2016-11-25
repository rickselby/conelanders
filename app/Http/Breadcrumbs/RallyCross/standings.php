<?php

Breadcrumbs::register('rallycross.standings.drivers',
    function($breadcrumbs, \App\Models\RallyCross\RxChampionship $championship) {
        $breadcrumbs->parent('rallycross.results.championship', $championship);
        $breadcrumbs->push('Driver Standings', route('rallycross.standings.drivers', $championship));
    }
);

Breadcrumbs::register('rallycross.standings.constructors',
    function($breadcrumbs, \App\Models\RallyCross\RxChampionship $championship) {
        $breadcrumbs->parent('rallycross.results.championship', $championship);
        $breadcrumbs->push('Constructor Standings', route('rallycross.standings.constructors', $championship));
    }
);
