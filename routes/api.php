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


Route::middleware('countryLimiter:' . env('COUNTRY_REQUEST_LIMIT'))->group(function () {

    Route::get('login', 'Auth\LoginController@apiLogin');

    Route::post('register', 'Auth\RegisterController@register');

    Route::middleware('auth:api')->group(function () {

        Route::get('user', function(Request $request) {
            return $request->user();
        });

        Route::post('products', 'ProductsController@store');

        Route::resource('orders', 'OrdersController')->only(['store', 'index']);

        Route::get('logout', 'Auth\LoginController@apiLogout');
    });
});

