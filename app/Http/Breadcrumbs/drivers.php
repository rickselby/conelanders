<?php

Breadcrumbs::register('driver.index', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Drivers', route('driver.index'));
});

Breadcrumbs::register('driver.show', function($breadcrumbs, \App\Models\Driver $driver) {
    $breadcrumbs->parent('driver.index');
    $breadcrumbs->push($driver->name, route('driver.show', $driver));
});