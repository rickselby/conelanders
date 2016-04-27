<?php

Breadcrumbs::register('nation.index', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Nations', route('nation.index'));
});

Breadcrumbs::register('nation.create', function($breadcrumbs) {
    $breadcrumbs->parent('nation.index');
    $breadcrumbs->push('Create', route('nation.create'));
});

Breadcrumbs::register('nation.edit', function($breadcrumbs, \App\Models\Nation $nation) {
    $breadcrumbs->parent('nation.index');
    $breadcrumbs->push($nation->name, route('nation.edit', $nation));
});
