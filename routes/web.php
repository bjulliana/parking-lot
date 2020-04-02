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
Route::get('new', 'WebController@create')->name('tickets.new');
Route::post('tickets', 'WebController@store')->name('tickets.add');
Route::get('all', 'WebController@getAllTickets')->name('tickets.all');
Route::get('tickets/{number}', 'WebController@getOneTicket')->name('tickets.show');
Route::post('payments/{number}', 'WebController@pay')->name('tickets.pay');
Route::post('receipt/{number}', 'WebController@receipt')->name('tickets.receipt');
