<?php

namespace App\Imports;

use App\Models\Documento;
//use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Funcionario;
use App\Models\D_observacion;
use App\Models\Titularidad;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow,WithValidation};

class ImportarTitularidad1 implements ToModel,WithHeadingRow,WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $funcionario='';
        $documento='';
        $vacio='';
        if($row['ci']!=''){
            $funcionario=Funcionario::all()->where('fun_ci','=',$row['ci'])->first();
        }else{
            $funcionario=Funcionario::all()->where('fun_nombre','=',$row['nombre'])->first();
        }
        if($funcionario){
            if($row['registro']=='SI'){
                $funcionario->fun_telefonos='Cel: '.$row['celular']." - Fijo: ".$row['fijo'];
                $funcionario->fun_email=$row['correo'];
                $funcionario->fun_sexo= $row['sexo'];
                if($row['fecha']!=''){
                    $funcionario->fun_fecha_ingreso=$row['fecha'];
                }
                $funcionario->fun_nacionalidad=$row['nacionalidad'];
                $funcionario->fun_estado=$row['estado'];
                $funcionario->fun_obs_personal=$row['observacion'];
                $funcionario->fun_facultad=$row['facultad'];
                $funcionario->fun_carrera=$row['carrera'];
                $funcionario->save();
            }
            /*if($row['grt']=='titularidad'){
                $titularidad=Titularidad::create([
                    'cod_fun'=>$funcionario->cod_fun,
                    'dt_detalle'=>$row['dt'],
                    'dt_universidad'=>$row['ut'],
                    'dt_gestion'=>$row['gt'],
                    'dt_verificado'=>$row['vt'],
                    'dt_obs'=>$row['ot'],
                ]);

            }*/
        }
    }
    public function rules(): array
    {
        return [

        ];
    }
}
