<?php

#Route::get('/', 'AssettoCorsaController@index')->name('races.index');

// Management

Route::group(['prefix' => 'races', 'namespace' => 'Races'], function() {
    Route::resource('car', 'CarController', ['except' => 'show']);

    Route::resource('category', 'CategoryController');

    Route::resource('category.championship', 'ChampionshipController');

    Route::get('championship/{championship}/entrant/css', 'ChampionshipEntrantController@css')->name('races.championship.entrant.css');
    Route::resource('championship.entrant', 'ChampionshipEntrantController');

    Route::resource('championship.team', 'ChampionshipTeamController');

    Route::post('championship/{championship}/event/{event}/copy-sessions',
        'ChampionshipEventController@copySessions')->name('races.championship.event.copy-sessions');
    Route::post('championship/{championship}/event/{event}/sort-sessions',
        'ChampionshipEventController@sortSessions')->name('races.championship.event.sort-sessions');
    Route::post('championship/{championship}/event/{event}/signup',
        'ChampionshipEventController@signup')->name('races.championship.event.signup');
    Route::resource('championship.event', 'ChampionshipEventController', [['except' => ['index']]]);

    Route::resource('championship.event.session', 'ChampionshipEventSessionController', [['except' => ['index']]]);

    Route::post('championship/{championship}/event/{event}/session/{session}/results-upload',
        'ChampionshipEventSessionController@resultsUpload')->name('races.championship.event.session.results-upload');
    Route::get('championship/{championship}/event/{event}/session/{session}/results-scan',
        'ChampionshipEventSessionController@resultsScan')->name('races.championship.event.session.results-scan');
    Route::put('championship/{championship}/event/{event}/session/{session}/release-date',
        'ChampionshipEventSessionController@releaseDate')->name('races.championship.event.session.release-date');

    Route::post('championship/{championship}/event/{event}/session/{session}/entrants/update',
        'ChampionshipEventSessionEntrantController@update')->name('races.championship.event.session.entrants.update');
    Route::post('championship/{championship}/event/{event}/session/{session}/entrants/points',
        'ChampionshipEventSessionEntrantController@setPoints')->name('races.championship.event.session.entrants.points');
    Route::post('championship/{championship}/event/{event}/session/{session}/entrants/fastest-lap-points',
        'ChampionshipEventSessionEntrantController@setFastestLapPoints')->name('races.championship.event.session.entrants.fastest-lap-points');
    Route::post('championship/{championship}/event/{event}/session/{session}/entrants/points-sequence',
        'ChampionshipEventSessionEntrantController@applyPointsSequence')->name('races.championship.event.session.entrants.points-sequence');
    Route::post('championship/{championship}/event/{event}/session/{session}/entrants/fastest-lap-points-sequence',
        'ChampionshipEventSessionEntrantController@applyFastestLapPointsSequence')->name('races.championship.event.session.entrants.fastest-lap-points-sequence');
    Route::post('championship/{championship}/event/{event}/session/{session}/entrants/started',
        'ChampionshipEventSessionEntrantController@setStarted')->name('races.championship.event.session.entrants.started');
    Route::post('championship/{championship}/event/{event}/session/{session}/entrants/started-session',
        'ChampionshipEventSessionEntrantController@setStartedFromSession')->name('races.championship.event.session.entrants.started-session');
    Route::get('championship/{championship}/event/{event}/session/{session}/entrants/{entrant}/delete',
        'ChampionshipEventSessionEntrantController@destroy')->name('races.championship.event.session.entrants.destroy');

    Route::get('server', 'ServerController@index')->name('races.server.index');
    Route::get('server/status', 'ServerController@status')->name('races.server.status');
    Route::post('server/config', 'ServerController@updateConfig')->name('races.server.update-config');
    Route::post('server/start', 'ServerController@start')->name('races.server.start');
    Route::post('server/stop', 'ServerController@stop')->name('races.server.stop');

    Route::post('playlists', 'PlaylistController@update')->name('races.playlists');
});

// Standings - must go last, to catch anything not caught already
Route::group(['namespace' => 'Races'], function() {

    Route::get('{category}', 'ResultsController@index')->name('races.index');

    Route::get('{category}/{championship}', 'ResultsController@championship')->name('races.results.championship');

    #Route::get('{championship}', 'ResultsController@championship')->name('races.results.championship');
    Route::get('{category}/{championship}/results/{event}', 'ResultsController@event')->name('races.results.event');
    Route::get('{category}/{championship}/results/{event}/{session}/lapchart', 'ResultsController@lapChart')->name('races.results.event.session.lapchart');
    Route::get('{category}/{championship}/driver-standings', 'StandingsController@drivers')->name('races.standings.drivers');
    Route::get('{category}/{championship}/constructor-standings', 'StandingsController@constructors')->name('races.standings.constructors');
    Route::get('{category}/{championship}/team-standings', 'StandingsController@teams')->name('races.standings.teams');

});
