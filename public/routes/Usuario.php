<?php
use App\Http\Controllers\UsuarioController;

Route::get('editar datos personles/{id}',[UsuarioController::class,'fe_datos_personales'])->middleware('auth');
Route::post('g_cuenta_usuario', [UsuarioController::class,'g_cuenta_usuario']);
