<?php

Breadcrumbs::register('rallycross.championship.index', function($breadcrumbs) {
    # temporary, till there's a rallycross index
    $breadcrumbs->parent('home');
    $breadcrumbs->push('RX Championships', route('rallycross.championship.index'));
});

Breadcrumbs::register('rallycross.championship.create', function($breadcrumbs) {
    $breadcrumbs->parent('rallycross.championship.index');
    $breadcrumbs->push('Create', route('rallycross.championship.create'));
});

Breadcrumbs::register('rallycross.championship.show', function($breadcrumbs, \App\Models\RallyCross\RxChampionship $championship) {
    $breadcrumbs->parent('rallycross.championship.index');
    $breadcrumbs->push($championship->name, route('rallycross.championship.show', $championship));
});

Breadcrumbs::register('rallycross.championship.edit', function($breadcrumbs, \App\Models\RallyCross\RxChampionship $championship) {
    $breadcrumbs->parent('rallycross.championship.show', $championship);
    $breadcrumbs->push('Update', route('rallycross.championship.edit', $championship));
});

Breadcrumbs::register('rallycross.championship.event.create', function($breadcrumbs, \App\Models\RallyCross\RxChampionship $championship) {
    $breadcrumbs->parent('rallycross.championship.show', $championship);
    $breadcrumbs->push('Update', route('rallycross.championship.event.create', $championship));
});
