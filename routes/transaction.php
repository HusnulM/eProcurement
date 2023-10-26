<?php
use Illuminate\Support\Facades\Route;

Route::get('/notifduedatepbj',      'EmailNotifController@notifDueDatePBJ');
Route::get('/notifduedatepr',       'EmailNotifController@notifDueDatePR');
Route::get('/notifduedatepo',       'EmailNotifController@notifDueDatePO');

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

    Route::group(['prefix' => '/cancel/approve'], function () {

    });

    Route::group(['prefix' => '/transaction/pbj'], function () {
        Route::get('/',                        'Transaksi\PbjController@index')->middleware('checkAuth:transaction/pbj');
        Route::post('/save',                   'Transaksi\PbjController@save');//->middleware('checkAuth:transaction/pbj');
        Route::get('/list',                    'Transaksi\PbjController@list')->middleware('checkAuth:transaction/pbj/list');
        Route::get('/listpbj',                 'Transaksi\PbjController@listPBJ');//->middleware('checkAuth:transaction/pbj');
        Route::get('/detail/{p1}',             'Transaksi\PbjController@detailPBJ');//->middleware('checkAuth:transaction/pbj');
        Route::get('/budgetlist',              'Transaksi\PbjController@budgetLists');//->middleware('checkAuth:transaction/pbj');
        Route::get('/print/{p1}',              'Transaksi\PrintDocumentController@printpbj');//->middleware('checkAuth:transaction/pbj');
        Route::get('/create/{p1}',             'Transaksi\PbjController@create');//->middleware('checkAuth:transaction/pbj');
        Route::get('/datachecklisttidaklayak', 'Transaksi\PbjController@dataCekListTidakLayak');//->middleware('checkAuth:transaction/pbj');
        Route::get('/tidaklayak/detail/{p1}',  'Transaksi\ChecklistKendaraanController@detailCekListTidakLayak');//->middleware('checkAuth:transaction/pbj');

        Route::get('/change/{p1}',      'Transaksi\PbjController@changePBJ');//->middleware('checkAuth:transaction/pbj');

        Route::get('/listopenwo', 'Transaksi\PbjController@listOpenWO');//->middleware('checkAuth:transaction/pbj');

        Route::get('/wo/detail/{p1}',      'Transaksi\PbjController@detailWO');//->middleware('checkAuth:transaction/pbj');

    });

    Route::post('/transaction/pbj/deleteitem',          'Transaksi\PbjController@deletePBJItem');
    Route::post('/transaction/pbj/udpate',              'Transaksi\PbjController@update');

    Route::group(['prefix' => '/transaction/pbjtanpawo'], function () {
        Route::get('/',          'Transaksi\PbjController@createWithoueWO');
        //->middleware('checkAuth:transaction/pbj');
    });

    Route::get('/pbj/duedatepbj',      'Transaksi\PbjController@duedatepbj')->middleware('checkAuth:pbj/duedatepbj');
    Route::get('/pbj/duedatepbjlist',  'Transaksi\PbjController@listDuedatePBJ')->middleware('checkAuth:pbj/duedatepbj');


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
        Route::post('/update/{p1}',    'Transaksi\PurchaseRequestController@update')->middleware('checkAuth:proc/pr');

        Route::get('/listpr',          'Transaksi\PurchaseRequestController@listPR')->middleware('checkAuth:proc/pr');
        Route::get('/printlist',       'Transaksi\PrintDocumentController@printprlist')->middleware('checkAuth:proc/pr');
        Route::get('/print/{p1}',      'Transaksi\PrintDocumentController@printpr')->middleware('checkAuth:proc/pr');
        Route::get('/listapprovedpbj', 'Transaksi\PurchaseRequestController@listApprovedPbj')->middleware('checkAuth:proc/pr');
        Route::get('/detail/{p1}',     'Transaksi\PrintDocumentController@prdetail')->middleware('checkAuth:proc/pr');

        Route::get('/change/{p1}',      'Transaksi\PurchaseRequestController@changePR')->middleware('checkAuth:proc/pr');
        Route::post('/deleteitem',      'Transaksi\PurchaseRequestController@deletePRItem')->middleware('checkAuth:proc/pr');
        Route::get('/delete/{p1}',      'Transaksi\PurchaseRequestController@deletePR')->middleware('checkAuth:proc/pr');

        Route::get('/duedatepr',        'Transaksi\PurchaseRequestController@duedate')->middleware('checkAuth:proc/pr/duedatepr');
        Route::get('/duedateprlist',    'Transaksi\PurchaseRequestController@listDuedatePR')->middleware('checkAuth:proc/pr/duedatepr');
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
        Route::get('/detail/{p1}',    'Transaksi\PrintDocumentController@podetail')->middleware('checkAuth:proc/po');
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

    Route::group(['prefix' => '/proc/submitpo'], function () {
        Route::get('/',               'Transaksi\SubmitPurchaseOrderController@index')->middleware('checkAuth:proc/submitpo');
        Route::get('/polist',         'Transaksi\SubmitPurchaseOrderController@approvedPOList')->middleware('checkAuth:proc/submitpo');
        Route::get('/submittedpolist','Transaksi\SubmitPurchaseOrderController@submittedPO')->middleware('checkAuth:proc/submitpo');
        Route::post('/getitems',      'Transaksi\SubmitPurchaseOrderController@getPoItems')->middleware('checkAuth:proc/submitpo');
        Route::post('/submitdata',    'Transaksi\SubmitPurchaseOrderController@submitDatatoApi')->middleware('checkAuth:proc/submitpo');
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

    Route::group(['prefix' => '/approve/spk'], function () {
        Route::get('/',                         'Transaksi\ApproveSpkController@index')->middleware('checkAuth:approve/spk');
        Route::post('/save',                    'Transaksi\ApproveSpkController@save')->middleware('checkAuth:approve/spk');
        Route::get('/approvelist',              'Transaksi\ApproveSpkController@ApprovalList')->middleware('checkAuth:approve/spk');
        Route::get('/detail/{p1}',              'Transaksi\ApproveSpkController@approveDetail')->middleware('checkAuth:approve/spk');

        Route::post('/approveitems/{p1}',       'Transaksi\ApproveSpkController@approveItems')->middleware('checkAuth:approve/spk');
        Route::post('/rejectitems/{p1}',       'Transaksi\ApproveSpkController@rejectItems')->middleware('checkAuth:approve/spk');
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
        Route::get('/create/{p1}',     'Transaksi\SpkController@create')->middleware('checkAuth:logistic/wo');
        Route::get('/detailchecklist/{p1}', 'Transaksi\SpkController@detailChecklist')->middleware('checkAuth:logistic/wo');
        Route::get('/process',         'Transaksi\SpkController@processWO')->middleware('checkAuth:logistic/wo');
        Route::get('/listwo',          'Transaksi\SpkController@listwoview')->middleware('checkAuth:logistic/wo');
        Route::get('/listdatawo',      'Transaksi\SpkController@listdatawo'); //->middleware('checkAuth:logistic/wo');
        Route::get('/detail/{p1}',     'Transaksi\SpkController@wodetail')->middleware('checkAuth:logistic/wo');
        Route::get('/print/{p1}',      'Transaksi\PrintDocumentController@printwo')->middleware('checkAuth:logistic/wo');
        Route::post('/save',           'Transaksi\SpkController@save')->middleware('checkAuth:logistic/wo');

        // Route::get('/print/{p1}',   'Transaksi\PrintDocumentController@printwo')->middleware('checkAuth:printdoc/wo');

        Route::get('/datachecklisttidaklayak', 'Transaksi\SpkController@dataCekListTidakLayak')->middleware('checkAuth:logistic/wo');
        Route::post('/findkendaraan',   'Transaksi\SpkController@findkendaraan'); //->middleware('checkAuth:logistic/wo');
        Route::get('/listdatawotoprocess',      'Transaksi\SpkController@listdatawotoprocess')->middleware('checkAuth:logistic/wo');
        Route::get('/listapprovedpbj', 'Transaksi\SpkController@listApprovedPbj')->middleware('checkAuth:logistic/wo');
        Route::post('/saveprocess',    'Transaksi\SpkController@saveWoProcess')->middleware('checkAuth:logistic/wo');

        Route::get('/change/{p1}',      'Transaksi\SpkController@changeWO')->middleware('checkAuth:logistic/wo');
        Route::post('/update/{p1}',     'Transaksi\SpkController@update')->middleware('checkAuth:logistic/wo');
        Route::post('/deleteitem',      'Transaksi\SpkController@deleteWOItem')->middleware('checkAuth:logistic/wo');
        Route::get('/delete/{p1}',      'Transaksi\SpkController@deleteWO')->middleware('checkAuth:logistic/wo');


    });
    // logistic/terimapo

    Route::group(['prefix' => '/logistic/bast'], function () {
        Route::get('/',                 'Transaksi\BastController@index')->middleware('checkAuth:logistic/bast');
        Route::get('/create/{p}',       'Transaksi\BastController@create')->middleware('checkAuth:logistic/bast');
        Route::post('/save',            'Transaksi\BastController@save')->middleware('checkAuth:logistic/bast');
        Route::get('/listbast',         'Transaksi\BastController@viewListBast')->middleware('checkAuth:logistic/bast');
        Route::get('/databast',         'Transaksi\BastController@dataBast')->middleware('checkAuth:logistic/bast');
        Route::post('/finduser',        'Transaksi\BastController@findUser');

        Route::get('/listdatabast',     'Transaksi\BastController@listDataBast')->middleware('checkAuth:logistic/bast');
        Route::get('/detail/{p}',       'Transaksi\BastController@detailBAST')->middleware('checkAuth:logistic/bast');
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

});
