<?php

namespace App\Http\Controllers;

use App\Models\Diario;
use App\Http\Requests\UsuarioRequest;
use App\Models\Habilitacion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UsuarioController extends Controller
{
    public function g_usuario(UsuarioRequest $form){
        if(isset($form['id'])){
            $usuario=User::find($form['id']);
            $usuario->name=$form['nombre'];
            $usuario->sexo=$form['sexo'];
            $usuario->ci=$form['ci'];

            $usuario->cargo=$form['cargo'];
            $usuario->direccion=$form['direccion'];
            $usuario->contacto=$form['contacto'];
            if($form['rol']=='ADMINISTRADOR'){
                $usuario->givePermissionTo(Permission::all());
            }else{
                if($usuario->rol=='ADMINISTRADOR'){
                    $usuario->revokePermissionTo(Permission::all());
                }
            }
            $usuario->rol=$form['rol'];
            if($form->file('foto1')) {
                if(Storage::exists('img/foto/'.$usuario->foto)){
                    Storage::delete('img/foto/'.$usuario->foto);
                }
                $nombre=$usuario->id.'-'.$usuario->name.'.jpg';
                Image::make($form->file('foto1'))->resize(150, 150)->save('img/foto/'.$nombre);
                $usuario->foto=$nombre;
            }
            if($form['pas']!=''){
                if($form['pas']==$form['repas']){
                    $usuario->password=Hash::make($form['pas']);
                    $usuario->save();
                    \Session::flash('exito','Se ha actualizado exitosamente el usuario');
                }else{
                    \Session::flash('error','Los passwords no coinciden');
                }
            }else{
                $usuario->save();
                \Session::flash('exito','Se ha actualizado exitosamente el usuario');

            }
            return redirect('mostrar cuenta usuario/'.$usuario->id);
        }else{
            $usuarios=User::all()->where('login','=',$form['login']);
            if(sizeof($usuarios)<1){
                $usuario=User::create([
                    'name'=>mb_strtoupper($form['nombre']),
                    'ci'=>$form['ci'],
                    'email'=>$form['login'],
                    'contacto'=>$form['contacto'],
                    'direccion'=>$form['direccion'],
                    'bloqueado'=>'f',
                    'cargo'=>$form['cargo'],
                    'rol'=>$form['rol'],
                    'sexo'=>$form['sexo'],
                    'password'=>Hash::make($form['ci']),
                    'unidad'=>'Archivos'
                ]);
                $habilitacion=Habilitacion::create([
                    'hab_fecha'=>date('d-m-Y H:i:s'),
                    'hab_accion'=>'d',
                    'id_user'=>$usuario->id,
                ]);
                if($form->file('foto')) {
                    $nombre=$usuario->id.'-'.$usuario->name.'.jpg';
                    Image::make($form->file('foto'))->resize(150, 150)->save('img/foto/'.$nombre);
                    $usuario->foto=$nombre;
                    $usuario->save();
                }
                $usuario->assignRole($form['rol']);
                if($form['rol']=='ADMINISTRADOR'){
                    $usuario->givePermissionTo(Permission::all());
                }

                \Session::flash('exito','Se ha creado exitosamente el usuario');

            }else{
                \Session::flash('error','Ya existe un usario con ese login');
            }
            return redirect('l_usuario/f');
        }

    }
    public function f_habilitar_usuario($id){
        //return $id." - ";
        $usuario=User::find($id);
        return view('session.habilitar_usuario',compact('usuario'));
    }
    public function habilitar_usuario(Request $form){
        $usuario=User::find($form['id']);
        $bloqueado=$usuario->bloqueado;
        if($usuario->bloqueado=='f') {
            $usuario->bloqueado = 't';
            $habilitacion=Habilitacion::create([
                'hab_fecha'=>date('d-m-Y H:i:s'),
                'hab_accion'=>'b',
                'id_user'=>$usuario->id,
            ]);
            SessionController::write('U','','Deshabilitar','habilitacion','7',$usuario->id);
            \Session::flash('exito','Se ha bloqueado exitosamente al usuario ');
        }else{
            $usuario->bloqueado = 'f';
            $habilitacion=Habilitacion::create([
                'hab_fecha'=>date('d-m-Y H:i:s'),
                'hab_accion'=>'d',
                'id_user'=>$usuario->id,
            ]);
            SessionController::write('U','','Habilitar','habilitacion','7',$usuario->id);
            \Session::flash('exito','Se ha habilitado exitosamente al usuario');
        }

        $usuario->save();
        return redirect('l_usuario/'.$bloqueado);
    }
    public function f_editar_usuario($id){
        $usuario=User::find($id);
        return view('session.f_editar_usuario',compact('usuario'));
    }

    public function rendimiento($num,$id){
        $usu=User::find($id);
        if($num==0){
            return view('session.rendimiento.panel_rendimiento',compact('usu'));
        }else{
            return view('session.rendimiento.panel_rendimiento_usu',compact('usu'));
        }

    }
    public function grafico_rendimiento(Request $form){
        if($form['tipo']=='sis') {
            $id_per = $form['id_per'];
            $año = $form['a'];
            $mi = $form['mi'];
            $mes = $form['mi'];
            $mf = $form['mf'];
            $diafinal = date('d', (mktime(0, 0, 0, $mf + 1, 1, $año) - 1));

            $fecha_i = '01/' . $mi . '/' . $año;
            $fecha_f = $diafinal . '/' . $mf . '/' . $año;
            $diario = array();
            $usuario = User::find($form['id_per']);

            $diario = DB::select("select b.bit_id,b.bit_inicio,EXTRACT(MONTH from bit_inicio) as mes,count(e.cod_eve) as cantidad
                                from seguridad.bitacoras b, seguridad.evento_bitacoras e
                                where b.cod_bit=e.cod_bit and b.bit_id=$id_per and (e.eve_operacion='CREATE' or e.eve_operacion='C') and e.created_at>='$fecha_i' and e.created_at<='$fecha_f'
                                group by b.bit_id,b.bit_inicio order by b.bit_inicio;");

            $diario1 = DB::select("select b.bit_id,b.bit_inicio,EXTRACT(MONTH from bit_inicio) as mes,count(e.cod_eve) as cantidad
                                from seguridad.bitacoras b, seguridad.evento_bitacoras e
                                where b.cod_bit=e.cod_bit and b.bit_id=$id_per and (e.eve_operacion='UPDATE'  or e.eve_operacion='U') and e.created_at>='$fecha_i' and e.created_at<='$fecha_f'
                                group by b.bit_id,b.bit_inicio order by b.bit_inicio;");
            $diario2 = DB::select("select b.bit_id,b.bit_inicio,EXTRACT(MONTH from bit_inicio) as mes,count(e.cod_eve) as cantidad
                                from seguridad.bitacoras b, seguridad.evento_bitacoras e
                                where b.cod_bit=e.cod_bit and b.bit_id=$id_per and (e.eve_operacion='DELETE'  or e.eve_operacion='D') and e.created_at>='$fecha_i' and e.created_at<='$fecha_f'
                                group by b.bit_id,b.bit_inicio order by b.bit_inicio;");

            return view('session.rendimiento.grafico_rendimiento', compact('diario', 'diario1', 'diario2', 'mi', 'id_per', 'mf', 'año', 'mes', 'usuario'));
        }else{
            $id_per=$form['id_per'];
            $año=$form['a'];
            $mi=$form['mi'];
            $mes=$form['mi'];
            $mf=$form['mf'];
            $diafinal=date('d',(mktime(0,0,0,$mf+1,1,$año)-1));

            $fecha_i='01/'.$mi.'/'.$año;
            $fecha_f=$diafinal.'/'.$mf.'/'.$año;
            $diario=array();
            if($mi==$mf){

                if($mi<10){$mi="0".$mi;}
                $fecha_i=$año.'-'.$mi.'-01';
                $fecha_f=$año.'-'.$mi.'-'.$diafinal;
                $diario=Diario::all()
                    ->where('dia_fech','<=',$fecha_f)
                    ->where('dia_fech','>=',$fecha_i)
                    //->where('dia_cal','>',-0)
                    ->where('dia_calificacion')
                    ->where('id',$id_per)
                    ->sortBy('dia_fech');
            }else{
                $sql="select EXTRACT(MONTH from dia_fech) as mes, avg(dia_calificacion) as cal from diarios
                        where dia_fech between '$fecha_i' and '$fecha_f'
                        group by mes order by mes ASC";

                $diario=DB::select($sql);
            }
            $user=User::find($form['id_per']);
            return view('session.rendimiento.rendimiento_per',compact('diario','mi','id_per','mf','año','mes','user'));
        }
    }
    public function fe_datos_personales($id){
        $usuario=User::find($id);
        return view('usuario.fe_datos_personales',compact('usuario'));
    }
    public function g_cuenta_usuario(Request $form){
        if(isset($form['id'])){
            $usuario=User::find($form['id']);
            if($form->file('foto1')) {
                if(Storage::exists('img/foto/'.$usuario->foto)){
                    Storage::delete('img/foto/'.$usuario->foto);
                }
                $nombre=$usuario->id.'-'.$usuario->name.'.jpg';
                Image::make($form->file('foto1'))->resize(150, 150)->save('img/foto/'.$nombre);
                $usuario->foto=$nombre;
            }
            if($form['pas']!=''){
                if($form['pas']==$form['repas']){
                    $usuario->password=Hash::make($form['pas']);
                    $usuario->save();
                    \Session::flash('exito','Se ha actualizado exitosamente el usuario');
                }else{
                    \Session::flash('error','Los passwords no coinciden');
                }
            }
        }
        return redirect('editar datos personles/'.$form['id']);
    }
}
