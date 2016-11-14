<?php

Breadcrumbs::register('races.category.index', function($breadcrumbs) {
    $breadcrumbs->parent('races.index');
    $breadcrumbs->push('Categories', route('races.category.index'));
});

Breadcrumbs::register('races.category.create', function($breadcrumbs) {
    $breadcrumbs->parent('races.category.index');
    $breadcrumbs->push('Create', route('races.category.create'));
});

Breadcrumbs::register('races.category.show', function($breadcrumbs, \App\Models\Races\RacesCategory $category) {
    $breadcrumbs->parent('races.category.index');
    $breadcrumbs->push($category->name, route('races.category.show', $category));
});

Breadcrumbs::register('races.category.edit', function($breadcrumbs, \App\Models\Races\RacesCategory $category) {
    $breadcrumbs->parent('races.category.show', $category);
    $breadcrumbs->push('Update', route('races.category.edit', $category));
});

Breadcrumbs::register('races.category.championship.create', function($breadcrumbs, \App\Models\Races\RacesCategory $category) {
    $breadcrumbs->parent('races.category.show', $category);
    $breadcrumbs->push('Create', route('races.category.championship.create', $category));
});
