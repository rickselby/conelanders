<?php

Breadcrumbs::register('home', function($breadcrumbs) {
    $breadcrumbs->push('Home', route('home'));
});

Breadcrumbs::register('log-viewer', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Logs', route('log-viewer'));
});

include('Breadcrumbs/races.php');
include('Breadcrumbs/dirt-rally.php');
include('Breadcrumbs/drivers.php');
include('Breadcrumbs/nations.php');
include('Breadcrumbs/points-sequences.php');
include('Breadcrumbs/rallycross.php');
include('Breadcrumbs/role.php');
include('Breadcrumbs/user.php');
