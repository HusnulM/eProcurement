<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {

    Route::get('/allmaterial',          'Transaksi\GeneralController@allMaterial');
    Route::get('/matstock',             'Transaksi\GeneralController@matstockAll');
    Route::get('/matstockbywhs/{p1}',   'Transaksi\GeneralController@matstockByWhs');

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
        Route::get('/listpbj',      'Transaksi\PbjController@listPBJ')->middleware('checkAuth:transaction/pbj');  
        Route::get('/detail/{p1}',  'Transaksi\PbjController@detailPBJ')->middleware('checkAuth:transaction/pbj');  
        Route::get('/budgetlist',   'Transaksi\PbjController@budgetLists')->middleware('checkAuth:transaction/pbj');  
        
        Route::get('/create/{p1}',             'Transaksi\PbjController@create')->middleware('checkAuth:transaction/pbj');
        Route::get('/datachecklisttidaklayak', 'Transaksi\PbjController@dataCekListTidakLayak')->middleware('checkAuth:transaction/pbj');
        Route::get('/tidaklayak/detail/{p1}',   'Transaksi\ChecklistKendaraanController@detailCekListTidakLayak')->middleware('checkAuth:transaction/pbj');
    });

    Route::group(['prefix' => '/datachecklistkendaraan'], function () {
        Route::get('/',                 'Transaksi\ChecklistKendaraanController@ViewdataCekList')->middleware('checkAuth:datachecklistkendaraan');
        Route::get('/detail/{p1}',      'Transaksi\ChecklistKendaraanController@detailCekList')->middleware('checkAuth:datachecklistkendaraan');
        Route::get('/tidaklayak',       'Transaksi\ChecklistKendaraanController@ViewdataCekListTidakLayak')->middleware('checkAuth:datachecklistkendaraan');
        Route::get('/tidaklayak/detail/{p1}',       'Transaksi\ChecklistKendaraanController@detailCekListTidakLayak')->middleware('checkAuth:datachecklistkendaraan');
        Route::get('/datachecklist',    'Transaksi\ChecklistKendaraanController@dataCekList')->middleware('checkAuth:datachecklistkendaraan');
        Route::get('/datachecklisttidaklayak', 'Transaksi\ChecklistKendaraanController@dataCekListTidakLayak')->middleware('checkAuth:datachecklistkendaraan');
        Route::post('/export',                'ExportDataController@exportCeklistAll')->middleware('checkAuth:datachecklistkendaraan');
        // Route::get('/detail/{p1}',              'Transaksi\ApprovePbjController@approveDetail')->middleware('checkAuth:approve/pbj');
    });

    Route::group(['prefix' => '/approve/budget'], function () {
        Route::get('/',              'Transaksi\ApproveBudgetController@index')->middleware('checkAuth:approve/budget');
        Route::post('/save',         'Transaksi\ApproveBudgetController@save')->middleware('checkAuth:approve/budget');
        Route::get('/approvelist',   'Transaksi\ApproveBudgetController@budgetApprovalList')->middleware('checkAuth:approve/budget');
    });

    // transaksi/checklistken
    Route::group(['prefix' => '/checklistkendaraan'], function () {
        Route::get('/',                         'Transaksi\ChecklistKendaraanController@index')->middleware('checkAuth:checklistkendaraan');
        Route::post('/save',                    'Transaksi\ChecklistKendaraanController@save')->middleware('checkAuth:checklistkendaraan');
        Route::get('/datachecklistkendaraan',   'Transaksi\ChecklistKendaraanController@ViewdataCekList')->middleware('checkAuth:checklistkendaraan');
        Route::get('/datachecklist',            'Transaksi\ChecklistKendaraanController@dataCekList')->middleware('checkAuth:checklistkendaraan');
        // Route::get('/detail/{p1}',              'Transaksi\ApprovePbjController@approveDetail')->middleware('checkAuth:approve/pbj');
    });

    

    // datachecklisttidaklayak

    Route::group(['prefix' => '/approve/pbj'], function () {
        Route::get('/',                         'Transaksi\ApprovePbjController@index')->middleware('checkAuth:approve/pbj');
        Route::post('/save',                    'Transaksi\ApprovePbjController@save')->middleware('checkAuth:approve/pbj');
        Route::get('/approvelist',              'Transaksi\ApprovePbjController@pbjApprovalList')->middleware('checkAuth:approve/pbj');
        Route::get('/detail/{p1}',              'Transaksi\ApprovePbjController@approveDetail')->middleware('checkAuth:approve/pbj');

        Route::post('/approveitems/{p1}',       'Transaksi\ApprovePbjController@approveItems')->middleware('checkAuth:approve/pbj');
    });

    Route::group(['prefix' => '/proc/pr'], function () {
        Route::get('/',                'Transaksi\PurchaseRequestController@index')->middleware('checkAuth:proc/pr');
        Route::post('/save',           'Transaksi\PurchaseRequestController@save')->middleware('checkAuth:proc/pr');
        Route::get('/listpr',          'Transaksi\PurchaseRequestController@listPR')->middleware('checkAuth:proc/pr');  

        Route::get('/print/{p1}',      'Transaksi\PurchaseRequestController@printpr')->middleware('checkAuth:proc/pr'); 
        Route::get('/listapprovedpbj', 'Transaksi\PurchaseRequestController@listApprovedPbj')->middleware('checkAuth:proc/pr');
        // Route::get('/budgetlist',   'Transaksi\PurchaseRequestController@budgetLists')->middleware('checkAuth:proc/pr');  
        
    });

    Route::group(['prefix' => '/approve/pr'], function () {
        Route::get('/',                         'Transaksi\ApprovePurchaseRequestController@index')->middleware('checkAuth:approve/pr');
        Route::post('/save',                    'Transaksi\ApprovePurchaseRequestController@save')->middleware('checkAuth:approve/pr');
        Route::get('/approvelist',              'Transaksi\ApprovePurchaseRequestController@ApprovalList')->middleware('checkAuth:approve/pr');
        Route::get('/detail/{p1}',              'Transaksi\ApprovePurchaseRequestController@approveDetail')->middleware('checkAuth:approve/pr');
    });

    Route::group(['prefix' => '/proc/po'], function () {
        Route::get('/',               'Transaksi\PurchaseOrderController@index')->middleware('checkAuth:proc/po');
        Route::post('/save',          'Transaksi\PurchaseOrderController@save')->middleware('checkAuth:proc/po');
        Route::get('/listpo',         'Transaksi\PurchaseOrderController@listPO')->middleware('checkAuth:proc/po');  
        // Route::get('/budgetlist',   'Transaksi\PurchaseRequestController@budgetLists')->middleware('checkAuth:proc/pr');  

        Route::get('/print/{p1}',     'Transaksi\PrintDocumentController@printpo')->middleware('checkAuth:printdoc/po');   
        Route::get('/detail/{p1}',    'Transaksi\PrintDocumentController@podetail')->middleware('checkAuth:printdoc/po');   
        Route::get('/printlist',      'Transaksi\PrintDocumentController@printpolist')->middleware('checkAuth:printdoc/po'); 

        Route::get('/listapprovedpr', 'Transaksi\PurchaseOrderController@getApprovedPR')->middleware('checkAuth:proc/po');
        
    });

    Route::group(['prefix' => '/approve/po'], function () {
        Route::get('/',                         'Transaksi\ApprovePurchaseOrderController@index')->middleware('checkAuth:approve/po');
        Route::post('/save',                    'Transaksi\ApprovePurchaseOrderController@save')->middleware('checkAuth:approve/po');
        Route::get('/approvelist',              'Transaksi\ApprovePurchaseOrderController@ApprovalList')->middleware('checkAuth:approve/po');
        Route::get('/detail/{p1}',              'Transaksi\ApprovePurchaseOrderController@approveDetail')->middleware('checkAuth:approve/po');
    });

    Route::group(['prefix' => '/approve/spk'], function () {
        Route::get('/',                         'Transaksi\ApproveSpkController@index')->middleware('checkAuth:approve/spk');
        Route::post('/save',                    'Transaksi\ApproveSpkController@save')->middleware('checkAuth:approve/spk');
        Route::get('/approvelist',              'Transaksi\ApproveSpkController@ApprovalList')->middleware('checkAuth:approve/spk');
        Route::get('/detail/{p1}',              'Transaksi\ApproveSpkController@approveDetail')->middleware('checkAuth:approve/spk');

        Route::post('/approveitems/{p1}',       'Transaksi\ApproveSpkController@approveItems')->middleware('checkAuth:approve/spk');
    });

    Route::group(['prefix' => '/logistic/terimapo'], function () {
        Route::get('/',                 'Transaksi\ReceiptPoController@index')->middleware('checkAuth:logistic/terimapo');
        Route::post('/save',            'Transaksi\ReceiptPoController@save')->middleware('checkAuth:logistic/terimapo');
        Route::get('/listapprovedpo',   'Transaksi\ReceiptPoController@getApprovedPO')->middleware('checkAuth:logistic/terimapo');
    });

    Route::group(['prefix' => '/logistic/pengeluaran'], function () {
        Route::get('/',                 'Transaksi\IssueMaterialController@index')->middleware('checkAuth:logistic/pengeluaran');
        Route::post('/save',            'Transaksi\IssueMaterialController@save')->middleware('checkAuth:logistic/pengeluaran');
        Route::get('/listapprovedpo',   'Transaksi\IssueMaterialController@getApprovedPO')->middleware('checkAuth:logistic/pengeluaran');
    });

    Route::group(['prefix' => '/logistic/wo'], function () {
        Route::get('/',                'Transaksi\SpkController@index')->middleware('checkAuth:logistic/wo');
        Route::get('/process',         'Transaksi\SpkController@processWO')->middleware('checkAuth:logistic/wo');
        Route::get('/listwo',          'Transaksi\SpkController@listwoview')->middleware('checkAuth:logistic/wo');
        Route::get('/listdatawo',      'Transaksi\SpkController@listdatawo')->middleware('checkAuth:logistic/wo');
        Route::get('/detail/{p1}',     'Transaksi\SpkController@wodetail')->middleware('checkAuth:logistic/wo');   
        Route::get('/print/{p1}',      'Transaksi\SpkController@printprlist')->middleware('checkAuth:logistic/wo');  
        Route::post('/save',           'Transaksi\SpkController@save')->middleware('checkAuth:logistic/wo');

        Route::post('/findkendaraan',   'Transaksi\SpkController@findkendaraan')->middleware('checkAuth:logistic/wo');
        Route::get('/listdatawotoprocess',      'Transaksi\SpkController@listdatawotoprocess')->middleware('checkAuth:logistic/wo');
        Route::get('/listapprovedpbj', 'Transaksi\SpkController@listApprovedPbj')->middleware('checkAuth:logistic/wo');
        Route::post('/saveprocess',    'Transaksi\SpkController@saveWoProcess')->middleware('checkAuth:logistic/wo');
    });
    // logistic/terimapo
});