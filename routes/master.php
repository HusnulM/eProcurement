<?php
use Illuminate\Support\Facades\Route;

Route::get('/coba', function () {
    echo "Coba";
});

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => '/master/item'], function () {
        Route::get('/',             'Master\ItemMasterController@index')->middleware('checkAuth:master/item');
        Route::get('/create',       'Master\ItemMasterController@create')->middleware('checkAuth:master/item');
        Route::get('/edit/{p1}',    'Master\ItemMasterController@edit')->middleware('checkAuth:master/item');
        Route::post('/save',        'Master\ItemMasterController@save')->middleware('checkAuth:master/item');
        Route::post('/update',      'Master\ItemMasterController@update')->middleware('checkAuth:master/item');
        Route::get('/delete/{id}',  'Master\ItemMasterController@delete')->middleware('checkAuth:master/item');
        Route::get('/itemlist',     'Master\ItemMasterController@itemLists')->middleware('checkAuth:master/item');
        Route::get('/itemcatlist',  'Master\ItemMasterController@itemCategoryLists')->middleware('checkAuth:master/item');
        Route::get('/uomlists',     'Master\ItemMasterController@uomLists')->middleware('checkAuth:master/item');

        Route::post('/saveitemcategory',   'Master\ItemMasterController@saveitemcategory')->middleware('checkAuth:master/item');
        Route::post('/updateitemcategory', 'Master\ItemMasterController@updateitemcategory')->middleware('checkAuth:master/item');
        Route::get('/deleteitemcat/{id}',  'Master\ItemMasterController@deleteItemCategory')->middleware('checkAuth:master/item');

        Route::post('/saveuom',          'Master\ItemMasterController@saveuom')->middleware('checkAuth:master/item');
        Route::post('/updateuom',        'Master\ItemMasterController@updateuom')->middleware('checkAuth:master/item');
        Route::get('/deleteuom/{p1}',    'Master\ItemMasterController@deleteuom')->middleware('checkAuth:master/item');

        Route::get('/upload',            'Master\ItemMasterController@upload')->middleware('checkAuth:master/item');
        Route::post('/upload/save',      'Master\ItemMasterController@importMaterial')->middleware('checkAuth:master/item');

        Route::post('/findpartnumber', 'Master\ItemMasterController@findPartnumber');

        Route::get('/exporttemplate',          'Master\ItemMasterController@downloadTemplate')->middleware('checkAuth:master/item');
    });

    Route::group(['prefix' => '/master/vendor'], function () {
        Route::get('/',             'Master\VendorMasterController@index')->middleware('checkAuth:master/vendor');
        Route::get('/create',       'Master\VendorMasterController@create')->middleware('checkAuth:master/vendor');
        Route::get('/edit/{p1}',    'Master\VendorMasterController@edit')->middleware('checkAuth:master/vendor');
        Route::post('/save',        'Master\VendorMasterController@save')->middleware('checkAuth:master/vendor');
        Route::post('/update',      'Master\VendorMasterController@update')->middleware('checkAuth:master/vendor');
        Route::get('/delete/{id}',  'Master\VendorMasterController@delete')->middleware('checkAuth:master/vendor');
        Route::get('/vendorlists',  'Master\VendorMasterController@vendorLists')->middleware('checkAuth:master/vendor');
        Route::post('/findvendor',  'Master\VendorMasterController@findVendor');

        Route::get('/upload',            'Master\VendorMasterController@upload')->middleware('checkAuth:master/vendor');
        Route::post('/upload/save',      'Master\VendorMasterController@importVendor')->middleware('checkAuth:master/vendor');
    });

    Route::group(['prefix' => '/master/costmaster'], function () {
        Route::get('/',             'Master\CostMasterController@index')->middleware('checkAuth:master/costmaster');
        Route::get('/create',       'Master\CostMasterController@create')->middleware('checkAuth:master/costmaster');
        Route::get('/edit/{p1}',    'Master\CostMasterController@edit')->middleware('checkAuth:master/costmaster');
        Route::get('/costlist',     'Master\CostMasterController@costLists')->middleware('checkAuth:master/costmaster');
        Route::get('/costgrouplist','Master\CostMasterController@costGroupLists')->middleware('checkAuth:master/costmaster');

        Route::post('/save',           'Master\CostMasterController@save')->middleware('checkAuth:master/costmaster');
        Route::post('/updatecost',     'Master\CostMasterController@update')->middleware('checkAuth:master/costmaster');
        Route::post('/savecostgroup',  'Master\CostMasterController@saveCostGroup')->middleware('checkAuth:master/costmaster');
        Route::post('/updatecostgroup','Master\CostMasterController@updateCostGroup')->middleware('checkAuth:master/costmaster');

        Route::get('/deletecostgroup/{id}',  'Master\CostMasterController@deleteGroup')->middleware('checkAuth:master/costmaster');
        Route::get('/deletecostmaster/{id}', 'Master\CostMasterController@delete')->middleware('checkAuth:master/costmaster');

        Route::post('/findcostmaster',  'Master\CostMasterController@findcostCode');
    });

    Route::group(['prefix' => '/master/warehouse'], function () {
        Route::get('/',             'Master\WarehouseController@index')->middleware('checkAuth:master/warehouse');
        Route::get('/create',       'Master\WarehouseController@create')->middleware('checkAuth:master/warehouse');
        Route::post('/save',        'Master\WarehouseController@save')->middleware('checkAuth:master/warehouse');
        Route::post('/update',      'Master\WarehouseController@update')->middleware('checkAuth:master/warehouse');
        Route::get('/delete/{id}',  'Master\WarehouseController@delete')->middleware('checkAuth:master/warehouse');
        Route::get('/warehouselist','Master\WarehouseController@warehouseLists')->middleware('checkAuth:master/warehouse');
        Route::post('/findwhs',     'Master\WarehouseController@findWhs');
    });

    Route::group(['prefix' => '/master/project'], function () {
        Route::get('/',             'Master\ProjectController@index')->middleware('checkAuth:master/project');
        Route::post('/save',        'Master\ProjectController@save')->middleware('checkAuth:master/project');
        Route::post('/update',      'Master\ProjectController@update')->middleware('checkAuth:master/project');
        Route::get('/delete/{id}',  'Master\ProjectController@delete')->middleware('checkAuth:master/project');

        Route::get('/projectlist',  'Master\ProjectController@projectlist')->middleware('checkAuth:master/project');
        Route::post('/findproject', 'Master\ProjectController@findproject');//->middleware('checkAuth:master/project');
    });
});
