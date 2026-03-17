<?php

namespace App\Imports;

use App\Autoridad;
use App\Firma;
use App\Plan_archivo;
use App\Resolucion;
use App\Codigo_archivo;
use App\Archivado;

//use Maatwebsite\Excel\Concerns\ToModel;

use App\Tomo;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow,WithValidation};


class importarRESC implements ToModel,WithHeadingRow,WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use Importable;
    public function model(array $row)
    {
        $resolucion = Resolucion::all()->where('res_desc', '=', $row['referencia'])
            ->where('res_numero', '=', '4')
            ->where('res_importacion', '=', '1-1784763771-RR2010')->first();
        if ($resolucion){
            $resolucion->res_numero = $row['numero'];
            $resolucion->save();
        }
        return null;
    }
    public function rules(): array
    {
        return [
            'fecha' => 'required|date',
            'numero' => 'required',
            'tomo'=>'required',
            'ano'=>'required',
            'tipo'=>['required', Rule::in(['rr','rcu','rs','rvr','rcf','rcc'])],
        ];
    }
}
