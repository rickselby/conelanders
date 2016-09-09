<?php

Breadcrumbs::register('assetto-corsa.standings.driver',
    function($breadcrumbs, \App\Models\AssettoCorsa\AcChampionship $championship) {
        $breadcrumbs->parent('assetto-corsa.results.championship', $championship);
        $breadcrumbs->push('Driver Standings', route('assetto-corsa.standings.driver', $championship));
    }
);
