<?php

Breadcrumbs::register('assetto-corsa', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Assetto Corsa', route('assetto-corsa'));
});