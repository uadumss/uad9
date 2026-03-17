    @if(Session::has('exito'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('exito') !!}
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


            <div class="bg-primary centrar_bloque col-md-5 p-2 mb-4 rounded shadow">
                <h5 class="text-white text-center font-weight-bolder">Lista de tareas asignadas</h5>
            </div>

            <table class="table table-sm rounded shadow-sm table-hover" border="0">
                <tr class="bg-gradient-light shadow text-dark text-left">
                    <th>Nº</th>
                    <th>Tarea</th>
                    <th>Concluido</th>
                    <th>Actividad</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Conclusión</th>
                    <th>Avance</th>
                    <th>Ingresar</th>
                </tr>
                <?php $i=1;?>
                @foreach($tarea as $t)
                    <tr class="text-left">
                        <th class="text-dark">{{$i}}</th>
                        <td class="text-primary font-weight-bolder">{{$t->tar_nombre}}</td>
                        <td class="">
                            @if($t->tar_concluido=='t')
                                @if($t->tar_inf_final=='t')
                                    <i class="fas fa-check-square text-success mt-1"></i>
                                @else
                                    <span class="mensaje-peligro" style="font-size: 0.8em">Falta informe final</span>
                                @endif
                            @endif
                        </td>
                        <td >{{$t->act_nombre}}</td>
                        <td>{{date("d/m/Y", strtotime($t->tar_fi))}}</td>
                        <td>
                            <?php if($t->tar_ff!=''){?>
                            {{date("d/m/Y", strtotime($t->tar_ff))}}
                            <?php }?>
                        </td>
                        <td>
                        @if($t->tar_cotidiano!='t')
                            <?php
                            $porcentaje=0;
                            foreach ($porcen as $p):
                                if($t->cod_tar==$p->cod_tar){
                                    $porcentaje=$p->suma;
                                }
                            endforeach;
                            ?>
                            <div class="progress bg-dark">
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
                            @else
                                <span class="bg-info rounded p-1 font-italic text-white font-weight-bold" style="font-size: 0.8em">Tarea cotidiana</span>
                            @endif
                        </td>
                        <td class="text-right">
                                <a class="btn btn-light btn-circle btn-sm text-primary" title="Registrar" data-target="#listaDiario"  data-toggle="modal"
                                    onclick="cargarDatos('{{url('listar reporte diario adm/'.$t->cod_tar)}}','panel_diario')">
                                    <h6 class="pt-2"><i class="fas fa-arrow-alt-circle-right"></i></h6>
                                </a>
                        </td>
                    </tr>
                    <?php $i++;?>
                @endforeach
            </table>



    <div class="modal fade" id="listaDiario" tabindex="-1">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content  border-bottom-primary">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-edit"></i>Tarea</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="panel_diario">

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="observacion" style="z-index: 3000;"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" id="panel_observacion">

        </div>
    </div>
<script>
    function obtenerDatos(ruta,panel){
        //alert(ruta);
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

    function cargarDatoss(id){
        $('#listaAsignados').html('');
        //alert('hola');
        var ruta="{{url('a_mostrar_observacionesTarea')}}"+"/"+id;
        $.ajax({
            url: ruta,
            type: 'GET',
            data:'',
            success: function (resp) {
                $('#panelRevision').html(resp);
            },
            error: function () {
                alert('No se puede ejecutar la petición');
            }
        });
    }
</script>


