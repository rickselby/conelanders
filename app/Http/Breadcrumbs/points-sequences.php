<?php

Breadcrumbs::register('points-sequence.index', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Points Sequences', route('points-sequence.index'));
});

Breadcrumbs::register('points-sequence.create', function($breadcrumbs) {
    $breadcrumbs->parent('points-sequence.index');
    $breadcrumbs->push('New', route('points-sequence.create'));
});

Breadcrumbs::register('points-sequence.show', function($breadcrumbs, \App\Models\PointsSequence $sequence) {
    $breadcrumbs->parent('points-sequence.index');
    $breadcrumbs->push($sequence->name, route('points-sequence.show', $sequence));
});

Breadcrumbs::register('points-sequence.edit', function($breadcrumbs, \App\Models\PointsSequence $sequence) {
    $breadcrumbs->parent('points-sequence.show', $sequence);
    $breadcrumbs->push('Update', route('points-sequence.edit', $sequence));
});
