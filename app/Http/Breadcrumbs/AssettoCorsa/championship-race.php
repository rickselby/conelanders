<?php

Breadcrumbs::register('assetto-corsa.championship.race.show',
    function($breadcrumbs, $champSlug, $raceSlug, $race = null) {
        if (!$race) {
            $race = \Request::get('race');
        }
        $breadcrumbs->parent('assetto-corsa.championship.show', $race->championship);
        $breadcrumbs->push($race->name, route('assetto-corsa.championship.race.show', [$race->championship, $race]));
    }
);

Breadcrumbs::register('assetto-corsa.championship.race.edit',
    function($breadcrumbs, $champSlug, $raceSlug, $race = null) {
        if (!$race) {
            $race = \Request::get('race');
        }
        $breadcrumbs->parent('assetto-corsa.championship.race.show', '', '', $race);
        $breadcrumbs->push('Update', route('assetto-corsa.championship.race.edit', [$race->championship, $race]));
    }
);

Breadcrumbs::register('assetto-corsa.championship.race.entrants',
    function($breadcrumbs, $champSlug, $raceSlug, $race = null) {
        if (!$race) {
            $race = \Request::get('race');
        }
        $breadcrumbs->parent('assetto-corsa.championship.race.show', '', '', $race);
        $breadcrumbs->push('Update', route('assetto-corsa.championship.race.entrants', [$race->championship, $race]));
    }
);
