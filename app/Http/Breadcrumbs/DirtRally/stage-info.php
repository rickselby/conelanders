<?php

Breadcrumbs::register('dirt-rally.stage-info.index',
    function($breadcrumbs) {
        $breadcrumbs->parent('dirt-rally.index');
        $breadcrumbs->push('Stage Management', route('dirt-rally.stage-info.index'));
    }
);

Breadcrumbs::register('dirt-rally.stage-info.create',
    function($breadcrumbs) {
        $breadcrumbs->parent('dirt-rally.stage-info.index');
        $breadcrumbs->push('Create Stage', route('dirt-rally.stage-info.create'));
    }
);

Breadcrumbs::register('dirt-rally.stage-info.edit',
    function($breadcrumbs, \App\Models\DirtRally\DirtStageInfo $stage_info) {
        $breadcrumbs->parent('dirt-rally.stage-info.index');
        $breadcrumbs->push('Update Stage', route('dirt-rally.stage-info.edit', $stage_info));
    }
);
