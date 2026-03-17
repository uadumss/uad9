<?php

namespace App\Imports;

use App\Models\Noatentado\Sancionado;
use App\Models\Persona;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportarSancionado implements ToModel,WithHeadingRow,WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $persona=array();
        if($row['ci']!=''){
            $persona=Persona::where('per_ci','=',$row['ci'])->first();
        }
        $sancionado=array();
        $uuid=(String)Str::uuid();
        if($persona){
            $sancionado=Sancionado::create([
                'id_per'=>$persona->id_per,
                'cod_san'=>$uuid,
                'san_sentencia'=>$row['sentencia'],
                'san_referencia'=>$row['referencia'],
                'san_resolucion'=>$row['resolucion'],
                'san_observacion'=>$row['observacion'],
            ]);
        }else{
            $persona=Persona::create([
                'per_nombre'=>mb_strtoupper($row['nombre']),
                'per_apellido'=>mb_strtoupper($row['apellido']),
                'per_ci'=>mb_strtoupper($row['ci']),
            ]);
            $sancionado=Sancionado::create([
                'id_per'=>$persona->id_per,
                'cod_san'=>$uuid,
                'san_sentencia'=>$row['sentencia'],
                'san_referencia'=>$row['referencia'],
                'san_resolucion'=>$row['resolucion'],
                'san_observacion'=>$row['observacion'],
            ]);
        }

        return $sancionado;
    }
    public function rules(): array
    {
        return [
            'apellido' => 'required',
            'nombre' => 'required',
        ];
    }
}
