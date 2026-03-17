
    <?php
    use App\Funciones;
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


        <div class="card-body" id="tablaDiario">
            <div class="row">
                <div class="col-md-3">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 alert-info">
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
                                </tr>
                                @if($tarea['tar_con']=='t')
                                <tr>
                                    <td colspan="2" ><span class="text-danger font-weight-bolder font-italic">Esta tarea ya esta concluida</span></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 alert-info">
                            <div class="">
                                <h5 class="m-0 font-weight-bold text-dark">Reporte diario</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="bg-primary centrar_bloque col-md-4 rounded shadow-sm">
                                <h5 class="text-white text-center">Listado de reportes diarios</h5>
                            </div>

                            <table class="table table-sm table-hover shadow-sm">
                                <tr class="bg-info shadow-sm text-white">
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
                                                    {{$d->dia_cal}}
                                                </td>
                                                <td>
                                                    @if($d->dia_porcen>0)
                                                        {{$d->dia_porcen}} %
                                                        <?php $porcentajeTotal+=$d->dia_porcen;?>
                                                    @endif
                                                </td>
                                            @endif
                                            <td width="" class="text-center">
                                                @if($d->dia_corregir=='t')
                                                    <?php $corregir=1;?>
                                                    <a href="#" class="btn btn-light btn-sm btn-circle text-danger" data-target="#mostrarDesc" data-toggle="modal" onclick="cargarDatos({{$d->id_dia}})">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @else
                                                    @if($d->dia_aceptado=='t')
                                                        <a href="#" class="btn btn-light btn-circle btn-sm text-success " data-target="#mostrarDesc" data-toggle="modal" onclick="cargarDatos({{$d->id_dia}})">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    @else
                                                        <a href="#" class="btn btn-light btn-circle btn-sm text-primary " data-target="#mostrarDesc" data-toggle="modal" onclick="cargarDatos({{$d->id_dia}})">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif

                                                @endif

                                            </td>
                                            <td>
                                                @if($tarea['tar_con']=='t' || $des['des_con']=='t' || $d->dia_aceptado=='t' || $d->dia_corregir!='f' )
                                                    &nbsp;&nbsp;<i class="fas fa-trash text-gray-500" style="font-size: 0.75em;"></i>
                                                @else
                                                    <a href="#" class="btn btn-light btn-circle btn-sm text-dark" data-target="#eliminar{{$i}}" data-toggle="modal">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    <!-- MODAL ELIMINAR-->
                                                    <div class="modal fade" id="eliminar{{$i}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content border-bottom-danger">
                                                                <div class="modal-header bg-danger">
                                                                    <h5 class="modal-title text-white" id="exampleModalLabel"><img src="{{url('img/icon/eliminar.png')}}"> Eliminar reporte diario</h5>
                                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Esta seguro de eliminar el registro de fecha: <br/><br/>

                                                                    <div class="row">
                                                                        <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-8 p-2">
                                                                            {{date('d/m/Y',strtotime($d->dia_fech))}}
                                                                        </div>
                                                                        <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2>?</h2></div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                                                                    <form action="{{url('e_diario')}}" method="POST">
                                                                        @csrf
                                                                        <input class="btn btn-danger" type="submit" value="Aceptar"/>
                                                                        <input type="hidden" name="it" value="{{$tarea['id_tar']}}">
                                                                        <input type="hidden" name="id_dia" value="{{$d->id_dia}}">
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END MODAL -->
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


