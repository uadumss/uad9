<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ExportSinTomo implements FromQuery,WithHeadings
{
    use Exportable;
    protected $cod_tomo;
    protected $tipo;
    protected $numero=1;

    public function __construct($cod_tom,$tipo)
    {
        $this->cod_tomo = $cod_tom;
        $this->tipo=$tipo;
    }
    public function headings(): array
    {
        $cabecera=array();
        if($this->tipo=='ca' || $this->tipo=='da' || $this->tipo=='tp' || $this->tipo=='tpa'){
            $cabecera= ['N°','APELLIDO','NOMBRE', 'CI','PASSAPORTE', 'PAIS', 'FACULTAD', 'CARRERA', 'FECHA', 'NUMERO'];
        }else{
            if($this->tipo=='db' || $this->tipo=='re'){
                $cabecera= ['N°','APELLIDO','NOMBRE', 'CI','PASSAPORTE', 'PAIS',  'FECHA', 'NUMERO'];

            }else{
                if($this->tipo=='tpos' || $this->tipo=='di'){
                    $cabecera= ['N°','APELLIDO','NOMBRE', 'CI','PASSAPORTE', 'PAIS', 'MENSION', 'FECHA', 'NUMERO'];
                }else{
                    if($this->tipo=='su'){
                        $cabecera= ['N°','APELLIDO','NOMBRE', 'CI','PASSAPORTE', 'PAIS',  'FECHA', 'NUMERO'];
                    }
                }
            }
        }

        return $cabecera;
    }
    public function prepareRows($rows)
    {

        return $rows->transform(function ($titulo) {
            if($titulo->tit_fecha_emision!=''){
                $titulo->tit_fecha_emision = date('d/m/Y',strtotime($titulo->tit_fecha_emision));
            }
            $titulo->cod_tit=$this->numero;
            $this->numero++;
            return $titulo;
        });

    }
    public function query()
    {
        if($this->tipo=='ca' || $this->tipo=='da' || $this->tipo=='tp' || $this->tipo=='tpa'){
            $titulo = DB::table('titulos')
                ->join('personas', 'titulos.id_per', '=', 'personas.id_per')
                ->leftJoin('nacionalidads', 'personas.cod_nac', '=', 'nacionalidads.cod_nac')
                ->leftJoin('diploma_academicos', 'titulos.cod_tit', '=', 'diploma_academicos.cod_tit')
                ->leftJoin('carreras', 'diploma_academicos.cod_car', '=', 'carreras.cod_car')
                ->leftJoin('facultads', 'carreras.cod_fac', '=', 'facultads.cod_fac')
                ->leftJoin('modalidads', 'titulos.cod_mod', '=', 'modalidads.cod_mod')
                ->where('cod_tom', '=', $this->cod_tomo)
                ->select( 'titulos.cod_tit','per_apellido','per_nombre','per_ci','per_pasaporte','nac_codigo','fac_abreviacion','car_abreviacion', 'tit_fecha_emision','tit_nro_titulo')
                ->orderBy('fac_abreviacion', 'ASC')
                ->orderBy('car_abreviacion', 'ASC')
                ->orderBy('per_apellido', 'ASC')
                ->orderBy('per_nombre', 'ASC');
        }else{
            if($this->tipo=='db' || $this->tipo=='re'){
                $titulo=DB::table('titulos')
                    ->join('personas','titulos.id_per','=','personas.id_per')
                    ->leftJoin('nacionalidads', 'personas.cod_nac', '=', 'nacionalidads.cod_nac')
                    ->where('cod_tom','=',$this->cod_tomo)
                    ->select( 'per_apellido','per_nombre','per_ci','per_pasaporte','nac_codigo','tit_fecha_emision','tit_nro_titulo')
                    ->orderBy('tit_nro_titulo','ASC');

            }else{
                if($this->tipo=='tpos' || $this->tipo=='di'){
                    $titulo=DB::table('titulos')
                        ->join('personas','titulos.id_per','=','personas.id_per')
                        ->leftJoin('nacionalidads', 'personas.cod_nac', '=', 'nacionalidads.cod_nac')
                        ->leftJoin('modalidads','titulos.cod_mod','=','modalidads.cod_mod')
                        ->where('cod_tom','=',$this->cod_tomo)
                        ->select( 'per_apellido','per_nombre','per_ci','per_pasaporte','nac_codigo','tit_titulo', 'tit_fecha_emision','tit_nro_titulo')
                        ->orderBy('tit_nro_titulo','ASC');
                }else{
                    if($this->tipo=='su'){
                        $titulo=DB::table('titulos')
                            ->join('personas','titulos.id_per','=','personas.id_per')
                            ->leftJoin('nacionalidads', 'personas.cod_nac', '=', 'nacionalidads.cod_nac')
                            ->where('cod_tom','=',$this->cod_tomo)
                            ->select( 'per_apellido','per_nombre','per_ci','per_pasaporte','nac_codigo','tit_fecha_emision','tit_nro_titulo')
                            ->orderBy('per_apellido','ASC')
                            ->orderBy('per_nombre','ASC');
                    }
                }
            }
        }
        return $titulo;
    }
}
