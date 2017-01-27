<?php

Breadcrumbs::register('races.index', function($breadcrumbs, \App\Models\Races\RacesCategory $category) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push($category->name, route('races.index', $category));
});

include('Races/cars.php');
include('Races/category.php');
include('Races/championship.php');
include('Races/championship-entrants.php');
include('Races/championship-event.php');
include('Races/championship-event-session.php');
include('Races/championship-teams.php');
include('Races/results.php');
include('Races/standings.php');
