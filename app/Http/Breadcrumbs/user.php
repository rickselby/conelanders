<?php

Breadcrumbs::register('user.show', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('User Management', route('user.show'));
});

Breadcrumbs::register('user.championships', function($breadcrumbs){
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Your Championships', route('user.championships'));
});
