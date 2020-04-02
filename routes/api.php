<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('tickets', 'ApiController@store');
Route::get('all', 'ApiController@getAllTickets');
Route::get('tickets/{number}', 'ApiController@getOneTicket');
Route::post('payments/{number}', 'ApiController@pay');
Route::get('search', 'ApiController@search');
