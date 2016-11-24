<?php

Route::resource('car', 'CarController', ['except' => 'show']);

Route::resource('championship', 'ChampionshipController');

Route::resource('championship.admin', 'ChampionshipAdminController', ['only' => ['store', 'destroy']]);

Route::post('championship/{championship}/event/{event}/copy-sessions',
    'ChampionshipEventController@copySessions')->name('rallycross.championship.event.copy-sessions');
Route::post('championship/{championship}/event/{event}/sort-sessions',
    'ChampionshipEventController@sortSessions')->name('rallycross.championship.event.sort-sessions');
Route::put('championship/{championship}/event/{event}/release-date',
    'ChampionshipEventController@releaseDate')->name('rallycross.championship.event.release-date');
Route::post('championship/{championship}/event/{event}/heats-points-sequence',
    'ChampionshipEventController@heatsPointsSequence')->name('rallycross.championship.event.heats-points-sequence');
Route::post('championship/{championship}/event/{event}/heats-points',
    'ChampionshipEventController@heatsPoints')->name('rallycross.championship.event.heats-points');
Route::resource('championship.event', 'ChampionshipEventController');

Route::resource('championship.event.entrant', 'ChampionshipEventEntrantController');

Route::post('championship/{championship}/event/{event}/session/{session}/entrants/points',
    'ChampionshipEventSessionController@setPoints')->name('rallycross.championship.event.session.entrants.points');
Route::post('championship/{championship}/event/{event}/session/{session}/entrants/points-sequence',
    'ChampionshipEventSessionController@applyPointsSequence')->name('rallycross.championship.event.session.entrants.points-sequence');
Route::get('championship/{championship}/event/{event}/session/{session}/complete',
    'ChampionshipEventSessionController@markComplete')->name('rallycross.championship.event.session.complete');

Route::resource('championship.event.session', 'ChampionshipEventSessionController');
Route::resource('championship.event.session.entrant', 'ChampionshipEventSessionEntrantController');

Route::get('/', 'ResultsController@index')->name('rallycross.index');

Route::get('{championship}', 'ResultsController@championship')->name('rallycross.results.championship');

Route::get('{championship}/results/{event}', 'ResultsController@event')->name('rallycross.results.event');
Route::get('{championship}/driver-standings', 'StandingsController@drivers')->name('rallycross.standings.drivers');
Route::get('{championship}/constructor-standings', 'StandingsController@constructors')->name('rallycross.standings.constructors');
Route::get('{championship}/team-standings', 'StandingsController@teams')->name('rallycross.standings.teams');
