<?php

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

Route::group([
    'name' => 'api.',
    'prefix' => 'api',
    'middleware' => 'api'
], function () {

    Route::group([
        'name' => 'user.',
        'prefix' => 'user',
        'middleware' => 'auth:api'
    ], function () {
        Route::get('/details', 'User\UserController@details')->name('api.user.details');
    });
});

