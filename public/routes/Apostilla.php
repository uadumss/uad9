<?php
use \App\Http\Controllers\ApostillaController;

Route::group(['middleware'=>['permission:acceso al sistema - apo']],function(){

    //=======================COFIGURAR TRAMITE APOSTILLA===================
    Route::get('listar documentos apostilla',[ApostillaController::class,'l_doc_apostilla']);
    Route::get('editar documento apostilla/{cod_lis}',[ApostillaController::class,'fe_doc_apostilla'])->middleware(['permission:crear documento apostilla - apo|editar documento apostilla - apo']);
    Route::post('guardar documento apostilla',[ApostillaController::class,'g_doc_apostilla'])->middleware(['permission:crear documento apostilla - apo|editar documento apostilla - apo']);
    //
    Route::get('habilitar documento apostilla/{cod_lis}',[ApostillaController::class,'hab_doc_apostilla'])->middleware(['permission:habilitar documento apostilla - apo']);
    Route::get('eliminar documento apostilla/{cod_lis}',[ApostillaController::class,'f_eli_doc_apostilla'])->middleware(['permission:eliminar documento apostilla - apo']);
    Route::post('eli documento apostilla',[ApostillaController::class,'eli_doc_apostilla'])->middleware(['permission:eliminar documento apostilla - apo']);

    // ===========================SERVICIO DE APOSTILLA
    Route::get('listar tramite apostilla/{fecha}',[ApostillaController::class,'l_tramite_apostilla']);
    Route::get('listar tramite apostilla tabla/{fecha}',[ApostillaController::class,'l_tramite_apostilla_tabla']);
    Route::get('editar tramite apostilla/{cod_apos}',[ApostillaController::class,'fe_tramite_apostilla'])->middleware(['permission:editar trámite - apo|crear trámite - apo']);
    Route::post('guardar tramite apostilla',[ApostillaController::class,'g_tramite_apostilla'])->middleware(['permission:editar trámite - apo|crear trámite - apo']);
    Route::post('guardar apoderado tramite apostilla',[ApostillaController::class,'g_apoderado_tramite_apostilla'])->middleware(['permission:editar apoderado - apo']);

    //===========================TRAMITES DE APOSTILLA
    Route::get('agregar tramite lista apostilla/{cod_lis}/{cod_apos}',[ApostillaController::class,'fe_agregar_tramite_apostilla'])->middleware(['permission:agregar documento - apo']);
    Route::post('guardar agregar tramite apostilla',[ApostillaController::class,'g_agregar_tramite_apostilla'])->middleware(['permission:agregar documento - apo']);
    Route::get('ajax tabla agregar/{cod_apos}',[ApostillaController::class,'ajax_tabla_agregar']);//->middleware(['permission:agregar documento - apo|quitar doumento - apo']);

    Route::get('eliminar tramite agregado apostilla/{cod_dapo}',[ApostillaController::class,'eliminar_tramite_agregado'])->middleware(['permission:quitar doumento - apo']);
    Route::get('generar pdf tramites apostilla/{cod_apos}',[ApostillaController::class,'generar_pdf_apostilla'])->middleware(['permission:generar pdf - apo']);

    Route::get('formulario eliminar tramite apostilla/{cod_apos}',[ApostillaController::class,'f_eli_tramite_apostilla'])->middleware(['permission:eliminar trámite - apo']);
    Route::post('eliminar tramite apostilla',[ApostillaController::class,'eli_tramite_apostilla'])->middleware(['permission:eliminar trámite - apo']);

    Route::get('firmar tramite apostilla/{cod_apos}',[ApostillaController::class,'firmar_tramite_apostilla'])->middleware(['permission:firma trámite - apo']);

    Route::get('formulario entrega tramite apostilla/{cod_apos}',[ApostillaController::class,'fe_entrega_tramite_apostilla'])->middleware(['permission:entregar trámite - apo']);
    Route::post('entrega tramite apostilla',[ApostillaController::class,'entrega_tramite_apostilla'])->middleware(['permission:entregar trámite - apo']);
    Route::post('importar tramite apostilla',[ApostillaController::class,'importar_apostilla'])->middleware(['permission:importar trámite - apo']);
    //=========================BUSQUEDA
    Route::get('formulario busqueda apostilla',[ApostillaController::class,'fe_buscar_apostilla'])->middleware(['permission:buscar trámite - apo']);
    Route::post('buscar tramite apostilla',[ApostillaController::class,'buscar_apostilla'])->middleware(['permission:buscar trámite - apo']);
    Route::get('ver datos apostilla/{cod_apos}',[ApostillaController::class,'ver_datos_apostilla'])->middleware(['permission:buscar trámite - apo']);

    Route::get('lista reportes apostilla',[ApostillaController::class,'lista_reporte_apostilla'])->middleware(['permission:ver reportes - apo']);
    Route::post('ver reporte apostilla',[ApostillaController::class,'reporte_apostilla'])->middleware(['permission:ver reportes - apo']);

    Route::get('mostrar observacion tramite apostilla/{cod_apos}',[ApostillaController::class,'mostrar_observacion_tramite_apostilla']);
    Route::post('guardar observacion tramite apostilla',[ApostillaController::class,'g_observacion_tramite_apostilla']);


});

