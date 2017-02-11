<?php

Route::resource('session', 'SessionController');
Route::resource('session.entrant', 'SessionEntrantController', ['only' => ['store', 'destroy']]);

Route::get('/', 'ResultsController@index')->name('assetto-corsa.hotlaps.index');
Route::get('{session}', 'ResultsController@session')->name('assetto-corsa.hotlaps.session');
