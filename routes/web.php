<?php

use App\Http\Controllers\RoutingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankController;
//use App\Http\Controllers\Admin\LoginController;
// use App\Http\Controllers\Admin\HomeController;
// use App\Http\Controllers\Admin\UserController;


//require __DIR__ . '/auth.php';

// Route::get('', [RoutingController::class, 'index'])->name('landing');

// Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
//     Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
//     Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
//  Route::get('{any}', [RoutingController::class, 'root'])->name('any');
//});

Route::get('/', [\App\Http\Controllers\Admin\LoginController::class, 'showLoginForm'])->name('/');

Route::get('/admin/login', [\App\Http\Controllers\Admin\LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\Admin\LoginController::class, 'login'])
  ->name('admin.login');


Route::post('/admin/logout', [\App\Http\Controllers\Admin\LoginController::class, 'logout'])
  ->name('admin.logout');
//Route::get('admin/login', 'Admin\LoginController@showLoginForm')->name('admin.login');
//Route::post('admin/login', 'Admin\LoginController@login')->name('admin.login');
//Route::post('admin/logout', 'Admin\LoginController@logout')->name('admin.logout');



Route::name('admin.')->prefix('admin')->middleware(['auth:admin', 'role:SuperAdmin|Admin', 'IsApproved', 'preventBackHistory'])->group(function () {
  // Route::get('{any}', [\App\Http\Controllers\RoutingController::class, 'root'])->name('any');
  Route::get('/home', [\App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home');
  Route::get('dash', [\App\Http\Controllers\Admin\UserController::class, 'dash'])->name('dash');
  Route::get('env_mis', [\App\Http\Controllers\Admin\UserController::class, 'env_mis'])->name('env_mis');

  // Company List 

  Route::get('user', [\App\Http\Controllers\Admin\UserController::class, 'user_index'])->name('user.index');

  Route::get('user/add', [\App\Http\Controllers\Admin\UserController::class, 'adduser'])->name('adduser');


  Route::get('user/apidata', [\App\Http\Controllers\Admin\UserController::class, 'apidata'])->name('user.apidata');

  Route::post('user/store', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('user.store');

  Route::get('user/edit/{id}', [\App\Http\Controllers\Admin\UserController::class, 'edituser'])->name('user.edituser');

  Route::get('user/edit/{id}', [\App\Http\Controllers\Admin\UserController::class, 'edituser'])->name('user.edituser');

  Route::post('user/update', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('user.update');
  Route::post('user/submit', [\App\Http\Controllers\Admin\UserController::class, 'submit'])->name('user.submit');

  Route::get('user/exist/{id}', [\App\Http\Controllers\Admin\UserController::class, 'existuser'])->name('user.existuser');

  Route::post('user/existsubmit', [\App\Http\Controllers\Admin\UserController::class, 'existsubmit'])->name('user.existsubmit');

  Route::get('user/exist_edit/{id}', [\App\Http\Controllers\Admin\UserController::class, 'existuser_edit'])->name('user.existuser_edit');

  Route::get('user/delete/{id}', [\App\Http\Controllers\Admin\UserController::class, 'delete'])->name('user.deleteuser');
  Route::get('user/view/{id}', [\App\Http\Controllers\Admin\UserController::class, 'viewuser'])->name('user.viewuser');

  Route::post('user/existupdate', [\App\Http\Controllers\Admin\UserController::class, 'existupdate'])->name('user.existupdate');

  Route::get('user/prowess/api', [\App\Http\Controllers\Admin\UserController::class, 'prowessapi'])->name('user.prowessapi');














  // Create Bulk Company

  Route::get('user/bulk/company/create', [\App\Http\Controllers\Admin\CompanyBulkUploadController::class, 'createBulkCompany'])->name('user.bulk.company.create');

  Route::post('user/bulk/company/store', [\App\Http\Controllers\Admin\CompanyBulkUploadController::class, 'storeCorp'])->name('user.bulk.company.store');

  Route::delete('user/bulk/company/{file}', [\App\Http\Controllers\Admin\CompanyBulkUploadController::class, 'deleteCorp'])->name('user.bulk.company.delete');


  Route::get('bank_branch_bulk/index', [\App\Http\Controllers\Admin\BankBranchController::class, 'index'])->name('bank_branch_bulk.index');

  Route::get('bank_branch_bulk/create', [\App\Http\Controllers\Admin\BankBranchController::class, 'create'])->name('bank_branch_bulk.create');

  Route::post('bank_branch_bulk/store', [\App\Http\Controllers\Admin\BankBranchController::class, 'store'])->name('bank_branch_bulk.store');



  Route::get('new_admin', [\App\Http\Controllers\Admin\BankController::class, 'index'])->name('new_admin.index');
  Route::get('new_admin/create', [\App\Http\Controllers\Admin\BankController::class, 'create'])->name('new_admin.create');
  Route::post('new_admin/store', [\App\Http\Controllers\Admin\BankController::class, 'store'])->name('new_admin.store');
  Route::get('new_admin/edit/{id}', [\App\Http\Controllers\Admin\BankController::class, 'edit'])->name('new_admin.edit');
  Route::post('new_admin/update', [\App\Http\Controllers\Admin\BankController::class, 'update'])->name('new_admin.update');
  Route::post('new_admin/submit', [\App\Http\Controllers\Admin\BankController::class, 'submit'])->name('new_admin.submit');
  Route::get('new_admin/com_list/{bank_id}', [\App\Http\Controllers\Admin\BankController::class, 'com_list'])->name('new_admin.com_list');
  Route::get('new_admin/toggle_status/{id}', [\App\Http\Controllers\Admin\BankController::class, 'toggleStatus'])->name('admin.new_admin.toggle_status');












  //Route::get('companies', 'Admin\UserController@companies')->name('companies');
  //Route::get('dash_v1', 'Admin\UserController@dash_v1')->name('dash_v1');
  //Route::get('dash_v2', 'Admin\UserController@dash_v2')->name('dash_v2');

  // Route::get('user/prowess', 'Admin\UserController@userapi')->name('user.prowess');


  // Route::get('new_admin', 'Admin\BankController@index')->name('new_admin.index');
  // Route::get('new_admin/create', 'Admin\BankController@create')->name('new_admin.create');
  // Route::post('new_admin/store', 'Admin\BankController@store')->name('new_admin.store');
  // Route::get('new_admin/edit/{id}', 'Admin\BankController@edit')->name('new_admin.edit');
  // Route::post('new_admin/update', 'Admin\BankController@update')->name('new_admin.update');
  // Route::post('new_admin/submit', 'Admin\BankController@submit')->name('new_admin.submit');
  // Route::get('new_admin/com_list/{bank_id}', 'Admin\BankController@comp_list')->name('new_admin.com_list');

  Route::post('bank_branch_bulk/store', 'Admin\BankBranchController@store')->name('bank_branch_bulk.store');
  // Route::get('company_bulk/corp/create', 'Admin\CompanyBulkUploadController@create')->name('company_bulk.corp.create');
  Route::post('company_bulk/corp/store', 'Admin\CompanyBulkUploadController@storeCorp')->name('company_bulk.corp.store');
  //Route::get('company_bulk/retail/create', 'Admin\CompanyBulkUploadController@create')->name('company_bulk.retail.create');
  Route::post('company_bulk/retail/store', 'Admin\CompanyBulkUploadController@storeRetail')->name('company_bulk.retail.store');




  Route::get('locuz', 'Admin\UserController@locuz')->name('locuz');
  // Route::get('user/apidata', 'Admin\UserController@prowessapi')->name('user.apidata');
  Route::get('retail/add', 'Admin\UserController@retail_adduser')->name('retail.adduser');
  Route::get('user/retail_apidata', 'Admin\UserController@retail_apidata')->name('retail.apidata');
  Route::post('retail/store', 'Admin\UserController@retail_store')->name('retail.store');
  Route::get('retail_edit/{id}', 'Admin\UserController@retail_edituser')->name('retail.edituser');
  Route::post('retail/update', 'Admin\UserController@retail_update')->name('retail.update');
  Route::get('retail/exist/{id}', 'Admin\UserController@retail_existuser')->name('retail.existuser');
  Route::post('retail/submit', 'Admin\UserController@retail_submit')->name('retail.submit');
  Route::post('retail/existsubmit', 'Admin\UserController@retail_existsubmit')->name('retail.existsubmit');
  Route::get('retail/exist_edit/{id}', 'Admin\UserController@retail_existuser_edit')->name('retail.existuser_edit');
  Route::post('retail/existupdate', 'Admin\UserController@retail_existupdate')->name('retail.existupdate');
  // Route::get('user/add', 'Admin\UserController@adduser')->name('adduser');
  // Route::post('user/store', 'Admin\UserController@store')->name('user.store');
  // Route::get('user/edit/{id}', 'Admin\UserController@edituser')->name('user.edituser');
  // Route::get('user/exist/{id}', 'Admin\UserController@existuser')->name('user.existuser');
  // Route::get('user/delete/{id}', 'Admin\UserController@delete')->name('user.deleteuser');
  // Route::get('user/view/{id}', 'Admin\UserController@viewuser')->name('user.viewuser');
  // Route::post('user/update', 'Admin\UserController@update')->name('user.update');
  // Route::post('user/submit', 'Admin\UserController@submit')->name('user.submit');
  // Route::post('user/existsubmit', 'Admin\UserController@existsubmit')->name('user.existsubmit');
  // Route::get('user/exist_edit/{id}', 'Admin\UserController@existuser_edit')->name('user.existuser_edit');
  // Route::post('user/existupdate', 'Admin\UserController@existupdate')->name('user.existupdate');


  Route::get('company_list', 'Admin\ListController@index')->name('user.company_list');
  Route::get('company/view/{com_id}/{fy_id}', 'Admin\ListController@view')->name('user.company_view');
  Route::get('/company_data_view/{head_id}/{fy_id}/{com_id}', 'Admin\ListController@getSubQuesData_view')->name('companyData_view');

  Route::get('bank', 'Admin\BankController@index')->name('bank');


});
