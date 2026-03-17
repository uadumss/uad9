<?php
//=======================RUTAS DE DIPLOMAS Y TITULOS===========
use App\Http\Controllers\BuscarController;
use App\Http\Controllers\ImportarController;
use App\Http\Controllers\ObservacionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TomoController;
use App\Http\Controllers\TituloController;
use App\Http\Controllers\PersonaController;


Route::group(['middleware'=>['permission:acceso al sistema - dyt']],function(){
    //===============PDF============
    Route::get('pdf/{id}',[BuscarController::class,'pdf']);
    Route::get('pdf_a/{id}',[BuscarController::class,'pdf_a'])->middleware(['permission:mostrar antecedente - dyt']);

//==============IMPORTAR==============
    Route::get('lista importaciones/{id}',[ImportarController::class,'lista_importaciones']);
    //SOLO PARA ADMINISTRADORES
    Route::get('listar importacionUsuario/{id}',[ImportarController::class,'lista_importacionUsuario']);
    Route::get('f_eli_importacion/{cod_imp}',[ImportarController::class,'form_eliminar_importacion']);
    Route::get('datos importacion/{cod_imp}',[ImportarController::class,'ver_importacion']);
    Route::post('eliminar importacion',[ImportarController::class,'eliminar_importacion']);
    Route::get('descargar importacion/{cod_imp}',[ImportarController::class,'descargar_importacion']);
//Route::post('verificar importacion/','ImportarController@verificar_importacion');
    Route::post('verificar importacion/',[ImportarController::class,'verificar_importacion'])->middleware(['permission:realizar importación - dyt']);

//===============REPORTES============
    Route::get('reportes',[ReporteController::class,'form_reporte']);
    Route::post('generar reporte',[ReporteController::class,'generar_reporte']);
    Route::get('fe_reporte/{tipoDocumento}',[ReporteController::class,'fe_reporte']);

//===============OBSERVACIONES============
    Route::get('ver obs/{id}',[ObservacionController::class,'l_obs']);
    Route::post('g_obs',[ObservacionController::class,'g_obs'])->middleware(['permission:registrar solucion titulo - dyt|crear observacion titulo - dyt']);;
    Route::post('e_obs',[ObservacionController::class,'e_obs'])->middleware(['permission:eliminar observacion titulo - dyt']);;

//==============BUSQUEDA=============
    Route::get('buscar_t',[BuscarController::class,'f_buscar'])->middleware(['permission:busqueda - dyt']);
    Route::post('buscar_t',[BuscarController::class,'f_buscarPost'])->middleware(['permission:busqueda - dyt|busqueda ioavanzada - dyt']);;
    Route::get('ver datos/{id}',[BuscarController::class,'f_ver_datos'])->middleware(['permission:busqueda - dyt']);

//====================TOMOS
    Route::get('tomo/{tipo}/{gestion}',[TomoController::class,'listarTomos']);
    Route::post('g_tomo',[TomoController::class,'GuardarTomo'])->middleware(['permission:crear tomo - dyt|editar tomo - dyt']);
    Route::post('e_tomo/',[TomoController::class,'EliminarTomo'])->middleware(['permission:eliminar tomo - dyt']);
    Route::get('fe_tomo/{id}',[TomoController::class,'fe_tomo'])->middleware(['permission:editar tomo - dyt']);
    Route::get('f_eli_tomo/{id}',[TomoController::class,'f_cerrarTomo'])->middleware(['permission:eliminar tomo - dyt']);
    //CARRERAS DEL TOMO
    Route::get('fe_car/{id}',[TomoController::class,'fe_carrera']);
    Route::get('añadir carrera tomo/{id}/{accion}',[TomoController::class,'añadir_carrera']);
    //Route::get('cargar carreras/{id}','TomoController@cargar_carrera');
    Route::post('a_tomocarrera',[TomoController::class,'GuardarCarreraTomo'])->middleware(['permission:asignar carrera - dyt']);
    Route::post('a_tomofac',[TomoController::class,'GuardarFacultadTomo'])->middleware(['permission:asignar carrera - dyt']);
    Route::get('e_carTomo/{id}',[TomoController::class,'e_carrera'])->middleware(['permission:eliminar carrera tomo - dyt']);

    Route::get('cerrar tomo/{id}',[TomoController::class,'f_cerrarTomo'])->middleware(['permission:consolidar tomo - dyt']);
    Route::post('cerrar_tomo/',[TomoController::class,'cerrarTomo'])->middleware(['permission:consolidar tomo - dyt']);
    Route::get('imprimir lista/{cod_tom}',[TomoController::class,'imprimir_lista']);

//====================TITULOS
    Route::get('l_titulo/{id_tomo}',[TituloController::class,'ListarTitulo']);
    Route::post('asignarTomos/{gestion}/{tipo}/{cod_tomo}',[TituloController::class,'l_SinTomo']);
    Route::post('g_titulo',[TituloController::class,'GuardarTitulo'])->middleware(['permission:crear titulo - dyt|editar titulo - dyt']);
    Route::get('fe_titulo/{id_titulo}',[TituloController::class,'fe_Titulo'])->middleware(['permission:editar titulo - dyt']);
    Route::get('f_eli_titulo/{id}',[TituloController::class,'f_eli_titulo'])->middleware(['permission:eliminar titulo - dyt']);
    Route::post('e_titulo',[TituloController::class,'e_Titulo'])->middleware(['permission:eliminar titulo - dyt']);
    //=====================CAMBIAR TITULOS DE TOMO
    Route::get('f_cambiar a tomo/{cod_tit}',[TituloController::class,'f_cambiarTomo'])->middleware(['permission:cambiar titulo a tomo - dyt']);
    Route::get('o_tomos/{gestion}/{tipo}',[TituloController::class,'o_tomos'])->middleware(['permission:cambiar titulo a tomo - dyt']);
    Route::post('cambiar_tomo',[TituloController::class,'cambiar_tomo'])->middleware(['permission:cambiar titulo a tomo - dyt']);
    //=====================VERIFICACION DE TITULOS
    Route::post('verificar titulos',[TituloController::class,'verificar_titulos'])->middleware(['permission:verificar titulos faltantes - dyt']);

    //=====================TITULOS SIN TOMO
    Route::get('sintomo/{gestion}/{tipo}',[TituloController::class,'ListarTituloSinTomo']);
    Route::get('l_sintomos/{gestion}/{tipo}/{cod_tomo}',[TituloController::class,'l_SinTomo']);
    Route::post('asignarTomo',[TituloController::class,'asignar_tomo']);
    Route::post('f_asignar rango tomo',[TomoController::class,'f_asignar_rango_tomo']);
    Route::post('asignar rango tomo',[TomoController::class,'asignar_rango_tomo']);

    //Route::get('sintomo/{gestion}/{tipo}','TituloController@ListarTituloSinTomo');
    //Route::get('l_sintTomo','TituloController@asignar_tomo');


});


?>
