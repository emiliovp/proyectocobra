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

Route::group(['prefix' => 'usuarios'], function(){
    Route::get('/lista','UserController@index')->name('ListaUsuarios'); // ->middleware('doublesession');
    Route::get('/anyData',  'UserController@anyData')->name('listusers');
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