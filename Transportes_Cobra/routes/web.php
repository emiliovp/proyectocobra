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
    //return view('welcome');
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/{id}', 'HomeController@index')->name('hijo');

Route::group(['prefix' => 'usuarios'], function(){
    Route::get('/lista','UserController@index')->name('ListaUsuarios')->middleware('auth');
    Route::get('/anyData',  'UserController@anyData')->name('listusers');
    Route::get('/nuevo',  'UserController@nuevo')->name('nuevousuario');
    Route::post('/stored',  'UserController@stored')->name('storedusuario');
    Route::get('/editar/{id}',  'UserController@updusr')->name('editusr');
    Route::post('/update',  'UserController@updateusuario')->name('editarusuario');
    Route::post('/baja',  'UserController@baja_usr')->name('bajausr');
});
Route::group(['prefix' => 'perfiles'], function(){
    Route::get('/lista','PerfilController@index')->name('PerfilesUsuarios')->middleware('auth');
    Route::get('/anyData', 'PerfilController@anyData')->name('listperfiles');
    Route::get('/nuevo',  'PerfilController@sotredperfil')->name('nuevoperfil');
    Route::get('/mhijo',  'PerfilController@modhijo')->name('mohijo');
    Route::post('store', 'PerfilController@stored')->name('storedper');
    Route::get('/editar/{id}',  'PerfilController@updperfil')->name('editperfil');
    Route::post('/update',  'PerfilController@modificacionperfil')->name('updateperfil');
    Route::post('/baja',  'PerfilController@bajaPerfil')->name('bajaperfil');
});
Route::group(['prefix' => 'areas'], function(){
    Route::get('/lista','AreaController@index')->name('areas')->middleware('auth');
    Route::get('/anyData', 'AreaController@anyData')->name('listaareas');
    Route::post('/stored', 'AreaController@stored')->name('storedareas');
    Route::post('/editar',  'AreaController@updated')->name('editararea');
    Route::post('/baja',  'AreaController@baja_area')->name('bajaararea');
});
Route::group(['prefix' => 'catalogos'], function(){
    /**Administración de catálogos */
    Route::get('/lista','CatalogoController@index')->name('catalogos')->middleware('auth');
    Route::get('/anyData', 'CatalogoController@anyData')->name('listacata');
    Route::post('/stored', 'CatalogoController@stored')->name('storedcatalogo');   
    Route::post('/editar',  'CatalogoController@updated')->name('editacatalogo');
    Route::post('/baja',  'CatalogoController@baja_catalog')->name('bajacatalogo');
    //**Administración de opciones */
    Route::get('/listaopciones/{id}', 'OpcionesCatalogosController@index')->name('listaopciones')->middleware('auth');
    Route::get('/dataIndexOptCat', 'OpcionesCatalogosController@dataIndexOptCat')->name('dataIndexOptCat');
    Route::get('/altaopt/{id}', 'OpcionesCatalogosController@altaOpcion')->name('altaopciones');
    Route::get('/opbycat',    'OpcionesCatalogosController@OptionByCatId')->name('opByCat');
    Route::post('/storeoptcat', 'OpcionesCatalogosController@storeoptcat')->name('storeoptcat');
    Route::get('/editaropt/{id}/{opt}', 'OpcionesCatalogosController@editarOpcion')->name('editaropciones');
    Route::post('/updateoptcat', 'OpcionesCatalogosController@updateoptcat')->name('updateoptcat');
    Route::put('/eliminacionopciones', 'OpcionesCatalogosController@deleteoptcat')->name('eliminarOptCatalogos');    
});
/**Solicitudes */
Route::group(['prefix' => 'solicitud'], function(){
    Route::get('/lista','SolcitudController@index')->name('solicitud')->middleware('auth');
    Route::get('/anyData',  'SolcitudController@anyData')->name('listSolicitud');
    Route::get('/alta','SolcitudController@nuevaSolicitud')->name('nuevasolicitud');
    Route::get('/autocomplete','SolcitudController@autocomplete')->name('autocomplete');
    Route::get('/getbodega','SolcitudController@getbodegas')->name('getbodega');
    Route::get('/opcion',  'SolcitudController@getopt')->name('getopt');
    Route::post('/storedsolicitud', 'SolcitudController@stored')->name('storedsolicitud');
});
/**Servicios */
Route::group(['prefix' => 'servicio'], function(){
    Route::get('/lista','servicioController@index')->name('servicios');
    Route::get('/anyData',  'servicioController@anyData')->name('listServicio');
    Route::get('/atender/{id}',  'servicioController@atendersol')->name('atendersol');
    Route::post('/stored', 'servicioController@storedProveedores')->name('storedProveedor');
});
/**Custodias */
Route::group(['prefix' => 'custodia'], function(){
    Route::get('/lista','CustodiaController@index')->name('custodias');
    Route::get('/anyData',  'CustodiaController@anyData')->name('listCustodia');
    Route::get('/atender/{id}',  'CustodiaController@atendercustodia')->name('atendercustodia');
    Route::post('/stored', 'CustodiaController@storedCustodia')->name('storedcustodia');
});
/**proveedores */
Route::group(['prefix' => 'proveedores'], function(){
    Route::get('/lista', 'ProveedoresController@index')->name('proveedores');
});