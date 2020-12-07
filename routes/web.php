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

// Route::get('/', function () {
//     return view('welcome');
// });
    Route::get('/','StationController@dropDownShow')->name('front');

    //Stations
    Route::get('/stations','StationController@index')->name('station.index')->middleware('auth');
    Route::get('/station/create','StationController@create')->name('station.create')->middleware('auth');
    Route::post('/station','StationController@store')->name('station.store')->middleware('auth');
    Route::get('/station/{station}','StationController@show')->name('station.show')->middleware('auth');
    Route::get('/station/{station}/edit','StationController@edit')->name('station.edit')->middleware('auth');
    Route::put('/station/{station}','StationController@update')->name('station.update')->middleware('auth');
    Route::delete('/station/{station}','StationController@destroy')->name('station.destroy')->middleware('auth');

    //Route::get('/nearby','LocationController@index')->name('location');
    Route::get('/planner','PlannerController@index')->name('planner');
    Route::get('/planner/search','PlannerController@search')->name('search');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
