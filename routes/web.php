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

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

/** Rotas para extrair,listar dados em tabela (agrupando na middleware auth) **/
Route::group(['middleware' => 'auth'], function() {

  Route::get('/dados','DadoController@index');

  Route::get('dados/extrair', 'DadoController@extrair');
});
