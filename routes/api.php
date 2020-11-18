<?php

use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('auth', 'Login@Auth');



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('verify-token', 'Login@VerifyToken');

Route::resource('user', 'UsuariosController');
Route::post('status-user/{id}/{status}', 'UsuariosController@statusUser');

Route::resource('modulos', 'ModulosController');
Route::post('status-modulo/{id}/{status}', 'ModulosController@status');


Route::resource('funciones', 'FuncionesController');
Route::post('status-funciones/{id}/{status}', 'FuncionesController@status');


Route::get('list-funciones', 'FuncionesController@listFunciones');

Route::get('verify-permiso', 'Login@VerifyPermiso');

Route::resource('roles', 'RolesController');
Route::post('status-rol/{id}/{status}', 'RolesController@status');


Route::resource('city', 'CityController');
Route::post('status-city/{id}/{status}', 'CityController@status');

Route::get('logs/sessions', 'LogsController@session');
Route::get('logs/events/adviser', 'LogsController@EventsAdvisers');
Route::get('logs/events/clients', 'LogsController@eventsClients');

Route::get('notifications/get', 'NotificationsController@Get');

Route::resource('providers', 'ProvidersController');
Route::get('providers/status/{id}/{status}', 'ProvidersController@status');

Route::resource('clients', 'ClientsController');
Route::get('clients/status/{id}/{status}', 'ClientsController@status');


Route::resource('categories', 'CategoryController');
Route::get('categories/status/{id}/{status}', 'CategoryController@status');

Route::resource('products', 'ProductsController');
Route::get('products/status/{id}/{status}', 'ProductsController@status');

Route::resource('products/entry/stock', 'ProductsEntryController');
Route::resource('products/entry/output', 'ProductusOutputController');


Route::get('products/get/existence/warehouse/{warehouse}', 'ProductsController@GetExistenceWarehouse');




Route::get('invoice/print/{id}', 'InvoiceController@ShowInvoice');



Route::get('output/export/excel', 'InvoiceController@ExportExcel');
Route::get('output/export/excel/reemision', 'InvoiceController@ExportExcelReemision');




Route::resource('tasks', 'TasksController');

Route::get('tasks/status/{id}/{status}', 'TasksController@status');

Route::resource('reemisiones', 'ReemisionesController');


Route::get('reemision/print/{id}', 'InvoiceController@ShowInvoiceReemision');

Route::get('rakin-producto', 'ProductsRankingController@listRankinProducts');
Route::post('state-stock', 'StateStockController@listStateStock');
Route::get('almacen/existence/{warehouse}', 'AlmacenController@GetAlmacen');


Route::post('products/movimiento/output', 'TraspasoController@createOuptTraspase');
Route::get('products/movimiento/list', 'TraspasoController@ListOuptTraspase');
Route::get('products/movimiento/detail/{id}', 'TraspasoController@ListOuptTraspaseById');
Route::get('products/remision/invoice/{id}', 'ReemisionesController@RemisionToInvoice');
Route::post('implantes/technical/reception', 'TechnicalReceptionImplantesController@CreateTechnicalReceptionImplante');
Route::put('implantes/technical/reception/edit/{id}', 'TechnicalReceptionImplantesController@EditarTechnicalReceptionImplante');
Route::get('technical/reception/implante', 'TechnicalReceptionImplantesController@ListTechnicalReceptionImplante');
Route::get('technical/reception/implante/delete/{id}', 'TechnicalReceptionImplantesController@DeleteTechnicalReceptionImplante');
Route::get('products/get/implante/{id}', 'ImplantesController@GetExistenceImplante');
