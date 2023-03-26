<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => 'auth'], function () {

    Route::group(['prefix' => '/general/setting'], function () {
        Route::get('/',                       'Config\GeneralSettingController@index')->middleware('checkAuth:general/setting');
        Route::post('/save',                  'Config\GeneralSettingController@save')->middleware('checkAuth:general/setting');
        Route::post('/savetheme',             'Config\GeneralSettingController@saveAppTheme')->middleware('checkAuth:general/setting');
        Route::post('/savebgimg',             'Config\GeneralSettingController@saveBgImage')->middleware('checkAuth:general/setting');
    });

    Route::group(['prefix' => '/config/users'], function () {
        Route::get('/',                       'Config\UserController@index')->middleware('checkAuth:config/users');
        Route::get('/create',                 'Config\UserController@create')->middleware('checkAuth:config/users');
        Route::get('/edit/{id}',              'Config\UserController@edit')->middleware('checkAuth:config/users');
        Route::get('/objectauth/{id}',        'Config\UserController@objectauth')->middleware('checkAuth:config/users');
        Route::get('/objectauth/delete/{p1}/{p2}', 'Config\UserController@deleteObjectauth')->middleware('checkAuth:config/users');
        
        Route::post('/saveobjectauth',        'Config\UserController@saveObjectauth')->middleware('checkAuth:config/users');
        Route::post('/save',                  'Config\UserController@save')->middleware('checkAuth:config/users');
        Route::post('/update',                'Config\UserController@update')->middleware('checkAuth:config/users');
        Route::get('/delete/{id}',            'Config\UserController@delete')->middleware('checkAuth:config/users');
        Route::get('/userlist',               'Config\UserController@userlist')->middleware('checkAuth:config/users');
    });

    Route::group(['prefix' => '/config/roles'], function () {
        Route::get('/',                       'Config\RolesController@index')->middleware('checkAuth:config/roles');
        Route::get('/create',                 'Config\RolesController@create')->middleware('checkAuth:config/roles');
        Route::get('/assignment/{id}',        'Config\RolesController@assignment')->middleware('checkAuth:config/roles');

        Route::post('/save',                  'Config\RolesController@save')->middleware('checkAuth:config/roles');
        Route::post('/update',                'Config\RolesController@update')->middleware('checkAuth:config/roles');
        Route::get('/delete/{id}',            'Config\RolesController@delete')->middleware('checkAuth:config/roles');
        Route::get('/rolelist',               'Config\RolesController@rolelist')->middleware('checkAuth:config/roles');
        Route::get('/userroles/{p1}',         'Config\RolesController@userroles')->middleware('checkAuth:config/roles');
        Route::get('/rolemenus/{p1}',         'Config\RolesController@rolemenus')->middleware('checkAuth:config/roles');
        Route::get('/listuser/{p1}',          'Config\RolesController@dataadduser')->middleware('checkAuth:config/roles');
        Route::get('/listmenu/{p1}',          'Config\RolesController@dataaddmenu')->middleware('checkAuth:config/roles');
        Route::post('/saveroleuser',          'Config\RolesController@saveroleuser')->middleware('checkAuth:config/roles');
        Route::post('/saverolemenu',          'Config\RolesController@saverolemenu')->middleware('checkAuth:config/roles');

        Route::post('/deletemenuassignment',  'Config\RolesController@deleteMenuAssignment')->middleware('checkAuth:config/roles');
        Route::post('/deleteuserassignment',  'Config\RolesController@deleteUserAssignment')->middleware('checkAuth:config/roles');
    });

    Route::group(['prefix' => '/config/menus'], function () {
        Route::get('/',                       'Config\MenusController@index')->middleware('checkAuth:config/menus');
        Route::post('/savemenu',              'Config\MenusController@saveMenus')->middleware('checkAuth:config/menus');
        Route::post('/savegroup',             'Config\MenusController@saveGroup')->middleware('checkAuth:config/menus');
        Route::post('/updatemenu',            'Config\MenusController@updateMenu')->middleware('checkAuth:config/menus');
        Route::post('/updategroup',           'Config\MenusController@updateGroup')->middleware('checkAuth:config/menus');
        Route::get('/deletemenu/{id}',        'Config\MenusController@deleteMenu')->middleware('checkAuth:config/menus');
        Route::get('/deletegroup/{id}',       'Config\MenusController@deleteGroup')->middleware('checkAuth:config/menus');
    });

    // Route::group(['prefix' => '/config/workflow/budget'], function () {

    // });

    Route::group(['prefix' => '/config/workflow'], function () {
        Route::get('/',                   'Config\WorkflowController@index')->middleware('checkAuth:config/workflow');
        Route::post('/savebudgetwf',      'Config\WorkflowController@saveBudgetApproval')->middleware('checkAuth:config/workflow');
        Route::get('/deletebudgetwf/{id}','Config\WorkflowController@deleteBudgetwf')->middleware('checkAuth:config/workflow');

        Route::post('/savepbjwf',         'Config\WorkflowController@savePbjApproval')->middleware('checkAuth:config/workflow');
        Route::post('/savespkwf',         'Config\WorkflowController@saveSPKApproval')->middleware('checkAuth:config/workflow');

        Route::post('/saveprwf',          'Config\WorkflowController@savePRApproval')->middleware('checkAuth:config/workflow');
        Route::post('/savepowf',          'Config\WorkflowController@savePOApproval')->middleware('checkAuth:config/workflow');

        Route::post('/savecategories',    'Config\WorkflowController@saveCategories')->middleware('checkAuth:config/workflow');
        Route::post('/updatecategories',  'Config\WorkflowController@updateCategories')->middleware('checkAuth:config/workflow');
        Route::get('/deletecategories/{id}',   'Config\WorkflowController@deleteCategories')->middleware('checkAuth:config/workflow');

        Route::post('/savegroup',         'Config\WorkflowController@saveGroup')->middleware('checkAuth:config/workflow');
        Route::post('/updategroup',       'Config\WorkflowController@updateGroup')->middleware('checkAuth:config/workflow');
        Route::get('/deletegroup/{id}',   'Config\WorkflowController@deleteGroup')->middleware('checkAuth:config/workflow');

        Route::post('/saveassignment',    'Config\WorkflowController@saveAssignment')->middleware('checkAuth:config/workflow');

        Route::get('/deleteassignment/{p1}/{p2}/{p3}/{p4}/{p5}',   'Config\WorkflowController@deleteAssignment')->middleware('checkAuth:config/workflow');
        Route::get('/deletepbjwf/{id}','Config\WorkflowController@deletePBJwf')->middleware('checkAuth:config/workflow');
        Route::get('/deletewowf/{id}','Config\WorkflowController@deleteSPKwf')->middleware('checkAuth:config/workflow');
        Route::get('/deleteprwf/{id}','Config\WorkflowController@deletePRwf')->middleware('checkAuth:config/workflow');
        Route::get('/deletepowf/{id}','Config\WorkflowController@deletePOwf')->middleware('checkAuth:config/workflow');
    });

    Route::group(['prefix' => '/config/objectauth'], function () {
        Route::get('/',             'Config\ObjectAuthController@index')->middleware('checkAuth:config/objectauth');  
        Route::post('/save',        'Config\ObjectAuthController@save')->middleware('checkAuth:config/objectauth');
        Route::get('/delete/{p1}',  'Config\ObjectAuthController@delete')->middleware('checkAuth:config/objectauth');
    });
});