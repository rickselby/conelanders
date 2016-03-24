<?php

Route::group(['middleware' => ['web']], function () {

    Route::get('/login', 'Auth\AuthController@index')->name('login.index');
    Route::get('/login/google', 'Auth\AuthController@loginGoogle')->name('login.google');
    Route::get('/login/google/done', 'Auth\AuthController@loginGoogleDone');
    Route::get('/logout', 'Auth\AuthController@logout')->name('logout');

    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::resource('season', 'SeasonController');

    Route::resource('season.event', 'SeasonEventController', [['except' => ['index']]]);

    Route::resource('season.event.stage', 'SeasonEventStageController', [['except' => ['index']]]);

    Route::post('points-system/{system}/points', 'PointsSystemController@points')->name('points-system.points');
    Route::resource('points-system', 'PointsSystemController');

    Route::get('/standings/{system}', 'StandingsController@show')->name('standings.show');
    Route::get('/standings/{system}/season/{season}', 'StandingsController@season')->name('standings.season');
    Route::get('/standings/{system}/season/{season}/event/{event}', 'StandingsController@event')->name('standings.event');
    Route::get('/standings/{system}/season/{season}/event/{event}/stage/{stage}', 'StandingsController@stage')->name('standings.stage');

    Route::get('/times', 'TimesController@index')->name('times.index');
    Route::get('/times/season/{season}', 'TimesController@season')->name('times.season');
    Route::get('/times/season/{season}/event/{event}', 'TimesController@event')->name('times.event');
    Route::get('/times/season/{season}/event/{event}/stage/{stage}', 'TimesController@stage')->name('times.stage');

    Route::group(['middleware' => ['admin']], function() {
        Route::get('event-id-help', function () {
            return view('event-id-help');
        })->name('event-id-help');
    });
});
