<?php

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

/**
|-----------------------------------------------
| Authentication Routes.......
|-----------------------------------------------
 */
Route::group(['namespace' => 'App\Auth', 'middleware' => 'api', 'prefix' => 'auth'], function () {

    //Login...
    Route::post('login', 'LoginController@login');

    //Register...
    // Route::post('register', 'RegisterController@register');
    Route::post('register', 'RegisterControllert@register');
    //Forgot Password...
    Route::post('password/forgot', 'ForgotPasswordController@forgot');

    //Reset Password...
    Route::get('token/{token}', 'ResetPasswordController@token');
    Route::post('reset', 'ResetPasswordController@reset');
});

/**
|-----------------------------------------------
| Logout Routes.......
|-----------------------------------------------
 */
Route::group(['namespace' => 'App\Auth', 'middleware' => 'auth:api', 'prefix' => 'auth'], function () {
    Route::get('logout', 'LoginController@logout');
});

/**
|-----------------------------------------------
| Profile Routes.......
|-----------------------------------------------
 */
Route::group(['namespace' => 'App\User', 'middleware' => 'auth:api'], function () {
    Route::get('profile', 'UserController@profile');
    Route::post('profile/update', 'UserController@profileUpdate');
    Route::post('profile/change-password', 'UserController@profileChangePassword');
});
