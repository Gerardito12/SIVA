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
    return view('welcome');
});

Auth::routes();
Route::group(['middleware'=>'auth'],function(){
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/capturarpuntos/unavez','puntosfijos@solounaves');
    Route::get('/capturarpuntos/masdeunavez','puntosfijos@morethanonce');
    Route::get('/capturarpuntos/api/municipio','puntosfijos@Municipios');
    Route::post('/capturarpuntos/api/localidad','puntosfijos@Localidades');
    Route::post('/capturarpuntos/api/direccion','puntosfijos@Direccion');
    Route::post('/capturarpuntos/api/unavez','puntosfijos@SaveSolounavez');
});

# Admin routes
Route::group(['middleware'=>'auth'],function(){
    Route::get('/admin/configuracion','adminController@Index');
    Route::get('/admin/configuracion/admonusers','adminController@AdmonUsers');
    Route::get('/admin/configuracion/admonnotifictions','adminController@AdmonNotifictions');
    Route::get('/admin/configuracion/admonguides','adminController@AdmonGuides');
    Route::get('/admin/configuracion/admonregister','adminController@AdmonRegister');
    Route::post('/admin/configuracion/api/admonregistersave','adminController@AdmonRegisterSave');
});
