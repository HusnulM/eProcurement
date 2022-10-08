<?php
use Illuminate\Support\Facades\Route;

Route::get('/coba', function () {
    echo "Coba";
});

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => '/master/item'], function () {
        Route::get('/',             'Master\ItemMasterController@index')->middleware('checkAuth:master/item');
        Route::get('/create',       'Master\ItemMasterController@create')->middleware('checkAuth:master/item');
        Route::post('/save',        'Master\ItemMasterController@save')->middleware('checkAuth:master/item');
        Route::post('/update',      'Master\ItemMasterController@update')->middleware('checkAuth:master/item');
        Route::get('/delete/{id}',  'Master\ItemMasterController@delete')->middleware('checkAuth:master/item');  
        Route::get('/itemlist',     'Master\ItemMasterController@itemLists')->middleware('checkAuth:master/item');  
        Route::get('/itemcatlist',  'Master\ItemMasterController@itemCategoryLists')->middleware('checkAuth:master/item');  
        Route::get('/uomlists',     'Master\ItemMasterController@uomLists')->middleware('checkAuth:master/item');  
       
        Route::post('/saveitemcategory', 'Master\ItemMasterController@saveitemcategory')->middleware('checkAuth:master/item');
        Route::post('/saveuom',          'Master\ItemMasterController@saveuom')->middleware('checkAuth:master/item');
    });

    Route::group(['prefix' => '/master/vendor'], function () {
        Route::get('/',             'Master\VendorMasterController@index')->middleware('checkAuth:master/item');
        Route::get('/create',       'Master\VendorMasterController@create')->middleware('checkAuth:master/item');
        Route::post('/save',        'Master\VendorMasterController@save')->middleware('checkAuth:master/item');
        Route::post('/update',      'Master\VendorMasterController@update')->middleware('checkAuth:master/item');
        Route::get('/delete/{id}',  'Master\VendorMasterController@delete')->middleware('checkAuth:master/item');  
        Route::get('/vendorlists',  'Master\VendorMasterController@vendorLists')->middleware('checkAuth:master/item');  
        
    });

    Route::group(['prefix' => '/master/department'], function () {
        Route::get('/',             'Master\DepartmentMasterController@index')->middleware('checkAuth:master/department');
        Route::get('/create',       'Master\DepartmentMasterController@create')->middleware('checkAuth:master/department');
        Route::post('/save',        'Master\DepartmentMasterController@save')->middleware('checkAuth:master/department');
        Route::post('/update',      'Master\DepartmentMasterController@update')->middleware('checkAuth:master/department');
        Route::get('/delete/{id}',  'Master\DepartmentMasterController@delete')->middleware('checkAuth:master/department');  
        Route::get('/deptlists',    'Master\DepartmentMasterController@departmentLists')->middleware('checkAuth:master/department');  
        
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
});