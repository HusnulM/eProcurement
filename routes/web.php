<?php

use Illuminate\Support\Facades\Route;

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

Route::get(
    '/down',
    function () {
        \Artisan::call('down');
        dd("Done");
        // Artisan::call( 'down', [
        //     '--secrect' => 'allow-certain-users-to-access-the-application-using-this-secret',
        // ] );

        // dd( Artisan::output() );
    }
);

Route::get(
    '/up',
    function () {
        // Artisan::call( 'up' );
        // dd(Artisan::output());
        \Artisan::call('up');
        dd("Done");
    }
);

Route::group(['middleware' => 'guest'], function(){
    Route::group(['middleware' => 'revalidate'], function () {
        Route::get('/',              'HomeController@index')->name('login');
        Route::post('authenticate',  'HomeController@login');
    });
});

Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => 'revalidate'], function () {
        Route::get('/dashboard',      'HomeController@dashboard');
        Route::post('logout',         'HomeController@logout')->name('logout');
        Route::get('logout2',         'HomeController@logout')->name('logout');
        Route::post('changepassword', 'HomeController@changepassword')->name('changepassword');
    });
});