<?php

namespace App\Imports;

use App\Diploma_academico;
use App\Persona;
use App\Revalida;
use App\T_observacion;
use App\Titulo;
use App\Tomo;
//use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow,WithValidation};



class ImportarDB implements ToModel,WithHeadingRow,WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use Importable;
    public function model(array $row)
    {
        $tituloRegisrado=DB::table('titulos')->join('tomos','titulos.cod_tom','=','tomos.cod_tom')
            ->where('tit_nro_titulo','=',$row['numero'])
            ->where('tom_tipo','=',$row['tipo'])
            ->where('tom_gestion','=',$row['ano'])
            ->get();
        if(sizeof($tituloRegisrado)>0){
            //dd($tituloRegisrado);
            return null;
        }else{
            $tomo=Tomo::where('tom_numero','=',$row['tomo'])
                ->where('tom_tipo','=',$row['tipo'])
                ->where('tom_gestion','=',$row['ano'])->get();
            $cod_tomo='';
            if(sizeof($tomo)<1){
                $tomo=Tomo::create([
                    'tom_numero'=>$row['tomo'],
                    'tom_gestion'=>$row['ano'],
                    'tom_tipo'=>$row['tipo'],
                ]);
                $cod_tomo=$tomo->cod_tom;
            }else{

                $cod_tomo=$tomo[0]->cod_tom;
            }
            $id_per=0;
            $apellido=mb_strtoupper($row['apellido']);
            $nombre=mb_strtoupper($row['nombre']);
            $personaExistente=DB::select("select id_per from personas where per_nombre='$nombre' and per_apellido='$apellido'");
            if(sizeof($personaExistente)>0){
                $id_per=$personaExistente[0]->id_per;
            }else{
                $persona=Persona::create([

                    'per_nombre'=>$nombre,
                    'per_apellido'=>$apellido,
                    'per_ci'=>$row['ci'],
                    'per_pasaporte'=>$row['pasaporte'],
                    'cod_nac'=>$row['pais'],
                ]);
                $id_per=$persona->id_per;
            }

            $obs=0;
	    
            if($row['observacion']!=''){
                $obs=1;
            }
		$revalida='';
	    if($row['re_pais']!="")
		{
			$revalida='t';
		}

            $titulo =Titulo::create([
                'tit_nro_titulo'=>$row['numero'],
                'tit_nro_folio'=>$row['folio'],
                'tit_fecha_emision'=>$row['fecha'],
                'tit_titulo'=>$row['titulo'],
                'tit_grado'=>$row['grado'],
                'cod_tom'=>$cod_tomo,
                'id_per'=>$id_per,
                'tit_obs'=>$obs,
                'tit_tipo'=>$row['tipo'],
                'cod_mod'=>$row['modalidad'],
                'tit_ref'=>$row['referencia'],
                'tit_revalida'=>$revalida,
                'tit_importacion'=>\Session::get('id_import'),
                'tit_gestion'=>$row['ano_tit'],

            ]);

            if($row['observacion']!=''){
                $observacion=T_observacion::create([
                    'obs_observacion'=>$row['observacion'],
                    'cod_tit' => $titulo->cod_tit,
                    'obs_fecha'=> date('d/m/Y'),
                ]);
            }
            if($row['tipo']=='ca' || $row['tipo']=='da' || $row['tipo']=='tp' ) {
                $diploma_a = Diploma_academico::create([
                    'cod_tit' => $titulo->cod_tit,
                    'cod_car' => $row['carrera']
                ]);
            }	
            if($row['tipo']=='re' || $row['re_pais']!='') {
                $revalida = Revalida::create([
                    'cod_tit' => $titulo->cod_tit,
                    'cod_nac'=>$row['re_pais'],
                    're_fecha' => $row['re_fecha'],
                    're_universidad'=>$row['re_universidad'],
                ]);
            }
            return $titulo;
        }
    }
    public function rules(): array
    {
        return [
            'fecha' => 'required|date',
            'apellido' => 'required',
            'tomo'=>'required',
            'ano'=>'required',
            'tipo'=>['required', Rule::in(['di','ca','da','db','tp','tpos','re','su'])],
        ];
    }
}
