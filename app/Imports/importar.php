<?php

namespace App\Imports;

use App\Diploma_academico;
use App\Persona;
use App\Titulo;
use App\Tomo;
use Maatwebsite\Excel\Concerns\ToModel;

class importar implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $tomo=Tomo::where('tom_numero','=',$row[9])
            ->where('tom_tipo','=',"$row[10]")
            ->where('tom_gestion','=',$row[0])->get();
        $cod_tomo='';
        if(sizeof($tomo)<1){
            $tomo=Tomo::create([
               'tom_numero'=>$row[9],
               'tom_gestion'=>$row[0],
               'tom_tipo'=>$row[10],
            ]);
            $cod_tomo=$tomo->cod_tom;
        }else{

            $cod_tomo=$tomo[0]->cod_tom;
        }
        $persona=Persona::create([
            'per_nombre'=>mb_strtoupper($row[7]),
            'per_apellido'=>mb_strtoupper($row[6]),
        ]);
        $titulo =Titulo::create([
            'tit_nro_titulo'=>$row[8],
            'tit_nro_folio'=>$row[5],
            'tit_fecha_emision'=>$row[4]."/".$row[3]."/".$row[0],
            'cod_tom'=>$cod_tomo,
            'id_per'=>$persona->id_per,
        ]);
        $diploma_a=Diploma_academico::create([
            'cod_tit'=>$titulo->cod_tit,
        ]);
        //$titulo=$row[0]." / ".$row[1]." / ".$row[2]." / ".$row[3]." / ".$row[4]." / ".$row[5]." / ".$row[6]." / ".$row[7]." / ".$row[8]." / ".$row[9]." / ".$row[10]."<br/>";
        return $titulo;
    }
}
