@extends('marco/pagina')
@section('contenido')
    @if(Session::has('exito'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="font-weight-bold">{!! session('exito') !!}</span>
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="font-weight-bold text-dark">{!! session('error') !!}</span>
        </div>
    @endif
    @if(count($errors)>0)
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                @foreach($errors->all() as $e)
                    <li class="font-weight-bold te">{{$e}} - </li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class=""><i class="fas fa-user-friends"></i>&nbsp;&nbsp; Lista de usuarios y reportes por día</h5>
            </div>
        </div>
        <div class="card-body">
            <div class=" input-group -sm p-2">

                <SPAN class="font-weight-bold font-italic text-dark">Fecha : </SPAN>&nbsp;&nbsp;<span class="text-primary">{{date('d/m/Y',strtotime($fecha))}}</span> &nbsp;&nbsp;|
                &nbsp;<span class="font-weight-bold text-dark font-italic">Seleccionar fecha : </span>&nbsp;<input type="date" name="fecha" class="form-control form-control-sm col-md-2"
                                                                                            onchange="document.location='{{url("listar reportes fecha adm")}}/'+this.value;">
            </div>
            <div class="card  mb-4">
                <div class="card-body">
                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Lista de usuarios y reportes por fecha</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr class="bg-gray-600 text-white">
                                <th>Nº</th>
                                <th class="text-right">CI</th>
                                <th class="text-left">Foto</th>
                                <th class="text-left">Nombre</th>
                                <th class="text-left">Fecha de reporte</th>
                                <th class="text-right">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=1;?>
                            @foreach($usuarios as $u)
                                <tr style="font-size: 0.9em">
                                    <th class="border-right font-weight-bolder text-primary">{{$i}}</th>
                                    <td class="text-right">{{$u->ci}}</td>
                                    <td>@if($u->foto!='')
                                            <img src="{{url('img/foto/'.$u->foto)}}" width="40" height="40" class="imgRedonda float-right"/>
                                        @endif
                                    </td>
                                    <td class="font-weight-bold">
                                        {{$u->name}}
                                    </td>
                                    <td>
                                    <?php
                                            foreach ($reportes as $r):
                                                if($u->id==$r->id){
                                                    if($r->dia_fech_reportado==$fecha){
                                                        echo "<span class=''>".date('d/m/Y',strtotime($r->dia_fech_reportado))."</span><br/>";
                                                    }
                                                    else{
                                                        echo "<span class='text-danger font-weight-bold'>".date('d/m/Y',strtotime($r->dia_fech_reportado))."</span><br/>";
                                                    }
                                               }
                                            endforeach;
                                        ?>
                                    </td>
                                    <td class="text-right">
                                        <?php
                                            foreach ($reportes as $r):
                                                if($u->id==$r->id){
                                                    ?>
                                                        <a href="#" class="btn btn-light btn-circle btn-sm " data-target="#observacion" data-toggle="modal"
                                                            onclick="cargarDatos('{{url('revision diario adm/'.$r->cod_dia)}}','panel_observacion')">

                                                                @if($r->dia_aceptado!='t')
                                                                    <i class="fas fa-edit text-primary"></i>
                                                                @else
                                                                    <i class="fas fa-check text-success"></i>
                                                                @endif
                                                        </a><br/>
                                                    <?php
                                                }
                                            endforeach;
                                        ?>
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

    <!--===========================END ==============================-->
    <div class="modal fade" id="observacion" style="z-index: 1500;" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" id="panel_observacion">

        </div>
    </div>
    <!-- =============================== ====================-->

    <script>
        function cargarDatosssss(ruta,panel){
            $('#'+panel).html("<br/><br/><div class='d-flex justify-content-center text-danger'><div class='spinner-border' role='status'> <span class='visually-hidden'></span></div></div>");
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
