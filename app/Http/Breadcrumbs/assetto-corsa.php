<?php

Breadcrumbs::register('assetto-corsa.index', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Assetto Corsa', route('assetto-corsa.index'));
});

include('AssettoCorsa/championship.php');
include('AssettoCorsa/championship-event.php');
include('AssettoCorsa/championship-event-session.php');
include('AssettoCorsa/standings.php');
