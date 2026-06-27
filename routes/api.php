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

Route::domain('{tenant_code}.kiot.test')
    ->middleware('tenant.discovery')
    ->namespace('App\Http\Controllers')
    ->group(function () {
        Route::middleware('guest:api')->post('login', 'UserController@login');
        Route::middleware('auth:api')->apiResource('users', 'UserController');
    });
