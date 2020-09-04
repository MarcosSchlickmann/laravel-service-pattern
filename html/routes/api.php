<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\BloqueioSistema;
use App\Http\Middleware\IsAdmin;

Route::prefix('v1')->group(function() {

	Route::get('teste', function(){
		return "hello world";
	});

	Route::group(['middleware' => BloqueioSistema::class], function(){
		
		Route::post('login', 'AuthController@login')->name('login');
		Route::prefix('user')->group(function() {
			Route::post('register', 'UserController@register');
		});

		Route::group(['middleware' => 'auth:api'], function(){
			Route::post('logout', 'AuthController@logout')->name('logout');

			Route::group(['middleware' => IsAdmin::class], function(){
				Route::prefix('parametros')->group(function () {
					Route::get('/', 'ParametroController@show');
					Route::post('/', 'ParametroController@update');
				});
			});
		});
		
	});

});
