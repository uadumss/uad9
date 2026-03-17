<?php

namespace App\Imports;

use App\Models\Documento;
//use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Funcionario;
use App\Models\D_observacion;
use App\Models\T_observacion;
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

        $funcionario=Funcionario::all()->where('fun_nombre','=',$row['nombre'])
            ->where('fun_doc_adm','=','A')->first();
        if($funcionario){
            if($funcionario->fun_doc_adm=='A'){
                $funcionario->fun_folder='t';
                $funcionario->fun_fecha_folder=date('d/m/Y');
                $funcionario->save();

                $titulo=Documento::create([
                    'doc_titulo'=>$row['detalle'],
                    'cod_fun'=>$funcionario->cod_fun,
                    'doc_universidad'=>$row['universidad'],
                    'doc_umss'=>$row['doc_umss'],
                    'doc_legalizado'=>$row['legalizado'],
                    'doc_verificado'=>$row['legalizado'],
                    'doc_grado'=>$row['grado'],
                    'doc_gestion'=>$row['gestion'],
                    'doc_tipo'=>$row['tipo'],
                ]);
                if($row['obstitulo']!=''){
                    $observacion=D_observacion::create([
                       'cod_doc'=>$titulo->cod_doc,
                       'od_obs'=>$row['obstitulo'],
                       'od_fecha'=>date('d/m/Y'),
                    ]);
                    $titulo->doc_obs='t';
                    $titulo->save();
                }
            }
        }else{
            return null;
        }

    }
    public function rules(): array
    {
        return [

        ];
    }
}
