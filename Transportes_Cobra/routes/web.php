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
    Route::get('/lista','UserController@index')->name('ListaUsuarios'); // ->middleware('doublesession');
    Route::get('/anyData',  'UserController@anyData')->name('listusers');
    Route::get('/nuevo',  'UserController@nuevo')->name('nuevousuario');
    Route::post('/stored',  'UserController@stored')->name('storedusuario');
    Route::post('/baja',  'UserController@baja_usr')->name('bajausr');
});
Route::group(['prefix' => 'perfiles'], function(){
    Route::get('/lista','PerfilController@index')->name('PerfilesUsuarios'); // ->middleware('doublesession');
    Route::get('/anyData', 'PerfilController@anyData')->name('listperfiles');
    Route::get('/nuevo',  'PerfilController@sotredperfil')->name('nuevoperfil');
    Route::get('/mhijo',  'PerfilController@modhijo')->name('mohijo');
    Route::post('store', 'PerfilController@stored')->name('storedper');
    Route::post('/baja',  'PerfilController@bajaPerfil')->name('bajaperfil');
});
Route::group(['prefix' => 'areas'], function(){
    Route::get('/lista','AreaController@index')->name('areas'); // ->middleware('doublesession');
    Route::get('/anyData', 'AreaController@anyData')->name('listaareas');
    Route::post('/stored', 'AreaController@stored')->name('storedareas');
    Route::post('/editar',  'AreaController@updated')->name('editararea');
    Route::post('/baja',  'AreaController@baja_area')->name('bajaararea');
});
Route::group(['prefix' => 'catalogos'], function(){
    /**Administración de catálogos */
    Route::get('/lista','CatalogoController@index')->name('catalogos'); // ->middleware('doublesession');
    Route::get('/anyData', 'CatalogoController@anyData')->name('listacata');
    Route::post('/stored', 'CatalogoController@stored')->name('storedcatalogo');   
    Route::post('/editar',  'CatalogoController@updated')->name('editacatalogo');
    Route::post('/baja',  'CatalogoController@baja_catalog')->name('bajacatalogo');
    //**Administración de opciones */
    Route::get('/listaopciones/{id}', 'OpcionesCatalogosController@index')->name('listaopciones');
    Route::get('/dataIndexOptCat', 'OpcionesCatalogosController@dataIndexOptCat')->name('dataIndexOptCat');
    Route::get('/altaopt/{id}', 'OpcionesCatalogosController@altaOpcion')->name('altaopciones');
    Route::get('/opbycat',    'OpcionesCatalogosController@OptionByCatId')->name('opByCat');
    Route::post('/storeoptcat', 'OpcionesCatalogosController@storeoptcat')->name('storeoptcat');
    Route::get('/editaropt/{id}/{opt}', 'OpcionesCatalogosController@editarOpcion')->name('editaropciones');
    Route::post('/updateoptcat', 'OpcionesCatalogosController@updateoptcat')->name('updateoptcat');
    Route::put('/eliminacionopciones', 'OpcionesCatalogosController@deleteoptcat')->name('eliminarOptCatalogos');    
});