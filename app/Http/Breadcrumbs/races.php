<?php

Breadcrumbs::register('races.index', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Races', route('races.index'));
});

include('Races/championship.php');
include('Races/championship-entrants.php');
include('Races/championship-event.php');
include('Races/championship-event-session.php');
include('Races/championship-teams.php');
include('Races/results.php');
include('Races/standings.php');
