<?php

Route::group(['middleware' => ['web']], function () {

    Route::get('/', 'ConelandersController@index')->name('home');
    Route::get('/calendar/', 'ConelandersController@calendar')->name('calendar');

    Route::get('/login', 'Auth\AuthController@index')->name('login.index');
    Route::get('/login/google', 'Auth\AuthController@loginGoogle')->name('login.google');
    Route::get('/login/google/done', 'Auth\AuthController@loginGoogleDone');
    Route::get('/logout', 'Auth\AuthController@logout')->name('logout');

    Route::get('nation/image/{nation}', 'NationController@image')->name('nation.image');
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

    Route::group(['prefix' => 'api', 'namespace' => 'API'], function() {
        Route::group(['prefix' => 'races', 'namespace' => 'Races'], function() {
            include('Routes/API/races.php');
        });
    });

    Route::get('about', function() {
        return view('about');
    })->name('about');

    Route::get('user', 'UserController@show')->name('user.show');
    Route::post('user/select-driver', 'UserController@selectDriver')->name('user.select-driver');
    Route::post('user/update-profile', 'UserController@updateProfile')->name('user.update-profile');
    Route::get('user/assignments', 'UserController@assignments')->name('user.assignments');
    Route::get('user/assign/{user}', 'UserController@assign')->name('user.assign');

    Route::get('playlists', 'PlaylistController@index')->name('playlists.index');

    # This needs to be last, there's the catchall for {category}
    include('Routes/races.php');

});
