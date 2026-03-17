<?php

namespace App\Imports;

use App\Models\Documento;
//use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Funcionario;
use App\Models\D_observacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow,WithValidation};

class ImportarDoc implements ToModel,WithHeadingRow,WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $funcionario="";
        $documento='';
        $vacio='';
        if($row['ci']!=''){
            $funcionario=Funcionario::all()->where('fun_ci','=',$row['ci'])->first();
        }else{
            $funcionario=Funcionario::all()->where('fun_nombre','=',$row['nombre'])->first();
        }
        if($funcionario){

        }else{
            if($row['ci']!='') {
                $funcionario = Funcionario::create([
                    'fun_ci' => $row['ci'],
                    'fun_nombre' => $row['nombre'],
                    'cod_nac' => 29,
                    'fun_doc_adm' => 'D',
                    'fun_nacionalidad' => 'B',
                    'fun_sexo' => 'M',
                ]);
            }else{
                if($row['nombre']!='') {
                    $funcionario = Funcionario::create([
                        'fun_ci' => $row['ci'],
                        'fun_nombre' => $row['nombre'],
                        'cod_nac' => 29,
                        'fun_doc_adm' => 'D',
                        'fun_nacionalidad' => 'B',
                        'fun_sexo' => 'M',
                    ]);
                }
            }
        }
        if($funcionario){
            if($funcionario->cod_fun){
                if($row['db']=='SI'){
                    $doc_umss=$row['udb']=='UMSS'?'t':'f';
                    $documento=Documento::create([
                        'doc_titulo'=>'DIPLOMA DE BACHILLER',
                        'cod_fun'=>$funcionario->cod_fun,
                        'doc_universidad'=>$row['udb'],
                        'doc_gestion'=>$row['gdb'],
                        'doc_legalizado'=>'t',
                        'doc_verificado'=>$row['vdb'],
                        'doc_umss'=>$doc_umss,
                        'doc_tipo'=>'DIPLOMA DE BACHILLER',
                        'doc_grado'=>'BACHILLER',
                    ]);
                    if($row['odb']!=''){
                        $observacion=D_observacion::create([
                            'cod_doc'=>$documento->cod_doc,
                            'od_obs'=>$row['odb'],
                            'od_fecha'=>date('d/m/Y'),
                        ]);
                        $documento->doc_obs='t'; $documento->save();
                        $funcionario->fun_obs='t';$funcionario->save();
                    }
                }else{
                    if($row['db']=='NO') {
                        $funcionario->fun_db = 'f';
                        $funcionario->save();
                    }
                }
                //====================================DA
                if($row['da']=='SI'){
                    $doc_umss=$row['uda']=='UMSS'?'t':'f';
                    $documento=Documento::create([
                        'doc_titulo'=>$row['tda'],
                        'cod_fun'=>$funcionario->cod_fun,
                        'doc_universidad'=>$row['uda'],
                        'doc_gestion'=>$row['gda'],
                        'doc_legalizado'=>'t',
                        'doc_verificado'=>$row['vda'],
                        'doc_umss'=>$doc_umss,
                        'doc_tipo'=>'DIPLOMA ACADEMICO',
                        'doc_grado'=>'PROFESIONAL',
                    ]);
                    if($row['oda']!=''){
                        $observacion=D_observacion::create([
                            'cod_doc'=>$documento->cod_doc,
                            'od_obs'=>$row['oda'],
                            'od_fecha'=>date('d/m/Y'),
                        ]);
                        $documento->doc_obs='t'; $documento->save();
                        $funcionario->fun_obs='t';$funcionario->save();
                    }
                }else{
                    if($row['da']=='NO') {
                        $funcionario->fun_da = 'f';
                        $funcionario->save();
                    }
                }
                //=====================================TPN
                if($row['tpn']=='SI'){
                    $doc_umss=$row['utpn']=='UMSS'?'t':'f';
                    $documento=Documento::create([
                        'doc_titulo'=>$row['ttpn'],
                        'cod_fun'=>$funcionario->cod_fun,
                        'doc_universidad'=>$row['utpn'],
                        'doc_gestion'=>$row['gtpn'],
                        'doc_legalizado'=>'t',
                        'doc_verificado'=>$row['vtpn'],
                        'doc_umss'=>$doc_umss,
                        'doc_tipo'=>'TITULO PROFESIONAL',
                        'doc_grado'=>'PROFESIONAL',
                    ]);
                    if($row['otpn']!=''){
                        $observacion=D_observacion::create([
                            'cod_doc'=>$documento->cod_doc,
                            'od_obs'=>$row['otpn'],
                            'od_fecha'=>date('d/m/Y'),
                        ]);
                        $documento->doc_obs='t'; $documento->save();
                        $funcionario->fun_obs='t';$funcionario->save();
                    }
                }else{
                    if($row['tpn']=='NO') {
                        $funcionario->fun_tp = 'f';
                        $funcionario->save();
                    }
                }
                //===============================DDU
                if($row['ddu']=='SI'){
                    $doc_umss=$row['uddu']=='UMSS'?'t':'f';
                    $documento=Documento::create([
                        'doc_titulo'=>$row['tddu'],
                        'cod_fun'=>$funcionario->cod_fun,
                        'doc_universidad'=>$row['uddu'],
                        'doc_gestion'=>$row['gddu'],
                        'doc_legalizado'=>'t',
                        'doc_verificado'=>$row['vddu'],
                        'doc_umss'=>$doc_umss,
                        'doc_edu_superior'=>'t',
                        'doc_tipo'=>mb_strtoupper($row['tiddu']),
                        'doc_grado'=>mb_strtoupper($row['grddu'])
                    ]);
                    if($row['oddu']!=''){
                        $observacion=D_observacion::create([
                            'cod_doc'=>$documento->cod_doc,
                            'od_obs'=>$row['oddu'],
                            'od_fecha'=>date('d/m/Y'),
                        ]);
                        $documento->doc_obs='t'; $documento->save();
                        $funcionario->fun_obs='t';$funcionario->save();
                    }
                }else{
                    if($row['ddu']=='NO') {
                        $funcionario->fun_ddu = 'f';
                        $funcionario->save();
                    }
                }
                //===============================TPOS
                if($row['pos']=='SI'){
                    $doc_umss=$row['upos']=='UMSS'?'t':'f';
                    $documento=Documento::create([
                        'doc_titulo'=>$row['tpos'],
                        'cod_fun'=>$funcionario->cod_fun,
                        'doc_universidad'=>$row['upos'],
                        'doc_gestion'=>$row['gpos'],
                        'doc_legalizado'=>'t',
                        'doc_verificado'=>$row['vpos'],
                        'doc_umss'=>$doc_umss,
                        'doc_tipo'=>mb_strtoupper($row['tipos']),
                        'doc_grado'=>mb_strtoupper($row['grpos'])
                    ]);
                    if($row['opos']!=''){
                        $observacion=D_observacion::create([
                            'cod_doc'=>$documento->cod_doc,
                            'od_obs'=>$row['opos'],
                            'od_fecha'=>date('d/m/Y'),
                        ]);
                        $documento->doc_obs='t'; $documento->save();
                        $funcionario->fun_obs='t';$funcionario->save();
                    }
                }

            }
        }


        /*return new Documento([
            //
        ]);*/
    }
    public function rules(): array
    {
        return [

        ];
    }

}
