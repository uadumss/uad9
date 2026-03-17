<!--================ADMINISTRACION -->

    <div class="modal-content  border-bottom-primary">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-edit"></i> Actividad</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
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
            <div style="height: 650px;">
                <div class="row">
                    <div class="col-md-5">
                        <div>
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                     <i class="fas fa-folder-open text-warning"></i>&nbsp;&nbsp;  <span class="text-dark font-italic">{{$act->act_nombre}}</span>

                                            @if($act->act_cotidiano!='t')

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
                                                @else
                                                <br/>
                                                <span class="bg-info rounded p-1 font-italic text-white font-weight-bold" style="font-size: 0.8em">Actividad cotidiana</span>
                                                @endif
                                </div>
                            </div>
                            <div class="bg-primary centrar_bloque p-1 mb-4 col-md-8 rounded shadow">
                                <h5 class="text-white text-center">Lista de tareas de la actividad</h5>
                            </div>
                        </div>
                        <hr class="sidebar-divider"/>
                        <div class="overflow-auto" style="height: 450px;">
                            <table class="table-hover table table-sm" border="0">
                                <tr class="bg-gradient-light text-dark rounded shadow-sm">
                                    <th>Nº</th>
                                    <th>Datos</th>
                                    <th>Opciones</th>
                                </tr>
                                <?php $i=1; $por=0;?>
                                @foreach($tar as $t)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td><span class="font-weight-bold">{{$t['tar_nombre']}}</span><br/>
                                            <span style="font-size: 0.8em">
                                        <span class="font-italic font-weight-bold">Tiempo : </span><span>{{date("d/m/Y", strtotime($t['tar_fi']))}} <?php if($t['tar_ff']!=''){?>{{'-'.date("d/m/Y", strtotime($t['tar_ff']))}}<?php }?></span> |
                                        <span class="font-italic font-weight-bold">Programado al : </span><span class="bg-primary text-white rounded font-weight-bolder" style="font-size: 0.8em;">&nbsp; {{$t['tar_por']}} % &nbsp;</span> <?php $por+=$t['tar_por']?>
                                        <br/>
                                                <!--ASIGNADO A-->
                                        @foreach($designados as $des)
                                                    @if($des->cod_tar==$t['cod_tar'])
                                                        <a href="#" class="" data-target="#tarea" data-toggle="modal"
                                                           onclick="cargarDatos('{{url("datos asignados/".$des->cod_des)}}','panel_tarea');">
                                                @if($des->foto!='')
                                                                <img src="{{url('img/foto/'.$des->foto)}}" class="imgRedonda centrar_bloque" width="40" height="40">
                                                            @else
                                                                <img src="{{url('img/icon/sin foto'.$des->sexo.'.png')}}" class="imgRedonda centrar_bloque" width="40" height="40">
                                                            @endif
                                                            {{$des->name}}
                                                    </a>
                                                    @endif
                                                @endforeach
                                        @if($t->tar_cotidiano!='t')
                                            <!-- =============AVANCE ===============-->
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
                                        @else
                                                            <br/>
                                            <span class="bg-info rounded p-1 font-italic text-white font-weight-bold">Tarea cotidiana</span>
                                        @endif
                                    </span>
                                        </td>
                                        <td class="text-right">
                                            @if($t['tar_hab']=='t')
                                                <a href="#" onclick="cargarDatos('{{url('habilitar tarea adm/'.$t['cod_tar'])}}','panel_actividad')" class="btn btn-light btn-circle btn-sm text-success">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            @else
                                                <a href="#" onclick="cargarDatos('{{url('habilitar tarea adm/'.$t['cod_tar'])}}','panel_actividad')" class="btn btn-light btn-circle btn-sm text-dark">
                                                    <i class="fas fa-lock"></i>
                                                </a>
                                            @endif

                                            <a class="btn btn-light btn-circle btn-sm text-primary" data-target="#tarea" data-toggle="modal"
                                               onclick="cargarDatos('{{url('datos tarea actividad adm/'.$t['cod_tar'])}}','panel_tarea')">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#tarea" data-toggle="modal"
                                               onclick="cargarDatos('{{url('f_eliminiar tarea adm/'.$t['cod_tar'])}}','panel_tarea')">
                                                <i class="fas fa-trash"></i>
                                            </a>

                                            <a class="btn btn-light btn-circle btn-sm text-primary"
                                               onclick="cargarDatos('{{url('listar reporte diario adm/'.$t['cod_tar'])}}','panel_diario')">
                                                <i class="fas fa-arrow-alt-circle-right" style="font-size: 1.3em;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php $i++;?>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="col-md-7 overflow-auto border rounded">
                        <div class="" id="panel_diario">

                        </div>
                    </div>
                </div>
            </div>
                @if($act->act_cot!='t')
                    @if($por==100)
                        <span class="text-right text-dark  font-weight-bold pr-4" style="font-size: 0.75em">  ACTIVIDAD PROGRAMADA AL {{$por}} %</span>
                    @else
                        <span class="text-right text-danger font-weight-bold pr-4" style="font-size: 0.75em"> ACTIVIDAD PROGRAMADA AL {{$por}} %</span>
                    @endif
                @endif
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>


<!--MODAL LISTA DE ASIGNADOS -->
<div class="modal fade" id="asignado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-bottom-info">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"><i class="fas fa-user"></i> Lista de Funcionarios responsables</h5>
                <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="listaAsignados">

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

