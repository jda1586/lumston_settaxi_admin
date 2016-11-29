<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

Route::group(array('prefix' => Config::get('app.app_path')), function()
{
    Route::any("/login", 'AppUsuarioController@login');
    Route::any("/elegirTaxi", 'AppUsuarioController@elegirTaxi');
    Route::any("/nuevoViaje", 'AppUsuarioController@nuevoViaje');
    Route::any("/iniciarViaje", 'AppUsuarioController@iniciarViaje');
    Route::any("/iniciarTaxiLibre", 'AppUsuarioController@iniciarTaxiLibre');
    Route::any("/actualizarPosicion", 'AppUsuarioController@actualizarPosicion');
    Route::any("/actualizarPosicionGps", 'AppUsuarioController@actualizarPosicionGps');
    Route::any("/finalizarViaje", 'AppUsuarioController@finalizarViaje');
    Route::any("/enviarEmail", 'AppUsuarioController@enviarEmail');
    Route::any("/getMapDetails", 'AppUsuarioController@getMapDetails');

    Route::any("/logout", 'AppUsuarioController@logout');
});

Route::group(array('prefix' => "api/v2"), function()
{
    // Session Controller
    Route::any("/loginTaxista", 'ApiV2SessionController@loginTaxista');
    Route::any("/loginUsuario", 'ApiV2SessionController@loginUsuario');
    Route::any("/logout", 'ApiV2SessionController@logout');

    // User Controller
    Route::any("/registroUsuario", 'ApiV2UserController@registroUsuario');
    Route::any("/actualizarUsuario", 'ApiV2UserController@actualizarUsuario');
    Route::any("/actualizarPassword", 'ApiV2UserController@actualizarPassword');
    Route::any("/facebookConnect", 'ApiV2SessionController@facebookConnect');
    Route::any("/recuperarPassword", 'ApiV2UserController@recuperarPassword');

    // Viaje Cliente Controller

    Route::any("/cotizarViajeUsuario", 'ApiV2UserViajeController@cotizarViajeUsuario');
    Route::any("/solicitarTaxistaViajeUsuario", 'ApiV2UserViajeController@solicitarTaxistaViajeUsuario');
    Route::any("/cancelarViajeUsuario", 'ApiV2UserViajeController@cancelarViajeUsuario');
    Route::any("/verViajeTaxiPosicion", 'ApiV2UserViajeController@verViajeTaxiPosicion');
    Route::any("/calificarViaje", 'ApiV2UserViajeController@calificarViaje');


    // Viaje Taxista Controller

    Route::any("/cotizarViajeTaxista", 'ApiV2TaxistaViajeController@cotizarViajeTaxista');
    Route::any("/listaViajesSolicitados", 'ApiV2TaxistaViajeController@listaViajesSolicitados');
    Route::any("/aceptarViajeSolicitado", 'ApiV2TaxistaViajeController@aceptarViajeSolicitado');
    Route::any("/iniciarViaje", 'ApiV2TaxistaViajeController@iniciarViaje');
    Route::any("/cancelarViajeTaxista", 'ApiV2TaxistaViajeController@cancelarViajeTaxista');
    Route::any("/actualizarPosicionTaxi", 'ApiV2TaxistaViajeController@actualizarPosicionTaxi');
    Route::any("/verViajesAbiertos", 'ApiV2TaxistaViajeController@verViajesAbiertos');
    Route::any("/verViajesAbiertos", 'ApiV2TaxistaViajeController@verViajesAbiertos');
    Route::any("/enviarEmail", 'ApiV2TaxistaViajeController@enviarEmail');
    Route::any("/obtenerRuta", 'ApiV2TaxistaViajeController@obtenerRuta');


    // Location Controller
    Route::any("/locations", 'ApiV2LocationsController@getLocations');
});


/***************** Adminstracion del sistema ******************/


Route::any('/locations', 'UbicacionesController@locations');
Route::any('/ubicaciones/delete/{id}', 'UbicacionesController@delete');
Route::any('/ubicaciones/{id}/{nombre}', 'UbicacionesController@details');
Route::controller("/ubicaciones", 'UbicacionesController');

Route::any('/viajes/fixCost', 'ViajesController@fixcost');
Route::any('/viajes/{id}', 'ViajesController@details');

Route::any('/sitios/delete/{id}', 'SitiosController@delete');
Route::any('/sitios/{id}/{nombre}', 'SitiosController@details');
Route::controller("/sitios", 'SitiosController');

Route::any('/taxistas/delete/{id}', 'TaxistasController@delete');
Route::any('/taxistas/{id}/{nombre}', 'TaxistasController@details');
Route::controller("/taxistas", 'TaxistasController');

Route::any('/propietarios/delete/{id}', 'PropietariosController@delete');
Route::any('/propietarios/{id}/{nombre}', 'PropietariosController@details');
Route::controller("/propietarios", 'PropietariosController');

Route::any('/taxis/delete/{id}', 'TaxisController@delete');
Route::any('/taxis/{id}/{nombre}', 'TaxisController@details');
Route::controller("/taxis", 'TaxisController');

Route::any('/usuarios/delete/{id}', 'UsuariosController@delete');
Route::any('/usuarios/{id}/{nombre}', 'UsuariosController@details');
Route::controller("/usuarios", 'UsuariosController');

Route::controller("/login", 'LoginController');
Route::controller("/viajes", 'HomeController');
Route::controller("/realtime", 'RealController');
Route::controller("/", 'HomeController');
