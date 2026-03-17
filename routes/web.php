<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\PersonaController;


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
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PermisosController;

Route::get('/', function () {
    //return "hola";
    return view('auth.login');
});
Route::get('/home', function () {
    //return 'hola';
    return view('inicio');
})->middleware('auth');

Route::get('/index', function () {
    return view('inicio');
})->middleware('auth');

require __DIR__ . '/DiplomasTitulos.php';
require __DIR__ . '/Resoluciones.php';
//require __DIR__ . '/Resoluciones_fc.php';
require __DIR__ . '/Servicios.php';
require __DIR__ . '/Facultad.php';
require __DIR__ . '/Administracion.php';
require __DIR__ . '/Docente-administrivo.php';
require __DIR__ . '/Apostilla.php';
require __DIR__ . '/No-atentado.php';
require __DIR__ . '/Usuario.php';
require __DIR__ . '/Configuracion.php';
require __DIR__ . '/Claustros.php';


Auth::routes();

//================RENDIMIENTO
    Route::get('rendimiento/{num}/{id}',[UsuarioController::class,'rendimiento']);
    Route::post('rendimiento_per',[UsuarioController::class,'grafico_rendimiento']);
//

Route::group(['middleware'=>['permission:acceso al sistema - adm']],function() {



//=====================PERMISOS===========
    Route::get('listar permisos/{id}/{num}', [PermisosController::class,'listar_permisos']);
    Route::post('guardar objeto', [PermisosController::class,'guardar_objeto']);
    Route::post('guardar permiso', [PermisosController::class,'guardar_permiso']);
    Route::post('asignar permiso', [PermisosController::class,'asignar_permiso']);

//================USUARIOS

    Route::get('habilitar usuario/{id}', [UsuarioController::class,'f_habilitar_usuario']);
    Route::post('habilitar', [UsuarioController::class,'habilitar_usuario']);


	Route::get('corregir',[PersonaController::class,'corregir']);

});
Route::get('datos_per/{ci}',[PersonaController::class,'datos_per']);
Route::get('datos_apo/{ci}',[PersonaController::class,'datos_apo']);

