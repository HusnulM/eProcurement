<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => '/transaction/budgeting'], function () {
        Route::get('/',             'Transaksi\BudgetingController@index')->middleware('checkAuth:transaction/budgeting');
        Route::post('/save',        'Transaksi\BudgetingController@save')->middleware('checkAuth:transaction/budgeting');
        Route::get('/list',         'Transaksi\BudgetingController@list')->middleware('checkAuth:transaction/budgeting');  
        Route::get('/budgetlist',   'Transaksi\BudgetingController@budgetLists')->middleware('checkAuth:transaction/budgeting');  
        
    });

    Route::group(['prefix' => '/transaction/pbj'], function () {
        Route::get('/',             'Transaksi\PbjController@index')->middleware('checkAuth:transaction/pbj');
        Route::post('/save',        'Transaksi\PbjController@save')->middleware('checkAuth:transaction/pbj');
        Route::get('/list',         'Transaksi\PbjController@list')->middleware('checkAuth:transaction/pbj');  
        Route::get('/budgetlist',   'Transaksi\PbjController@budgetLists')->middleware('checkAuth:transaction/pbj');  
        
    });

    Route::group(['prefix' => '/approve/budget'], function () {
        Route::get('/',              'Transaksi\ApproveBudgetController@index')->middleware('checkAuth:approve/budget');
        Route::post('/save',         'Transaksi\ApproveBudgetController@save')->middleware('checkAuth:approve/budget');
        Route::get('/approvelist',   'Transaksi\ApproveBudgetController@budgetApprovalList')->middleware('checkAuth:approve/budget');
    });

    Route::group(['prefix' => '/approve/pbj'], function () {
        Route::get('/',                         'Transaksi\ApprovePbjController@index')->middleware('checkAuth:approve/pbj');
        Route::post('/save',                    'Transaksi\ApprovePbjController@save')->middleware('checkAuth:approve/pbj');
        Route::get('/approvelist',              'Transaksi\ApprovePbjController@pbjApprovalList')->middleware('checkAuth:approve/pbj');
        Route::get('/detail/{p1}',              'Transaksi\ApprovePbjController@approveDetail')->middleware('checkAuth:approve/pbj');
    });

    Route::group(['prefix' => '/proc/pr'], function () {
        Route::get('/',             'Transaksi\PurchaseRequestController@index')->middleware('checkAuth:proc/pr');
        Route::post('/save',        'Transaksi\PurchaseRequestController@save')->middleware('checkAuth:proc/pr');
        Route::get('/list',         'Transaksi\PurchaseRequestController@list')->middleware('checkAuth:proc/pr');  
        // Route::get('/budgetlist',   'Transaksi\PurchaseRequestController@budgetLists')->middleware('checkAuth:proc/pr');  
        
    });

    Route::group(['prefix' => '/approve/pr'], function () {
        Route::get('/',                         'Transaksi\ApprovePurchaseRequestController@index')->middleware('checkAuth:approve/pr');
        Route::post('/save',                    'Transaksi\ApprovePurchaseRequestController@save')->middleware('checkAuth:approve/pr');
        Route::get('/approvelist',              'Transaksi\ApprovePurchaseRequestController@ApprovalList')->middleware('checkAuth:approve/pr');
        Route::get('/detail/{p1}',              'Transaksi\ApprovePurchaseRequestController@approveDetail')->middleware('checkAuth:approve/pr');
    });

    Route::group(['prefix' => '/proc/po'], function () {
        Route::get('/',             'Transaksi\PurchaseOrderController@index')->middleware('checkAuth:proc/po');
        Route::post('/save',        'Transaksi\PurchaseOrderController@save')->middleware('checkAuth:proc/po');
        Route::get('/list',         'Transaksi\PurchaseOrderController@list')->middleware('checkAuth:proc/po');  
        // Route::get('/budgetlist',   'Transaksi\PurchaseRequestController@budgetLists')->middleware('checkAuth:proc/pr');  
        
    });
});