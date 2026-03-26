<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecaudacionesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('recaudaciones')->group(function () {
    Route::post('buscar-control-documento', [RecaudacionesController::class, 'buscarPorControlYDocumento']);
    Route::post('buscar-control', [RecaudacionesController::class, 'buscarPorControl']);
    Route::post('buscar-documento', [RecaudacionesController::class, 'buscarPorDocumento']);
    Route::post('extraer-datos-documento', [RecaudacionesController::class, 'extraerDatosDocumento']);
    Route::post('extraer-datos-diploma', [RecaudacionesController::class, 'extraerDatosDiploma']);
});
