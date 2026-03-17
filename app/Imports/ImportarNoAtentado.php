<?php

namespace App\Imports;

use App\Models\Noatentado\Cargo_convocatoria;
use App\Models\Noatentado\Noatentado;
use App\Models\Persona;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportarNoAtentado implements ToModel,WithHeadingRow,WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use Importable;
    public function model(array $row)
    {
        $persona=Persona::where('per_ci','=',$row['ci'])->first();
        $noatentado=array();

        $cargo=$this::getCargo(mb_strtoupper($row['cargo']));
        if($cargo=='' && $row['cargo']!=''){
            $cargo_conv=Cargo_convocatoria::create([
                'carg_nombre'=>mb_strtoupper($row['cargo']),
                'cod_con'=>\Session::get('cod_con'),
            ]);
            $cargo=$cargo_conv->cod_carg;
        }
        if($persona){
            $persona_registrada=Noatentado::where('id_per','=',$persona->id_per)
                ->where('cod_dtra','=',\Session::get('cod_dtra'))
                ->first();
            if(!$persona_registrada){
                $noatentado=Noatentado::create([
                   'cod_dtra'=>\Session::get('cod_dtra'),
                   'id_per'=>$persona->id_per,
                   'cod_carg'=>$cargo,
                   'noa_cargo'=>mb_strtoupper($row['cargo']),
                   'noa_identificador'=>\Session::get('cod_dtra'),
                   'noa_unidad'=>mb_strtoupper($row['unidad']),
               ]);
            }
            if($row['sis']!='' && $persona->per_cod_sis==''){
                $persona->per_cod_sis=$row['sis'];
                $persona->save();
            }
        }else{
            $persona=Persona::create([
                'per_nombre'=>mb_strtoupper($row['nombre']),
                'per_apellido'=>mb_strtoupper($row['apellido']),
                'per_ci'=>mb_strtoupper($row['ci']),
                'per_cod_sis'=>$row['sis'],
            ]);            $noatentado=Noatentado::create([
                'cod_dtra'=>\Session::get('cod_dtra'),
                'id_per'=>$persona->id_per,
                'cod_carg'=>$cargo,
                'noa_cargo'=>mb_strtoupper($row['cargo']),
                'noa_identificador'=>\Session::get('cod_dtra'),
                'noa_unidad'=>mb_strtoupper($row['unidad']),
            ]);
        }
        return $noatentado;
    }
    public function rules(): array
    {
        return [
            'apellido' => 'required',
            'nombre' => 'required',
            'ci'=>'required',
        ];
    }
    public function getCargo($cargo){
        $cod_con=\Session::get('cod_con');
        $cargo=Cargo_convocatoria::where('cod_con','=',$cod_con)->where('carg_nombre','=',$cargo)->first();
        if($cargo){
            return $cargo->cod_carg;
        }else{
            return '';
        }
    }
}
