<?php

Breadcrumbs::register('home', function($breadcrumbs) {
    $breadcrumbs->push('Home', route('home'));
});

include('Breadcrumbs/assetto-corsa.php');
include('Breadcrumbs/dirt-rally.php');
include('Breadcrumbs/drivers.php');
include('Breadcrumbs/nations.php');
include('Breadcrumbs/points-sequences.php');
include('Breadcrumbs/role.php');
