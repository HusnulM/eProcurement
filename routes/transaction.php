<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => '/transaction/budgeting'], function () {
        Route::get('/',             'Transaksi\BudgetingController@index')->middleware('checkAuth:transaction/budgeting');
        Route::post('/save',        'Transaksi\BudgetingController@save')->middleware('checkAuth:transaction/budgeting');
        Route::get('/list',         'Transaksi\BudgetingController@list')->middleware('checkAuth:transaction/budgeting');  
        Route::get('/budgetlist',   'Transaksi\BudgetingController@budgetLists')->middleware('checkAuth:transaction/budgeting');  
        
    });

    Route::group(['prefix' => '/approve/budget'], function () {
        Route::get('/',              'Transaksi\ApproveBudgetController@index')->middleware('checkAuth:approve/budget');
        Route::post('/save',         'Transaksi\ApproveBudgetController@save')->middleware('checkAuth:approve/budget');
        Route::get('/approvelist',   'Transaksi\ApproveBudgetController@budgetApprovalList')->middleware('checkAuth:approve/budget');
    });
});