<?php
//=======================RUTAS DE DIPLOMAS Y TITULOS===========
use App\Http\Controllers\ClaustroController;
use App\Http\Controllers\ConsejeroController;


Route::group(['middleware'=>['permission:acceder al sistema - cla']],function(){
    Route::get('lista consejo/',[ClaustroController::class,'l_consejo']);
    Route::get('fe_consejo/{codigo}/{cod_fre}/{tipo}',[ClaustroController::class,'fe_consejo']);
    Route::get('l_consejeros/{codigo}/{tipo}',[ClaustroController::class,'consejeros']);

//======================HCU=========================
    Route::get('fu_consejo/{tipo}/{cod}',[ClaustroController::class,'fu_consejo']);
    Route::get('lista consejeros/{tipo}/{cod}',[ClaustroController::class,'l_consejeros']);
    Route::get('fe_frente/{tipo}/{cod}/{cod_fre}',[ClaustroController::class,'fe_frente']);
    Route::post('g_frente',[ClaustroController::class,'g_frente'])->middleware(['permission:crear frente - cla|editar frente - cla']);
    Route::post('g_consejero',[ClaustroController::class,'g_consejero'])->middleware(['permission:crear consejero - cla|editar consejero - cla']);

    Route::get('editar consejero/{ci}',[ClaustroController::class,'fe_consejero'])->middleware(['permission:ver datos consejero - cla']);
    Route::get('editar datos consejero/{cod_ele}',[ClaustroController::class,'fe_datos_consejero'])->middleware(['permission:ver datos consejero - cla']);
    Route::post('g_renuncia',[ClaustroController::class,'g_renuncia'])->middleware(['permission:ver datos consejero - cla']);
    Route::post('e_consejero',[ClaustroController::class,'e_consejero'])->middleware(['permission:eliminar consejero - cla']);

    Route::get('f_eli_frente/{cod_fre}',[ClaustroController::class,'f_eli_frente'])->middleware(['permission:eliminar frente - cla']);
    Route::post('eli_frente',[ClaustroController::class,'eli_frente'])->middleware(['permission:eliminar frente - cla']);
    Route::get('lista frente consejeros/{cod_fre}',[ClaustroController::class,'lista_frente']);

});
?>
