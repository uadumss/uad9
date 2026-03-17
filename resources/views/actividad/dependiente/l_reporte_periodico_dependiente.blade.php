@extends('marco/pagina')
@section('contenido')
    <?php $corregir=0;?>
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
    <div class="card shadow mb-2">
        <div class="card-header alert-primary">
            <div class="d-sm-flex align-items-center justify-content-between mb-2">
                <h5 class="m-0 font-weight-bold text-dark">Lista de informes</h5>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-target="#reporteFinal" data-toggle="modal"
                    onclick="cargarDatos('{{url("f_editar_reporteFinal/0")}}','panel_reporteFinal')">
                     + Informe periódico </a>
            </div>
        </div>
        <div class="card-body">
            <div>
                @if($user->foto!='')
                    <img src="{{url('img/foto/'.$user->foto)}}" width="50" height="50" class="imgRedonda centrar_bloque"/>
                @else
                    <img src="{{url('img/icon/sin foto'.$user->sexo.'.png')}}" class="imgRedonda centrar_bloque" width="50" height="50">
                @endif
                <span class="font-italic text-dark" style="font-size: 0.85em">{{$user->name}}</span>
            </div>
            <div class="bg-primary centrar_bloque col-md-5 p-2 mb-4 shadow rounded">
                <h5 class="text-white text-center">Lista de informes por periodo</h5>
            </div>

            <div>
                <table class="table table-sm rounded shadow-sm">
                    <tr class="bg-gray-600 text-white shadow-sm">
                        <th>Nº</th>
                        <th>Nº de Informe</th>
                        <th>Periodo del informe</th>
                        <th>Fecha de revisión</th>
                        <th>Calificación</th>
                        <th>Opciones</th>
                    </tr>
                    <?php $i=1;?>
                    @foreach($reportePeriodico as $inf)
                        @if($inf->rt_bandera_corregido=='t')
                        <tr class="alert-warning">
                        @else
                            <tr>
                        @endif
                            <td>{{$i}}</td>
                            <td>{{$inf['rt_num']}}</td>
                            <td>{{date('d/m/Y',strtotime($inf['rt_fech_ini']))}} <strong class="text-dark">&nbsp; &nbsp; al &nbsp; &nbsp;</strong>{{date('d/m/Y',strtotime($inf['rt_fech_fin']))}}</td>
                            <td>@if($inf['rt_fech_rev'])
                                    {{date('d/m/Y',strtotime($inf['rt_fech_rev']))}}
                                @endif
                            </td>
                            <td>{{$inf['rt_cal']}}</td>
                            <td>


                                    <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#reporteFinal" data-toggle="modal"
                                       onclick="cargarDatos('{{url("f_revisar_reporte_periodico/".$inf['cod_rt'])}}','panel_reporteFinal')">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                @if($inf['rt_apr']=='f')
                                        <a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-target="#reporteFinal" data-toggle="modal"
                                           onclick="cargarDatos('{{url("f_eliminar_reporteFinal/".$inf['cod_rt'])}}','panel_reporteFinal')">
                                            <i class="fas fa-trash"></i></a>
                                @else
                                        &nbsp;&nbsp;<i class="fas fa-trash" style="font-size: 0.75em;"></i>
                                @endif
                            </td>
                        </tr>
                        <?php $i++;?>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
        <!--===============MODAL NUEVO REPORTE PERIODICO-->
        <div class="modal fade" id="reporteFinal" tabindex="-1" style="z-index: 1500" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document" id="panel_reporteFinal">

            </div>

        </div>
        <!--====================END=======================-->
    <script>
            function cargarDatos(ruta,panel){
                $('#'+panel).html("<br/><br/><div class='d-flex justify-content-center'><div class='spinner-border text-danger' role='status'> <span class='visually-hidden'></span></div></div>");
                $.ajax({
                    url: ruta,
                    type: 'GET',
                    data:'',
                    success: function (resp) {
                        $('#'+panel).html(resp);
                        obtDiario(0)
                    },
                    error: function () {
                        alert('No se puede ejecutar la petición');
                    }
                });
            }

        function obtDiario(numero) {
            var fi=$('#fi'+numero).val();
            var ff=$('#ff'+numero).val();
            if(ff!='' && fi!='') {
                var datos="fi="+fi+"&ff="+ff;
                var ruta = "{{url('o_reporteDiario')}}";

                $.ajax({
                    url: ruta,
                    type: 'GET',
                    data:datos,
                    success: function (resp) {
                        $('#diarios'+numero).html(resp);
                    },
                    error: function (error) {
                        alert('No se puedo efectuar la petición')
                    }
                });
            }
        }
    </script>

@endsection
