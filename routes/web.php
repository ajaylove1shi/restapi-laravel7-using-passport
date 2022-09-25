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

 Route::view('/', 'welcome');

Route::get('refresh', function () {
    \Artisan::call('clear-compiled');
    \Artisan::call('cache:clear');
    \Artisan::call('view:clear');
    \Artisan::call('config:clear');
    \Artisan::call('config:cache');
});

