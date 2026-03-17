<?php

use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\DocumentoController;

Route::group(['middleware'=>['permission:acceder al sistema - dya']],function(){

    Route::get('listar funcionario/{funcionario}',[FuncionarioController::class,'l_funcionario']);
    Route::get('fe_funcionario/{cod_fun}',[FuncionarioController::class,'fe_funcionario']);
    Route::post('g_funcionario',[FuncionarioController::class,'g_funcionario']);
    Route::get('fe_eliminar funcionario/{cod_fun}',[FuncionarioController::class,'fe_eli_funcionario']);
    Route::post('eli_funcionario',[FuncionarioController::class,'eli_funcionario']);
    Route::get('fe_presentar folder/{cod_fun}',[FuncionarioController::class,'fe_presentar_folder']);
    Route::post('g_folder',[FuncionarioController::class,'g_folder']);
//=======================DOCUMENTOS=====================
    Route::get('listar documentos funcionario/{cod_fun}',[DocumentoController::class,'l_documentos']);
    //editar documento
    Route::get('fe_documento/{cod_doc}/{cod_fun}',[DocumentoController::class,'fe_documento']);
    Route::post('g_documento/',[DocumentoController::class,'g_documento']);
    //eliminar documento
    Route::get('fe_eliminar documento/{cod_doc}/{cod_fun}',[DocumentoController::class,'fe_eli_documento']);
    Route::post('eli_documento',[DocumentoController::class,'eli_documento']);

//=================OBSERVACION A DOCUMENTOS=============
    Route::get('fe_observacion documento/{cod_doc}',[DocumentoController::class,'fe_obs_documento']);
    Route::post('g_obs_documento',[DocumentoController::class,'g_obs_documento']);
    Route::post('e_obs_documento',[DocumentoController::class,'e_obs_documento']);
//================TITULARIDAD===============
    Route::get('fe_documento titularidad/{cod_dt}/{cod_fun}',[DocumentoController::class,'fe_documento_titularidad']);
    Route::post('g_documento titularidad/',[DocumentoController::class,'g_documento_titularidad']);
    //eliminar titularidad
    Route::get('fe_eliminar titularidad/{cod_doc}/{cod_fun}',[DocumentoController::class,'fe_eli_titularidad']);
    Route::post('eli_titularidad',[DocumentoController::class,'eli_titularidad']);

//========================IMPORTAR DOCENTES======
    Route::post('importar docente',[DocumentoController::class,'importar_docente']);
    Route::post('importar titularidad',[DocumentoController::class,'importar_titularidad']);
    Route::post('importar nuevos',[DocumentoController::class,'importar_nuevo']);

    // =======================CARRERA DOCENTE
    Route::get('e_carrera funcionario/{cod_trb}',[FuncionarioController::class,'e_carrera_funcionario']);
    //===================================
    Route::get('reporte dya',[FuncionarioController::class,'fe_reporte']);
    Route::post('procesar reporte dya',[FuncionarioController::class,'procesar_reporte']);
    //

});
