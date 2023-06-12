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
    });

    Route::group(['prefix' => '/master/department'], function () {
        Route::get('/',             'Master\DepartmentMasterController@index')->middleware('checkAuth:master/department');
        Route::get('/create',       'Master\DepartmentMasterController@create')->middleware('checkAuth:master/department');
        Route::post('/save',        'Master\DepartmentMasterController@save')->middleware('checkAuth:master/department');
        Route::post('/update',      'Master\DepartmentMasterController@update')->middleware('checkAuth:master/department');
        Route::get('/delete/{id}',  'Master\DepartmentMasterController@delete')->middleware('checkAuth:master/department');  
        Route::get('/deptlists',    'Master\DepartmentMasterController@departmentLists')->middleware('checkAuth:master/department');  
        
    });

    Route::group(['prefix' => '/master/jabatan'], function () {
        Route::get('/',             'Master\JabatanMasterController@index')->middleware('checkAuth:master/jabatan');
        Route::get('/create',       'Master\JabatanMasterController@create')->middleware('checkAuth:master/jabatan');
        Route::post('/save',        'Master\JabatanMasterController@save')->middleware('checkAuth:master/jabatan');
        Route::post('/update',      'Master\JabatanMasterController@update')->middleware('checkAuth:master/jabatan');
        Route::get('/delete/{id}',  'Master\JabatanMasterController@delete')->middleware('checkAuth:master/jabatan');  
        Route::get('/jabatanlist',  'Master\JabatanMasterController@jabatanLists')->middleware('checkAuth:master/jabatan');  
        
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

    Route::group(['prefix' => '/master/mekanik'], function () {
        Route::get('/',             'Master\MekanikController@index')->middleware('checkAuth:master/mekanik');
        Route::get('/create',       'Master\MekanikController@create')->middleware('checkAuth:master/mekanik');
        Route::post('/save',        'Master\MekanikController@save')->middleware('checkAuth:master/mekanik');
        Route::post('/update',      'Master\MekanikController@update')->middleware('checkAuth:master/mekanik');
        Route::get('/delete/{id}',  'Master\MekanikController@delete')->middleware('checkAuth:master/mekanik');  
        Route::get('/mekaniklist',  'Master\MekanikController@mekanikLists')->middleware('checkAuth:master/mekanik');   
        Route::post('/findmekanik', 'Master\MekanikController@findMekanik');
    });

    Route::group(['prefix' => '/master/kendaraan'], function () {
        Route::get('/',               'Master\KendaraanController@index')->middleware('checkAuth:master/kendaraan');
        Route::get('/create',         'Master\KendaraanController@create')->middleware('checkAuth:master/kendaraan');
        Route::post('/save',          'Master\KendaraanController@save')->middleware('checkAuth:master/kendaraan');
        Route::post('/update',        'Master\KendaraanController@update')->middleware('checkAuth:master/kendaraan');
        Route::get('/delete/{id}',    'Master\KendaraanController@delete')->middleware('checkAuth:master/kendaraan');  
        Route::get('/kendaraanlist',  'Master\KendaraanController@kendaraanLists')->middleware('checkAuth:master/kendaraan');   
        Route::post('/findkendaraan', 'Master\KendaraanController@findKendaraan');
        Route::post('/findkendaraan2','Master\KendaraanController@findKendaraan2');
        Route::get('/upload',          'Master\KendaraanController@upload')->middleware('checkAuth:master/kendaraan');
        Route::post('/upload/save',    'Master\KendaraanController@importKendaraan')->middleware('checkAuth:master/kendaraan');
    });

    Route::group(['prefix' => '/master/doctype'], function () {
        Route::get('/',           'Master\DoctypeController@index')->middleware('checkAuth:master/doctype');
        Route::post('/save',      'Master\DoctypeController@save')->middleware('checkAuth:master/doctype');
        Route::post('/update',    'Master\DoctypeController@update')->middleware('checkAuth:master/doctype');
        Route::get('/delete/{id}','Master\DoctypeController@delete')->middleware('checkAuth:master/doctype');  
    });

    Route::group(['prefix' => '/master/docarea'], function () {
        Route::get('/',           'Master\DocareaController@index')->middleware('checkAuth:master/docarea');  
        Route::get('/delete/{id}','Master\DocareaController@delete')->middleware('checkAuth:master/docarea');  
        Route::get('/getemail/{id}','Master\DocareaController@getDocAreaEmail')->middleware('checkAuth:master/docarea');  

        Route::post('/save',      'Master\DocareaController@save')->middleware('checkAuth:master/docarea');
        Route::post('/update',    'Master\DocareaController@update')->middleware('checkAuth:master/docarea');
        Route::post('/deletemail','Master\DocareaController@deleteEmail')->middleware('checkAuth:master/docarea');  
    });

    Route::group(['prefix' => '/master/doclevel'], function () {
        Route::get('/',           'Master\DoclevelController@index')->middleware('checkAuth:master/doclevel');  
        Route::post('/save',      'Master\DoclevelController@save')->middleware('checkAuth:master/doclevel');
        Route::post('/update',    'Master\DoclevelController@update')->middleware('checkAuth:master/doclevel');
        Route::get('/delete/{id}','Master\DoclevelController@delete')->middleware('checkAuth:master/doclevel');  
    });

    Route::group(['prefix' => '/master/customer'], function () {
        Route::get('/',             'Master\CustomerController@index')->middleware('checkAuth:master/customer');  
        Route::post('/save',        'Master\CustomerController@save')->middleware('checkAuth:master/customer');
        Route::post('/update',      'Master\CustomerController@update')->middleware('checkAuth:master/customer');
        Route::get('/delete/{id}',  'Master\CustomerController@delete')->middleware('checkAuth:master/customer');  

        Route::get('/lists',        'Master\CustomerController@customerlist')->middleware('checkAuth:master/customer');  
        Route::post('/findcustomer', 'Master\CustomerController@findcustomer')->middleware('checkAuth:master/customer');  
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