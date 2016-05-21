<?php

Route::get('/', 'AssettoCorsaController@index')->name('assetto-corsa.index');

Route::get('championship/{championship}/entrants/', 'ChampionshipEntrantController@index')->name('assetto-corsa.championship.entrants.index');
Route::post('championship/{championship}/entrants/update', 'ChampionshipEntrantController@update')->name('assetto-corsa.championship.entrants.update');

Route::get('/championship/{championship}/race/{race}/entrants',
    'ChampionshipRaceController@entrants')->name('assetto-corsa.championship.race.entrants');
Route::post('/championship/{championship}/race/{race}/save-entrants',
    'ChampionshipRaceController@saveEntrants')->name('assetto-corsa.championship.race.save-entrants');
Route::post('/championship/{championship}/race/{race}/update-entrants',
    'ChampionshipRaceController@updateEntrants')->name('assetto-corsa.championship.race.update-entrants');
Route::get('/championship/{championship}/race/{race}/delete-entrant/{entrant}',
    'ChampionshipRaceController@deleteEntrant')->name('assetto-corsa.championship.race.delete-entrant');
Route::post('/championship/{championship}/race/{race}/update-release-date',
    'ChampionshipRaceController@updateReleaseDate')->name('assetto-corsa.championship.race.update-release-date');
Route::post('/championship/{championship}/race/{race}/qualifying-results-upload',
    'ChampionshipRaceController@qualifyingResultsUpload')->name('assetto-corsa.championship.race.qualifying-results-upload');
Route::post('/championship/{championship}/race/{race}/race-results-upload',
    'ChampionshipRaceController@raceResultsUpload')->name('assetto-corsa.championship.race.race-results-upload');
Route::get('/championship/{championship}/race/{race}/qualifying-results-scan',
    'ChampionshipRaceController@qualifyingResultsScan')->name('assetto-corsa.championship.race.qualifying-results-scan');
Route::get('/championship/{championship}/race/{race}/race-results-scan',
    'ChampionshipRaceController@raceResultsScan')->name('assetto-corsa.championship.race.race-results-scan');

Route::resource('championship', 'ChampionshipController');
Route::resource('championship.race', 'ChampionshipRaceController', [['except' => ['index']]]);

Route::get('{championship}', 'StandingsController@championship')->name('assetto-corsa.standings.championship');
Route::get('{championship}/{race}', 'StandingsController@race')->name('assetto-corsa.standings.race');
Route::get('{championship}/{race}/lapchart', 'StandingsController@lapChart')->name('assetto-corsa.standings.race.lapchart');

