@extends('marco/pagina')
@section('contenido')
    <script src="{{ asset('node_modules/tinymce/tinymce.js') }}"></script>
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
            {!! session('error') !!}
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
        <div class="card-header alert-primary py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class="m-0 font-weight-bold text-dark">Lista de Tareas</h5>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-target="#tarea" data-toggle="modal"
                   onclick="cargarDatos('{{url("f_editar tarea/0/".$act->cod_act)}}','panel_tarea')">
                    + Nueva tarea</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <a href="{{url('listar actividades')}}" class="btn btn-sm btn-outline-info text-dark "><i class="fas fa-arrow-circle-left"></i> Atrás</a><br/><br/>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-primary">
                            <div class="">
                                <h5 class="m-0 font-weight-bold text-white">Datos de la actividad</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <i class="fas fa-folder-open text-warning"></i>&nbsp;&nbsp;<span class="text-primary font-weight-bolder">Actividad :</span><br/>
                            <span class="text-dark font-weight-bolder">{{$act->act_nombre}}</span>

                            <br/><br/>
                            <table class="table table-sm ml-4 mr-2" style="font-size: 0.8em">
                                <tr>

                                    <td> <span class="text-primary font-weight-bolder">Fecha de inicio: </span><br/>
                                        <span class="text-dark font-weight-bolder">{{date('d/m/Y',strtotime($act->act_fi))}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td> <span class="text-primary font-weight-bolder">Fecha de conclusión: </span><br/>
                                        <span class="text-dark font-weight-bolder">
                                            @if($act->act_ff!='')
                                                {{date('d/m/Y',strtotime($act->act_ff))}}
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    @if($act->act_cotidiano!='t')
                                    <td>
                                        <span class="text-primary font-weight-bolder">Avance: </span><br/>
                                        <div class="col-md-10 pt-2">
                                            <div class="progress bg-gray-500">

                                                <?php $porcentaje=0;
                                                if(isset($porcenAct[0]->suma)){
                                                    $porcentaje=$porcenAct[0]->suma;
                                                }
                                                ?>
                                                <?php if($porcentaje<1){$porcentaje=0;}?>
                                                <div class="progress-bar progress-bar-striped bg-info text-white" role="progressbar" style="width: {{$porcentaje}}%" aria-valuenow="{{$porcentaje}}" aria-valuemin="0" aria-valuemax="100">
                                                    <span class="font-weight-bolder">{{$porcentaje}} %</span>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                        @else
                                        <td><span class="bg-info rounded p-1 font-italic text-white font-weight-bold" style="font-size: 1em">Tarea cotidiana</span></td>
                                        @endif


                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="bg-primary centrar_bloque p-2 mb-4 col-md-5 rounded shadow">
                        <h5 class="text-white text-center">Lista de tareas de la actividad</h5>
                    </div>
                    <div>
                        <table class="table table-sm rounded shadow-sm table-hover" style="font-size: 0.8em">
                            <tr class="bg-gray-600 text-white rounded shadow-sm">
                                <th>Nº</th>
                                <th>Nombre</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Conclusión</th>
                                <th>Responsables</th>
                                <th>Porcentaje</th>
                                <th>% Avance</th>
                                <th>Opciones</th>
                                <th>Reportes</th>
                            </tr>
                            <?php $i=1; $por=0;?>
                            @foreach($tar as $t)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$t['tar_nombre']}}</td>
                                    <td>{{date("d/m/Y", strtotime($t['tar_fi']))}}</td>
                                    <td>
                                        <?php if($t['tar_ff']!=''){?>
                                        {{date("d/m/Y", strtotime($t['tar_ff']))}}
                                        <?php }?>
                                    </td>
                                    <td>

                                        @foreach($designados as $des)
                                            @if($des->cod_tar==$t['cod_tar'])
                                                <a href="#" class="" data-target="#tarea" data-toggle="modal"
                                                   onclick="cargarDatos('{{url("datos asignados/".$des->cod_des)}}','panel_tarea')">
                                                    @if($des->foto!='')
                                                        <img src="{{url('img/foto/'.$des->foto)}}" width="40" height="40" class="imgRedonda">
                                                    @else
                                                        <img src="{{url('img/icon/sin foto'.$des->sexo.'.png')}}" width="40" height="40" class="imgRedonda">
                                                    @endif
                                                    {{$des->name}}
                                                </a>
                                                <br/>
                                            @endif
                                        @endforeach

                                    </td>
                                    @if($t->tar_cotidiano=='t')
                                        <td><span class="bg-info rounded p-1 font-italic text-white font-weight-bold">Tarea cotidiana</span></td>
                                        <td></td>
                                    @else
                                        <td class="text-right pr-4">
                                            <span class="bg-primary text-white rounded font-weight-bolder" style="font-size: 0.8em;">&nbsp; {{$t['tar_por']}} % &nbsp;</span>
                                            <?php $por+=$t['tar_por']?>
                                        </td>
                                        <td>
                                            <?php
                                            $porcentaje=0;
                                            foreach ($porcen as $p):
                                                if($t['cod_tar']==$p->cod_tar){
                                                    $porcentaje=$p->suma;
                                                }
                                            endforeach;
                                            ?>
                                            <div class="progress bg-gray-500">
                                                @if($porcentaje<33)
                                                    <?php if($porcentaje<1){$porcentaje=0;}?>
                                                    <div class="progress-bar progress-bar-striped bg-danger text-white" role="progressbar" style="width: {{$porcentaje}}%" aria-valuenow="{{$porcentaje}}" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="font-weight-bolder">{{$porcentaje}} %</span>
                                                        @else
                                                            @if($porcentaje<66)
                                                                <div class="progress-bar progress-bar-striped bg-warning text-white" role="progressbar" style="width: {{$porcentaje}}%" aria-valuenow="{{$porcentaje}}" aria-valuemin="0" aria-valuemax="100">
                                                                    @else
                                                                        <div class="progress-bar progress-bar-striped bg-success text-white" role="progressbar" style="width: {{$porcentaje}}%" aria-valuenow="{{$porcentaje}}" aria-valuemin="0" aria-valuemax="100">
                                                                            @endif
                                                                            <span class="font-weight-bolder">{{$porcentaje}} %</span>
                                                                            @endif

                                                                        </div>
                                                                </div>
                                        </td>
                                    @endif
                                    <td>
                                        @if(Auth::user()->id==$t['id_responsable'])
                                            @if($t['tar_hab']=='t')
                                                <a href="{{url('habilitar tarea/'.$t['cod_tar'])}}" class="btn btn-light btn-circle btn-sm text-success">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            @else
                                                <a href="{{url('habilitar tarea/'.$t['cod_tar'])}}" class="btn btn-light btn-circle btn-sm text-dark">
                                                    <i class="fas fa-lock"></i>
                                                </a>
                                            @endif
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#tarea" data-toggle="modal"
                                               onclick="cargarDatos('{{url("f_editar tarea/".$t['cod_tar']."/".$act->cod_act)}}','panel_tarea')">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-target="#tarea" data-toggle="modal"
                                               onclick="cargarDatos('{{url("f_eliminar tarea/".$t['cod_tar'])}}','panel_tarea')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{url('listar reportes/'.$t['cod_tar'].'/tarea')}}" class="btn btn-circle btn-sm btn-light text-primary"><i class="fas fa-angle-right" style="font-size: 1.3em;"></i></a>
                                    </td>
                                </tr>
                                <?php $i++;?>
                            @endforeach
                            @if($act->act_cot!='t')
                            <tr>
                                <td colspan="5"></td>
                                @if($por==100)
                                    <th class="text-right text-dark pr-4">  TOTAL % = {{$por}}</th>
                                @else
                                    <th class="text-right text-danger pr-4">  TOTAL % = {{$por}}</th>
                                @endif
                                <td></td>
                            </tr>
                           @endif
                        </table>
                    </div>
                </div>

            </div>

        </div>
        <div class="modal fade" id="tarea" tabindex="-1" style="z-index: 1500" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document" id="panel_tarea">
            </div>
        </div>
    </div>

    <script>
        tinymce.init({
            selector:'#desc',
            width: 750,height: 300,
            menubar:false,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
            ],
            toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table preview',
        });
    </script>
    <script type="text/javascript">
        function cargarDatos(ruta,panel){
            $('#'+panel).html("<br/><br/><div class='d-flex justify-content-center'><div class='spinner-border text-danger' role='status'> <span class='visually-hidden'></span></div></div>");
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
    </script>
@endsection
