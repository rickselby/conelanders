<?php

Breadcrumbs::register('rallycross.car.index', function($breadcrumbs) {
    # temporary, till there's a rallycross index
    $breadcrumbs->parent('home');
    $breadcrumbs->push('RX Cars', route('rallycross.car.index'));
});

Breadcrumbs::register('rallycross.car.create', function($breadcrumbs) {
    $breadcrumbs->parent('rallycross.car.index');
    $breadcrumbs->push('Create', route('rallycross.car.create'));
});

Breadcrumbs::register('rallycross.car.edit', function($breadcrumbs, \App\Models\RallyCross\RxCar $car) {
    $breadcrumbs->parent('rallycross.car.index', $car);
    $breadcrumbs->push('Update', route('rallycross.car.edit', $car));
});
