<?php

Breadcrumbs::register('rallycross.index', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('RallyCross', route('rallycross.index'));
});

include('RallyCross/cars.php');
include('RallyCross/championship.php');
include('RallyCross/championship-event.php');
include('RallyCross/championship-event-session.php');
include('RallyCross/results.php');
include('RallyCross/standings.php');
