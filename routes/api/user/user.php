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

Route::group([
    'name' => 'api.',
    'prefix' => 'api',
    'middleware' => 'api'
], function () {

    Route::group([
        'name' => 'user.',
        'prefix' => 'user',
        'middleware' => 'api:auth'
    ], function () {
        Route::get('details', 'Auth\APIAuthController@details')->name('api.user.details');
        Route::get('/user', function (Request $request) {
            return $request->user();
        })->name('api.user.model');
    });
});

