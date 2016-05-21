<?php

Breadcrumbs::register('dirt-rally.index', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Dirt Rally', route('dirt-rally.index'));
});

include('DirtRally/championship.php');
include('DirtRally/championship-season.php');
include('DirtRally/championship-season-event.php');
include('DirtRally/championship-season-event-stage.php');
include('DirtRally/nationstandings.php');
include('DirtRally/standings.php');
include('DirtRally/times.php');
