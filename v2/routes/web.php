<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

// Authentication routes (register, login, logout...)
Auth::routes();

Route::middleware(['auth'])->group(function(){
    // Home route
    Route::get('/', 'HomeController@index')->name('home');

    // Account routes
    Route::get('account', 'AccountController@edit')->name('account.edit');
    Route::match(['put', 'patch'], 'account', 'AccountController@update')->name('account.update');

    // User supplementing routes
    Route::get('/users/{id}', 'UserController@show')->name('users.show');
    Route::delete('users/{id}/clients/{client}', 'UserController@destroyClient')->name('users.clients.destroy');
    Route::delete('users/{id}/access/clients/{client}', 'UserController@destroyAccessClient')->name('users.access.clients.destroy');

    // User resource routes
    Route::resource('users', 'UserController', ['except' => [
        'show', 'store'
    ]])->middleware('can:manage,App\User');

    //////////////

    /* Sobre */
    Route::get('/sobre/edit', 'SobreController@edit')->name('sobre.edit');
    Route::post('/sobre/update/{id}', 'SobreController@update')->name('sobre.update');

    /* Equipe */
    Route::resource('equipe', 'EquipeController'); 
    
    /* Season */
    Route::resource('season', 'SeasonTypeController'); 
    
    /* Partner roles */
    Route::resource('partnerroles', 'PartnerRoleController'); 
    
    /* Edition */
    Route::resource('edition', 'EditionController'); 

    /* Video */
    Route::resource('video', 'VideoController', ['except' => [
        'edit', 'create', 'index'
    ]]);
    Route::get('/video/edit/{id}/{edition_id}', 'VideoController@edit')->name('video.edit');
    Route::get('/video/create/{edition_id}', 'VideoController@create')->name('video.create');
    Route::get('/video/index/{edition_id}', 'VideoController@index')->name('video.index');

    /* Saiba mais */
    Route::resource('saibamais', 'SaibaMaisController', ['except' => [
        'edit', 'index'
    ]]);
    Route::get('/saibamais/edit/{edition_id}', 'SaibaMaisController@edit')->name('saibamais.edit');
    Route::get('/saibamais/index/{id}', 'SaibaMaisController@index')->name('saibamais.index');


    /* Link Saiba mais */
    Route::resource('linksaibamais', 'LinkSaibaMaisController', ['except' => [
        'create', 'edit'
    ]]);
    Route::get('/linksaibamais/create/{edition_id}/{saibamais_id}', 'LinkSaibaMaisController@create')->name('linksaibamais.create');
    Route::get('/linksaibamais/edit/{edition_id}/{saibamais_id}', 'LinkSaibaMaisController@edit')->name('linksaibamais.edit');

});

// Files resource routes
Route::resource('files', 'FileController', ['only' => [
    'index', 'store'
]]);


 