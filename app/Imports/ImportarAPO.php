<?php

namespace App\Imports;

use App\Models\Apostilla;
use App\Models\Lista_doc_apostilla;
use App\Models\Persona;
use App\Models\Apoderado;
use App\Models\Detalle_apostilla;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow,WithValidation};
use Illuminate\Support\Str;


class ImportarAPO implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $persona=Persona::where('per_ci','=',$row['ci'])->first();
        if(!$persona){
            $persona=Persona::create([
                'per_nombre'=>$row['nombre'],
                'per_apellido'=>$row['apellido'],
                'per_ci'=>$row['ci'],
                'per_celular'=>$row['telefono'],
                'per_sistema'=>4,
            ]);
        }else{
            $persona->per_sistema=4;
            $persona->save();
        }
        $cod_apo=null;
        $apoderado=array();
        if($row['ci_apoderado']!=''){
            $apoderado=Apoderado::where('apo_ci','=',$row['ci_apoderado'])->first();
            if(!$apoderado){
                $apoderado=Apoderado::create([
                    'apo_ci'=>$row['ci_apoderado'],
                    'apo_nombre'=>$row['nombre_apoderado'],
                    'apo_apellido'=>$row['apellido_apoderado'],
                    'apo_sistema'=>4,
                ]);

            }else{
                $apoderado->apo_sistema=4;
                $apoderado->save();
            }
            $cod_apo=$apoderado->cod_apo;
        }
        $uuid=(String)Str::uuid();
        $fecha=date('d/m/Y',strtotime($row['fecha']));

        if($row['detalle'][0]=='='){
            $row['detalle']=substr($row['detalle'], 2);     // bcdef
        }else{
            $row['detalle']=substr($row['detalle'], 1);     // bcdef
        }

        $detalle=explode('-',$row['detalle']);
        $tramite_apostilla=Apostilla::create([
            'id_per'=>$persona->id_per,
            'cod_apos'=>$cod_apo,
            'cod_apos'=>$uuid,
            'apos_numero'=>$row['numero_tra'],
            'apos_clave'=>$row['clave'],
            'apos_qr'=>$row['qr'],
            'apos_fecha_ingreso'=>$fecha,
            'apos_gestion'=>date('Y',strtotime($fecha)),
            'apos_obs'=>sizeof($detalle)."/".utf8_decode($row['detalle']),
            'apos_estado'=>3,
            'created_at'=>$row['created_at'],
            'updated_at'=>$row['updated_at'],
            //'apos_estado'=>sizeof($detalle),
        ]);
        $detalle=explode('-',$row['detalle']);
        $lista_documentos["Diploma de Bachiller"]=133;
        $lista_documentos["Diploma Académico"]=134;
        $lista_documentos["Transcripción de notas secundaria"]=163;
        $lista_documentos["Transcripción de Notas secundaria"]=163;


        $lista_documentos["Título en Provisión Nacional"]=135;
        $lista_documentos["Maestría"]=136;
        $lista_documentos["Especialidad"]=137;
        $lista_documentos["Diplomado"]=138;
        $lista_documentos["Doctorado"]=139;

        $lista_documentos["Cert. Acreditativo Diploma de Bachiller"]=118;
        $lista_documentos["Cert. Acreditativo Dip. Bachiller"]=118;

        $lista_documentos["Cert. Acreditativo Diploma Académico"]=119;
        $lista_documentos["Cert. Acreditativo Dip. Académico"]=119;


        $lista_documentos["Cert. Acreditativo T.P.N"]=120;
        $lista_documentos["Cert. Acreditativo de Maestría"]=121;
        $lista_documentos["Cert. Acreditativo Maestría"]=121;


        $lista_documentos["Cert. Acreditativo de Especialidad"]=122;
        $lista_documentos["Cert. Acreditativo Especialidad"]=122;


        $lista_documentos["Cert. Acreditativo de Diplomado"]=123;
        $lista_documentos["Cert. Acreditativo Diplomado"]=123;

        $lista_documentos["Cert. Acreditativo de Doctorado"]=124;
        $lista_documentos["Cert. Acreditativo Doctorado"]=124;


        $lista_documentos["Cert. Conclusión de plan de estudios"]=125;
        $lista_documentos["Cert. Conclusión de Plan de Estudios"]=125;

        $lista_documentos["Cert. Promedio General"]=126;

        $lista_documentos["Cert. Estudio de Posgrado"]=128;
        $lista_documentos["Certificación al Mercosur"]=129;
        $lista_documentos["Cert. Universidad Plena"]=130;
        $lista_documentos["Certificación Nomina Docente"]=131;
        $lista_documentos["Carga Horaria"]=140;
        $lista_documentos["Internado Rotatorio"]=141;
        $lista_documentos["Años de Provincia"]=142;
        $lista_documentos["Internado sin Carga Horaria"]=143;
        $lista_documentos["Internado con Carga Horaria"]=144;
        $lista_documentos["Plan de Estudios"]=145;
        $lista_documentos["Certificado de Notas"]=132;
        $lista_documentos["Programas Analíticos"]=146;
        $lista_documentos["Resolución de Revalida"]=147;
        $lista_documentos["Resolución por excelencia"]=148;

        $lista_documentos["Resolución concede Diploma académico"]=149;
        $lista_documentos["Resolución que concede Dip. Académico"]=149;

        $lista_documentos["Resolución que concede Maestrías"]=150;
        $lista_documentos["Resolución concede Titulo en provision Nacional"]=150;
        $lista_documentos["Resolución que concede T.P.N"]=150;


        $lista_documentos["Certificado de Estudio"]=164;
        $lista_documentos["Acta de Defensa de Grado"]=165;
        $lista_documentos["Traducción Diploma Académico"]=151;

        $lista_documentos["Traducción Titulo en Provisión Nacional"]=152;
        $lista_documentos["Traducción de T.P.N"]=152;

        $lista_documentos["Traducción Maestría"]=153;
        $lista_documentos["Traducción Otros"]=153;

        $lista_documentos["Fotocopia Legalizada de Diploma de Bachiller"]=154;
        $lista_documentos["Fotocopia Leg. Dip. Bachiller"]=154;

        $lista_documentos["Fotocopia Legalizada de Diploma Académico"]=155;
        $lista_documentos["Fotocopia Leg. Dip. Académico"]=155;

        $lista_documentos["Fotocopia Legalizada Transcripción de notas de secundaria"]=156;
        $lista_documentos["Fotocopia Leg. Transcripción de Notas de secundaria"]=156;
        $lista_documentos["Fotocopia Leg. Transcripción de notas de secundaria"]=156;

        $lista_documentos["Fotocopia Legalizada Titulo en Provisión Nacional"]=157;
        $lista_documentos["Fotocopia Leg. T.P.N"]=157;

        $lista_documentos["Fotocopia Legalizada Maestría"]=158;
        $lista_documentos["Fotocopia Leg. Maestría"]=158;

        $lista_documentos["Fotocopia Legalizada Especialidad"]=159;
        $lista_documentos["Fotocopia Leg. Especialidad"]=159;


        $lista_documentos["Fotocopia Legalizada Diplomado"]=160;
        $lista_documentos["Fotocopia Leg. Diplomado"]=160;

        $lista_documentos["Fotocopia Legalizada Doctorado"]=161;
        $lista_documentos["Fotocopia Leg. Doctorado"]=161;

        $lista_documentos["Cert. Conversión de notas"]=127;
        $lista_documentos["Cert. Conversión de Notas"]=127;

        foreach ($detalle as $d):
            $uuid=(String)Str::uuid();

                $cod_lis=$lista_documentos[utf8_decode($d)];

            if($cod_lis!=0){
                $detalle_apostilla=Detalle_apostilla::create([
                    'cod_dapo'=>$uuid,
                    'cod_apos'=>$tramite_apostilla->cod_apos,
                    'dapo_fecha_ingreso'=>$fecha,
                    'dapo_hab'=>'t',
                    'cod_lis'=>$cod_lis,
                ]);
            }
        endforeach;

        return $tramite_apostilla;
    }

}
