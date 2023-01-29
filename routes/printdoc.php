<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {

    Route::group(['prefix' => '/printdoc/pbj'], function () {
        Route::get('/',             'Transaksi\PrintDocumentController@pbjlist')->middleware('checkAuth:printdoc/pbj');
        Route::get('/print/{p1}',   'Transaksi\PrintDocumentController@printpbj')->middleware('checkAuth:printdoc/pbj');   
        Route::get('/printlist',    'Transaksi\PrintDocumentController@printpbjlist')->middleware('checkAuth:printdoc/pbj');   
    });

    Route::group(['prefix' => '/printdoc/pr'], function () {
        Route::get('/',             'Transaksi\PrintDocumentController@prlist')->middleware('checkAuth:printdoc/pr');
        Route::get('/print/{p1}',   'Transaksi\PrintDocumentController@printpr')->middleware('checkAuth:printdoc/pr');   
        Route::get('/detail/{p1}',  'Transaksi\PrintDocumentController@prdetail')->middleware('checkAuth:printdoc/pr');   
        Route::get('/printlist',    'Transaksi\PrintDocumentController@printprlist')->middleware('checkAuth:printdoc/pr');   
    });    

    Route::group(['prefix' => '/printdoc/po'], function () {
        Route::get('/',             'Transaksi\PrintDocumentController@polist')->middleware('checkAuth:printdoc/po');
        Route::get('/print/{p1}',   'Transaksi\PrintDocumentController@printpo')->middleware('checkAuth:printdoc/po');   
        Route::get('/detail/{p1}',  'Transaksi\PrintDocumentController@podetail')->middleware('checkAuth:printdoc/po');   
        Route::get('/printlist',    'Transaksi\PrintDocumentController@printpolist')->middleware('checkAuth:printdoc/po');   
    });

    Route::group(['prefix' => '/printdoc/wo'], function () {
        Route::get('/',             'Transaksi\PrintDocumentController@wolist')->middleware('checkAuth:printdoc/wo');
        Route::get('/print/{p1}',   'Transaksi\PrintDocumentController@printwo')->middleware('checkAuth:printdoc/wo');   
        Route::get('/detail/{p1}',  'Transaksi\PrintDocumentController@wodetail')->middleware('checkAuth:printdoc/wo');   
        Route::get('/printlist',    'Transaksi\PrintDocumentController@printpolist')->middleware('checkAuth:printdoc/wo');   
    });
    
});