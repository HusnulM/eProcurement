<?php
use Illuminate\Support\Facades\Route;

// <!-- reports/documentlist -->
Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => '/reports'], function () {
        Route::get('/documentlist',            'Reports\DocumentReportController@index')->middleware('checkAuth:reports/documentlist');
        Route::get('/loaddoclist',             'Reports\DocumentReportController@loadReportDocList')->middleware('checkAuth:reports/documentlist');
        Route::post('/documentlist/detail',    'Reports\DocumentReportController@loadDocVersionDetail')->middleware('checkAuth:reports/documentlist');

        Route::post('/documentlist/export', 'Reports\DocumentReportController@exportdata');
    });
});