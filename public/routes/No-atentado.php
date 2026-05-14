<?php

use App\Http\Controllers\Noatentado\ConvocatoriaController;
use App\Http\Controllers\Noatentado\TramiteNoAtentadoController;
use App\Http\Controllers\ImportarController;
use App\Http\Controllers\Noatentado\SancionadosController;
Route::group(['middleware'=>['permission:acceder al sistema - noa']],function(){
    //=========================CONVOCATORIA
    Route::get('lista convocatoria noatentado/{gestion}',[ConvocatoriaController::class,'l_convocatoria']);
    Route::get('actualizar lista convocatoria noatentado/{gestion}',[ConvocatoriaController::class,'l_tabla_convocatoria']);

    Route::get('editar convocatoria noatentado/{cod_con}',[ConvocatoriaController::class,'fe_convocatoria'])->middleware(['permission:crear convocatoria - noa|editar convocatoria - noa']);;
    Route::post('guardar convocatoria noatentado',[ConvocatoriaController::class,'g_convocatoria'])->middleware(['permission:crear convocatoria - noa|editar convocatoria - noa']);
    Route::get('cargos convocatoria noatentado/{cod_carg}/{cod_con}',[ConvocatoriaController::class,'fe_cargos_convocatoria'])->middleware(['permission:crear convocatoria - noa|editar convocatoria - noa']);
    Route::post('guardar cargo convocatoria noatentado',[ConvocatoriaController::class,'g_cargo'])->middleware(['permission:crear convocatoria - noa|editar convocatoria - noa']);

    Route::post('eliminar cargo convocatoria noatentado',[ConvocatoriaController::class,'eli_cargo'])->middleware(['permission:crear convocatoria - noa|editar convocatoria - noa']);
    Route::get('actualizar cargos convocatoria/{cod_con}',[ConvocatoriaController::class,'actualizar_cargos'])->middleware(['permission:crear convocatoria - noa|editar convocatoria - noa']);
    Route::get('editar unidad convocatoria noatentado/{cod_con}',[ConvocatoriaController::class,'fe_unidad'])->middleware(['permission:crear convocatoria - noa|editar convocatoria - noa']);
    Route::get('obtener unidad convocatoria noatentado/{unidad}/{cod_con}',[ConvocatoriaController::class,'lista_unidad'])->middleware(['permission:crear convocatoria - noa|editar convocatoria - noa']);
    Route::post('guardar unidad convocatoria noatentado',[ConvocatoriaController::class,'g_unidad'])->middleware(['permission:crear convocatoria - noa|editar convocatoria - noa']);
    Route::get('PDF_convocatoria/{cod_con}',[ConvocatoriaController::class,'PDF_convocatoria']);
    Route::get('formulario eliminar convocatoria noatentado/{cod_con}',[ConvocatoriaController::class,'fe_eli_convocatoria'])->middleware(['permission:eliminar convocatoria - noa']);
    Route::post('eliminar convocatoria',[ConvocatoriaController::class,'eli_convocatoria'])->middleware(['permission:eliminar convocatoria - noa']);

//=========================== Tramites con convocatoria
    Route::get('listar tramite convocatoria/{cod_con}',[TramiteNoAtentadoController::class,'l_tramite_convocatoria']);
    Route::get('actualizar lista tramite convocatoria/{cod_con}',[TramiteNoAtentadoController::class,'tabla_tramite_convocatoria']);
    Route::get('editar tramite convocatoria/{cod_con}/{cod_dtra}',[TramiteNoAtentadoController::class,'fe_noatentado_convocatoria'])->middleware(['permission:crear tramite - noa|editar tramite - noa']);
    Route::post('guardar tramite convocatoria noatentado',[TramiteNoAtentadoController::class,'g_tramite_convocatoria'])->middleware(['permission:crear tramite - noa|editar tramite - noa']);
    Route::get('formulario eliminar tramite noatentado/{cod_dtra}',[TramiteNoAtentadoController::class,'f_eli_tramite'])->middleware(['permission:eliminar tramite - noa']);
    Route::post('eliminar tramite convocatoria noatentado',[TramiteNoAtentadoController::class,'eli_tramite'])->middleware(['permission:eliminar tramite - noa']);

    //===========GLOSA
    Route::get('formulario glosa noatentado/{cod_dtra}',[TramiteNoAtentadoController::class,'fe_glosa'])->middleware(['permission:generar glosa - noa']);
    Route::get('generar pdf noatentado/{cod_dtra}',[TramiteNoAtentadoController::class,'generarPDF'])->middleware(['permission:generar glosa - noa']);
    Route::post('generar documento noatentado',[TramiteNoAtentadoController::class,'generar_documento'])->middleware(['permission:generar glosa - noa']);
    Route::get('formulario corregir tramite noatentado/{cod_dtra}',[TramiteNoAtentadoController::class,'f_corregir_tramite_noa']);
    Route::post('corregir tramite noatentado',[TramiteNoAtentadoController::class,'corregir_tramite_noa']);

//================================CANDIDATOS
    Route::get('editar candidato convocatoria/{cod_dtra}/{id_per}',[TramiteNoAtentadoController::class,'fe_candidato'])->middleware(['permission:crear tramite - noa|editar tramite - noa']);
    Route::post('guardar candidato convocatoria',[TramiteNoAtentadoController::class,'g_candidato'])->middleware(['permission:crear tramite - noa|editar tramite - noa']);
    Route::get('formulario eliminar candidato/{cod_noa}',[TramiteNoAtentadoController::class,'fe_eli_candidato'])->middleware(['permission:crear tramite - noa|editar tramite - noa']);
    Route::post('eliminar candidato noatentado',[TramiteNoAtentadoController::class,'eli_candidato'])->middleware(['permission:crear tramite - noa|editar tramite - noa']);
    Route::get('agregar candidato excel convocatoria/{cod_dtra}',[TramiteNoAtentadoController::class,'fe_agregar_excel'])->middleware(['permission:crear tramite - noa|editar tramite - noa']);
    Route::post('guardar candidato excel convocatoria',[ImportarController::class,'g_excel_noatentado'])->middleware(['permission:crear tramite - noa|editar tramite - noa']);

//========================= SANCIONADOS - LISTA NEGRA
    Route::get('lista sancionados noatentado',[SancionadosController::class,'Lista_sancionados']);
    Route::get('actualizar lista sancionado noatentado',[SancionadosController::class,'Lista_sancionados_tabla'])->middleware(['permission:crear sancionado - noa|editar sancionado - noa']);
    Route::get('editar sancionado/{cod_san}',[SancionadosController::class,'fe_sancionado'])->middleware(['permission:crear sancionado - noa|editar sancionado - noa']);
    Route::post('guardar sancionado noatentado',[SancionadosController::class,'g_sancionado'])->middleware(['permission:crear sancionado - noa|editar sancionado - noa']);
    Route::get('formulario eliminar sancionado noatentado/{cod_san}',[SancionadosController::class,'f_eli_sancionado'])->middleware(['permission:eliminar sancionado - noa']);
    Route::post('eliminar sancionado noatentado',[SancionadosController::class,'eli_sancionado'])->middleware(['permission:eliminar sancionado - noa']);

    Route::get('lista detalle sancion noatentado/{cod_san}',[SancionadosController::class,'l_detalle_sancion']);
    Route::get('editar detalle sancion/{cod_san}/{cod_dsan}',[SancionadosController::class,'fe_detalle'])->middleware(['permission:crear detalle sancioando - noa|editar detalle sancioando - noa']);
    Route::post('guardar detalle sancion',[SancionadosController::class,'g_detalle'])->middleware(['permission:crear detalle sancioando - noa|editar detalle sancioando - noa']);
    Route::get('formulario eliminar detalle sancion/{cod_dsan}',[SancionadosController::class,'f_eli_detalle'])->middleware(['permission:eliminar detalle sancionado - noa']);
    Route::post('eliminar detalle sancion',[SancionadosController::class,'eli_detalle'])->middleware(['permission:eliminar detalle sancionado - noa']);

    Route::post('importar sancionado noatentado',[ImportarController::class,'importarSancionado']);

    //================ RESOLUCION SANCIONADO
    Route::get('obtener resolucion sancionado/{cod_san}',[SancionadosController::class,'l_resolucion_sancionado'])->middleware(['permission:crear sancionado - noa|editar sancionado - noa']);
    Route::post('buscar resolucion sancion',[SancionadosController::class,'buscar_resolucion'])->middleware(['permission:crear sancionado - noa|editar sancionado - noa']);
    Route::post('asignar resolucion sancionado',[SancionadosController::class,'g_resolucion_sancionado'])->middleware(['permission:crear sancionado - noa|editar sancionado - noa']);

    //================== ENTREGA
    Route::get('formulario entrega tramite noatentado/{cod_dtra}',[TramiteNoAtentadoController::class,'fe_entrega'])->middleware(['permission:entregar tramite - noa']);
    Route::post('guardar apoderado noatentado',[TramiteNoAtentadoController::class,'g_apoderado'])->middleware(['permission:entregar tramite - noa']);
    Route::post('g_entrega_noa',[TramiteNoAtentadoController::class,'g_entrega'])->middleware(['permission:entregar tramite - noa']);
    Route::get('actualizar lista entrega noatentado',[TramiteNoAtentadoController::class,'actualizar_lista_entrega'])->middleware(['permission:entregar tramite - noa']);
    Route::get('datos tramite noa/{cod_dtra}',[TramiteNoAtentadoController::class,'f_conf_entrega_noa'])->middleware(['permission:entregar tramite - noa']);
});


