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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>['auth', 'superadmin']],function(){
//http://localhost:8080/Laravel/apiRest/public/api/v1/

Route::post('v1/auth/login','UserController@login');
Route::post('v1/auth/refresh','UserController@refresh'); //debe estar protegida
Route::get('v1/auth/logout','UserController@logout'); //debe estar protegida

Route::group(['prefix'=>'v1','middleware'=>['jwt.auth']],function(){
    

    Route::get('projects','ProjectController@index')->name('project.index');
    Route::get('projects/{id}','ProjectController@show')->name('project.show');
    Route::post('projects','ProjectController@store')->name('project.store');
    Route::put('projects','ProjectController@store')->name('project.update');
    Route::delete('projects','ProjectController@index')->name('project.delete');
});
