<?php

Route::get('/', 'AssettoCorsaController@index')->name('assetto-corsa.index');

// Management

Route::resource('car', 'CarController', ['except' => 'show']);

Route::resource('championship', 'ChampionshipController');

Route::resource('championship.entrant', 'ChampionshipEntrantController');

Route::post('championship/{championship}/event/{event}/copy-sessions',
    'ChampionshipEventController@copySessions')->name('assetto-corsa.championship.event.copy-sessions');
Route::post('championship/{championship}/event/{event}/sort-sessions',
    'ChampionshipEventController@sortSessions')->name('assetto-corsa.championship.event.sort-sessions');
Route::resource('championship.event', 'ChampionshipEventController', [['except' => ['index']]]);

Route::resource('championship.event.session', 'ChampionshipEventSessionController', [['except' => ['index']]]);

Route::post('championship/{championship}/event/{event}/session/{session}/results-upload',
    'ChampionshipEventSessionController@resultsUpload')->name('assetto-corsa.championship.event.session.results-upload');
Route::get('championship/{championship}/event/{event}/session/{session}/results-scan',
    'ChampionshipEventSessionController@resultsScan')->name('assetto-corsa.championship.event.session.results-scan');
Route::put('championship/{championship}/event/{event}/session/{session}/release-date',
    'ChampionshipEventSessionController@releaseDate')->name('assetto-corsa.championship.event.session.release-date');

Route::post('championship/{championship}/event/{event}/session/{session}/entrants/update',
    'ChampionshipEventSessionEntrantController@update')->name('assetto-corsa.championship.event.session.entrants.update');
Route::post('championship/{championship}/event/{event}/session/{session}/entrants/points',
    'ChampionshipEventSessionEntrantController@setPoints')->name('assetto-corsa.championship.event.session.entrants.points');
Route::post('championship/{championship}/event/{event}/session/{session}/entrants/fastest-lap-points',
    'ChampionshipEventSessionEntrantController@setFastestLapPoints')->name('assetto-corsa.championship.event.session.entrants.fastest-lap-points');
Route::post('championship/{championship}/event/{event}/session/{session}/entrants/points-sequence',
    'ChampionshipEventSessionEntrantController@applyPointsSequence')->name('assetto-corsa.championship.event.session.entrants.points-sequence');
Route::post('championship/{championship}/event/{event}/session/{session}/entrants/fastest-lap-points-sequence',
    'ChampionshipEventSessionEntrantController@applyFastestLapPointsSequence')->name('assetto-corsa.championship.event.session.entrants.fastest-lap-points-sequence');
Route::post('championship/{championship}/event/{event}/session/{session}/entrants/started',
    'ChampionshipEventSessionEntrantController@setStarted')->name('assetto-corsa.championship.event.session.entrants.started');
Route::post('championship/{championship}/event/{event}/session/{session}/entrants/started-session',
    'ChampionshipEventSessionEntrantController@setStartedFromSession')->name('assetto-corsa.championship.event.session.entrants.started-session');
Route::get('championship/{championship}/event/{event}/session/{session}/entrants/{entrant}/delete',
    'ChampionshipEventSessionEntrantController@destroy')->name('assetto-corsa.championship.event.session.entrants.destroy');


Route::get('server', 'ServerController@index')->name('assetto-corsa.server.index');
Route::get('server/status', 'ServerController@status')->name('assetto-corsa.server.status');
Route::post('server/config', 'ServerController@updateConfig')->name('assetto-corsa.server.update-config');
Route::post('server/start', 'ServerController@start')->name('assetto-corsa.server.start');
Route::post('server/stop', 'ServerController@stop')->name('assetto-corsa.server.stop');

Route::post('playlists', 'PlaylistController@update')->name('assetto-corsa.playlists');

// Standings - must go last, to catch anything not caught already

Route::get('championship-css/{championship}', 'AssettoCorsaController@championshipCSS')->name('assetto-corsa.championship-css');

Route::get('{championship}', 'ResultsController@championship')->name('assetto-corsa.results.championship');
Route::get('{championship}/results/{event}', 'ResultsController@event')->name('assetto-corsa.results.event');
Route::get('{championship}/results/{event}/{session}/lapchart', 'ResultsController@lapChart')->name('assetto-corsa.results.event.session.lapchart');
Route::get('{championship}/driver-standings', 'StandingsController@drivers')->name('assetto-corsa.standings.drivers');
Route::get('{championship}/constructor-standings', 'StandingsController@constructors')->name('assetto-corsa.standings.constructors');
