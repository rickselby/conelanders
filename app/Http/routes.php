<?php

Route::group(['middleware' => ['web']], function () {

    Route::get('/', function () {
        return Redirect::route('season.index');
    });

    Route::resource('season', 'SeasonController', ['parameters' => [
        'season' => 'season_id',
    ]]);

    Route::resource('season.event', 'SeasonEventController', ['parameters' => [
        'season' => 'season_id',
        'event' => 'event_id',
    ]]);

    Route::resource('season.event.stage', 'SeasonEventStageController', ['parameters' => [
        'season' => 'season_id',
        'event' => 'event_id',
        'stage' => 'stage_id',
    ]]);
    //
});
