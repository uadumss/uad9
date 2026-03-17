<?php

use App\Http\Controllers\PersonaController;

Route::get('corregir datos persona',[PersonaController::class,'f_persona']);

Route::group(['middleware'=>['permission:corregir duplicados - adm']],function(){
    //======================== CORRECCIONES PERSONA
    Route::get('formulario duplicados',[PersonaController::class,'f_duplicados']);
    Route::get('lista duplicados/{tipo}',[PersonaController::class,'lista_duplicados']);
    Route::get('listar duplicado/{tipo}/{ci}',[PersonaController::class,'lista_duplicado']);
    Route::post('corregir duplicados',[PersonaController::class,'corregir_duplicados']);
    Route::post('corregir duplicados ci',[PersonaController::class,'corregir_persona_ci_duplicado']);
    //======================== CORREGIR BLOQUE
    Route::post('corregir bloque duplicado',[PersonaController::class,'corregir_bloque_duplicado']);

});

Route::group(['middleware'=>['permission:corregir datos personales  ci - adm']],function(){
    Route::get('corregir por ci',[PersonaController::class,'formulario_corregir']);
    Route::post('fe_persona',[PersonaController::class,'buscar_ci']);
    Route::post('g_persona',[PersonaController::class,'g_persona']);
});


