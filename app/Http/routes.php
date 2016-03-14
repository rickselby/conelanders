<?php

Route::group(['middleware' => ['web']], function () {

    Route::get('/login', 'Auth\AuthController@index')->name('login.index');
    Route::get('/login/google', 'Auth\AuthController@loginGoogle')->name('login.google');
    Route::get('/login/google/done', 'Auth\AuthController@loginGoogleDone');
    Route::get('/logout', 'Auth\AuthController@logout')->name('logout');

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
});
