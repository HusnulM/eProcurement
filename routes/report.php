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

    Route::group(['prefix' => '/report'], function () {
        Route::get('/budgetrequest',            'Reports\ReportsController@requestbudget')->middleware('checkAuth:report/budgetrequest');
        Route::get('/budgetrequestlist',        'Reports\ReportsController@budgetRequestlist')->middleware('checkAuth:approve/budget');

        Route::get('/pbj',                      'Reports\ReportsController@pbj')->middleware('checkAuth:report/pbj');
        Route::get('/pbjlist',                  'Reports\ReportsController@pbjList')->middleware('checkAuth:report/pbj');

        Route::get('/po',                       'Reports\ReportsController@po')->middleware('checkAuth:report/po');
        Route::get('/polist',                   'Reports\ReportsController@poList')->middleware('checkAuth:report/po');

        Route::get('/pr',                       'Reports\ReportsController@pr')->middleware('checkAuth:report/pr');
        Route::get('/prlist',                   'Reports\ReportsController@prList')->middleware('checkAuth:report/pr');

        Route::get('/wo',                       'Reports\ReportsController@wo')->middleware('checkAuth:report/wo');
        Route::get('/wolist',                   'Reports\ReportsController@woList')->middleware('checkAuth:report/wo');

        Route::get('/grpo',                     'Reports\ReportsController@grpo')->middleware('checkAuth:report/grpo');
        Route::get('/grpolist',                 'Reports\ReportsController@grpoList')->middleware('checkAuth:report/grpo');

        Route::get('/issue',                    'Reports\ReportsController@issue')->middleware('checkAuth:report/issue');
        Route::get('/issuelist',                'Reports\ReportsController@issueList')->middleware('checkAuth:report/issue');

        Route::get('/stock',                    'Reports\ReportsController@stock')->middleware('checkAuth:report/stock');
        Route::get('/stocklist',                'Reports\ReportsController@stockList')->middleware('checkAuth:report/stock');
    });
});