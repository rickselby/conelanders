<?php

Route::get('/', 'AssettoCorsaController@index')->name('assetto-corsa.index');

// Management

Route::resource('championship', 'ChampionshipController');

Route::get('championship/{championship}/entrants/', 'ChampionshipEntrantController@index')->name('assetto-corsa.championship.entrants.index');
Route::post('championship/{championship}/entrants/update', 'ChampionshipEntrantController@update')->name('assetto-corsa.championship.entrants.update');

Route::post('/championship/{championship}/event/{event}/copy-sessions',
    'ChampionshipEventController@copySessions')->name('assetto-corsa.championship.event.copy-sessions');
Route::resource('championship.event', 'ChampionshipEventController', [['except' => ['index']]]);

Route::resource('championship.event.session', 'ChampionshipEventSessionController', [['except' => ['index']]]);

Route::post('/championship/{championship}/event/{event}/session/{session}/results-upload',
    'ChampionshipEventSessionController@resultsUpload')->name('assetto-corsa.championship.event.session.results-upload');
Route::get('/championship/{championship}/event/{event}/session/{session}/results-scan',
    'ChampionshipEventSessionController@resultsScan')->name('assetto-corsa.championship.event.session.results-scan');
Route::put('/championship/{championship}/event/{event}/session/{session}/release-date',
    'ChampionshipEventSessionController@releaseDate')->name('assetto-corsa.championship.event.session.release-date');

Route::resource('championship.event.session.entrants', 'ChampionshipEventSessionEntrantController', [['except' => ['index', 'show', 'update', 'destroy']]]);

Route::post('/championship/{championship}/event/{event}/session/{session}/entrants/update',
    'ChampionshipEventSessionEntrantController@update')->name('assetto-corsa.championship.event.session.entrants.update');
Route::post('/championship/{championship}/event/{event}/session/{session}/entrants/points',
    'ChampionshipEventSessionEntrantController@setPoints')->name('assetto-corsa.championship.event.session.entrants.points');
Route::post('/championship/{championship}/event/{event}/session/{session}/entrants/fastest-lap-points',
    'ChampionshipEventSessionEntrantController@setFastestLapPoints')->name('assetto-corsa.championship.event.session.entrants.fastest-lap-points');
Route::post('/championship/{championship}/event/{event}/session/{session}/entrants/points-sequence',
    'ChampionshipEventSessionEntrantController@applyPointsSequence')->name('assetto-corsa.championship.event.session.entrants.points-sequence');
Route::post('/championship/{championship}/event/{event}/session/{session}/entrants/fastest-lap-points-sequence',
    'ChampionshipEventSessionEntrantController@applyFastestLapPointsSequence')->name('assetto-corsa.championship.event.session.entrants.fastest-lap-points-sequence');
Route::post('/championship/{championship}/event/{event}/session/{session}/entrants/started',
    'ChampionshipEventSessionEntrantController@setStarted')->name('assetto-corsa.championship.event.session.entrants.started');
Route::post('/championship/{championship}/event/{event}/session/{session}/entrants/started-session',
    'ChampionshipEventSessionEntrantController@setStartedFromSession')->name('assetto-corsa.championship.event.session.entrants.started-session');
Route::get('/championship/{championship}/event/{event}/session/{session}/entrants/{entrant}/delete',
    'ChampionshipEventSessionEntrantController@destroy')->name('assetto-corsa.championship.event.session.entrants.destroy');

// Standings

Route::get('{championship}', 'StandingsController@championship')->name('assetto-corsa.standings.championship');
Route::get('{championship}/{event}', 'StandingsController@event')->name('assetto-corsa.standings.event');
Route::get('{championship}/{event}/{session}/lapchart', 'StandingsController@lapChart')->name('assetto-corsa.standings.event.session.lapchart');

