<?php

Route::post('points-system/{system}/points', 'PointsSystemController@points')->name('points-system.points');
Route::resource('points-system', 'PointsSystemController');

Route::resource('championship', 'ChampionshipController');
Route::resource('championship.season', 'ChampionshipSeasonController', [['except' => ['index']]]);
Route::resource('championship.season.event', 'ChampionshipSeasonEventController', [['except' => ['index']]]);
Route::resource('championship.season.event.stage', 'ChampionshipSeasonEventStageController', [['except' => ['index']]]);

Route::get('standings', 'StandingsController@index')->name('dirt-rally.standings.index');
Route::get('standings/{system}', 'StandingsController@system')->name('dirt-rally.standings.system');
Route::get('standings/{system}/{championship}', 'StandingsController@championship')->name('dirt-rally.standings.championship');
Route::get('standings/{system}/{championship}/overview', 'StandingsController@overview')->name('dirt-rally.standings.overview');
Route::get('standings/{system}/{championship}/{season}', 'StandingsController@season')->name('dirt-rally.standings.season');
Route::get('standings/{system}/{championship}/{season}/{event}', 'StandingsController@event')->name('dirt-rally.standings.event');
Route::get('standings/{system}/{championship}/{season}/{event}/{stage}', 'StandingsController@stage')->name('dirt-rally.standings.stage');

Route::get('nation-standings/', 'NationStandingsController@index')->name('dirt-rally.nationstandings.index');
Route::get('nation-standings/{system}', 'NationStandingsController@system')->name('dirt-rally.nationstandings.system');
Route::get('nation-standings/{system}/{championship}', 'NationStandingsController@championship')->name('dirt-rally.nationstandings.championship');
Route::get('nation-standings/{system}/{championship}/overview', 'NationStandingsController@overview')->name('dirt-rally.nationstandings.overview');
Route::get('nation-standings/{system}/{championship}/{season}', 'NationStandingsController@season')->name('dirt-rally.nationstandings.season');
Route::get('nation-standings/{system}/{championship}/{season}/{event}', 'NationStandingsController@event')->name('dirt-rally.nationstandings.event');

Route::get('times', 'TimesController@index')->name('dirt-rally.times.index');
Route::get('times/{championship}', 'TimesController@championship')->name('dirt-rally.times.championship');
Route::get('times/{championship}/{season}', 'TimesController@season')->name('dirt-rally.times.season');
Route::get('times/{championship}/{season}/{event}', 'TimesController@event')->name('dirt-rally.times.event');
Route::get('times/{championship}/{season}/{event}/{stage}', 'TimesController@stage')->name('dirt-rally.times.stage');

Route::group(['middleware' => ['admin']], function() {
    Route::get('event-id-help', function () {
        return view('event-id-help');
    })->name('dirt-rally.event-id-help');
});

