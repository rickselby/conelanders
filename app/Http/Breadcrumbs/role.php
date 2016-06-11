<?php

Breadcrumbs::register('role.index', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Role Management', route('role.index'));
});

Breadcrumbs::register('role.create', function($breadcrumbs) {
    $breadcrumbs->parent('role.index');
    $breadcrumbs->push('Create', route('role.create'));
});

Breadcrumbs::register('role.show', function($breadcrumbs, \Spatie\Permission\Models\Role $role) {
    $breadcrumbs->parent('role.index');
    $breadcrumbs->push($role->name, route('role.show', $role));
});

Breadcrumbs::register('role.edit', function($breadcrumbs, \Spatie\Permission\Models\Role $role) {
    $breadcrumbs->parent('role.show', $role);
    $breadcrumbs->push('Update', route('role.edit', $role));
});
