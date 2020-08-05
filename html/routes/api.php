<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {
	Route::prefix('inscricoes')->group(function(){
		Route::get('/', 'InscricaoController@index');
		Route::post('/', 'InscricaoController@store');
		Route::get('/{id}', 'InscricaoController@show');
		Route::patch('/{id}', 'InscricaoController@update');
		Route::delete('/{id}', 'InscricaoController@destroy');
	});
});