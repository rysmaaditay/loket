<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/event/create', 'ApiController@createEvent')->name('api.event.create');
Route::post('/location/create', 'ApiController@createLocation')->name('api.location.create');
Route::post('/event/ticket/create', 'ApiController@createTicket')->name('api.ticket.create');
Route::get('/event/get_info/{id?}', 'ApiController@getEvent')->name('api.event.get');
Route::post('/transaction/purchase', 'ApiController@createTransaction')->name('api.transaction.create');
Route::get('/transaction/get_info/{id?}', 'ApiController@getTransaction')->name('api.transaction.get');