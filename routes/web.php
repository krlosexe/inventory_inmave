<?php

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

Route::get('/', function () {
    return view('login');
});

Route::post('auth', 'Login@Auth');
Route::get('logout/{id}', 'Login@Logout');

Route::get('/dashboard', function () {
    return view('dashboard');
});


Route::get('/users', function () {
    return view('perfiles.Users.gestion');
});


Route::get('rol', function () {
    return view('perfiles.Roles.gestion');
});


Route::get('modules', function () {
    return view('perfiles.Modulos.gestion');
});


Route::get('funciones', function () {
    return view('perfiles.Funciones.gestion');
});

Route::get('providers', function () {
    return view('configuracion.providers.gestion');
});

Route::get('clients', function () {
    return view('contacts.clients.gestion');
});


Route::get('categories', function () {
    return view('configuracion.categories.gestion');
});



Route::get('products', function () {
    return view('warehouse.products.gestion');
});



Route::get('stock', function () {
    return view('warehouse.technical_reception.gestion');
});

Route::get('output', function () {
    return view('warehouse.output.gestion');
});




Route::get('reemisiones', function () {
    return view('warehouse.reemisiones.gestion');
});



// Route::get('technical_reception', function () {
//     return view('warehouse.technical_reception.gestion');
// });

Route::get('technical_reception', function () {
    return view('implantes.gestion');
});


Route::get('procedures', function () {
    return view('configuracion.procedures.gestion');
});



Route::get('tasks', function () {
    return view('tasks.gestion');
});

Route::get('raking-products', function () {
    return view('reports.productRanking');
});

Route::get('state-stock', function () {
    return view('reports.stateStock');
});


Route::get('almacen', function () {
    return view('reports.alamacen');
});

Route::get('ventas_implantes', function () {
    return view('implantes.output.gestion');
});


Route::get('ventas_reemisiones', function () {
    return view('implantes.reemisiones.gestion');
});

