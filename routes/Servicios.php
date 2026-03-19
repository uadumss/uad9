<?php
use App\Http\Controllers\TramiteController;
use App\Http\Controllers\TramiteLegalizacionController;
use App\Http\Controllers\ConfrontacionController;
use App\Http\Controllers\GlosaController;
use App\Http\Controllers\PersonaController;
use \App\Http\Controllers\QRController;
use \App\Http\Controllers\ReporteServiciosController;
Route::group(['middleware'=>['permission:acceso al sistema - srv']],function(){
    //===================TRAMITE=======================
    Route::get('listar tramites',[TramiteController::class,'lista_tramite']);
    Route::get('fe_tramite/{tipo}/{cod_tre}',[TramiteController::class,'fe_tramite'])->middleware(['permission:crear tramite - srv|editar tramite - srv']);
    Route::post('g_legalizacion',[TramiteController::class,'g_tramite'])->middleware(['permission:crear tramite - srv|editar tramite - srv']);
    Route::get('habilitar tramite/{cod_tre}',[TramiteController::class,'hab_tramite'])->middleware(['permission:habilitar tramite - srv']);
    Route::get('f_eli_tramite/{cod_tre}',[TramiteController::class,'f_eli_tramite'])->middleware(['permission:eliminar tramite - srv']);
    Route::post('eli_tramite',[TramiteController::class,'eli_tramite'])->middleware(['permission:eliminar tramite - srv']);

    //=========================EDITAR GLOSAS=====================
    Route::get('l_glosa/{cod_tre}',[GlosaController::class,'listar_glosa']);
    Route::get('fe_glosa/{cod_glo}/{cod_tre}',[GlosaController::class,'fe_glosa']);
    Route::post('g_glosa/',[GlosaController::class,'g_glosa'])->middleware(['permission:crear glosa - srv|editar glosa - srv']);
    Route::get('f_eliminar_glosa/{cod_glo}',[GlosaController::class,'f_eliminar_glosa'])->middleware(['permission:eliminar glosa - srv']);
    Route::post('eliminar_glosa',[GlosaController::class,'eliminar_glosa'])->middleware(['permission:eliminar glosa - srv']);


    //=========================CANCELAR TRAMITE=====================
    Route::get('cancelar_tra/{cod_tra}',[ConfrontacionController::class,'cancelar_tra']);
    //=========================CONFRoNTACION=======================
        //Route::get('listar confrontacion',[LegalizacionController::class,'lista_legalizaciones']);
    Route::post('generar numero confrontacion',[ConfrontacionController::class,'generar_numero']);
    Route::post('guardar confrontacion',[ConfrontacionController::class,'g_confrontacion']);

    //=============================BUSQUEDA TRAMITE===========================
    Route::post('generar numero busqueda',[ConfrontacionController::class,'generar_numero_busqueda'])->middleware(['permission:crear traleg - srv']);
    Route::get('busqueda doc encontrado/{cod_dtra}',[ConfrontacionController::class,'f_busqueda_encontrado'])->middleware(['permission:generar glosa docleg - srv']);
    Route::post('g_busqueda_encontrado',[ConfrontacionController::class,'g_busqueda_encontrado'])->middleware(['permission:generar glosa docleg - srv']);

    //==========================TRAMITE DE LEGALIZACION==========================
    Route::get('listar tramite legalizacion/{fecha}',[TramiteLegalizacionController::class,'lista_leg']);
    Route::post('generar numero',[TramiteLegalizacionController::class,'generar_numero'])->middleware(['permission:crear traleg - srv']);
    Route::post('g_traleg',[TramiteLegalizacionController::class,'g_traleg'])->middleware(['permission:editar datos traleg - srv']);
    Route::get('ltl_ajax/{fecha}',[TramiteLegalizacionController::class,'lista_leg_ajax']);
    Route::get('f_eli_tra_legalizacion/{cod_tra}',[TramiteLegalizacionController::class,'f_eli_tra_legalizacion'])->middleware(['permission:eliminar traleg - srv']);
    Route::post('eli_traleg',[TramiteLegalizacionController::class,'eli_traleg'])->middleware(['permission:eliminar traleg - srv']);

    Route::get('buscar tramite legalizacion/{tramite}',[TramiteLegalizacionController::class,'buscar_tramite']);
                //===============================CAMBIAR TIPO DE TRAMITE Y NOMBRE================
    Route::get('f_cambiar tipo tramite/{cod_tra}',[TramiteLegalizacionController::class,'f_tipo_tramite']);
    Route::post('e_tipo tramite',[TramiteLegalizacionController::class,'e_tipo_tramite']);

    //===============================CERTIFICACION================
    //Route::post('generar numero certificacion',[TramiteLegalizacionController::class,'generar_numero_certificacion']);
    //==================APDOERADO=========================
    //Route::get('datos_per/{ci}',[PersonaController::class,'datos_per']);

    Route::get('datos apoderado/{cod_tra}',[TramiteLegalizacionController::class,'f_apoderado'])->middleware(['permission:editar apoderado traleg - srv']);
    Route::post('guardar apoderado/',[TramiteLegalizacionController::class,'g_apoderado'])->middleware(['permission:editar apoderado traleg - srv']);


    //=======================DOCUMENTOS DE LEGALIZACION DOCLEG======================
    Route::get('datos tramite legalizacion/{cod_tra}',[TramiteLegalizacionController::class,'fe_traleg']);
    Route::post('validar valorado recaudaciones/{cod_tra}',[TramiteLegalizacionController::class,'validar_valorado_recaudaciones']);
    Route::post('g_docleg',[TramiteLegalizacionController::class,'g_docleg'])->middleware(['permission:crear docleg - srv']);
    Route::get('f_eli_docleg/{cod_dtra}',[TramiteLegalizacionController::class,'f_eli_docleg'])->middleware(['permission:eliminar docleg - srv']);
    Route::post('eli_docleg',[TramiteLegalizacionController::class,'eli_docleg'])->middleware(['permission:eliminar docleg - srv']);
    Route::get('obs_docleg/{cod_dtra}',[TramiteLegalizacionController::class,'obs_docleg']);
    Route::post('g_obs_docleg',[TramiteLegalizacionController::class,'g_obs_docleg']);
    Route::get('verificacion sitra/{cod_dtra}',[TramiteLegalizacionController::class,'verificacion_sitra']);
    Route::get('fe_corregir_docleg/{cod_dtra}',[TramiteLegalizacionController::class,'fe_corregir_docleg'])->middleware(['permission:deshacer generado glosa - srv']);
    Route::post('corregir_docleg',[TramiteLegalizacionController::class,'corregir_docleg'])->middleware(['permission:deshacer generado glosa - srv']);
    Route::get('cambiar interno docleg/{cod_dtra}',[TramiteLegalizacionController::class,'corregir_interno'])->middleware(['permission:deshacer generado glosa - srv']);

    //========================GLOSA DE LEGALIZACION========================
    Route::get('generar glosa_leg/{cod_dtra}',[TramiteLegalizacionController::class,'generar_glosa_legalizacion'])->middleware(['permission:generar glosa docleg - srv']);
    Route::get('elegir_modelo/{cod_glo}/{cod_dtra}',[TramiteLegalizacionController::class,'elegir_modelo'])->middleware(['permission:generar glosa docleg - srv']);
    Route::post('legalizar titulo',[TramiteLegalizacionController::class,'legalizarTitulo'])->middleware(['permission:generar glosa docleg - srv']);

    Route::get('configurar impresion pdf leg/{cod_dtra}',[TramiteLegalizacionController::class,'conf_generar_pdf'])->middleware(['permission:imprimir legalizacion docleg - srv']);
    Route::get('cambiar posicion pdf/{cdtra}/{posicion}',[TramiteLegalizacionController::class,'cambiar_posicion_PDF'])->middleware(['permission:imprimir legalizacion docleg - srv']);
    Route::get('generar pdf/{cdtra}',[TramiteLegalizacionController::class,'generarPDF'])->middleware(['permission:imprimir legalizacion docleg - srv']);
    Route::get('ver documento pdf legalizado/{cod_dtra}',[TramiteLegalizacionController::class,'ver_documento']);

    //=========================ENTREGA====================================
    Route::get('panel entrega legalizacion/{cod_tra}',[TramiteLegalizacionController::class,'f_entrega']);
    Route::get('datos legalizado/{varios}/{cod_dtra}',[TramiteLegalizacionController::class,'f_conf_entrega'])->middleware(['permission:entregar legalizacion docleg - srv']);
    Route::post('g_entrega',[TramiteLegalizacionController::class,'g_entrega'])->middleware(['permission:entregar legalizacion docleg - srv']);

    //=========================ENTREGA DIRECTA================

    Route::get('lista tramite entrega',[TramiteLegalizacionController::class,'l_entrega'])->middleware(['permission:listar entregas - srv']);
    Route::get('ltl_ajax_entrega',[TramiteLegalizacionController::class,'lista_entrega_ajax'])->middleware(['permission:listar entregas - srv']);

    //=========================REPORTES =====================
    Route::get('lista reportes servicios',[ReporteServiciosController::class,'l_reportes']);
    Route::get('modal reporte servicios/{modal}',[ReporteServiciosController::class,'panel_reportes']);
    Route::post('reporte servicios personal',[ReporteServiciosController::class,'reporte_servicios_personal']);
    Route::get('detalle tramite reporte/{cod_dtra}',[ReporteServiciosController::class,'reporte_detalle_tramite']);
                    //GENERAL
    Route::post('reporte servicios general',[ReporteServiciosController::class,'reporte_servicios_general']);
                    //ESTADISTICO
    Route::post('reporte servicios estadistico',[ReporteServiciosController::class,'reporte_servicios_estadistico']);
                    //LISTAS PDF
    Route::post('reporte servicios listas',[ReporteServiciosController::class,'reporte_servicios_listas_PDF']);
                    // LISTAS DE ENTREGAS
    Route::post('reporte servicios entregas',[ReporteServiciosController::class,'reporte_servicios_entrega_PDF']);

    //======================IMORTAR LEGALIZCION
    Route::get('fe_importar Legalizacion',[TramiteLegalizacionController::class,'fe_importar_legalizacion']);
    Route::post('verificar importacion legalizacion',[TramiteLegalizacionController::class,'verificar_importacion_legalizacion']);

    //===========BUSCAR VALORADO
    Route::get('buscar valorado/{valorado}',[\App\Http\Controllers\BuscarController::class,'buscar_valorado']);

});


Route::get('verificar_qr/{codigo}',[QRController::class,'verificar_QR']);
