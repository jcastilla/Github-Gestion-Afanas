<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as' => 'admin.index', function () {
    return view('welcome');
}]);

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function(){

	Route::get('/', ['as' => 'admin.index', function () {
    	return view('welcome');
	}]);

	Route::group(['middleware' => 'admin'], function(){
		Route::resource('users', 'UsersController');
		Route::get('users/{id}/destroy', [
			'uses'	=> 'UsersController@destroy',
			'as'	=> 'admin.users.destroy'
		]);
	});

	Route::resource('centros', 'CentrosController');
	Route::get('centros/{id}/destroy', [
		'uses'	=> 'CentrosController@destroy',
		'as'	=> 'admin.centros.destroy'
	]);

	Route::resource('personas', 'PersonasController');
	Route::get('personas/{id}/destroy', [
		'uses'	=> 'PersonasController@destroy',
		'as'	=> 'admin.personas.destroy'
	]);

	Route::resource('momentosDias', 'momentosDiasController');
	Route::get('momentosDias/{id}/destroy', [
		'uses'	=> 'MomentosDiasController@destroy',
		'as'	=> 'admin.momentosDias.destroy'
	]);

});

//Login
Route::get('admin/auth/login', [
	'uses' 	=> 'Auth\AuthController@getLogin',
	'as'	=> 'admin.auth.login'
]);
Route::post('admin/auth/login', [
	'uses' 	=> 'Auth\AuthController@postLogin',
	'as'	=> 'admin.auth.login'
]);
Route::get('auth/logout', [
	'uses'	=>	'Auth\AuthController@logout',
	'as'	=>	'admin.auth.logout'
]);

//***********************************************
//reset password
Route::get('password/reset/{token}', [
	'uses'	=> 'Auth\PasswordController@getReset',
	'as'	=> 'password.reset'
]);
Route::post('password/reset', [
	'uses' 	=> 'Auth\PasswordController@postReset',
	'as'	=> 'password.reset'
]);

//***********************************************





//// FULL CALENDAR EVENTOS
Route::resource('admin/users/guardaEventos', 'CalendarController');

Route::post('guardaEventos', array('as' => 'guardaEventos','uses' => 'CalendarController@create'));
Route::get('guardaEventos', 'CalendarController@index');
Route::get('cargaEventos{id?}','CalendarController@index');
Route::post('actualizaEventos', array('as' => 'actualizaEventos','uses' => 'CalendarController@update'));
Route::post('eliminaEvento','CalendarController@delete');

//// FULL NOCHE CALENDAR EVENTOS
Route::resource('admin/users/guardaNocheEventos', 'NocheCalendarController');
	
Route::post('guardaNocheEventos', array('as' => 'guardaNocheEventos','uses' => 'NocheCalendarController@create'));
Route::get('guardaNocheEventos', 'NocheCalendarController@index');
//Route::get('cargaEventosNoche{id?}','NocheCalendarController@index');
Route::get('cargaNocheEventos{id?}','NocheCalendarController@index');
Route::post('actualizaNocheEventos', array('as' => 'actualizaNocheEventos','uses' => 'NocheCalendarController@update'));
Route::post('eliminaNocheEvento','NocheCalendarController@delete');

//RECUPERAR CONTRASEÑA
//Route::auth();






Route::auth();

Route::get('/home', 'HomeController@index');
