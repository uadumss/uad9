<?php

namespace App\Http\Controllers;

use App\Models\A_cargo;
use App\Models\Apoderado;
use App\Models\D_tramita;
use App\Models\Nacionalidad;
use App\Models\Persona;
use App\Models\Persona_prueba;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonaController extends Controller
{
    public function __construct(){
        $this->middleware(['permission:corregir datos personales  ci - adm'], ['only' => ['formulario_corregir','g_persona','buscar_ci']]);

        $this->middleware(['permission:corregir duplicados - adm'], ['only' => ['corregir_duplicados','corregir_persona_ci_duplicado','lista_duplicados','lista_duplicado']]);

    }
    public function datos_per($ci){
        $persona=Persona::where('per_ci','=',$ci)->select('per_nombre','per_apellido','per_sexo','per_ci_exp','per_celular','per_cod_sis')->get();
        $dato="No";
        if(sizeof($persona)>0){
            $dato=json_encode($persona[0]);
        }
        return ($dato);
    }
    public function datos_apo($ci){
        $persona=Apoderado::where('apo_ci','=',$ci)->select('apo_nombre','apo_apellido')->get();
        $dato="No";
        if(sizeof($persona)>0){
            $dato=json_encode($persona[0]);
        }
        return ($dato);
    }
    // FUNCION TEMPORAL PARA CORREGIR Ñ
    public function corregir(){

        $persona=DB::select("select * from personas where per_apellido like '%EÃ%'");
        foreach ($persona as $p):
            $letra="Ñ";
            $corregido= str_replace("Ã", $letra, $p->per_apellido);
            $per=Persona::find($p->id_per);
            $per->per_apellido=$corregido;
            $per->save();
        endforeach;
        return view('corregir',compact('persona'));
    }
    public function m_c_persona($id_per){
        //$usu=Per::find($id_per);
        $usu=User::find($id_per);
        //$responsable=Responsable::find($id_per);
        //$funcionario=Funcionario::find($id_per);
        return view('session.administracion.usuario',compact('usu'));
    }
    //==================RESPONSABLES===============
    public function listaFun_cargo($id){
        $responsable=User::find($id);
        $fun=DB::table('a_cargos')
            ->join('users','a_cargos.id','=','users.id')
            ->select('users.name','a_cargos.id','a_cargos.ac_inicio','a_cargos.ac_fin',
                'a_cargos.cod_ac','a_cargos.ac_hab','users.foto')
            ->where('a_cargos.id_responsable',$id)
            ->where('a_cargos.ac_fin', NULL)
            ->orderBy('users.name')
            ->get();

        $sql="select * from users where bloqueado='f' and id<>$id and id not in ".
            "(select a.id from a_cargos a, users p where a.id=p.id and a.id_responsable=$id and a.ac_fin IS NULL) ".
            " order by name ASC";
        $personas=DB::select($sql);

        $personas=DB::select($sql);
        //$personas=Persona::all()->where('per_hab','=','t')->where('id_per','<>',$ip)->sortBy('per_ape');

        return view('session.administracion.e_asignacion',compact('fun','personas','id','responsable'));
    }
    public function habilitar_responsable(Request $form){
        $id=$form['res'];
        $cuenta=User::find($id);
        $cuenta->responsable='t';
        $cuenta->save();
        return redirect("lista a cargos/".$id);
    }
    public function a_verificar_acargo(Request $request){
        $fun=$request['ip'];
        $res=$request['res'];
        $lista_resp=DB::table('a_cargos')
            ->join('users','a_cargos.id_responsable','=','users.id')
            ->select('users.name')
            ->where('a_cargos.ac_fin',NULL)
            ->where('a_cargos.id','=',$fun)
            ->get();
        if(sizeof($lista_resp)>0){
            $texto="<h6 class='text-dark'>El funcionario, esta asignado a :</h6>
                    <div>";
            $i=1;
            foreach ($lista_resp as $l):
                $texto.=$i.". ".$l->name."<br/>";
                $i++;
            endforeach;
            $texto.="</div><br/>";
            return $texto;
        }else{
            return null;
        }
    }
    public function a_g_acargo(Request $request){

        //return $request['fun']."  ".$request['res'];
        A_cargo::create([
            'id'=>$request['fun'],
            'id_responsable'=>$request['res'],
            'ac_hab'=>'t',
            'ac_inicio'=>date('d/m/Y'),
        ]);
        \Session::flash('exito',"La asignacion se ha hecho de manera satisfactoria");
        return redirect('lista a cargos/'.$request['res']);
    }
    public function f_habilitar_asignacion($tipo,$id){
        $a_cargo=A_cargo::find($id);
        $user=User::find($a_cargo->id);
        switch ($tipo){
            case 'a':
                return view('session.administracion.asignacion.f_habilitar_asignacion',compact('a_cargo','user'));
                break;
            case 'f':
                return view('session.administracion.asignacion.f_finalizar_asignacion',compact('a_cargo','user'));
                break;
            case 'e':
                return view('session.administracion.asignacion.f_eliminar_asignacion',compact('a_cargo','user'));
                break;
        }

    }
    public function habilitar_asignacion_funcionario(Request $form)
    {
        $a_cargo = A_cargo::find($form['cac']);
        switch ($form['tipo']){
            case 'a':
                $a_cargo->ac_hab == 't' ? $a_cargo->ac_hab = 'f' : $a_cargo->ac_hab = 't';
                $a_cargo->save();
                $a_cargo->ac_hab == 't' ? \Session::flash('exito', 'Se ha habilitado al funcionario exitosamente') : \Session::flash('exito', 'Se ha deshabilitado al funcionario exitosamente');
                break;
            case 'f':
                $a_cargo->ac_fin = date('d/m/Y');
                $a_cargo->save();
                \Session::flash('exito', 'Se ha registrado la conclusión de la asignación del funcionario exitosamente');
                break;
            case 'e':
                $a_cargo->delete();
                \Session::flash('exito', 'Se ha eliminado la asignación del funcionario exitosamente');
                break;
            }

        return redirect('lista a cargos/'.$a_cargo->id_responsable);
    }
    public function historialAsigFun($id){
        $fun=DB::table('a_cargos')
            ->join('users','a_cargos.id','=','users.id')
            ->select('users.name','a_cargos.id','a_cargos.ac_inicio','a_cargos.ac_fin',
                'a_cargos.cod_ac','a_cargos.ac_hab','users.foto')
            ->where('a_cargos.id_responsable','=',$id)
            ->orderByDesc('a_cargos.ac_inicio')
            ->orderByDesc('a_cargos.ac_fin')
            ->get();
        //return sizeof($fun).' - '.$ip;
        return view('session.administracion.historial_asignacion',compact('fun','id'));
    }
    public function formulario_corregir(){
        return view('session.administracion.persona.correccion.l_persona');
    }

    public function buscar_ci(Request $form){
	$form->validate([
            'ci'=>'required',
        ]);
        $persona=Persona::where('per_ci','=',$form['ci'])->first();
        //dd($persona);
        $nacionalidad=Nacionalidad::all()->sortBy('nac_nombre');
        return view('session.administracion.persona.correccion.fe_persona',compact('persona','nacionalidad'));
    }
    public function g_persona(Request $form){
        $form->validate([
            'ci'=>'required','ip'=>'required','apellido'=>'required','nombre'=>'required',
        ]);
        $persona=Persona::find($form['ip']);
        $nacionalidad=Nacionalidad::all()->sortBy('nac_nombre');
        if($persona) {
            $nombre=$persona->per_nombre." ".$persona->per_apellido;
            $antiguo=json_encode($persona);
            $persona->per_ci=$form['ci'];
            $persona->per_pasaporte=$form['pass'];
            $persona->per_apellido=mb_strtoupper($form['apellido']);
            $persona->per_nombre=mb_strtoupper($form['nombre']);
            $persona->per_sexo=mb_strtoupper($form['sexo']);
            $persona->cod_nac=mb_strtoupper($form['nac']);
            $persona->per_ci_exp=mb_strtoupper($form['expedido']);
            $persona->save();
            $nuevo=json_encode($persona);
            SessionController::write('U',$antiguo,$nuevo,'personas','3',$persona->id_per);
            \Session::flash('exito persona','Se ha actualizado correctamente los datos');

            $docleg=DB::select("select d.* from d_tramitas d, tramitas t ,personas p where per_ci='".$form['ci']."' and p.id_per=t.id_per and t.cod_tra=d.cod_tra;");

            $glosa1="";
            foreach ($docleg as $d){
                $glosa=$d->dtra_glosa;
                $glosa1= str_replace($nombre, mb_strtoupper($form['nombre'])." ".mb_strtoupper($form['apellido']), $glosa);
                $d_tramita=D_tramita::find($d->cod_dtra);
                $d_tramita->dtra_glosa=$glosa1;
                $d_tramita->save();
            }
            //return $glosa1;
            return view('session.administracion.persona.correccion.fe_persona',compact('persona','nacionalidad'));

        }else{
            \Session::flash('error persona','Ocurrio un error al actualizar los datos');
        }
    }
    public function f_persona(){
        return view('session.administracion.persona.persona');
    }
    public function f_duplicados(){
        return view('session.administracion.persona.duplicado.f_duplicados');
    }
    public function lista_duplicados($tipo){
        switch ($tipo){
            case 'total':
                $lista=DB::select('select count(per_ci), per_ci, per_apellido,per_nombre from personas group by per_ci,per_apellido,per_nombre  HAVING count(per_ci)>1 order by per_apellido, per_nombre ASC');
                //return sizeof($lista);
                return view('session.administracion.persona.duplicado.l_duplicados',compact('lista','tipo'));

                break;
            case 'apellido':
                $lista=DB::select('select count(per_ci), per_ci,per_apellido from personas
                                        where per_ci not in (select per_ci from personas group by per_ci,per_nombre,per_apellido having count(per_ci)>1)
                                        group by per_ci,per_apellido HAVING count(per_ci)>1;');
                return view('session.administracion.persona.duplicado.l_duplicados',compact('lista','tipo'));
                break;


            case 'nombre':
                $lista=DB::select('select count(per_ci), per_ci,per_nombre from personas
                                        where per_ci not in (select per_ci from personas group by per_ci,per_nombre,per_apellido having count(per_ci)>1)
                                        group by per_ci,per_nombre HAVING count(per_ci)>1;');
                //return sizeof($lista);
                return view('session.administracion.persona.duplicado.l_duplicados',compact('lista','tipo'));
                break;

            case 'ci':
                $lista=DB::select('select count(per_ci), per_ci from personas
                                        where per_ci not in (select per_ci from personas group by per_ci,per_nombre,per_apellido having count(per_ci)>1)
                                        and per_ci not in (select per_ci from personas group by per_ci,per_nombre having count(per_ci)>1)
                                        and per_ci not in (select per_ci from personas group by per_ci,per_apellido having count(per_ci)>1)
                                        group by per_ci HAVING count(per_ci)>1;');

                return view('session.administracion.persona.duplicado.l_duplicados',compact('lista','tipo'));
                break;
        }
    }
    public function lista_duplicado($tipo,$ci){
        $duplicados='';
        if($ci!=''){
            $duplicados=Persona::where('per_ci','=',$ci)->orderBy('id_per','ASC')->get();
        }else{
            \Session::flash('error_modal','CI no válido');
        }
        return view('session.administracion.persona.duplicado.fe_duplicado',compact('duplicados','tipo'));
    }
    public function corregir_duplicados(Request $form){
        $form->validate([
            'ip'=>'required',
            'ci'=>'required',
            'tipo'=>'required',
        ]);
        $duplicados=Persona::where("per_ci","=",$form['ci'])->where('id_per','<>',$form['ip'])->get();
        //dd($duplicados);
        $persona=Persona::find($form['ip']);

        //$lista=DB::select('select count(per_ci), per_ci, per_apellido,per_nombre from personas group by per_ci,per_apellido,per_nombre HAVING count(per_ci)>1;');
        foreach ($duplicados as $d){
            DB::update("update titulos set id_per=".$form['ip']." where id_per=".$d->id_per);
            DB::update("update tramitas set id_per=".$form['ip']." where id_per=".$d->id_per);
            DB::update("update noatentado.noatentado set id_per=".$form['ip']." where id_per=".$d->id_per);
            DB::update("update apostilla.apostilla set id_per=".$form['ip']." where id_per=".$d->id_per);
            DB::update("update noatentado.sancionados set id_per=".$form['ip']." where id_per=".$d->id_per);
            $this->copiar_datos_personales($persona,$d);

            return $persona->per_apellido.' '.$persona->per_nombre;
        }
    }
    public function copiar_datos_personales($persona1,$persona2){
        if($persona1->per_cod_sis==""){
            $persona1->per_cod_sis=$persona2->per_cod_sis;
        }
        if($persona1->per_sexo==''){
            $persona1->per_cod_sis=$persona2->per_cod_sis;
        }
        if($persona1->per_telefono==''){
            $persona1->per_telefono=$persona2->per_telefono;
        }
        if($persona1->per_celular==''){
            $persona1->per_celular=$persona2->per_celular;
        }
        if($persona1->per_contacto==''){
            $persona1->per_contacto=$persona2->per_contacto;
        }
        if($persona1->per_ci_exp==''){
            $persona1->per_ci_exp=$persona2->per_ci_exp;
        }
        if($persona1->per_email==''){
            $persona1->per_email=$persona2->per_email;
        }
        if($persona1->cod_nac==''){
            $persona1->cod_nac=$persona2->cod_nac;
        }
        $persona1->save();
        $antiguo=json_encode($persona2);
        SessionController::write('D',"ELIMINADO POR DUPLICADO<br/>".$antiguo,'','personas',8,$persona2->id_per);
        $persona2->delete();
    }
    public function corregir_persona_ci_duplicado(Request $form){
        $form->validate([
            'ci'=>'required','ip'=>'required','apellido'=>'required','nombre'=>'required','tipo'=>'required',
        ]);
        $persona=Persona::find($form['ip']);
        $ci=$persona->per_ci;
        if($persona) {
            $nombre=$persona->per_nombre." ".$persona->per_apellido;
            $antiguo=json_encode($persona);
            $persona->per_ci=$form['ci'];

            $persona->per_apellido=mb_strtoupper($form['apellido']);
            $persona->per_nombre=mb_strtoupper($form['nombre']);
            $persona->save();
            $nuevo=json_encode($persona);
            SessionController::write('U','MODIFICACION POR DUPLICADO<br/>'.$antiguo,$nuevo,'personas','8',$persona->id_per);
            \Session::flash('exito_modal','Se ha actualizado correctamente los datos');

            $persona_correcta=DB::select("select * from personas where per_ci='".$ci."'");
            $nombre="";
            foreach ($persona_correcta as $p):
                $nombre.=$p->per_apellido." ".$p->per_nombre."<br/>";
                endforeach;
            return $nombre;

        }else{
            \Session::flash('error persona','Ocurrio un error al actualizar los datos');
        }
    }
    public function corregir_bloque_duplicado(Request $form){
        $lista=DB::select('select count(per_ci), per_ci, per_apellido,per_nombre from personas group by per_ci,per_apellido,per_nombre  HAVING count(per_ci)>1 order by per_apellido, per_nombre ASC limit 500');
        //dd($lista);
        $eliminados=array();
        $i=0;
        if(sizeof($lista)>0){
            foreach ($lista as $l):
                $duplicados=Persona::where("per_ci","=",$l->per_ci)->orderBy('id_per','ASC')->get();
                $persona=Persona::find($duplicados[0]->id_per);
                //dd($persona);

                foreach ($duplicados as $d){
                    $eliminados[$i]=$d->id_per.' * '.$d->per_ci." - ".$d->per_apellido." ".$d->per_nombre;
                    if($persona->id_per!=$d->id_per){
                        DB::update("update titulos set id_per=".$persona->id_per." where id_per=".$d->id_per);
                        DB::update("update tramitas set id_per=".$persona->id_per." where id_per=".$d->id_per);
                        DB::update("update noatentado.noatentado set id_per=".$persona->id_per." where id_per=".$d->id_per);
                        DB::update("update apostilla.apostilla set id_per=".$persona->id_per." where id_per=".$d->id_per);
                        DB::update("update noatentado.sancionados set id_per=".$persona->id_per." where id_per=".$d->id_per);
                        $this->copiar_datos_personales($persona,$d);
                    }
                    $i++;
                    //return $persona->per_apellido.' '.$persona->per_nombre;
                }
            endforeach;
        }
        \Session::flash('exitoDuplicado','Se ha procesado con exito '.sizeof($lista).' registros duplicados totales');
        return redirect('lista duplicados/total');

    }

    //'per_sexo','per_telefono','per_celular','per_contacto','cod_nac','per_ci_exp','per_email','per_sistema'
}
