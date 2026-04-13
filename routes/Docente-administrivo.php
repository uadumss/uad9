<?php

use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\DocumentoController;

Route::group(['middleware'=>['permission:acceder al sistema - dya']],function(){

    Route::get('listar funcionario/{funcionario}',[FuncionarioController::class,'l_funcionario']);
    Route::get('fe_funcionario/{cod_fun}',[FuncionarioController::class,'fe_funcionario']);
    Route::post('g_funcionario',[FuncionarioController::class,'g_funcionario']);
    Route::post('verificar-duplicado-funcionario',[FuncionarioController::class,'verificarDuplicado']);
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
    //descargar pdf documento
    Route::get('descargar pdf documento/{cod_doc}',[DocumentoController::class,'descargar_pdf_documento']);
    Route::get('ver pdf documento/{cod_doc}',[DocumentoController::class,'ver_pdf_documento']);
    Route::get('fe_enviar dpa/{cod_fun}',[DocumentoController::class,'fe_enviar_dpa']);
    Route::post('enviar dpa',[DocumentoController::class,'enviar_dpa']);
    Route::post('generar pdf dpa',[DocumentoController::class,'generar_pdf_dpa'])->name('generar.pdf.dpa');
    Route::get('ver pdf temporal dpa',[DocumentoController::class,'ver_pdf_temporal_dpa'])->name('ver.pdf.temporal.dpa');
    Route::get('ver pdf envio dpa/{cod_env_dpa}',[DocumentoController::class,'ver_pdf_envio_dpa']);
    Route::get('descargar pdf envio dpa/{cod_env_dpa}',[DocumentoController::class,'descargar_pdf_envio_dpa']);

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
    
    // =======================CONFORMIDAD
    Route::get('buscar-funcionarios',[FuncionarioController::class,'buscar_funcionarios']);
    Route::post('guardar-conformidad',[FuncionarioController::class,'guardar_conformidad']);
    Route::get('l_conformidad/{cod_fun}',[FuncionarioController::class,'l_conformidad']);
    Route::get('descargar conformidad/{cod_fcon}',[FuncionarioController::class,'descargar_conformidad']);
    Route::get('fe_formulario_conformidad/{cod_fcon}',[FuncionarioController::class,'fe_formulario_conformidad']);
    Route::post('editar_conformidad',[FuncionarioController::class,'editar_conformidad']);
    Route::get('fe_eliminar_conformidad/{cod_fcon}',[FuncionarioController::class,'fe_eli_conformidad']);
    Route::post('eliminar_conformidad',[FuncionarioController::class,'eliminar_conformidad']);
    
    // =======================UNIVERSIDADES
    Route::get('listar universidades',[FuncionarioController::class,'listar_universidades'])->middleware('permission:listar universidades - dya');
    Route::post('verificar-universidad',[FuncionarioController::class,'verificar_universidad'])->middleware('permission:listar universidades - dya');
    Route::post('crear universidad',[FuncionarioController::class,'crear_universidad'])->middleware('permission:listar universidades - dya');
    Route::put('actualizar universidad/{id}',[FuncionarioController::class,'actualizar_universidad'])->middleware('permission:listar universidades - dya');
    Route::delete('eliminar universidad/{id}',[FuncionarioController::class,'eliminar_universidad'])->middleware('permission:listar universidades - dya');
    //

});
