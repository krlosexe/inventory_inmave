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
Route::resource('implantes-clientes', 'ImplantesClientesController');
Route::get('clients/status/{id}/{status}', 'ClientsController@status');
Route::get('implantes-clientes/status/{id}/{status}', 'ImplantesClientesController@status');

Route::resource('categories', 'CategoryController');
Route::get('categories/status/{id}/{status}', 'CategoryController@status');

Route::resource('products', 'ProductsController');
Route::get('products/status/{id}/{status}', 'ProductsController@status');

Route::resource('products/entry/stock', 'ProductsEntryController');
Route::resource('products/entry/output', 'ProductusOutputController');

Route::get('products/get/existence/warehouse/{warehouse}', 'ProductsController@GetExistenceWarehouse');

Route::get('invoice/print/{id}', 'InvoiceController@ShowInvoice');

Route::get('output/export/excel/{init}/{finish}', 'InvoiceController@ExportExcel');
Route::get('output/export/excel/reemision', 'InvoiceController@ExportExcelReemision');

Route::resource('tasks', 'TasksController');

Route::get('tasks/status/{id}/{status}', 'TasksController@status');

Route::resource('reemisiones', 'ReemisionesController');

Route::get('reemision/print/{id}', 'InvoiceController@ShowInvoiceReemision');

Route::get('rakin-producto', 'ProductsRankingController@listRankinProducts');
Route::post('state-stock', 'StateStockController@listStateStock');
Route::get('almacen/existence/{factura}/{warehouse}', 'AlmacenController@GetAlmacen');


Route::post('products/movimiento/output', 'TraspasoController@createOuptTraspase');
Route::get('products/movimiento/list', 'TraspasoController@ListOuptTraspase');
Route::get('products/movimiento/detail/{id}', 'TraspasoController@ListOuptTraspaseById');
Route::get('products/remision/invoice/{id}/{user}', 'ReemisionesController@RemisionToInvoice');
Route::get('implantes/remision/invoice/{id}/{user}/{option}', 'ReemisionesController@ImplantesRemisionToInvoice');

Route::post('implantes/technical/reception', 'TechnicalReceptionImplantesController@CreateTechnicalReceptionImplante');
Route::put('implantes/technical/reception/edit/{id}', 'TechnicalReceptionImplantesController@EditarTechnicalReceptionImplante');
Route::get('technical/reception/implante/{rol}', 'TechnicalReceptionImplantesController@ListTechnicalReceptionImplante');
Route::get('technical/reception/implante/delete/{id}', 'TechnicalReceptionImplantesController@DeleteTechnicalReceptionImplante');
Route::get('products/get/implante/{id}', 'ImplantesController@GetExistenceImplante');

Route::post('reemisiones/implantes/create', 'ImplantesController@CreateImplanteRemision');
Route::get('reemisiones/implantes/list/{id}', 'ImplantesController@ListImplanteRemision');
Route::put('reemisiones/implantes/update/{id}', 'ImplantesController@UpdateImplanteRemision');

Route::post('output/implantes/create', 'ImplantesController@CreateImplanteOutput');
Route::get('output/implantes/list/{id}', 'ImplantesController@ListImplanteOutput');
Route::put('output/implantes/update/{id}', 'ImplantesController@UpdateImplanteOutput');

Route::post('products/implantes/create', 'ProductImplanteController@CreateProductImplante');
Route::get('products/implantes/list/{id}', 'ProductImplanteController@ListProductImplante');
Route::put('products/implantes/edit/{id}', 'ProductImplanteController@EditProductImplante');
Route::get('products/implantes/delete/{id}', 'ProductImplanteController@DeleteProductImplante');
Route::get('implantes/entry/output', 'ImplantesController@listImplantOutput');

Route::get('implantes/search/{code}', 'ImplantesController@searchSerial');


Route::post('products/medgel/create', 'MedgelProductsController@CreateProductMedgel');
Route::get('products/medgel/list', 'MedgelProductsController@ListProductMedgel');
Route::put('products/medgel/edit/{id}', 'MedgelProductsController@EditProductMedgel');
Route::get('products/medgel/delete/{id}', 'MedgelProductsController@DeleteProductMedgel');

Route::post('medgel/technical/reception/create', 'TechnicalReceptionMedgelController@CreateTechnicalReceptionMedgel');
Route::put('medgel/technical/reception/edit/{id}', 'TechnicalReceptionMedgelController@EditarTechnicalReceptionMedgel');
Route::get('medgel/technical/reception/list', 'TechnicalReceptionMedgelController@ListTechnicalReceptionMedgel');
Route::get('medgel/technical/reception/delete/{id}', 'TechnicalReceptionMedgelController@DeleteTechnicalReceptionMedgel');
Route::get('medgel/search/{code}', 'TechnicalReceptionMedgelController@searchReferencia');

Route::post('medgel/reemisiones/create', 'MedgelRemisionesController@CreateMedgelRemision');
Route::get('medgel/reemisiones/list', 'MedgelRemisionesController@ListMedgelRemision');
Route::put('medgel/reemisiones/update/{id}', 'MedgelRemisionesController@UpdateMedgelRemision');
Route::get('medgel/get/lote/{id}', 'MedgelRemisionesController@GetExistenceMedgel');
Route::get('medgel/remision/invoice/{id}/{user}','MedgelRemisionesController@MedgelRemisionToInvoice');

Route::post('medgel/output/create', 'MedgelOutputController@CreateMedgelOutput');
Route::get('medgel/output/list', 'MedgelOutputController@ListMedgelOutput');
Route::put('medgel/output/update/{id}', 'MedgelOutputController@UpdateMedgelOutput');

Route::get('invoice/implante/print/{id}', 'InvoiceController@ShowInvoiceImplantes');
Route::get('reemision/implante/print/{id}', 'InvoiceController@ShowInvoiceReemisionImplantes');

Route::post('implantes/products/movimiento/output', 'TraspasoController@createImplanteOuptTraspase');
Route::get('implantes/products/movimiento/list', 'TraspasoController@ListImplanteOuptTraspase');
Route::get('implantes/products/movimiento/detail/{id}', 'TraspasoController@ListImplanteOuptTraspaseById');
Route::get('implantes/products/get/existence/warehouse/{warehouse}', 'ProductImplanteController@GetExistenceWarehouse');
Route::get('implantes/reemisiones/status/{id}', 'ImplantesController@UpdateHeadRemision');
Route::get('get/implante/{id}', 'ImplantesController@GetImplante');
Route::get('get/clientes/implantes/list/{id}', 'ImplantesController@ListClienteImplanteOutput');
Route::get('get/implantes/list/trazabilidad/{id}', 'ImplantesController@ListImplanteTrazabilidad');
Route::get('impuesto/transporte/{factura}', 'AlmacenController@GetProductoByCosto');


Route::put('save/pay/{id_invoice}', 'InvoiceController@SavePay');
Route::get('get/pays/{id_invoice}', 'InvoiceController@GetPays');
Route::get('process/invoice/{id_invoice}', 'InvoiceController@Process');
