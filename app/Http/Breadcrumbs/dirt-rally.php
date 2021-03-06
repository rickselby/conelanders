<?php

Breadcrumbs::register('dirt-rally.index', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Dirt Rally', route('dirt-rally.index'));
});

Breadcrumbs::register('dirt-rally.championship', function ($breadcrumbs, \App\Models\DirtRally\DirtChampionship $championship) {
    $breadcrumbs->parent('dirt-rally.index');
    $breadcrumbs->push($championship->name, route('dirt-rally.championship', $championship));
});

Breadcrumbs::register('dirt-rally.event', function($breadcrumbs, $champSlug, $seasonSlug, $eventSlug) {
    $event = \Request::get('event');
    $breadcrumbs->parent('dirt-rally.index');
    $breadcrumbs->push($event->fullName, route('dirt-rally.event', [$event->season->championship, $event->season, $event]));
});

include('DirtRally/championship.php');
include('DirtRally/championship-season.php');
include('DirtRally/championship-season-event.php');
include('DirtRally/championship-season-event-stage.php');
include('DirtRally/nationstandings.php');
include('DirtRally/stage-info.php');
include('DirtRally/standings.php');
include('DirtRally/times.php');
