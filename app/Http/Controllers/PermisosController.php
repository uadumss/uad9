<?php

namespace App\Http\Controllers;

use App\Models\Objeto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class PermisosController extends Controller
{
    public function listar_permisos($id,$num){

            $usuario=User::find($id);
            $vista=array('','','','','','','','','');

            $permisos=$usuario->getAllPermissions();
            $totalPermisos=PermisosController::obtPermisos(PermisosController::subsistema($num));
            $permisosUsuario=PermisosController::obtPermisosUsuario($id);
            //dd($permisosUsuario);
            //dd($totalPermisos);
            $vista[$num]='btn-success';
            $subsistema=PermisosController::subsistema($num);
            $objetos=Objeto::all()->where('obj_subsistema','=',"$subsistema");
            return view('permisos.l_permisos',compact('usuario','vista','num','permisos','subsistema','totalPermisos','objetos','permisosUsuario'));
    }
    public function guardar_permiso(Request $form){
        try {
            $permiso=Permission::all()->where('name','=',$form['permiso']);
            $exito = true;
            $mensaje = 'El permiso se creó satisfactoriamente';
            
            if(sizeof($permiso)<1){
                //dd($form);
                //Permission::create(['name'=>'hola']);
                $creado=Permission::create([
                    'name'=>$form['permiso'],
                    'guard_name'=>'api',
                    'objeto'=>$form['objeto'],
                    'leyenda'=>$form['leyenda'],
                ]);
                if (!$creado || !$creado->id) {
                    throw new \Exception('Error al crear el permiso en la base de datos');
                }
                //dd($creado);
                PermisosController::asignarAlAdministrador($form['permiso']);
            }else{
                $exito = false;
                $mensaje = 'El permiso =>'.$form['permiso'].' Ya existe';
            }
            
            if ($form->expectsJson() || $form->ajax()) {
                return response()->json([
                    'exito' => $exito,
                    'mensaje' => $mensaje,
                    'redirect' => url('listar permisos/'.$form['id'].'/'.$form['num'])
                ]);
            }
            
            if($exito) {
                \Session::flash('exito', $mensaje);
            } else {
                \Session::flash('error', $mensaje);
            }
            return redirect('listar permisos/'.$form['id'].'/'.$form['num']);
        } catch (\Exception $e) {
            if ($form->expectsJson() || $form->ajax()) {
                return response()->json([
                    'exito' => false,
                    'mensaje' => 'Error: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }
    public function guardar_objeto(Request $form){
        try {
            // Validar primero
            $form->validate([
               'objeto' => 'required|string',
               'subsistema'=>['required', Rule::in(
                   ['Diplomas y Títulos', 'Resoluciones','Apostilla','Docente Administrativo','Servicios','Administracion','Resoluciones RCF - RCC','No atentado','Claustros'])],
            ]);
            
            $objeto=Objeto::all()
                ->where('obj_nombre','=',$form['objeto'])
                ->where('obj_subsistema','=',$form['subsistema']);
            $exito = true;
            $mensaje = 'El objeto se creó satisfactoriamente';
            
            if(sizeof($objeto)<1) {
                $nuevoObjeto = Objeto::create([
                    'obj_nombre' => $form['objeto'],
                    'obj_subsistema' => $form['subsistema'],
                ]);
                if (!$nuevoObjeto || !$nuevoObjeto->cod_obj) {
                    throw new \Exception('Error al crear el objeto en la base de datos');
                }
            }else{
                $exito = false;
                $mensaje = 'El objeto =>'.$form['objeto'].' Ya existe';
            }
            
            if ($form->expectsJson() || $form->ajax()) {
                return response()->json([
                    'exito' => $exito,
                    'mensaje' => $mensaje,
                    'redirect' => url('listar permisos/'.$form['id'].'/'.$form['num'])
                ]);
            }
            
            if($exito) {
                \Session::flash('exito', $mensaje);
            } else {
                \Session::flash('error', $mensaje);
            }
            return redirect('listar permisos/'.$form['id'].'/'.$form['num']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($form->expectsJson() || $form->ajax()) {
                $mensajes = implode(', ', array_reduce($e->errors(), function($carry, $item) {
                    return array_merge($carry, $item);
                }, []));
                return response()->json([
                    'exito' => false,
                    'mensaje' => 'Error de validación: ' . $mensajes
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($form->expectsJson() || $form->ajax()) {
                return response()->json([
                    'exito' => false,
                    'mensaje' => 'Error: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }
    public function obtPermisos($subsistema){
        $permisos=DB::table('seguridad.permissions')
            ->join('seguridad.objetos','seguridad.permissions.objeto','=','objetos.cod_obj')
            ->where('objetos.obj_subsistema','=',"$subsistema")
            ->orderBy('seguridad.permissions.objeto')
            ->get();
        return $permisos;
    }
    public static function obtPermisosUsuario($id){
        $permisos=DB::table('seguridad.model_has_permissions')
            ->where('model_has_permissions.model_id','=',$id)
            ->get();
        return $permisos;
    }
    public function asignar_permiso(Request $form){
        $usuario=User::find($form['id']);
        /*$listaUsuarios=User::all()->where('bloqueado','=','f');

        foreach ($listaUsuarios as $lu){
            $lu->givePermissionTo($form['val']);
        }*/

        if($form['check']=='true'){
            $usuario->givePermissionTo($form['val']);
        }else{
            //dd($form);
            //return "hola";
            return $usuario->revokePermissionTo($form['val']);
        }

    }
    public static function asignarAlAdministrador($permiso){
        $administadores=User::all()->where('rol','=','ADMINISTRADOR');
        foreach ($administadores as $a):
            $a->givePermissionTo(Permission::all());
        endforeach;
    }
    public static function subsistema($num){
        switch ($num){
            case 0:
                return 'Diplomas y Títulos'; break;
            case 1:
                return 'Resoluciones'; break;
            case 2:
                return 'Servicios'; break;
            case 3:
                return 'Apostilla'; break;
            case 4:
                return 'Docente Administrativo'; break;
            case 5:
                return 'Administracion'; break;
            case 6:
                return 'Resoluciones RCF - RCC'; break;
            case 7:
                return 'No atentado'; break;
            case 8:
                return 'Claustros'; break;

            default:
                return "--";
                break;
        }
    }
}
