<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Evento_bitacora;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class SessionController extends Controller
{
    /*
     * Variable sistema
     * 1 =>Sistema de Diplomas y Titulos
     * 2 =>Sistema de Resoluciones
     * 3 =>Sistema de Servicios
     *  =>Sistema de Servicios
     *
     * 8 => Administracion
     */
    public function l_usuario($bloqueado){
        $usuarios=User::all()->where('unidad','=','Archivos')->where('bloqueado','=',$bloqueado)->sortBy('name');
        return view('session.l_usuarios',compact('usuarios','bloqueado'));
    }
    public function lista_conexion($bit_id){
        $conexion=Bitacora::all()->where('bit_id','=',$bit_id)->sortByDesc('bit_inicio');
        $usuario=User::find($bit_id);

        return view('session.lista_conexiones',compact('conexion','usuario','bit_id'));
    }
    public function lista_acciones($id_con){
        $conexion=Bitacora::find($id_con);
        $usuario=User::find($conexion->bit_id);
        $acciones=DB::table('seguridad.evento_bitacoras')->where('cod_bit','=',$id_con)
            ->select('cod_eve','created_at','eve_tabla','eve_operacion')->orderByDesc('cod_eve')->get();
        //$acciones=Evento_bitacora::all()->where('cod_bit','=',$id_con)->sortByDesc('cod_eve');
        //return sizeof($acciones);
        return view('session.lista_acciones',compact('conexion','usuario','acciones'));
    }
    public function ver_accion($cod_eve){
        $accion=Evento_bitacora::find($cod_eve);
        $conexion=Bitacora::find($accion['cod_bit']);
        return view ('session.ver_accion',compact('accion','conexion'));
    }
    public function titulos_eliminados($pdf,$sistema){
            $ruta='';
            switch ($sistema){
                case 1:$ruta = 'trash/dt/'.$pdf;break;
                case 2:$ruta = 'trash/res/'.$pdf;break;
            }
            if (Storage::exists($ruta)) {
                return Storage::response($ruta);
            } else {
                $var = "<div class='alert alert-danger alert-dismissible'>No existe el archivo</div>";
                return $var;
            }
    }

    public static function write($operacion,$antiguo,$nuevo,$tabla,$sistema,$cod_objeto){
        $evento=Evento_bitacora::create([
           'eve_operacion'=>$operacion,
            'eve_nuevo'=>$nuevo,
            'eve_antiguo'=>$antiguo,
            'eve_tabla'=>$tabla,
            'eve_sistema'=>$sistema,
            'eve_cod_objeto'=>$cod_objeto,
            'cod_bit'=>\Session::get('cod_bit'),
        ]);
        return 0;
    }
    public static function ip_host(){
        $conexion='';
        if (isset($_SERVER["HTTP_CLIENT_IP"]))
        {
             $conexion.=" - HTTP_CLIENT_IP: ".$_SERVER["HTTP_CLIENT_IP"];
        }
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
            $conexion.=" - HTTP_X_FORWARDED_FOR: ".$_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        if (isset($_SERVER["HTTP_X_FORWARDED"]))
        {
            $conexion.=" - HTTP_X_FORWARDED: ".$_SERVER["HTTP_X_FORWARDED"];
        }
        if (isset($_SERVER["HTTP_FORWARDED_FOR"]))
        {
            $conexion.=" - HTTP_FORWARDED_FOR: ".$_SERVER["HTTP_FORWARDED_FOR"];
        }
        if (isset($_SERVER["HTTP_FORWARDED"]))
        {
            $conexion.=" - HTTP_FORWARDED: ".$_SERVER["HTTP_FORWARDED"];
        }
        if (isset($_SERVER["REMOTE_ADDR"]))
        {
            $conexion.=" - REMOTE_ADDR: ".$_SERVER["REMOTE_ADDR"];
        }
        return $conexion;
    }
    public static function reporte_evento($cod,$sistema,$tabla){
        $resultado=DB::table('seguridad.evento_bitacoras')
            ->join('seguridad.bitacoras','seguridad.evento_bitacoras.cod_bit','=','seguridad.bitacoras.cod_bit')
            ->where('eve_cod_objeto','=',$cod)
            ->where('eve_sistema','=',$sistema)
            ->where('eve_tabla','=',$tabla)
            ->select('evento_bitacoras.*','bitacoras.bit_usuario')->orderByDesc('created_at')->get();

        return $resultado;
    }
}
