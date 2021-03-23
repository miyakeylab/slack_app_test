<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/slack/events','App\Http\Controllers\EventController@index');
Route::post('/slack/interactive','App\Http\Controllers\InteractiveController@index');
//Route::post('/events', function () {
//    logger('test');
//});
