<?php

Breadcrumbs::register('races.car.index', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('AC Cars', route('races.car.index'));
});

Breadcrumbs::register('races.car.create', function($breadcrumbs) {
    $breadcrumbs->parent('races.car.index');
    $breadcrumbs->push('Create', route('races.car.create'));
});

Breadcrumbs::register('races.car.edit', function($breadcrumbs, \App\Models\Races\RacesCar $car) {
    $breadcrumbs->parent('races.car.index', $car);
    $breadcrumbs->push('Update', route('races.car.edit', $car));
});
