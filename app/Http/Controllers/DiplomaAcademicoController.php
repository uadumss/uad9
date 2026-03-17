<?php

namespace App\Http\Controllers;

use App\Models\Nacionalidad;
use App\Models\Tomo;
use App\Models\Grado;
use Illuminate\Http\Request;

class DiplomaAcademicoController extends Controller
{
    public function nuevoDiploma(){
        $nacion=Nacionalidad::all();
        $tomo=Tomo::all();
        $grado=Grado::all();
        return view('diplomas.da.fn_academico',compact('nacion','tomo','grado'));
    }
}
