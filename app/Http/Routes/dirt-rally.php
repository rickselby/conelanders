<?php

Route::get('/', 'DirtRallyController@index')->name('dirt-rally.index');

Route::resource('championship', 'ChampionshipController');
Route::resource('championship.season', 'ChampionshipSeasonController', [['except' => ['index']]]);
Route::resource('championship.season.event', 'ChampionshipSeasonEventController', [['except' => ['index']]]);
Route::resource('championship.season.event.stage', 'ChampionshipSeasonEventStageController', [['except' => ['index']]]);

Route::get('{championship}', 'DirtRallyController@championship')->name('dirt-rally.championship');

Route::get('{championship}/driver', 'StandingsController@championship')->name('dirt-rally.standings.championship');
Route::get('{championship}/driver/overview', 'StandingsController@overview')->name('dirt-rally.standings.overview');
Route::get('{championship}/driver/{season}', 'StandingsController@season')->name('dirt-rally.standings.season');
Route::get('{championship}/driver/{season}/{event}', 'StandingsController@event')->name('dirt-rally.standings.event');
Route::get('{championship}/driver/{season}/{event}/{stage}', 'StandingsController@stage')->name('dirt-rally.standings.stage');

Route::get('{championship}/nation', 'NationStandingsController@championship')->name('dirt-rally.nationstandings.championship');
Route::get('{championship}/nation/overview', 'NationStandingsController@overview')->name('dirt-rally.nationstandings.overview');
Route::get('{championship}/nation/{season}', 'NationStandingsController@season')->name('dirt-rally.nationstandings.season');
Route::get('{championship}/nation/{season}/{event}', 'NationStandingsController@event')->name('dirt-rally.nationstandings.event');
Route::get('{championship}/nation/{season}/{event}/{nation}', 'NationStandingsController@detail')->name('dirt-rally.nationstandings.detail');

Route::get('{championship}/times', 'TimesController@championship')->name('dirt-rally.times.championship');
Route::get('{championship}/times/{season}', 'TimesController@season')->name('dirt-rally.times.season');
Route::get('{championship}/times/{season}/{event}', 'TimesController@event')->name('dirt-rally.times.event');
Route::get('{championship}/times/{season}/{event}/{stage}', 'TimesController@stage')->name('dirt-rally.times.stage');

Route::get('event/{championship}/{season}/{event}', 'DirtRallyController@event')->name('dirt-rally.event');

Route::group(['middleware' => ['admin']], function() {
    Route::get('event-id-help', function () {
        return view('dirt-rally.event-id-help');
    })->name('dirt-rally.event-id-help');
});

