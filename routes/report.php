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
        Route::get('/budgetrequestlist',        'Reports\ReportsController@budgetRequestlist')->middleware('checkAuth:report/budgetrequest');

        Route::get('/budgetsummary',            'Reports\BudgetReportController@index')->middleware('checkAuth:report/budgetsummary');
        Route::get('/budgetsummarylist',        'Reports\BudgetReportController@getBudgetSummary')->middleware('checkAuth:report/budgetsummary');

        Route::get('/pbj',                      'Reports\ReportsController@pbj')->middleware('checkAuth:report/pbj');
        Route::get('/pbjlist',                  'Reports\ReportsController@pbjList')->middleware('checkAuth:report/pbj');
        Route::post('/exportpbj',               'ExportDataController@exportPBJ')->middleware('checkAuth:report/pbj');

        Route::get('/po',                       'Reports\ReportsController@po')->middleware('checkAuth:report/po');
        Route::get('/polist',                   'Reports\ReportsController@poList')->middleware('checkAuth:report/po');
        Route::post('/exportpo',                'ExportDataController@exportPO')->middleware('checkAuth:report/pr');

        Route::get('/pr',                       'Reports\ReportsController@pr')->middleware('checkAuth:report/pr');
        Route::get('/prlist',                   'Reports\ReportsController@prList')->middleware('checkAuth:report/pr');
        Route::post('/exportpr',                'ExportDataController@exportPR')->middleware('checkAuth:report/pr');

        Route::get('/wo',                       'Reports\ReportsController@wo')->middleware('checkAuth:report/wo');
        Route::get('/wolist',                   'Reports\ReportsController@woList')->middleware('checkAuth:report/wo');
        Route::post('/exportwo',                'ExportDataController@exportWO')->middleware('checkAuth:report/wo');

        Route::get('/grpo',                     'Reports\ReportsController@grpo')->middleware('checkAuth:report/grpo');
        Route::get('/grpolist',                 'Reports\ReportsController@grpoList')->middleware('checkAuth:report/grpo');

        Route::get('/issue',                    'Reports\ReportsController@issue')->middleware('checkAuth:report/issue');
        Route::get('/issuelist',                'Reports\ReportsController@issueList')->middleware('checkAuth:report/issue');
        Route::post('/exportissued',            'ExportDataController@exportIssued')->middleware('checkAuth:report/issue');

        Route::get('/stock',                    'Reports\ReportsController@stock')->middleware('checkAuth:report/stock');
        Route::get('/stocklist',                'Reports\ReportsController@stockList')->middleware('checkAuth:report/stock');
        Route::post('/exportstock',             'ExportDataController@exportStock')->middleware('checkAuth:report/stock');

        Route::get('/batchstock',               'Reports\ReportsController@batchStock')->middleware('checkAuth:report/batchstock');
        Route::get('/batchstocklist',           'Reports\ReportsController@batchStockList')->middleware('checkAuth:report/batchstock');
        Route::post('/exportbatchstock',        'ExportDataController@exportBatchStock')->middleware('checkAuth:report/batchstock');

        Route::get('/cost',                     'Reports\ReportsController@cost')->middleware('checkAuth:report/cost');
        Route::get('/costlist',                 'Reports\ReportsController@costList')->middleware('checkAuth:report/cost');
        Route::post('/exportcost',              'ExportDataController@exportCost')->middleware('checkAuth:report/cost');
    });
});