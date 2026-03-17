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

    <?php $gestion=$tomo['tom_gestion'];
    $tipo=$tomo['tom_tipo'];?>
    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="row">
                <div class="col-md-6">
                    <h5 class=""><i class="fas fa-file"></i>&nbsp;&nbsp;RESOLUCIONES  {{$gestion}} /
                            @if($tomo['tom_numero']==0)
                            <span class="text-danger border border-danger rounded font-italic p-2 mr-3 font-weight-bold" style="font-size: 0.8em">* Resoluciones sin tomo</span>
                            @else
                            <span class="font-weight-bold"> Tomo : {{$tomo['tom_numero']}}</span>
                            @endif
                    </h5>
                </div>
                <div class="col-md-6">
                    @can('crear resolucion - rr')
                        @if($tomo['tom_cerrado']!='t')
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm float-right mr-2" data-target="#Resolucion" data-toggle="modal"
                               onclick="cargarDatos('{{url("f_enlazar_resolucion/".$tomo->cod_tom)}}','panel_editar','0')">
                                + Enlace </a>&nbsp;&nbsp;

                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm float-right mr-2" data-target="#Resolucion" data-toggle="modal"
                               onclick="cargarDatos('{{url("fe_resolucion/0/".$tomo->cod_tom)}}','panel_editar','0')">
                                + Resolución </a>&nbsp;&nbsp;
                        @endif
                    @endcan
                    @if($tomo['tom_numero']!=0 && $tomo['tom_cerrado']!='t')
                        <a href="#" class="btn btn-sm btn-primary float-right mr-2" data-target="#Resolucion" data-toggle="modal" onclick="cargarDatos('{{url('l_res_sintomos/'.$gestion.'/'.$tomo['cod_tom'])}}','panel_editar','0')">
                            Resoluciones sin tomos</a>&nbsp;&nbsp;
                    @endif

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="input-group">
                <a href="{{url('listar tomos resoluciones/'.$tomo['tom_gestion'])}}" class="btn btn-outline-info btn-sm text-dark mt-1 shadow-sm"><i class="fas fa-arrow-alt-circle-left"></i> Atrás</a>
                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                <div class="col-md-2 input-group shadow-sm p-1" style="font-size: 0.9em; ">
                    <span class="text-dark font-weight-bold pt-2" style="font-size: 0.9em;"> Buscar Gestión :&nbsp; &nbsp;</span>
                    <select class="form-control form-control-sm col-md-4 border border-info"  name="gestion" onchange="$(location).attr('href','{{url("listar tomos resoluciones")}}'+'/'+this.value);">
                        <option value="{{$gestion}}">{{$gestion}}</option>
                        <?php $año=date('Y');?>
                        @for($i=$año;$i>1840;$i--)
                            <option value="{{$i}}">{{$i}}</option>
                        @endfor
                    </select>
                </div>
                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                <div class="float-left shadow-sm p-1">
                    <div class="input-group">
                        <span class="text-dark pt-1 font-weight-bold" style="font-size: .9em;"> Buscar tomo :&nbsp; &nbsp;</span>
                        <select class="form-control form-control-sm boder border-info"  name="gestion" onchange="$(location).attr('href','{{url("listar resoluciones")}}'+'/todos/'+this.value);">
                            <option value="" disabled selected hidden></option>
                            @foreach($listaTomo as $lt)
                                <option value="{{$lt['cod_tom']}}">{{$lt['tom_numero']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                <div class="float-left shadow-sm p-1">
                    <div class="input-group">
                        <span class="text-dark pt-1 font-weight-bold" style="font-size: .9em;"> Ver tipo de Resolución:&nbsp; &nbsp;</span>
                        <select class="form-control form-control-sm boder border-info"  name="gestion" onchange="$(location).attr('href','{{url("listar resoluciones")}}'+'/'+this.value+'/'+{{$tomo['cod_tom']}});">
                            <option value="{{$tipo_res}}">{{strtoupper($tipo_res)}}</option>
                            <option value="rcu">RCU</option>
                            <option value="rr">RR</option>
                            <option value="rvr">RVR</option>
                            <option value="rs">RS</option>
                            <option value="todos">TODOS</option>
                        </select>
                    </div>
                </div>
                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                <div class="float-right pt-1">
                    @if($tomo->tom_cerrado=='t')
                        <span class="text-danger p-2 font-italic font-weight-bold" style="font-size: 0.9em">* Consolidado</span>
                    @endif
                    @if($tomo->tom_numero!=0)
                        <span class="text-dark font-weight-bold pt-1" style="font-size: .9em;">Nro. Tomo : </span> <span class="text-dark">
                            {{$tomo['tom_numero']}} </span>
                        <a href="#" class="btn btn-sm btn-outline-info text-dark" data-target="#datosTomo" data-toggle="modal">
                            Mas detalles ...
                        </a>

                    @else
                        <span class="text-danger border border-danger rounded font-italic p-2 mr-3 font-weight-bold" style="font-size: 0.8em">* Resoluciones sin tomo</span>
                    @endif
                </div>

            </div>
            <hr class="sidebar-divider"/>
            <div>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                            <h6 class="text-white text-center">RESOLUCIONES</h6>
                        </div>
                        <span class="font-weight-bold text-dark" style="font-size: 0.9em"> Cantidad de Resoluciones : </span><span style="font-size: 0.9em">{{sizeof($resoluciones)}} </span>
                        <hr class="sidebar-divider"/>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr class="bg-gradient-secondary text-white text-center" style="font-size: 0.9em">
                                    <th>Nº</th>
                                    <th>Resolución</th>
                                    <th>Fecha</th>
                                    <th>Descripción</th>
                                    <th>Objeto</th>
                                    <th>Tema</th>
                                    <th>Códigos</th>
                                    <th>Tipo</th>
                                    <th>Observaciones</th>
                                    <th>Resolución</th>
                                    <th>Antecedente</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1;?>
                                @foreach($resoluciones as $r)
                                    <tr id="fila{{$i}}" style="font-size: 0.9em">
                                        <td class="border-right text-primary">{{$i}}</td>
                                        <td id="num{{$i}}" class="text-right">{{$r->res_numero}}
                                        </td>
                                        <td id="fec{{$i}}"class="text-right">
                                            <?php if($r->res_fecha!=''){?>
                                            {{date('d/m/Y',strtotime($r->res_fecha))}}
                                            <?php }?>
                                        </td>
                                        <td id="desc{{$i}}">{{$r->res_desc}} @if($r->res_enlace!='')
                                                / <span class="text-white bg-danger rounded pl-1 pr-1 font-weight-bold font-italic" style="font-size: 0.8em">Enlace</span>
                                            @endif
                                        </td>
                                        <td id="obj{{$i}}">{{$r->res_objeto}}</td>
                                        <td id="tem{{$i}}">{{$r->res_tema}}</td>
                                        <td id="cod{{$i}}">
                                            <?php $archivado=\App\Http\Controllers\ResolucionController::l_codigo($r->cod_res);
                                                echo $archivado;
                                            ?>
                                        </td>
                                        <td id="tip">{{strtoupper($r->res_tipo)}}</td>
                                        <td>
                                            @if($r->res_obs!='')
                                                <i class="fas fa-eye text-danger"></i>
                                            @endif
                                        </td>
                                        <td id="doc{{$i}}" class="text-right text-danger">
                                            @if($r->res_pdf!='')
                                                <img src="{{url('img/icon/tit.gif')}}" width="30" height="30">
                                            @endif
                                        </td>
                                        <td id="ant{{$i}}" class="text-right text-danger">
                                            @if($r->res_ant!='')
                                                <img src="{{url('img/icon/antecedente.gif')}}" width="30" height="30">
                                            @endif
                                        </td>

                                        <td class="text-right">
                                            @can('editar resolucion - rr')
                                                @if($r->res_enlace=='')
                                                    <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#Resolucion" data-toggle="modal"
                                                        onclick="cargarDatosTitulo('{{url("fe_resolucion/".$r->cod_res."/".$tomo->cod_tom)}}','panel_editar',{{$i}})" title="Editar resolución">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                            @endcan

                                            @can('cambiar resolucion a tomo - rr')
                                                    <a href="" class="btn btn-circle btn-light btn-sm text-primary" data-toggle="modal" data-target="#Resolucion"
                                                       onclick="cargarDatos('{{url('f_cambiar a tomo  resolucion/'.$r->cod_res)}}','panel_editar')" title="Cambiar de tomo"> <i class="fas fa-book"></i></a>
                                            @endcan

                                                <?php $cod_res = $r->res_enlace=='' ? $r->cod_res : $r->res_enlace; ?>
                                                    <a href="" class="btn btn-circle btn-light btn-sm text-primary" data-toggle="modal" data-target="#Resolucion"
                                                        onclick="cargarDatos('{{url('ver datos resolucion/'.$cod_res)}}','panel_editar',{{$i}})" title="Ver detalle de la resolución"> <i class="fas fa-arrow-right"></i></a>

                                            @can('eliminar resolucion - rr')
                                                <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#Resolucion" data-toggle="modal"
                                                   onclick="cargarDatos('{{url("f_eli_resolucion/".$r->cod_res)}}','panel_editar',{{$i}})"
                                                    title="Eliminar resolución"><i class="fas fa-trash-alt"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                    <?php $i++;?>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--=================================EDITAR RESOLUCION========================-->
        <div class="modal fade" id="Resolucion" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document" id="panel_editar">

            </div>
        </div>

        <!--=============================END==================================-->
        <!--=============================MODAL DATOS DE TOMO==================================-->
        <div class="modal fade" id="datosTomo" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" id="panel_editar">
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white" id="exampleModalLabel"> &nbsp;<i class="fas fa-book"></i> &nbsp;Datos del tomo </h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                @if($tomo->tom_cerrado=='t')
                                    <span class="text-danger p-2 font-italic font-weight-bold float-right" style="font-size: 0.9em">* Este tomo ya está consolidado, no se puede modificar</span>
                                @endif
                                <table class="col-md-12">
                                    <tr>
                                        <th class="text-primary font-italic col-md-2">Nº de tomo :</th>
                                        <td class="border-bottom border-dark">
                                            {{$tomo['tom_numero']}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-primary font-italic">Gestión :</th>
                                        <td class="border-bottom border-dark">
                                            {{$tomo['tom_gestion']}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-primary font-italic">Tipo de tomo :</th>
                                        <td class="border-bottom border-dark">
                                            RESOLUCION
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-primary font-italic">Rango :</th>
                                        <td class="border-bottom border-dark">
                                            {{$tomo['tom_rango']}}
                                        </td>
                                    </tr>

                                </table>
                                <br/>
                                <div>

                                    @if($tomo->tom_obs!='')
                                        <span class="font-italic font-weight-bold text-primary"> Observaciones :</span><br/>
                                        <div class="font-weight-bold alert-danger rounded p-2">
                                            {{$tomo['tom_obs']}}
                                        </div>
                                    @endif
                                </div>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>


        <input type="hidden" name="fila" id="fila" value="0">
    <script>
        function enviar_res(){
            var link = "{{url('g_resolucion/')}}";
            var token = "{{csrf_token()}}";
            var form = new FormData($('#form_editar').get(0));
            $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
            $.ajax({
                url: link,
                type: 'POST',
                contentType: false,
                processData: false,
                data:form,
                //data:$('#form_editar').serialize(),
                success: function (resp) {

                    $('#panel_editar').html(resp);
                    var form=$('#form_editar');
                    var fila=$('#fila').val();
                    $('#num'+fila).html($('#form_editar #numero').val());
                    var fecha=$('#form_editar #fecha').val().split('-');
                    $('#fec'+fila).html(fecha[2]+'/'+fecha[1]+'/'+fecha[0]);
                    $('#desc'+fila).html($('#form_editar #desc').val());
                    $('#obj'+fila).html($('#form_editar #objeto').val());
                    $('#tem'+fila).html($('#form_editar #tema').val());
                    $('#cod'+fila).html($('#archivado').text());
                    if($('#form_editar #pdf_val').val()=='1'){
                        $('#doc'+fila).html("<img src='{{url('img/icon/titulo.gif')}}' width='30' height='30'/>");
                    }
                    if($('#form_editar #pdf_val_ant').val()=='1'){
                        $('#ant'+fila).html("<img src='{{url('img/icon/antecedente.gif')}}' width='30' height='30'/>");
                    }
                },
                error: function (data) {
                    alert(data);
                    //$('#panel_error_archivo').show();
                }
            });
        }
        function cargarPlan(ruta,panel){
            var link="{{url('/')}}"+"/"+ruta;
            $('#panel_error_archivo').hide();
            var fila=$('#fila').val();
            $.ajax({
                url: link,
                type: 'GET',
                data:'',
                success: function (resp) {
                    $('#'+panel).html(resp);
                    $('#cod'+fila).html($('#archivado').text());
                },
                error: function () {
                    alert('No se puede ejecutar la petición');
                }
            });
        }
        /*
        function verDatos(url,panel,fila){
            $.ajax({
                url:url,
                type:'GET',
                data:'',
                success:function (resp) {
                    $('#'+panel).html(resp);
                    $('#fila_obs').val(fila);
                },
                error:function () {
                    alert('No se puede ejecutar la petición');
                }
            });
        }*/
    </script>
@endsection
