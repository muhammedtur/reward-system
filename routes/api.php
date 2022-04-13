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

Route::group(['middleware' => ['auth:sanctum', 'cors']], function () {
    Route::post('/auth/send_invitation', 'API\AuthController@sendInvitation');
});

// Set up 'cors' middleware for request methods (POST, GET etc.) and disable http request errors
Route::middleware('cors')->group(function () {
    Route::post('/auth/register', 'API\AuthController@register');
    Route::post('/auth/login', 'API\AuthController@login');
});
