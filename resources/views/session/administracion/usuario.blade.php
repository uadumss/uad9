@extends('marco/pagina')
@section('contenido')
    @if(Session::has('exito'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('exito') !!}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="font-weight-bold text-dark">{!! session('error') !!}</span>
        </div>
    @endif
    @if(count($errors)>0)
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{$e}} - </li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-body">
            <div>
                <ul class="nav nav-tabs">

                    <li class="nav-item">
                        <a class="nav-link text-danger" href="{{url('l_usuario/f')}}"><i class="fas fa-arrow-alt-circle-left"></i> Atrás</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#" onclick="cambiar(0,'a_o_datosPer',{{$usu['id']}})" id="0"><i class="fas fa-user"></i> Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiar(1,'{{url("datos usuario/".$usu["id"])}}')" id="1"><i class="fas fa-user-edit"></i> Editar </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiar(2,'{{url("lista a cargos/".$usu['id'])}}')" id="2"><i class="fas fa-user-friends"></i> Responsable</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiar(3,'{{url('listar actividades adm/'.$usu['id'])}}')" id="3"><i class="fas fa-atlas"></i> Actividad</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiar(4,'{{url("listar tareas adm/".$usu['id'])}}')" id="4"><i class="fas fa-tasks"></i> Tareas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiar(6,'{{url("lista conexiones/".$usu['id'])}}')" id="6"><i class="fas fa-network-wired"></i> Bitacora</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiar(7,'{{url("listar importacionUsuario/".$usu['id'])}}')" id="7"><i class="fas fa-upload"></i> Importaciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiar(5,'{{url("rendimiento/0/".$usu['id'])}}')" id="5"><i class="fas fa-chart-line"></i> Redimiento</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiar(9,'{{url("claustros/0/".$usu['id'])}}')" id="5"><i class="fas fa-chart-line"></i> Redimiento</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="cambiar(8,'{{url("listar permisos/".$usu['id'].'/0')}}')" id="8"><i class="fas fa-address-card text-danger"></i> Permisos</a>
                    </li>
                </ul>
            </div>
            <br/>

            <div class="row" id="panel_completo">
                <div class="col-md-3">
                    <div class="card shadow" id="d_personales">
                        <div class="card-header bg-primary">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h5 class="text-white">Datos personales</h5>
                                <button onclick="//$('#d_personales').hide(200);$('#btn_personal').show(200);"
                                        class="d-none d-sm-inline-block btn btn-sm btn-light shadow-sm">
                                    <i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body" id="d_personales">
                            <div>
                                <div class="centrar_bloque col-md-7">
                                    @if($usu['foto']!='')
                                        <img src="{{url('img/foto/'.$usu['foto'])}}" class="imgRedonda centrar_bloque" width="150" height="150">
                                    @else
                                        <img src="{{url('img/icon/sin foto'.$usu['sexo'].'.png')}}" class="imgRedonda centrar_bloque" width="150" height="150">
                                    @endif
                                </div>
                                <br/>
                               <div class="p-1">
                                   <table class="">
                                       <tr class="border-bottom">
                                           <th>Nombre:</th>
                                           <td>{{$usu['name']}}</td>
                                       </tr>
                                       <tr class="border-bottom">
                                           <th>Ci:</th>
                                           <td>{{$usu['ci']}}</td>
                                       </tr>
                                       <tr class="border-bottom">
                                           <th>Contacto:</th>
                                           <td>{{$usu['contacto']}}</td>
                                       </tr>
                                       <tr class="border-bottom">
                                           <th>Sexo:</th>
                                           @if($usu['sexo']=='M')
                                               <td>MASCULINO</td>
                                           @else
                                               <td>FEMENINO</td>
                                           @endif
                                       </tr>
                                       <tr class="border-bottom">
                                           <th>fecha ingreso:</th>
                                           <td>{{$usu['created_at']}}</td>
                                       </tr>
                                       <tr class="border-bottom">
                                           <th>Rol :</th>
                                           <td>{{$usu['rol']}}</td>

                                       </tr>
                                       <tr class="border-bottom">
                                           <th>Cargo :</th>
                                           <td>{{$usu['cargo']}}</td>
                                       </tr>
                                       <tr class="border-bottom">
                                           <th>Dirección:</th>
                                           <td>{{$usu['direccion']}}</td>
                                       </tr>
                                   </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9" id="p_trabajo">
                    <div class="card shadow">
                        <div class="card-header bg-primary">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h5 class="text-white">Panel de edición</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="panel">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    function cambiar(id,url){
        $('#panel').html("<br/><br/><div class='d-flex justify-content-center'><div class='spinner-border' role='status'> <span class='visually-hidden'></span></div></div>");
        var i=0;
        for(i=0;i<10;i++){
            $('#'+i).removeClass('active');
        }
        $('#'+id).addClass('active');
        if(id==0){
            $('#panel').html('');
        }else{
            $.ajax({
                url: url,
                type: 'GET',
                data:'',
                success: function (resp) {
                    $('#panel').html(resp);
                },
                error: function () {
                    alert('No se puede ejecutar la petición');
                }
            });
        }
    }

   /* function cargarDatos(ruta,panel){
        $('#'+panel).html("<br/><br/><div class='d-flex justify-content-center text-danger'><div class='spinner-border' role='status'> <span class='visually-hidden'></span></div></div>");
        $.ajax({
            url: ruta,
            type: 'GET',
            data:'',
            success: function (resp) {
                $('#'+panel).html(resp);
            },
            error: function () {
                alert('No se puede ejecutar la petición');
            }
        });
    }
*/

   /* function enviar(formulario,ruta,panel){
            $.ajax({
                type: "POST",
                url: ruta,
                data: $("#"+formulario).serialize(), // Adjuntar los campos del formulario enviado.
                success: function(resp)
                {
                    $('#'+panel).html(resp);
                }
            });
    }
    */

</script>
@endsection
