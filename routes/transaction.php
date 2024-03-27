<?php
use Illuminate\Support\Facades\Route;

Route::get('/notifduedatepbj',      'EmailNotifController@notifDueDatePBJ');
Route::get('/notifduedatepr',       'EmailNotifController@notifDueDatePR');
Route::get('/notifduedatepo',       'EmailNotifController@notifDueDatePO');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/allmaterial',          'Transaksi\GeneralController@allMaterial');
    Route::get('/matstock',             'Transaksi\GeneralController@matstockAll');
    Route::get('/matstockbywhs/{p1}',   'Transaksi\GeneralController@matstockByWhs');
    Route::get('/summatstockbywhs/{p1}','Transaksi\GeneralController@summaryStokByWhs');


    Route::group(['prefix' => '/transaction/budgeting'], function () {
        Route::get('/',             'Transaksi\BudgetingController@index')->middleware('checkAuth:transaction/budgeting');
        Route::post('/save',        'Transaksi\BudgetingController@save')->middleware('checkAuth:transaction/budgeting');
        Route::get('/list',         'Transaksi\BudgetingController@list')->middleware('checkAuth:transaction/budgeting');
        Route::get('/budgetlist',   'Transaksi\BudgetingController@budgetLists')->middleware('checkAuth:transaction/budgeting');
    });

    Route::group(['prefix' => '/quotation'], function () {
        Route::get('/',             'Transaksi\QuotationController@index')->middleware('checkAuth:quotation');
        Route::post('/save',        'Transaksi\BudgetingController@save')->middleware('checkAuth:quotation');
        Route::get('/list',         'Transaksi\BudgetingController@list')->middleware('checkAuth:quotation');
        Route::get('/budgetlist',   'Transaksi\BudgetingController@budgetLists')->middleware('checkAuth:quotation');
    });

    Route::group(['prefix' => '/proc/pr'], function () {
        Route::get('/',                'Transaksi\PurchaseRequestController@index')->middleware('checkAuth:proc/pr');
        Route::post('/save',           'Transaksi\PurchaseRequestController@save')->middleware('checkAuth:proc/pr');
        Route::post('/update/{p1}',    'Transaksi\PurchaseRequestController@update')->middleware('checkAuth:proc/pr');

        Route::get('/list',            'Transaksi\PurchaseRequestController@listPR')->middleware('checkAuth:proc/pr');
        Route::get('/printlist',       'Transaksi\PurchaseRequestController@getListPR')->middleware('checkAuth:proc/pr');
        Route::get('/print/{p1}',      'Transaksi\PrintDocumentController@printpr')->middleware('checkAuth:proc/pr');

        Route::get('/list/detail/{p1}',     'Transaksi\PurchaseRequestController@prdetail')->middleware('checkAuth:proc/pr');

        Route::get('/list/change/{p1}',      'Transaksi\PurchaseRequestController@changePR')->middleware('checkAuth:proc/pr');
        Route::post('/deleteitem',      'Transaksi\PurchaseRequestController@deletePRItem')->middleware('checkAuth:proc/pr');
        Route::get('/delete/{p1}',      'Transaksi\PurchaseRequestController@deletePR')->middleware('checkAuth:proc/pr');

        Route::get('/duedatepr',        'Transaksi\PurchaseRequestController@duedate')->middleware('checkAuth:proc/pr/duedatepr');
        Route::get('/duedateprlist',    'Transaksi\PurchaseRequestController@listDuedatePR')->middleware('checkAuth:proc/pr/duedatepr');

        Route::get('/approvedprintlist',      'Transaksi\ChangeApprovedPrController@approvedList')->middleware('checkAuth:proc/pr/changeapprovedpr');
        Route::get('/changeapprovedpr',       'Transaksi\ChangeApprovedPrController@index')->middleware('checkAuth:proc/pr/changeapprovedpr');
        Route::get('/changeapprovedpr/{p1}',  'Transaksi\ChangeApprovedPrController@change')->middleware('checkAuth:proc/pr/changeapprovedpr');
        Route::post('/savechangeapprovedpr',  'Transaksi\ChangeApprovedPrController@update')->middleware('checkAuth:proc/pr/changeapprovedpr');
    });

    Route::group(['prefix' => '/approve/pr'], function () {
        Route::get('/',                         'Transaksi\ApprovePurchaseRequestController@index')->middleware('checkAuth:approve/pr');
        Route::post('/save',                    'Transaksi\ApprovePurchaseRequestController@save')->middleware('checkAuth:approve/pr');
        Route::get('/approvelist',              'Transaksi\ApprovePurchaseRequestController@ApprovalList')->middleware('checkAuth:approve/pr');
        Route::get('/detail/{p1}',              'Transaksi\ApprovePurchaseRequestController@approveDetail')->middleware('checkAuth:approve/pr');

        Route::post('/approveitems',            'Transaksi\ApprovePurchaseRequestController@approveItems')->middleware('checkAuth:approve/pr');
    });

    Route::group(['prefix' => '/proc/po'], function () {
        Route::get('/',               'Transaksi\PurchaseOrderController@index')->middleware('checkAuth:proc/po');
        Route::post('/save',          'Transaksi\PurchaseOrderController@save')->middleware('checkAuth:proc/po');
        Route::get('/listpo',         'Transaksi\PurchaseOrderController@listPO')->middleware('checkAuth:proc/po');
        // Route::get('/budgetlist',   'Transaksi\PurchaseRequestController@budgetLists')->middleware('checkAuth:proc/pr');

        Route::get('/print/{p1}',     'Transaksi\PrintDocumentController@printpo')->middleware('checkAuth:proc/po');

        Route::get('/printlist',      'Transaksi\PrintDocumentController@printpolist')->middleware('checkAuth:proc/po');

        Route::get('/listapprovedpr', 'Transaksi\PurchaseOrderController@getApprovedPR')->middleware('checkAuth:proc/po');

        Route::get('/change/{p1}',      'Transaksi\PurchaseOrderController@changePO')->middleware('checkAuth:proc/po');
        Route::post('/update/{p1}',     'Transaksi\PurchaseOrderController@update')->middleware('checkAuth:proc/po');
        Route::post('/deleteitem',      'Transaksi\PurchaseOrderController@deletePOItem')->middleware('checkAuth:proc/po');
        Route::get('/delete/{p1}',      'Transaksi\PurchaseOrderController@deletePO')->middleware('checkAuth:proc/po');

        Route::get('/deleteattachment/{p1}/{p2}',      'Transaksi\PurchaseOrderController@deleteAttachment')->middleware('checkAuth:proc/po');

        Route::get('/duedatepo',        'Transaksi\PurchaseOrderController@duedate')->middleware('checkAuth:proc/po/duedatepo');
        Route::get('/duedatepolist',    'Transaksi\PurchaseOrderController@listDuedatePO')->middleware('checkAuth:proc/po/duedatepo');
    });

    Route::group(['prefix' => '/po/list'], function () {
        Route::get('/',             'Transaksi\PurchaseOrderController@listPO')->middleware('checkAuth:po/list');
        Route::get('/get',          'Transaksi\PurchaseOrderController@getListPO')->middleware('checkAuth:po/list');
        Route::get('/detail/{p1}',  'Transaksi\PurchaseOrderController@poDetail')->middleware('checkAuth:po/list');
    });

    Route::group(['prefix' => '/approve/po'], function () {
        Route::get('/',                         'Transaksi\ApprovePurchaseOrderController@index')->middleware('checkAuth:approve/po');
        Route::post('/save',                    'Transaksi\ApprovePurchaseOrderController@save')->middleware('checkAuth:approve/po');
        Route::get('/approvelist',              'Transaksi\ApprovePurchaseOrderController@ApprovalList')->middleware('checkAuth:approve/po');
        Route::get('/detail/{p1}',              'Transaksi\ApprovePurchaseOrderController@approveDetail')->middleware('checkAuth:approve/po');

        Route::post('/approveitems',            'Transaksi\ApprovePurchaseOrderController@approveItems')->middleware('checkAuth:approve/po');
    });

    Route::get('/regeneratepoapproval',               'Transaksi\ApprovePurchaseOrderController@reGenerateApproval');
    Route::get('/regenerateprapproval',               'Transaksi\ApprovePurchaseRequestController@reGenerateApproval');
    Route::get('/regenerateprapproval2',               'Transaksi\ApprovePurchaseRequestController@reGenerateOldApproval');

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

    Route::group(['prefix' => '/cancel/approve'], function () {
        Route::get('/wo',              'Transaksi\CancelApprovalController@index');//->middleware('checkAuth:cancel/approve/wo');
        Route::post('/wo/reset/{p1}',  'Transaksi\CancelApprovalController@resetApproveWO');//->middleware('checkAuth:cancel/approve/wo');
        Route::post('/wo/delete/{p1}', 'Transaksi\CancelApprovalController@deleteWO');//->middleware('checkAuth:cancel/approve/wo');

        Route::get('/pbj',              'Transaksi\CancelApprovePbjController@index');//->middleware('checkAuth:cancel/approve/pbj');
        Route::post('/pbj/reset/{p1}',  'Transaksi\CancelApprovePbjController@resetApprovePBJ');//->middleware('checkAuth:cancel/approve/pbj');
        Route::post('/pbj/delete/{p1}', 'Transaksi\CancelApprovePbjController@deletePBJ');//->middleware('checkAuth:cancel/approve/pbj');

        Route::get('/pr',              'Transaksi\CancelApprovePrController@index');//->middleware('checkAuth:cancel/approve/pr');
        Route::post('/pr/reset/{p1}',  'Transaksi\CancelApprovePrController@resetApprovePR');//->middleware('checkAuth:cancel/approve/pr');
        Route::post('/pr/delete/{p1}', 'Transaksi\CancelApprovePrController@deletePR');//->middleware('checkAuth:cancel/approve/pr');

        Route::get('/po',              'Transaksi\CancelApprovePoController@index');//->middleware('checkAuth:cancel/approve/po');
        Route::post('/po/reset/{p1}',  'Transaksi\CancelApprovePoController@resetApprovePO');//->middleware('checkAuth:cancel/approve/po');
        Route::post('/po/delete/{p1}', 'Transaksi\CancelApprovePoController@deletePO');//->middleware('checkAuth:cancel/approve/po');

        Route::get('/wo/approvedlist',  'Transaksi\CancelApprovalController@listApprovedWO');//->middleware('checkAuth:cancel/approve/wo');
        Route::get('/pbj/list',         'Transaksi\CancelApprovePbjController@listPBJ');//->middleware('checkAuth:cancel/approve/pbj');
        Route::get('/pr/list',          'Transaksi\CancelApprovePrController@listPR');//->middleware('checkAuth:cancel/approve/pr');
        Route::get('/po/list',          'Transaksi\CancelApprovePoController@listPO');//->middleware('checkAuth:cancel/approve/po');
    });

    Route::group(['prefix' => '/logistic/transfer'], function () {
        Route::get('/',                 'Transaksi\InventoryMovementController@index')->middleware('checkAuth:logistic/transfer');
        Route::post('/save',            'Transaksi\InventoryMovementController@save')->middleware('checkAuth:logistic/transfer');
        Route::get('/listmaterial',     'Transaksi\InventoryMovementController@getApprovedPO')->middleware('checkAuth:logistic/transfer');
        Route::get('/listgudang',      'Transaksi\InventoryMovementController@getApprovedPO')->middleware('checkAuth:logistic/transfer');
    });

});
