<?php

/*
|--------------------------------------------------------------------------
| Develop Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('develop/login', 'Develop\LoginController@index');
Route::get('develop/login/do', 'Develop\LoginController@login');
Route::get('develop/logout', 'Develop\LoginController@logout');

Route::middleware(['develop.service'])->group( function() {

    //代码发布
    Route::get('develop/release', 'Develop\ReleaseController@index'); //发布列表
    Route::get('ajax/release/search', 'Develop\ReleaseController@search'); //查询发布列表
    Route::get('ajax/release/revision/list', 'Develop\ReleaseController@getRevisions'); //获取待发布的svn版本列表
    Route::get('ajax/release/files/{id}', 'Develop\ReleaseController@getFiles'); //获取发布文件
    Route::get('ajax/released/files/{id}', 'Develop\ReleaseController@getReleasedFiles'); //获取已发布文件
    Route::get('ajax/release/do/{id}', 'Develop\ReleaseController@release'); //发布文件
    Route::get('ajax/release/delete/{id}', 'Develop\ReleaseController@delete'); //删除发布计划

});