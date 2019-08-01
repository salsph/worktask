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

Route::get('/', 'Auth\LoginController@showLoginForm');


Route::group(['middleware' => 'admin'], function(){

    /** Employees **/
    Route::get('admin/employees', 'EmployeeController@index');
    Route::get('admin/employees/list', 'EmployeeController@list');
    Route::get('admin/employees/editor/{id?}', 'EmployeeController@editor');
    Route::post('admin/employees/edit', 'EmployeeController@edit');
    Route::post('admin/employees/remove', 'EmployeeController@remove');
    Route::get('admin/employees/autocomplete', 'EmployeeController@autocomplete');

    /** Positions **/
    Route::get('admin/positions', 'PositionController@index');
    Route::get('admin/positions/list', 'PositionController@list');
    Route::get('admin/positions/editor/{id?}', 'PositionController@editor');
    Route::post('admin/positions/edit', 'PositionController@edit');
    Route::post('admin/positions/remove', 'PositionController@remove');
    Route::get('admin/positions/autocomplete', 'PositionController@autocomplete');

});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
