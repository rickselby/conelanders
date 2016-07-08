<?php

Breadcrumbs::register('dirt-rally.championship.season.event.stage.show',
    function($breadcrumbs, $champSlug, $seasonSlug, $eventSlug, $stageSlug, $stage = null) {
        if (!$stage) {
            $stage = \Request::get('stage');
        }
        $breadcrumbs->parent('dirt-rally.championship.season.event.show', '', '', '', $stage->event);
        $breadcrumbs->push($stage->ss.': '.$stage->name, route('dirt-rally.championship.season.event.stage.show', [$stage->event->season->championship, $stage->event->season, $stage->event, $stage]));
    }
);

Breadcrumbs::register('dirt-rally.championship.season.event.stage.edit',
    function($breadcrumbs, $champSlug, $seasonSlug, $eventSlug, $stageSlug, $stage = null) {
        if (!$stage) {
            $stage = \Request::get('stage');
        }
        $breadcrumbs->parent('dirt-rally.championship.season.event.stage.show', '', '', '', '', $stage);
        $breadcrumbs->push('Update', route('dirt-rally.championship.season.event.edit', [$stage->event->season->championship, $stage->event->season, $stage->event, $stage]));
    }
);
