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

Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'Api\AuthController@logout');

    Route::prefix('/groups')->group(function () {
        Route::get('/', 'Api\GroupController@index');
        Route::get('/{group:uuid}', 'Api\GroupController@show');
        Route::get('/{group:uuid}/join', 'Api\GroupController@join');

        Route::post('/{group:uuid}/notes', 'Api\NoteController@store');
    });
});
