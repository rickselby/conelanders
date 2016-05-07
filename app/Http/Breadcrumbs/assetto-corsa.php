<?php

Breadcrumbs::register('assetto-corsa.index', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Assetto Corsa', route('assetto-corsa.index'));
});

include('AssettoCorsa/championship.php');
include('AssettoCorsa/championship-race.php');
include('AssettoCorsa/points-systems.php');
include('AssettoCorsa/standings.php');
