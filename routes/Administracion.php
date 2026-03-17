<?php
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\ActividadTareaController;
use App\Http\Controllers\DiarioController;
use App\Http\Controllers\Reporte_periodoController;
use App\Http\Controllers\DependienteController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ImportarController;

Route::group(['middleware'=>['permission:acceso al sistema - adm']],function() {

    Route::get('mostrar cuenta usuario/{id}',[PersonaController::class,'m_c_persona']);
    Route::get('datos usuario/{id}', [UsuarioController::class,'f_editar_usuario']);
    Route::post('g_usuario', [UsuarioController::class,'g_usuario']);

    //===============SESSION BITACORAS===============
    Route::get('session', [SessionController::class,'session']);
    Route::get('l_usuario/{bloqueado}', [SessionController::class,'l_usuario']);
    Route::get('lista conexiones/{id}', [SessionController::class,'lista_conexion']);
    Route::get('lista acciones/{id_bit}',[SessionController::class,'lista_acciones']);
    Route::get('accion/{cod_eve}', [SessionController::class,'ver_accion']);
    Route::get('titulos eliminados/{pdf}/{sistema}', [SessionController::class,'titulos_eliminados']);

    //================ASIGNACION DE DEPENDIENTES================
    Route::get('lista a cargos/{id}',[PersonaController::class,'listaFun_cargo']);
    Route::post('habilitar responsable',[PersonaController::class,'habilitar_responsable']);
    Route::post('a_g_acargo',[PersonaController::class,'a_g_acargo']);//inserta la asignacion
    Route::post('a_v_acargo',[PersonaController::class,'a_verificar_acargo']);//verifica si el funcionario esta a cargo de otro
    Route::get('obtener asignacion/{tipo}/{cod_ac}',[PersonaController::class,'f_habilitar_asignacion']);
    Route::post('habilitar asignacion funcionario',[PersonaController::class,'habilitar_asignacion_funcionario']);
    Route::get('historial asignacion/{id}',[PersonaController::class,'historialAsigFun']);

    //===============VER ACTIVIDADES ADMINISTRACION
    //========================ADM ACTIVIDADES=================
    Route::get('listar actividades adm/{id}',[AdministradorController::class,'a_o_listaAct']);
    Route::get('habilitar actividad adm/{cod_act}',[AdministradorController::class,'hab_actividad']);
    Route::get('datos actividad adm/{cod_act}/{id}',[AdministradorController::class,'a_o_actividad']);
    Route::post('guardar actividad adm',[AdministradorController::class,'a_guardarActividad']);
    Route::get('f_eliminar actividad adm/{cod_act}',[AdministradorController::class,'f_eliminar_actividad']);
    Route::post('eliminar actividad adm',[AdministradorController::class,'e_actividad']);

//=====================ADM TAREAS DE ACTIVIDADES
    Route::get('listar tareas actividad adm/{cod_act}',[AdministradorController::class,'l_tareas']);
    Route::get('habilitar tarea adm/{cod_tar}',[AdministradorController::class,'a_hab_tarea']);

    Route::get('datos tarea actividad adm/{cod_tar}',[AdministradorController::class,'a_o_tarea']);
    Route::post('a_guardarTarea',[AdministradorController::class,'a_guardarTarea']);
    Route::get('f_eliminiar tarea adm/{cod_tar}',[AdministradorController::class,'f_eliminar_tarea']);
    Route::post('eliminar tarea adm',[AdministradorController::class,'eliminar_tarea']);

//================= ADM REPORTES DIARIOS =============
    Route::get('listar reporte diario adm/{cod_tar}',[AdministradorController::class,'l_reporte_diario']);
    Route::get('revision diario adm/{cod_dia}',[AdministradorController::class,'revision_diario']);
    Route::get('f_eliminar diario adm/{cod_dia}',[AdministradorController::class,'f_eliminar_diario']);
    Route::post('eliminar diario adm',[AdministradorController::class,'eliminar_diario']);

//==========================ADM TAREAS ASIGNADAS
    Route::get('listar tareas adm/{id}',[AdministradorController::class,'listar_tareas']);
//==========================VER REPORTES POR FECHA =======================
    Route::get('listar reportes fecha adm',[AdministradorController::class,'listar_reportes_fecha']);
    Route::get('listar reportes fecha adm/{fecha}',[AdministradorController::class,'listar_reportes_fecha1']);
});

//==================ACTIVIDADES=============
Route::get('listar actividades',[ActividadController::class,'l_actividad']);
Route::post('guardarActividad',[ActividadController::class,'guardarActividad']);
Route::get('habilitar_Actividad/{id}',[ActividadController::class,'hab_actividad']);
Route::get('obtener actividad/{cod_act}',[ActividadController::class,'f_obtener_actividad']);
Route::get('f_eliminar actividad/{cod_act}',[ActividadController::class,'f_eliminar_actividad']);
Route::post('eliminar actividad',[ActividadController::class,'e_actividad']);

// ================TAREAS====================
Route::get('listar tareas/{cod_act}',[ActividadTareaController::class,'listar_tareas']);
Route::get('f_editar tarea/{cod_tar}/{cod_act}',[ActividadTareaController::class,'f_editar_tarea']);
Route::post('guardarTarea',[ActividadTareaController::class,'guardarTarea']);
Route::get('habilitar tarea/{cod_tar}',[ActividadTareaController::class,'hab_Tarea']);
Route::get('f_eliminar tarea/{cod_tar}',[ActividadTareaController::class,'f_eliminar_tarea']);
Route::post('eliminar tarea',[ActividadTareaController::class,'e_tarea']);
Route::get('datos asignados/{id_des}',[ActividadTareaController::class,'f_listaAsignados']);

//=====================REVISION TAREAS================


Route::get('listar reportes/{cod_tar}/{redireccion}',[DiarioController::class,'l_reporte']);
Route::get('revision diario/{cod_des}/{redireccion}',[DiarioController::class,'revision_diario']);
Route::post('aceptar_diario',[DiarioController::class,'aceptarReporteDiario']);
Route::post('g_observacion',[DiarioController::class,'g_observacion']);

//=====================REGISTRO DE PERIODOS
Route::get('listar informePeriodo',[Reporte_periodoController::class,'l_informePeriodo']);
Route::get('f_editar_reporteFinal/{cod_rt}',[Reporte_periodoController::class,'f_editar_reporteFinal']);
Route::get('f_eliminar_reporteFinal/{cod_rt}',[Reporte_periodoController::class,'f_eliminar_reporte_periodico']);
Route::get('o_reporteDiario',[Reporte_periodoController::class,'o_reporteDiario']);
Route::post('g_informe',[Reporte_periodoController::class,'g_informe']);
Route::post('eliminar reporte periodo',[Reporte_periodoController::class,'e_reporte_periodo']);

//=====================REGISTRO DIARIO
Route::get('listar mis tareas',[DiarioController::class,'l_tareaRegistro']);
Route::get('listar reportes diarios/{cod_des}',[DiarioController::class,'l_reporteDiario']);
Route::get('f_editar_reporte/{cod_dia}/{cod_tar}',[DiarioController::class,'f_editar_diario']);
Route::post('g_diario',[DiarioController::class,'g_diario']);
Route::get('listar observaciones tarea/{cod_dia}',[DiarioController::class,'l_observacionesTarea']);
Route::get('f_reporte_conclusion/{cod_tar}',[DiarioController::class,'f_reporte_concluido']);
Route::get('f_eliminar_diario/{cod_dia}',[DiarioController::class,'f_eliminar_diario']);
Route::post('eliminar diario',[DiarioController::class,'eliminar_diario']);
Route::post('g_conclusion',[DiarioController::class,'g_diario']);

//========================DEPENDIENTES

Route::get('listar dependientes',[DependienteController::class,'l_dependientes']);
Route::get('lista tareas dependiente/{id}',[DependienteController::class,'l_tareas_dependiente']);
Route::get('l_reporte_periodico_dependiente/{id}',[DependienteController::class,'l_reporte_periodico_dependiente']);
Route::get('f_revisar_reporte_periodico/{cod_rt}',[DependienteController::class,'f_revisar_reporte_periodico']);
Route::post('g_observacion_periodico',[Reporte_periodoController::class,'g_observacion_periodico']);

//==============PRUEBA===========
Route::get('personal',function(){return view('prueba.prueba'); });
Route::post('personal',[ImportarController::class,'comparar_personal']);

