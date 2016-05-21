<?php

Route::get('/', 'DirtRallyController@index')->name('dirt-rally.index');

Route::resource('championship', 'ChampionshipController');
Route::resource('championship.season', 'ChampionshipSeasonController', [['except' => ['index']]]);
Route::resource('championship.season.event', 'ChampionshipSeasonEventController', [['except' => ['index']]]);
Route::resource('championship.season.event.stage', 'ChampionshipSeasonEventStageController', [['except' => ['index']]]);

Route::get('standings', 'StandingsController@index')->name('dirt-rally.standings.index');
Route::get('standings/{championship}', 'StandingsController@championship')->name('dirt-rally.standings.championship');
Route::get('standings/{championship}/overview', 'StandingsController@overview')->name('dirt-rally.standings.overview');
Route::get('standings/{championship}/{season}', 'StandingsController@season')->name('dirt-rally.standings.season');
Route::get('standings/{championship}/{season}/{event}', 'StandingsController@event')->name('dirt-rally.standings.event');
Route::get('standings/{championship}/{season}/{event}/{stage}', 'StandingsController@stage')->name('dirt-rally.standings.stage');

Route::get('nation-standings', 'NationStandingsController@index')->name('dirt-rally.nationstandings.index');
Route::get('nation-standings/{championship}', 'NationStandingsController@championship')->name('dirt-rally.nationstandings.championship');
Route::get('nation-standings/{championship}/overview', 'NationStandingsController@overview')->name('dirt-rally.nationstandings.overview');
Route::get('nation-standings/{championship}/{season}', 'NationStandingsController@season')->name('dirt-rally.nationstandings.season');
Route::get('nation-standings/{championship}/{season}/{event}', 'NationStandingsController@event')->name('dirt-rally.nationstandings.event');
Route::get('nation-standings/{championship}/{season}/{event}/{nation}', 'NationStandingsController@detail')->name('dirt-rally.nationstandings.detail');

Route::get('times', 'TimesController@index')->name('dirt-rally.times.index');
Route::get('times/{championship}', 'TimesController@championship')->name('dirt-rally.times.championship');
Route::get('times/{championship}/{season}', 'TimesController@season')->name('dirt-rally.times.season');
Route::get('times/{championship}/{season}/{event}', 'TimesController@event')->name('dirt-rally.times.event');
Route::get('times/{championship}/{season}/{event}/{stage}', 'TimesController@stage')->name('dirt-rally.times.stage');

Route::group(['middleware' => ['admin']], function() {
    Route::get('event-id-help', function () {
        return view('dirt-rally.event-id-help');
    })->name('dirt-rally.event-id-help');
});

