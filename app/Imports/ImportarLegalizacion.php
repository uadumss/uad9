<?php

namespace App\Imports;

use App\Models\D_tramita;
use App\Models\Persona;
use App\Models\Tramita;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\{ WithHeadingRow,WithValidation};

class ImportarLegalizacion implements ToModel,WithHeadingRow,WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use Importable;
    public function model(array $row)
    {
        $tramita=DB::table('tramitas')
            ->where('tra_numero','=',$row['tra_numero'])
            ->where('tra_fecha_solicitud','=',$row['fecha'])
            ->first();
        $cod_tra='';
        if($tramita){
            $tramita=Tramita::find($tramita->cod_tra);
            $cod_tra==$tramita->cod_tra;
        }else{
            $tramita=Tramita::create([
                'tra_numero'=>$row['tra_numero'],
                'tra_fecha_solicitud'=>$row['fecha'],
                'tra_tipo_tramite'=>$row['tra_tipo_tramite'],
                'tra_obs'=>$row['tra_obs'],
                'tra_gestion'=>$row['tra_gestion'],
            ]);
            $cod_tra=$tramita->cod_tra;
        }
        $id_per='';
        $persona=DB::table('personas')->where('per_ci','=',$row['per_ci'])->first();
        if($persona){
            $id_per=$persona->id_per;
        }else{
            $persona=Persona::create([
                'per_apellido'=>$row['per_apellido'],
                'per_nombre'=>$row['per_nombre'],
                'per_ci'=>$row['per_ci'],
                'per_ci_exp'=>$row['per_ci_exp'],
                'per_sexo'=>$row['per_sexo'],
                'cod_nac'=>29,
            ]);
            $id_per=$persona->id_per;
        }
        $tramita->id_per=$persona->id_per;
        $tramita->save();

        $d_tramita=DB::table('d_tramitas')
            ->where('cod_tra','=',$tramita->cod_tra)
            ->where('dtra_numero_tramite','=',$row['dtra_numero_tramite'])
            ->where('dtra_gestion_tramite','=',$row['dtra_gestion_tramite'])
            ->first();
        if($d_tramita){

        }else{
            $d_tramita=D_tramita::create([
                'cod_tra'=>$tramita->cod_tra,
                'cod_tre'=>$row['cod_tre'],
                'dtra_numero_tramite'=>$row['dtra_numero_tramite'],
                'dtra_gestion_tramite'=>$row['dtra_gestion_tramite'],
                'dtra_numero'=>$row['dtra_numero'],
                'dtra_gestion'=>$row['dtra_gestion'],
                'dtra_cod_tit'=>$row['dtra_cod_tit'],
                'dtra_estado_doc'=>$row['dtra_estado_doc'],
                'dtra_qr'=>$row['dtra_qr'],
                'dtra_titulo'=>$row['dtra_titulo'],
                'dtra_glosa'=>$row['dtra_glosa'],
                'dtra_glosa_posicion'=>$row['dtra_glosa_posicion'],
                'dtra_fecha_literal'=>$row['dtra_fecha_literal'],
                'dtra_anulado'=>$row['dtra_anulado'],
                'dtra_fecha_recojo'=>$row['dtra_fecha_recojo'],
                'dtra_obs'=>$row['dtra_obs'],
                'dtra_valorado'=>$row['dtra_valorado'],
                'dtra_valorado_busqueda'=>$row['dtra_valorado_busqueda'],
                'dtra_valorado_reintegro'=>$row['dtra_valorado_reintegro'],
                'dtra_costo'=>$row['dtra_costo'],
                'dtra_falso'=>$row['dtra_falso'],
                'dtra_generado'=>$row['dtra_generado'],
                'dtra_entregado'=>$row['dtra_entregado'],
                'dtra_interno'=>$row['dtra_interno'],
                'dtra_buscar_en'=>$row['dtra_buscar_en'],
                'dtra_cod_glosa'=>$row['dtra_cod_glosa'],
                'dtra_solo_sello'=>$row['dtra_solo_sello'],
                'dtra_fecha_registro'=>$row['dtra_fecha_registro'],
                'dtra_ptaang'=>$row['dtra_ptaang'],
                'dtra_verificacion_sitra'=>$row['dtra_verificacion_sitra'],
                'dtra_supletorio'=>$row['dtra_supletorio'],
            ]);
            return $d_tramita;
        }

    }
    public function rules(): array
    {
        return [
            'cod_tra' => 'required',
        ];
    }
}
