<?php
use App\Http\Controllers\FacultadController;
use App\Http\Controllers\UnidadController;

Route::group(['middleware'=>['permission:acceso al sistema - f']],function() {
    //==============================FACULTADES===========================

    Route::get('listar facultad',[FacultadController::class,'l_facultad']);
    Route::get('fe_facultad/{cod_fac}',[FacultadController::class,'fe_facultad'])->middleware(['permission:crear editar facultad - f']);
    Route::post('g_facultad',[FacultadController::class,'g_facultad'])->middleware(['permission:crear editar facultad - f']);
    Route::get('f_eli_facultad/{cod_fac}',[FacultadController::class,'f_eli_facultad'])->middleware(['permission:eliminar facultad - f']);
    Route::post('eli_facultad',[FacultadController::class,'eli_facultad'])->middleware(['permission:eliminar facultad - f']);
//===============================CARRERAS===================
    Route::get('l_carrera/{cod_fac}',[FacultadController::class,'l_carrera']);
    Route::get('fe_carrera/{cod_fac}/{cod_car}',[FacultadController::class,'fe_carrera'])->middleware(['permission:crear editar carrera - f']);
    Route::post('g_carrera',[FacultadController::class,'g_carrera'])->middleware(['permission:crear editar carrera - f']);
    Route::get('f_eli_carrera/{cod_fac}/{cod_car}',[FacultadController::class,'f_eli_carrera'])->middleware(['permission:eliminar carrera - f']);
    Route::post('eli_carrera',[FacultadController::class,'eli_carrera'])->middleware(['permission:eliminar carrera - f']);
//==============================UNIDADES===================
    Route::get('listar unidad',[UnidadController::class,'l_unidad']);
    Route::get('fe_unidad/{cod_uni}',[UnidadController::class,'fe_unidad']);
    Route::post('guardar unidad',[UnidadController::class,'g_unidad']);
    Route::get('formulario eliminar unidad/{cod_uni}',[UnidadController::class,'f_eli_unidad']);
    Route::post('eliminar unidad',[UnidadController::class,'eli_unidad']);


});
