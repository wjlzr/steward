<?php


/*
|--------------------------------------------------------------------------
| Task Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "task" middleware group. Enjoy building your API!
|
*/


Route::middleware(['task.service'])->group(function () {
    Route::any('/order/push', 'Task\OrderController@push');
});