<?php

Route::get('signups/current', 'SignupsController@current');
Route::get('signups/{championship}/current', 'SignupsController@championship');