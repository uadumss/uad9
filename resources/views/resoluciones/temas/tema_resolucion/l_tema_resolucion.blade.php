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


    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="row">
                <div class="col-md-6">
                    <h5 class=""><i class="fas fa-file"></i>&nbsp;&nbsp;TEMAS DE INTERES </h5>
                </div>
                <div class="col-md-6">



                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="input-group">
                <a href="{{url('temas interes/')}}" class="btn btn-outline-info btn-sm text-dark mt-1 shadow-sm"><i class="fas fa-arrow-alt-circle-left"></i> Atrás</a>
                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                <a href="#" class="btn btn-outline-info btn-sm text-dark mt-1 shadow-sm" data-target="#Tema" data-toggle="modal"
                   onclick="cargarDatos('{{url("fe_tema_resolucion/".$tema->cod_tem)}}','panel_tema')">
                    + Resolución </a>&nbsp;&nbsp;
                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                <a href="#" class="btn btn-outline-info btn-sm text-dark mt-1 shadow-sm" data-target="#Tema" data-toggle="modal"
                   onclick="cargarDatos('{{url("fe_importar tema/".$tema->cod_tem)}}','panel_tema')">
                    Importar tema </a>&nbsp;&nbsp;


            </div>
            <hr class="sidebar-divider"/>
            <div>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                            <h6 class="text-white text-center">LISTA DE TEMAS DE INTERES</h6>
                        </div>
                        <span class="text-dark" style="font-size: 12px">
                            <span class="font-weight-bold">Tema : </span><span class="font-italic">{{$tema->tem_titulo}}</span><br/>
                            <span class="font-weight-bold">Descripción : </span><span class="font-italic">{{$tema->tem_des}}</span>
                        </span>
                        <hr class="sidebar-divider"/>
                        <div id="panel_principal_resolucion">
                            @include('resoluciones.temas.tema_resolucion.l_actualizar_tema')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!--=================================EDITAR RESOLUCION========================-->
        <div class="modal fade" id="Tema" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-xl" role="document" id="panel_tema">

            </div>
        </div>

        <!--=============================END==================================-->
    <!--=================================EDITAR RESOLUCION========================-->
    <div class="modal fade" id="Asignar" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" id="panel_asignar">

        </div>
    </div>

    <!--=============================END==================================-->
@endsection
