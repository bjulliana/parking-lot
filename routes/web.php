<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'WebController@create')->name('tickets.new');
Route::get('tickets', 'WebController@create')->name('tickets.new');
Route::post('tickets', 'WebController@store')->name('tickets.add');
Route::get('all', 'WebController@index')->name('tickets.all');
Route::get('tickets/{number}', 'WebController@show')->name('tickets.show');
Route::post('payments/{number}', 'WebController@update')->name('tickets.pay');
