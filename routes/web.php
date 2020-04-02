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

Route::get('/', 'TicketsController@create')->name('tickets.new');
Route::get('/new', 'TicketsController@create')->name('tickets.new');
Route::post('/tickets', 'TicketsController@store')->name('tickets.add');
Route::get('/all', 'TicketsController@getAllTickets')->name('tickets.all');
Route::get('/tickets/{number}', 'TicketsController@getOneTicket')->name('tickets.show');
Route::post('/payments/{number}', 'TicketsController@pay')->name('tickets.pay');
Route::post('/receipt/{number}', 'TicketsController@receipt')->name('tickets.receipt');

// Route::resource('parkingspace', 'ParkingSpacesController');
// Route::resource('ticket', 'TicketsController');
