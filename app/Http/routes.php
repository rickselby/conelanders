<?php

Route::group(['middleware' => ['web']], function () {

    Route::get('/login', 'Auth\AuthController@index')->name('login.index');
    Route::get('/login/google', 'Auth\AuthController@loginGoogle')->name('login.google');
    Route::get('/login/google/done', 'Auth\AuthController@loginGoogleDone');
    Route::get('/logout', 'Auth\AuthController@logout')->name('logout');

    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::resource('nation', 'NationController', [['except' => ['show']]]);
    Route::resource('driver', 'DriverController', [['except' => ['create', 'store', 'destroy']]]);
    Route::resource('points-sequence', 'PointsSequenceController');
    Route::resource('role', 'RoleController');
    Route::group(['prefix' => 'role/{role}'], function() {
        Route::post('add-user', 'RoleController@addUser')->name('role.add-user');
        Route::delete('remove-user/{user}', 'RoleController@removeUser')->name('role.remove-user');
        Route::post('add-permission', 'RoleController@addPermission')->name('role.add-permission');
        Route::delete('remove-permission/{permission}', 'RoleController@removePermission')->name('role.remove-permission');
    });

    Route::group(['prefix' => 'dirt-rally', 'namespace' => 'DirtRally'], function() {
        include('Routes/dirt-rally.php');
    });

    Route::group(['prefix' => 'assetto-corsa', 'namespace' => 'AssettoCorsa'], function() {
        include('Routes/assetto-corsa.php');
    });

    Route::get('about', function() {
        return view('about');
    })->name('about');
});
