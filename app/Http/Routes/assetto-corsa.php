<?php

Route::get('/', 'AssettoCorsaController@index')->name('assetto-corsa.index');

Route::resource('championship', 'ChampionshipController');

Route::get('/championship/{championship}/race/{race}/entrants',
    'ChampionshipRaceController@entrants')->name('assetto-corsa.championship.race.entrants');
Route::post('/championship/{championship}/race/{race}/save-entrants',
    'ChampionshipRaceController@saveEntrants')->name('assetto-corsa.championship.race.save-entrants');
Route::post('/championship/{championship}/race/{race}/update-entrants',
    'ChampionshipRaceController@updateEntrants')->name('assetto-corsa.championship.race.update-entrants');
Route::post('/championship/{championship}/race/{race}/update-release-date',
    'ChampionshipRaceController@updateReleaseDate')->name('assetto-corsa.championship.race.update-release-date');
Route::post('/championship/{championship}/race/{race}/qualifying-results-upload',
    'ChampionshipRaceController@qualifyingResultsUpload')->name('assetto-corsa.championship.race.qualifying-results-upload');
Route::post('/championship/{championship}/race/{race}/race-results-upload',
    'ChampionshipRaceController@raceResultsUpload')->name('assetto-corsa.championship.race.race-results-upload');

Route::resource('championship.race', 'ChampionshipRaceController', [['except' => ['index']]]);

Route::resource('points-system', 'PointsSystemController');

Route::get('standings', 'StandingsController@index')->name('assetto-corsa.standings.index');
Route::get('standings/{system}', 'StandingsController@system')->name('assetto-corsa.standings.system');
Route::get('standings/{system}/{championship}', 'StandingsController@championship')->name('assetto-corsa.standings.championship');
Route::get('standings/{system}/{championship}/{race}', 'StandingsController@race')->name('assetto-corsa.standings.race');
Route::get('standings/{system}/{championship}/{race}/lapchart', 'StandingsController@lapChart')->name('assetto-corsa.standings.race.lapchart');

Route::get('championship/{championship}/entrants/', 'ChampionshipEntrantController@index')->name('assetto-corsa.championship.entrants.index');
Route::post('championship/{championship}/entrants/update', 'ChampionshipEntrantController@update')->name('assetto-corsa.championship.entrants.update');