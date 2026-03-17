<div class="">
    <br/>
    <div class="bg-primary centrar_bloque  col-md-10 rounded shadow-sm p-1">
        <h5 class="text-white text-center">Lista de reportes diarios</h5>
    </div>
    <i class="fas fa-folder-open text-warning"></i>&nbsp;&nbsp;<span class="text-dark font-italic font-weight-bold">Actividad: </span>&nbsp;&nbsp;  <span class="text-dark font-italic">{{$actividad->act_nombre}}</span><br/>
    <i class="fas fa-box text-danger"></i>&nbsp;&nbsp;<span class="text-dark font-italic font-weight-bold">Tarea: </span>&nbsp;&nbsp;  <span class="text-dark font-italic">{{$tarea->tar_nombre}}</span>
    <br/>
    @if($tarea->tar_cotidiano=='t')
        <span class="bg-info rounded p-1 font-italic text-white font-weight-bold" style="font-size: 0.8em">Tarea cotidiana</span>
    @endif
    <hr class="sidebar-divider"/>
    <br/>
    <div class="overflow-auto border" style="height: 500px">
        <table class="table-sm table shadow-sm rounded table-hover">
            <tr class="bg-gray-600 text-white">
                <th>Nº</th>
                <th>Fecha del reporte</th>
                <th>Calificación</th>
                <th>Porcentaje</th>
                <th>Fecha revisión</th>
                <th>Opciones</th>
            </tr>
            <?php $i=1;$porcentajeTotal=0;?>
            @foreach($diario as $d)
                @if($d->dia_corregir=='t')
                    <tr class="alert-danger">
                @else
                    @if($d->dia_corregir=='c')
                        <tr class="alert-warning">
                    @else
                        <tr>
                    @endif

                @endif
                        <td>{{$i}}</td>
                        <td>{{date('d/m/Y',strtotime($d->dia_fech))}}</td>
                        @if($d->dia_final=='t')
                            <td colspan="2"><span class="mensaje-peligro">INFORME FINAL</span></td>
                        @else
                            <td>{{$d->dia_calificacion}}</td>
                            <td>
                                @if($tarea->tar_cotidiano!='t')
                                        {{$d->dia_porcen}} %
                                        <?php $porcentajeTotal+=$d->dia_porcen;?>
                                @endif
                            </td>
                        @endif
                        <td>
                            @if($d->dia_fech_revision!='')
                                {{date('d/m/Y',strtotime($d->dia_fech_revision))}}
                            @endif
                        </td>
                        <td>
                            <a href="#" class="btn btn-light btn-circle btn-sm " data-target="#observacion" data-toggle="modal"
                               onclick="cargarDatos('{{url('revision diario adm/'.$d->cod_dia)}}','panel_observacion')">

                                @if($d->dia_aceptado!='t')
                                    <i class="fas fa-edit text-primary"></i>
                                @else
                                    <i class="fas fa-check text-success"></i>
                                @endif
                            </a>
                            <a href="#" class="btn btn-light btn-circle btn-sm " data-target="#observacion" data-toggle="modal"
                               onclick="cargarDatos('{{url('f_eliminar diario adm/'.$d->cod_dia)}}','panel_observacion')">
                                <i class="fas fa-trash-alt text-danger"></i>
                            </a>
                        </td>
                    </tr>
                    <?php $i++;?>
                    @endforeach
                    <tr>
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
