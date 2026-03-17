@extends('marco/pagina')
@section('contenido')
    <?php
    use App\Models\Funciones;
    $fun=new Funciones();
    ?>
    @if(Session::has('exito'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close " data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('exito') !!}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="row">
                <div class="col-md-6">
                    <h5 class=""><i class="fas fa-book"></i>&nbsp;&nbsp; Lista de reporte diario</h5>
                </div>
                <div class="col-md-6">
                    @if($des['des_hab']=='t')
                        @if($tarea['tar_inf_final']=='t' || $tarea['tar_hab']=='f' )
                            <div href="#" class="float-right d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm mr-3">
                                <i class="fas fa-edit"></i> Reportar conclusión</div>
                        @else
                            <a href="#" class="float-right d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-3" data-target="#reporte" data-toggle="modal"
                               onclick="cargarDatos('{{url("f_reporte_conclusion/".$tarea["cod_tar"])}}','panel_reporte')">
                                + Reportar conclusión</a>
                        @endif

                        @if($tarea['tar_concluido']=='t' || $tarea['tar_hab']=='f')
                            <div class="float-right d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm mr-3">
                                 Tarea concluida </div>
                        @else
                            <a href="#" class="float-right d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-3" data-target="#reporte" data-toggle="modal"
                                onclick="cargarDatos('{{url("f_editar_reporte/0/".$tarea["cod_tar"])}}','panel_reporte')">
                                + Reportar diario </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body" id="tablaDiario" style="font-size: 0.8em">
            <div class="row">
                <div class="col-md-3">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 alert-primary">
                            <div class="">
                                <h5 class="m-0 font-weight-bold text-dark">Datos de la tarea</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <i class="fas fa-folder-open text-warning"></i>&nbsp;&nbsp;<span class="text-primary font-weight-bolder">Actividad</span><br/>
                                <span class="text-dark font-weight-bolder">{{$actividad['act_nombre']}}</span>

                            <br/><br/>
                            <table class="table table-sm ml-4 mr-2">
                                <tr>
                                    <td colspan="2"> <span class="text-primary font-weight-bolder">Tarea:</span><br/>
                                        <span class="text-dark font-weight-bolder">{{$tarea['tar_nombre']}}</span>
                                    </td>
                                </tr>
                                <tr>

                                    <td> <span class="text-primary font-weight-bolder">Fecha de inicio: </span><br/>
                                        <span class="text-dark font-weight-bolder">{{date('d/m/Y',strtotime($tarea['tar_fi']))}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td> <span class="text-primary font-weight-bolder">Fecha de conclusión: </span><br/>
                                        <span class="text-dark font-weight-bolder">
                                            @if($tarea['tar_ff']!='')
                                                {{date('d/m/Y',strtotime($tarea['tar_ff']))}}
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    @if($tarea->tar_cotidiano!='t')
                                    <td> <span class="text-primary font-weight-bolder">Avance: </span><br/>
                                        <div class="col-md-10 pt-2">
                                            <?php $porcentaje=$totalPorcen[0]->porcentaje;?>
                                            <div class="progress bg-gray-500">
                                                <?php if($porcentaje<1){$porcentaje=0;}?>
                                                <div class="progress-bar progress-bar-striped bg-success text-white" role="progressbar" style="width: {{$porcentaje}}%" aria-valuenow="{{$porcentaje}}" aria-valuemin="0" aria-valuemax="100">
                                                    <span class="font-weight-bolder">{{$porcentaje}} %</span>

                                                    </div>
                                                </div>
                                        </div>
                                    </td>
                                    @else
                                        <td><span class="bg-info rounded p-1 font-italic text-white font-weight-bold" style="font-size: 1em">Tarea cotidiana</span></td>
                                    @endif
                                </tr>
                                @if($tarea['tar_concluido']=='t')
                                <tr>
                                    <td colspan="2" ><span class="text-danger font-weight-bolder font-italic">Esta tarea ya esta concluida</span></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-9" style="">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 alert-primary">
                            <div class="">
                                <h5 class="m-0 font-weight-bold text-dark">Reporte diario</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div>
                                <div class="bg-primary centrar_bloque col-md-4 rounded shadow-sm p-1">
                                    <h5 class="text-white text-center">Listado de reportes diarios</h5>
                                </div>
                                <br/>
                                <table class="table table-sm table-hover shadow-sm">
                                    <tr class="bg-gray-600 shadow-sm text-white">
                                        <th>Nº</th>
                                        <th>Fecha registro</th>
                                        <th>Fecha Revisi&oacute;n</th>
                                        <th>Calificaci&oacute;n</th>
                                        <th>Porcentaje</th>
                                        <th>Ver reporte</th>
                                        <th>Opciones</th>
                                    </tr>
                                    <?php $i=1; $porcentajeTotal=0;?>
                                    @foreach($diario as $d)
                                        <?php $corregir=0;?>
                                        @if($d->dia_corregir=='t')
                                            <tr class="alert-danger">
                                        @else
                                            <tr>
                                                @endif
                                                <td>{{$i}}</td>
                                                <td>{{date("d/m/Y", strtotime($d->dia_fech))}}</td>

                                                <td>
                                                    <?php if($d->dia_fech_revision!=''){?>
                                                    {{date("d/m/Y", strtotime($d->dia_fech_revision))}}
                                                    <?php }?>
                                                </td>
                                                @if($d->dia_final=='t')
                                                    <td colspan="2"><span class="mensaje-peligro">INFORME FINAL</span></td>
                                                @else
                                                    <td>
                                                        {{$d->dia_calificacion}}
                                                    </td>
                                                    @if($tarea->tar_cotidiano!='t')
                                                    <td>
                                                            {{$d->dia_porcen}} %
                                                            <?php $porcentajeTotal+=$d->dia_porcen;?>
                                                    </td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @endif
                                                <td class="text-center">
                                                    @if($d->dia_corregir=='t')
                                                        <?php $corregir=1;?>
                                                        <a href="#" class="btn btn-light btn-sm btn-circle text-danger" data-target="#reporte" data-toggle="modal"
                                                           onclick="cargarDatos('{{url('listar observaciones tarea/'.$d->cod_dia)}}','panel_reporte')">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                    @else
                                                        @if($d->dia_aceptado=='t')
                                                            <a href="#" class="btn btn-light btn-circle btn-sm text-success " data-target="#reporte" data-toggle="modal"
                                                               onclick="cargarDatos('{{url('listar observaciones tarea/'.$d->cod_dia)}}','panel_reporte')">
                                                                <i class="fas fa-check"></i>
                                                            </a>
                                                        @else
                                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary " data-target="#reporte" data-toggle="modal"
                                                               onclick="cargarDatos('{{url('listar observaciones tarea/'.$d->cod_dia)}}','panel_reporte')">
                                                                <i class="fas fa-eye"></i>
                                                            </a>

                                                        @endif

                                                    @endif
                                                </td>
                                                <td>
                                                    @if($tarea['tar_con']=='t' || $des['des_con']=='t' || $d->dia_aceptado=='t' || $d->dia_corregir!='f' )
                                                        &nbsp;&nbsp;<i class="fas fa-trash text-gray-500" style="font-size: 0.75em;"></i>
                                                    @else
                                                        <a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-target="#reporte" data-toggle="modal"
                                                            onclick="cargarDatos('{{url("f_eliminar_diario/".$d->cod_dia)}}','panel_reporte')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>

                                                    @endif
                                                </td>
                                            </tr>
                                            <?php $i++;?>
                                            @endforeach
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                @if($porcentajeTotal==100)
                                                    <td class="font-weight-bolder text-danger">Avance: {{$porcentajeTotal}} %</td>
                                                    <td colspan="2" class="font-weight-bolder text-danger italic">Tarea concluida</td>
                                                @else
                                                    <td class="font-weight-bolder text-dark">Avance: {{$porcentajeTotal}} %</td>
                                                    <td colspan="2"></td>
                                                @endif
                                            </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if($des['des_con']!='t')
        <!--===============MODAL NUEVO REPORTE DIARIO-->
        <div class="modal fade" id="reporte" tabindex="-1" style="z-index: 1500" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document" id="panel_reporte">

            </div>
        </div>
        <!--====================END=======================-->
    @endif


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
