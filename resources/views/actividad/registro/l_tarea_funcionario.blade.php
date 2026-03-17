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
        <div class="card-header py-3 alert-primary">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class="m-0 font-weight-bold text-dark"><i class="fas fas fa-pen-fancy"></i>&nbsp;&nbsp;Tareas</h5>
            </div>
        </div>
        <div class="card-body">

            <div class="bg-primary centrar_bloque col-md-5 p-2 mb-4 rounded shadow">
                <h5 class="text-white text-center font-weight-bolder">Lista de tareas asignadas</h5>
            </div>

            <table class="table table-sm rounded shadow-sm table-hover" border="0">
                <tr class="bg-gray-600 shadow text-white text-left">
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
                        @if($t->tar_cotidiano!='t')
                        <td>
                            <?php
                            $porcentaje=0;
                            foreach ($porcen as $p):
                                if($t->cod_tar==$p->cod_tar){
                                    $porcentaje=$p->suma;
                                }
                            endforeach;
                            ?>
                            <div class="progress bg-gray-500 mt-1">
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
                        @else
                           <td><span class="bg-info rounded p-1 font-italic text-white font-weight-bold" style="font-size: 0.8em">Tarea cotidiana</span></td>
                        @endif
                        <td class="">
                                <a href="{{url('listar reportes diarios/'.$t->cod_des)}}" class="btn btn-light btn-circle btn-sm text-primary" title="Registrar">
                                    <h6 class="pt-2"><i class="fas fa-arrow-alt-circle-right"></i></h6>
                                </a>
                        </td>
                    </tr>
                    <?php $i++;?>
                @endforeach
            </table>
        </div>
    </div>
@endsection
