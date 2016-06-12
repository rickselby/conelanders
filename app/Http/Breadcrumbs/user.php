<?php

Breadcrumbs::register('user.show', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('User Management', route('user.show'));
});