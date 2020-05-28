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

    /**
     * LOGIN & REGISTER
     */
    Route::group([
        'name' => 'auth.',
        'prefix' => 'auth',
        'middleware' => 'api'
    ], function () {

        Route::post('register', 'Auth\APIAuthController@register')->name('api.auth.register');
        Route::post('login', 'Auth\APIAuthController@login')->name('api.auth.login');

    });

    /**
     * GET LOGGED IN USER DETAILS
     */
    Route::group([
        'name' => 'auth.',
        'prefix' => 'auth',
        'middleware' => 'auth:api'
    ], function () {

        Route::get('details', 'Auth\APIAuthController@details')->name('api.auth.details');

    });

    /**
     * RESET PASSWORD
     */
    Route::group([    
        'name' => 'auth.',    
        'prefix' => 'auth/password-reset',
        'middleware' => 'api',    
    ], function () {    

        Route::post('create', 'Auth\PasswordResetController@create');
        Route::get('find/{token}', 'Auth\PasswordResetController@find');
        Route::post('reset', 'Auth\PasswordResetController@reset');

    });

});