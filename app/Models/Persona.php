<?php

namespace App\Models;

use App\Http\Controllers\TramiteLegalizacionController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Persona extends Model
{
    protected $fillable=['per_ci','per_pasaporte','per_apellido','per_nombre','per_cod_sis',
                        'per_sexo','per_telefono','per_celular','per_contacto','cod_nac','per_ci_exp','per_email','per_sistema'];
    protected $table='personas';
    protected $primaryKey='id_per';

    public static function getDatosPersonales($ci){
        $datos=DB::select("select * from personas where per_ci='$ci'");
        $nombres="";
        foreach ($datos as $d):
            $nombres.=$d->per_apellido." ".$d->per_nombre."<br/>";
        endforeach;
        return $nombres;
    }
    public static function getDatosTablas($id_per){
        $titulos='';
        $apostilla='';
        $noatentado='';
        $tramitas='';
        $sancionados='';
        $sitra=array();
        $persona=Persona::find($id_per);
        $respuesta=0;
        if($id_per!='' ) {
            $titulos=DB::select('select tit_tipo,tit_gestion,tit_nro_titulo,created_at from titulos where id_per='.$id_per);
            $apostilla=DB::select('select apos_numero,apos_gestion,created_at from apostilla.apostilla where id_per='.$id_per);
            $noatentado=DB::select('select noa_cargo,created_at from noatentado.noatentado where id_per='.$id_per);
            $tramitas=DB::select('select tra_tipo_tramite,tra_numero,tra_gestion,created_at from tramitas where id_per='.$id_per);
            $sancionados=DB::select('select san_sentencia,created_at from noatentado.sancionados where id_per='.$id_per);
            foreach ($titulos as $t):
                $Tramite=new TramiteLegalizacionController();
                $sitra=$Tramite->verificarSitra($persona->per_ci,$t->tit_nro_titulo,$t->tit_tipo);
                if($sitra->nombre==($persona->per_apellido." ".$persona->per_nombre)){
                    $respuesta=1;
                }
            endforeach;

        }else{

        }
        //return sizeof($titulos).'-'.sizeof($apostilla).'-'.sizeof($noatentado).'-'.sizeof($tramitas).'-'.sizeof($sancionados);
        return view('session.administracion.persona.duplicado.detalle_enlace',compact('titulos','apostilla','tramitas','sancionados','noatentado','respuesta'));
    }
    public static function Verificar_sitra($ci,$numero,$tipo){

    }
}
