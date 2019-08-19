<?php

/*
|--------------------------------------------------------------------------
| Eoa Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::domain('eoa.{env}.ebsig.com')->group(function() {
    Route::post('user/login', 'Eoa\UserController@login');
    Route::post('user/logout', 'Eoa\UserController@logout');
    Route::get('user/info', 'Eoa\UserController@loginInfo');
    Route::get('release/search', 'Eoa\ReleaseController@search');
    Route::get('release/revision/list', 'Eoa\ReleaseController@getRevisions');
    Route::get('released/files/{id}', 'Eoa\ReleaseController@getReleasedFiles');
});



