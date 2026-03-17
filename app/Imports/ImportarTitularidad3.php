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

class ImportarTitularidad implements ToModel,WithHeadingRow,WithValidation
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
            if($funcionario->fun_doc_adm=='D'){
                $funcionario->fun_doc_adm='E';
                $funcionario->fun_cargo=$row['cargo'];
                $funcionario->fun_tipo_contrato=$row['sector'];
                $funcionario->fun_facultad.=$row['da'].", ";
                $funcionario->fun_carrera.=$row['sector'].', ';
                $funcionario->save();
            }
        }else{
            $funcionario=Funcionario::create([
                'fun_nombre'=>$row['nombres'],
                'fun_ci'=>$row['ci'],
                'fun_cargo'=>$row['cargo'],
                'fun_tipo_contrato'=>$row['sector'],
                'fun_fecha_ingreso'=>$row['fecha'],
                'fun_facultad'=>$row['da'],
                'fun_carrera'=>$row['act'],
                'fun_doc_adm'=>'A',
                'cod_nac'=>29,
                'fun_nacionalidad'=>'B',
            ]);
            return $funcionario;
        }

    }
    public function rules(): array
    {
        return [

        ];
    }
}
