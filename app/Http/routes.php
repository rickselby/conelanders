<?php

Route::group(['middleware' => ['web']], function () {

    Route::get('/', function () {
        return Redirect::route('season.index');
    });

    Route::resource('season', 'SeasonController');
    Route::resource('event', 'EventController');
    Route::resource('stage', 'StageController');
    //
});
