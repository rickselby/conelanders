<?php

Route::get('/', 'AcHotlapController@index')->name('ac-hotlap.index');

Route::resource('session', 'SessionController');
Route::resource('session.entrant', 'SessionEntrantController', ['only' => ['store', 'destroy']]);
