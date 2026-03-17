<?php

namespace App\Imports;

use App\Models\Temas_resolucion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\{ WithHeadingRow,WithValidation};

class ImportarTema implements ToModel,WithHeadingRow,WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use Importable;
    public function model(array $row){

        $resolucion=DB::table('resolucions')
            ->where('res_numero','=',$row['numero'])
            ->where('res_tipo','=',$row['tipo'])
            ->where('res_gestion','=',$row['ano'])->first();
        if($resolucion){
            $temas_resolucion=Temas_resolucion::where('cod_res','=',$resolucion->cod_res)
                ->where('cod_tem','=',\Session::get('cod_tem'))->first();
            if(!$temas_resolucion){
                $uuid=(String)Str::uuid();
                $temas_resolucion=Temas_resolucion::create([
                    'cod_tr'=>$uuid,
                    'cod_res'=>$resolucion->cod_res,
                    'cod_tem'=>\Session::get('cod_tem'),
                ]);
                return $temas_resolucion;
            }
        }
    }
    public function rules(): array
    {
        return [
            'tipo' => 'required',
            'ano' => 'required',
            'numero'=>'required',
        ];
    }

}
