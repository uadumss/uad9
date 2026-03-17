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
    <?php

    ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class=""><i class="fas fa-file"></i>&nbsp;&nbsp;RESOLUCIONES  {{$gestion}}</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="input-group">
                @can('ver tomos - rr')
                    <a href="{{url('listar tomos resoluciones/'.$gestion)}}" class="btn btn-outline-info btn-sm text-dark mt-1 shadow-sm"><i class="fas fa-arrow-alt-circle-left"></i> Atrás</a>
                @endcan
                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                <div class="col-md-2 input-group shadow-sm p-1" style="font-size: 0.9em; ">
                    <span class="text-dark font-weight-bold pt-2" style="font-size: 0.9em;"> Buscar Gestión :&nbsp; &nbsp;</span>
                    <select class="form-control form-control-sm col-md-4 border border-info"  name="gestion" onchange="$(location).attr('href','{{url("listar resoluciones gestion")}}'+'/'+this.value+'/{{$tipo}}');">
                        <option value="{{$gestion}}">{{$gestion}}</option>
                        <?php $año=date('Y');?>
                        @for($i=$año;$i>1927;$i--)
                            <option value="{{$i}}">{{$i}}</option>
                        @endfor
                    </select>
                </div>
                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                <div class="col-md-2 input-group shadow-sm p-1" style="font-size: 0.9em; ">
                    <span class="text-dark font-weight-bold pt-2" style="font-size: 0.9em;"> Tipo de resolución :&nbsp; &nbsp;</span>
                    <select class="form-control form-control-sm col-md-4 border border-info"  name="gestion" onchange="$(location).attr('href','{{url("listar resoluciones gestion")}}'+'/{{$gestion}}/'+this.value);">
                        <option value="{{$tipo}}">{{strtoupper($tipo)}}</option>
                        <option value="rcu">RCU</option>
                        <option value="rr">RR</option>
                        <option value="rvr">RVR</option>
                        <option value="rs">RS</option>
                        <option value="rc">RC</option>
                        <option value="rcf">RCF</option>
                        <option value="rcc">RCC</option>
                    </select>
                </div>
                @can('importar pdf - rr')
                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                    <form action="{{url('complementar_pdf')}}" method="POST">
                        @csrf
                        <div class="input-group shadow-sm p-1" style="font-size: 0.9em; ">
                        <span class="text-dark font-weight-bold pt-2" style="font-size: 0.9em;"> Complementar PDF:&nbsp; &nbsp;</span>
                        <select class="form-control form-control-sm col-md-4 border border-info"  name="gestion" >
                            <option value="{{$gestion}}">{{$gestion}}</option>
                            <?php $año=date('Y');?>
                            @for($i=$año;$i>1927;$i--)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>&nbsp;&nbsp;
                        <input type="submit" class="btn btn-sm btn-danger shadow" value="Cambiar"/>
                        </div>
                    </form>
                @endcan
            </div>
            <hr class="sidebar-divider"/>
            <div>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                            <h6 class="text-white text-center">{{$tipoCompleto}} ({{strtoupper($tipo)}})</h6>
                        </div>
                        <span class="font-weight-bold text-dark" style="font-size: 0.9em"> Cantidad de Resoluciones : </span><span style="font-size: 0.9em">{{sizeof($resoluciones)}} </span>
                        <hr class="sidebar-divider"/>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr class="bg-gradient-secondary text-white text-center" style="font-size: 0.9em">
                                    <th>Nº</th>
                                    <th>Resolución</th>
                                    <th>Descripción</th>
                                    <th>Objeto</th>
                                    <th>Tema</th>
                                    <th>Códigos</th>
                                    <th>Tomo</th>
                                    <th>Tipo</th>
                                    <th>Observaciones</th>

                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1;?>
                                @foreach($resoluciones as $r)
                                    <tr id="fila{{$i}}" style="font-size: 0.9em">
                                        <td class="text-primary border-right">{{$i}}</td>
                                        <td id="num{{$i}}" class="text-right">{{$r->res_numero}}<br/></td>
                                        <td id="desc{{$i}}">
                                            <div class="text-dark border-bottom ">{{$r->res_desc}}</div>
                                            <span style="font-size: 0.8em">
                                                <span class="font-weight-bold text-dark font-italic">Fecha: </span> <span><?php if($r->res_fecha!=''){?>
                                                                                                    {{date('d/m/Y',strtotime($r->res_fecha))}}
                                                                                                    <?php }?>
                                                                                                </span> |
                                                    <span class="font-weight-bold text-dark font-italic">Tomo: </span> <span>{{$r->tom_numero}}</span> |

                                                    @if($r->res_pdf!='')
                                                        <span class="font-weight-bold text-dark font-italic">Resolución: </span><img src="{{url('img/icon/tit.gif')}}" width="15" height="15">
                                                    @endif

                                                @if($r->res_ant!='')
                                                    <span class="font-weight-bold text-dark font-italic">Antecedentes: </span><img src="{{url('img/icon/antecedente.gif')}}" width="15" height="15">
                                                @endif
                                            </span>
                                        </td>

                                        <td id="obj{{$i}}">{{$r->res_objeto}}</td>
                                        <td id="tem{{$i}}">{{$r->res_tema}}</td>

                                        <td id="cod{{$i}}">
                                            <?php $archivados=\App\Http\Controllers\ResolucionController::l_codigo($r->cod_res);
                                            echo $archivados;
                                            ?>
                                        </td>
                                        <td>{{$r->tom_numero}}</td>
                                        <td id="tip">{{$r->res_tipo}}</td>
                                        <td>
                                            @if($r->res_obs!='')
                                                <i class="fas fa-eye text-danger"></i>
                                            @endif
                                        </td>

                                        <td class="text-right">
                                            <a href="" class="btn btn-circle btn-light btn-sm text-primary" data-toggle="modal" data-target="#verDatos"
                                               onclick="verDatos('{{url('ver datos resolucion/'.$r->cod_res)}}','p_detalle')" title="Ver detalle de la resolución"> <i class="fas fa-arrow-right"></i></a>
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

        <!--=================================MODAL VER RESOLUCION ============================-->
        <div class="modal fade" id="verDatos" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
            <div class="modal-dialog modal-xl" role="document" id="p_detalle">

            </div>
        </div>
        <!--================================ END?===============================-->
        <script>
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
            }
        </script>
@endsection
