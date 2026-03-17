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
            $funcionario=Funcionario::all()->where('fun_ci','=',$row['ci'])
                ->where('fun_doc_adm','=','D')->first();
        }else{
            $funcionario=Funcionario::all()->where('fun_nombre','=',$row['nombre'])
                ->where('fun_doc_adm','=','D')->first();
        }
        if($funcionario){
            $funcionario->fun_actualizado='L';
            $funcionario->save();
        }

    }
    public function rules(): array
    {
        return [

        ];
    }
}
