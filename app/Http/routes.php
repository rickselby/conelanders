<?php

Route::group(['middleware' => ['web']], function () {

    Route::get('/login', 'Auth\AuthController@index')->name('login.index');
    Route::get('/login/google', 'Auth\AuthController@loginGoogle')->name('login.google');
    Route::get('/login/google/done', 'Auth\AuthController@loginGoogleDone');
    Route::get('/logout', 'Auth\AuthController@logout')->name('logout');

    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::resource('championship', 'ChampionshipController');
    Route::resource('championship.season', 'ChampionshipSeasonController', [['except' => ['index']]]);
    Route::resource('championship.season.event', 'ChampionshipSeasonEventController', [['except' => ['index']]]);
    Route::resource('championship.season.event.stage', 'ChampionshipSeasonEventStageController', [['except' => ['index']]]);

    Route::post('points-system/{system}/points', 'PointsSystemController@points')->name('points-system.points');
    Route::resource('points-system', 'PointsSystemController');

    Route::get('/standings/', 'StandingsController@index')->name('standings.index');
    Route::get('/standings/{system}', 'StandingsController@system')->name('standings.system');
    Route::get('/standings/{system}/{championship}', 'StandingsController@championship')->name('standings.championship');
    Route::get('/standings/{system}/{championship}/overview', 'StandingsController@overview')->name('standings.overview');
    Route::get('/standings/{system}/{championship}/{season}', 'StandingsController@season')->name('standings.season');
    Route::get('/standings/{system}/{championship}/{season}/{event}', 'StandingsController@event')->name('standings.event');
    Route::get('/standings/{system}/{championship}/{season}/{event}/{stage}', 'StandingsController@stage')->name('standings.stage');

    Route::get('/times', 'TimesController@index')->name('times.index');
    Route::get('/times/{championship}', 'TimesController@championship')->name('times.championship');
    Route::get('/times/{championship}/{season}', 'TimesController@season')->name('times.season');
    Route::get('/times/{championship}/{season}/{event}', 'TimesController@event')->name('times.event');
    Route::get('/times/{championship}/{season}/{event}/{stage}', 'TimesController@stage')->name('times.stage');

    Route::group(['middleware' => ['admin']], function() {
        Route::get('event-id-help', function () {
            return view('event-id-help');
        })->name('event-id-help');
    });
});
