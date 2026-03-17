<?php
use App\Http\Controllers\ImportarController;
use App\Http\Controllers\TomoResolucionController;
use App\Http\Controllers\ResolucionController;
use App\Http\Controllers\BuscarController;
use App\Http\Controllers\AutoridadController;
use App\Http\Controllers\CodigoArchivoController;

use App\Http\Controllers\TemasController;

//=====================IMPORTAR RESOLUCIONES=============
Route::group(['middleware'=>['permission:acceder al sistema - rr']],function(){

    Route::get('lista importaciones resolucion/{id}',[ImportarController::class,'lista_importaciones_res']);
    Route::post('verificar importacion res/',[ImportarController::class,'verificar_importacion_res'])->middleware(['permission:importar - rr']);
    Route::get('datos importacion resolucion/{cod_imp}',[ImportarController::class,'ver_importacion_res']);

//======================TOMOS RESOLUCIONES=============
    Route::get('listar tomos resoluciones/{gestion}',[TomoResolucionController::class,'l_tomos'])->middleware(['permission:ver tomos - rr']);
    Route::post('g_tomo_res',[TomoResolucionController::class,'g_tomos'])->middleware(['permission:crear tomo - rr|editar tomo - rr']);
    Route::get('fe_tomo_res/{id}',[TomoResolucionController::class,'fe_tomo'])->middleware(['permission:editar tomo - rr']);
    Route::get('f_eli_tomo_res/{id}',[TomoResolucionController::class,'f_cerrarTomo'])->middleware(['permission:eliminar tomo - rr']);
    Route::post('e_tomo_res/',[TomoResolucionController::class,'EliminarTomo'])->middleware(['permission:eliminar tomo - rr']);

    Route::get('cerrar tomo resolucion/{id}',[TomoResolucionController::class,'f_cerrarTomo'])->middleware(['permission:consolidar tomo - rr']);
    Route::post('cerrar_tomo_res/',[TomoResolucionController::class,'cerrarTomo'])->middleware(['permission:consolidar tomo - rr']);

//=======================RESOLUCIONES===========
    Route::get('listar resoluciones/{tipo}/{cod_tom}',[ResolucionController::class,'l_resolucion']);
    Route::post('g_resolucion',[ResolucionController::class,'g_resolucion'])->middleware(['permission:crear resolucion - rr|editar resolucion - rr']);
    Route::get('fe_resolucion/{cod_res}/{cod_tom}',[ResolucionController::class,'fe_resolucion'])->middleware(['permission:editar resolucion - rr|permission:crear resolucion - rr']);
    Route::get('f_eli_resolucion/{cod_res}',[ResolucionController::class,'f_eli_resolucion'])->middleware(['permission:eliminar resolucion - rr']);
    Route::post('e_resolucion',[ResolucionController::class,'eli_resolucion'])->middleware(['permission:eliminar resolucion - rr']);
    Route::get('ver datos resolucion/{cod_res}',[ResolucionController::class,'datos_resolucion']);



    Route::get('pdf_a resolucion/{cod_res}',[ResolucionController::class,'pdf_a'])->middleware(['permission:mostrar antecedentes - rr']);
            //========RESOLUCIONES POR GESTION
            Route::get('listar resoluciones gestion/{gestion}/{tipo}',[ResolucionController::class,'listar_gestion'])->middleware(['permission:listar resoluciones - rr']);
            Route::post('complementar_pdf',[ResolucionController::class,'complementar_pdf']);
//=========================BUSQUEDAS=============
    Route::get('buscar resolucion',[BuscarController::class,'f_buscar_resolucion'])->middleware(['permission:buscar - rr']);
    Route::post('buscar resolucion',[BuscarController::class,'f_buscar_resolucion_post'])->middleware(['permission:busqueda avanzada - rr|buscar - rr']);

//=====================CAMBIAR RESOLUCION DE TOMO
    Route::get('f_cambiar a tomo  resolucion/{cod_res}',[ResolucionController::class,'f_cambiarTomo'])->middleware(['permission:cambiar resolucion a tomo - rr']);
    Route::get('o_tomos resolucion/{gestion}/{tipo}',[ResolucionController::class,'o_tomos'])->middleware(['permission:cambiar resolucion a tomo - rr']);
    Route::post('cambiar_tomo resolucion',[ResolucionController::class,'cambiar_tomo_resolucion'])->middleware(['permission:cambiar resolucion a tomo - rr']);

//=======================AUTORIDADES ===========

    Route::get('listar autoridades',[AutoridadController::class,'l_autoridad']);
    Route::post('g_autoridad',[AutoridadController::class,'g_autoridad'])->middleware(['permission:crear autoridad - rr|editar autoridad - rr']);
    Route::get('fe_autoridad/{cod_aut}',[AutoridadController::class,'fe_autoridad'])->middleware(['permission:editar autoridad - rr']);
    Route::get('hab_autoridad/{cod_aut}/{valor}',[AutoridadController::class,'h_autoridad'])->middleware(['permission:habilitar autoridad - rr|deshabilitar autoridad - rr']);
    Route::get('autoridad deshabilitado',[AutoridadController::class,'l_desHab_autoridad'])->middleware(['permission:habilitar autoridad - rr']);

// ======================CODIGO DE ARCHIVADO=======

    Route::get('lista codigos/{cod_plan}',[CodigoArchivoController::class,'l_codigos']);
    Route::post('g_codigo',[CodigoArchivoController::class,'g_codigo'])->middleware(['permission:crear codigo - rr|editar codigo - rr']);
    Route::get('fe_codigo/{cod_carch}',[CodigoArchivoController::class,'fe_codigo'])->middleware(['permission:editar codigo - rr']);
    Route::get('f_eli_codigo/{cod_carch}',[CodigoArchivoController::class,'f_eli_codigo'])->middleware(['permission:eliminar codigo - rr']);
    Route::post('eliminar codigo',[CodigoArchivoController::class,'e_codigo'])->middleware(['permission:eliminar codigo - rr']);

// ======================PLANES DE ARCHIVADO=======
    Route::get('lista planes',[CodigoArchivoController::class,'l_plan']);
    Route::post('g_plan',[CodigoArchivoController::class,'g_plan'])->middleware(['permission:crear plan - rr|editar plan - rr']);
    Route::get('fe_plan/{cod_plan}',[CodigoArchivoController::class,'fe_plan'])->middleware(['permission:editar plan - rr']);
    Route::get('f_eli_plan/{cod_plan}',[CodigoArchivoController::class,'f_eli_plan'])->middleware(['permission:eliminar plan - rr']);
    Route::post('eliminar plan',[CodigoArchivoController::class,'e_plan'])->middleware(['permission:eliminar plan - rr']);

    //===================RESOLUCIONES SIN TOMO====================
    Route::get('resoluciones sintomo/{gestion}',[TomoResolucionController::class,'l_resolucionSinTomo']);
    Route::get('l_res_sintomos/{gestion}/{tomo}',[ResolucionController::class,'l_res_sinTomo']);

    Route::post('asignarTomoRes',[ResolucionController::class,'asignar_tomo']);

    //======================ENLAZAR RESOLUCIONES=================
    Route::get('f_enlazar_resolucion/{cod_tom}',[ResolucionController::class,'f_enlazar_resolucion']);
    Route::post('buscar_resolucion_enlace',[ResolucionController::class,'listar_resoluciones_enlace']);

    Route::get('ver datos resolucion/{cod_tom}/{cod_res}',[ResolucionController::class,'datos_resolucion_enlace']);
    Route::post('enlazar resolucion',[ResolucionController::class,'enlazar_resolucion']);


    //===================TEMAS DE INTERES
    Route::group(['middleware'=>['permission:acceder a temas - rr']],function(){

        Route::get('temas interes',[TemasController::class,'l_temas']);
        Route::get('fe_tema/{ct}',[TemasController::class,'fe_tema']);
        Route::post('g_tema',[TemasController::class,'g_tema']);
        Route::get('f_eli_tema/{cod_tem}',[TemasController::class,'f_eli_tema']);
        Route::post('eli_tema',[TemasController::class,'eli_tema']);
                    //tema_resolucion
        Route::get('tema resolucion/{cod_tem}',[TemasController::class,'l_tema_resolucion']);
        Route::get('fe_tema_resolucion/{cod_tem}',[TemasController::class,'fe_tema_resolucion']);

    //Route::get('pdf resolucion temas/{cod_res}',[TemasController::class,'mostrar_pdf_temas']);

        Route::get('asignar resolucion/{cod_tem}/{cod_res}',[TemasController::class,'fe_asignar_resolucion']);
        Route::post('asignar resolucion tema',[TemasController::class,'asignar_resolucion']);

        Route::get('l_resolucion_tema_modal/{cod_tem}',[TemasController::class,'l_tema_resolucion_modal']);
        Route::get('f_eli tema resolucion/{cod_res}',[TemasController::class,'f_eli_resolucion_tema']);
        Route::post('eli_resolucion_tema/',[TemasController::class,'eli_resolucion_tema']);
        Route::get('actualizar lista tema/{cod_tem}',[TemasController::class,'actualizar_lista_tema']);
        Route::get('descargar resoluciones temas/{cod_tem}',[TemasController::class,'descargar_resoluciones_temas']);
        //============ IMPORTAR TEMAS
        Route::get('fe_importar tema/{cod_tem}',[TemasController::class,'fe_importar_tema']);
        Route::post('verificar importacion temas',[TemasController::class,'verificar_importacion_temas']);

    });
    //====================DETALLE CODIGO=======================

    Route::get('editar detalle codigo/{cod_carch}',[CodigoArchivoController::class,'fe_detalle_codigo']); //accede a los detalles del código de archivado
    Route::post('guardar detalle codigo',[CodigoArchivoController::class,'g_detalle_codigo']);//guarda un nuevo tema del codigo de archivado
    Route::get('tema codigo tabla/{cod_carch}',[CodigoArchivoController::class,'tema_codigo_tabla']);// actualiza la tabla de temas del codigo de archivado

    Route::get('editar tema codigo/{cod_det}',[CodigoArchivoController::class,'fe_tema_codigo']);//formulario para editar el tema del condigo

    Route::get('f_eliminar tema codigo/{cod_det}',[CodigoArchivoController::class,'f_eli_tema_codigo']);
    Route::post('eliminar tema codigo',[CodigoArchivoController::class,'eli_tema_codigo']);

    //Asignar tema de codigo a la resolucion
    Route::get('codigo tema resolucion/{cod_res}/{cod_carch}',[CodigoArchivoController::class,'l_tema_resolucion']);
    Route::get('guardar temas resolucion/{cod_res}/{cod_det}',[CodigoArchivoController::class,'g_tema_resolucion']);
    Route::get('actualizar temas resolucion/{cod_res}/{cod_det}',[CodigoArchivoController::class,'actualizar_tema_resolucion']);
    Route::get('eliminar plan resolucion/{cod_arc}',[ResolucionController::class,'eli_plan_resolucion']);

    Route::get('listar temas resolucion corregir',[CodigoArchivoController::class,'lista_resoluciones_corregir_temas']);
    Route::get('mostrar resoluciones tema corregir/{criterio}',[CodigoArchivoController::class,'mostrar_resolucion_tema']);
    Route::post('corregir temas resolucion',[CodigoArchivoController::class,'lista_resoluciones_corregir']);
    route::post('asignar temas resolucion corregido',[CodigoArchivoController::class,'asignar_tema_reslocion_corregido']);

});
Route::get('ver datos resolucion/{cod_res}',[ResolucionController::class,'datos_resolucion'])->middleware(['permission:acceder al sistema - rr|acceder al sistema - noa']);
Route::get('pdf resolucion/{cod_res}',[ResolucionController::class,'pdf'])->middleware(['permission:acceder al sistema - rr|acceder al sistema - noa']);



