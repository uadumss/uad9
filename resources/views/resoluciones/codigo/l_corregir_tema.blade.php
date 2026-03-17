@extends('marco/pagina')
@section('contenido')
    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class=" ">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class=""><i class="fas fa-check"></i>&nbsp;Corregir resoluciones</h5>
                    </div>

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="bg-primary centrar_bloque p-1 col-md-6 rounded shadow">
                <h5 class="text-white text-center">Lista de temas</h5>
            </div>

            <div class=" input-group -sm p-2">
                <div class="input-group shadow-sm p-1" style="font-size: 0.9em">
                    <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                    <form id="form_corregir_temas" action="{{url('corregir temas resolucion')}}" method="POST">
                        @csrf
                        <div class="input-group shadow-sm p-1" style="font-size: 0.9em">
                            <input type="text"  class="form-control-sm form-control" name="criterio" /> &nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="submit" class="btn btn-sm btn-outline-info text-dark" name="enviar" value="Buscar"/>
                        </div>
                    </form>
                    <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                    <form id="form_corregir_temas" action="{{url('asignar temas resolucion corregido')}}" method="POST">
                        @csrf
                        <div class="input-group shadow-sm p-1" style="font-size: 0.9em">
                            <input type="hidden" name="criterio" value="{{$criterio}}">
                            <select class="custom-select custom-select-sm " name="tema">
                                <option></option>
                                @foreach($plan as $p)
                                    <option value="{{$p->cod_det}}">{{$p->plan_numero."/".$p->carch_numero." ".$p->carch_titulo." - ".$p->det_nombre}}</option>
                                @endforeach
                            </select>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="submit" class="btn btn-sm btn-outline-info text-dark" name="enviar" value="Asignar tema"/>
                        </div>
                    </form>
                </div>
            </div>
            <hr/>
            <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr class="bg-gradient-secondary text-white text-center" style="font-size: 0.9em">
                    <th>Nº</th>
                    <th>Tema</th>
                    <th>Cantidad</th>
                    <th>Opciones</th>
                </tr>
                </thead>
                <tbody>
                <?php $i=1;?>
                @foreach($resultado as $r)
                    <tr id="fila{{$i}}" style="font-size: 0.9em">
                        <td class="text-primary border-right">{{$i}}</td>
                        <td >{{$r->tema}}</td>
                        <td class="text-right">{{$r->cantidad}}
                            <a class="btn btn-sm btn-light btn-circle text-danger" data-toggle="modal" data-target="#Detalle" onclick="cargarDatos('{{url('mostrar resoluciones tema corregir/'.$r->tema)}}','panel_detalle')">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        </td>

                        <td class="text-right">
                            <div id="panel_asignar{{$i}}">
                                <form id="form_corregir_temas{{$i}}" action="" method="POST">
                                    @csrf
                                    <div class="input-group shadow-sm p-1" style="font-size: 0.9em">
                                        <input type="hidden" name="criterio" value="{{$r->tema}}">
                                        <select class="custom-select custom-select-sm  rounded" name="tema">
                                            @foreach($plan as $p)
                                                <option value="{{$p->cod_det}}">{{$p->plan_numero."/".$p->carch_numero." ".$p->carch_titulo." - ".$p->det_nombre}}</option>
                                            @endforeach
                                        </select>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-outline-info text-dark" type="button"
                                                    onclick="enviar('form_corregir_temas{{$i}}','{{url('asignar temas resolucion corregido')}}','panel_asignar{{$i}}')">Asignar tema</button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                        <?php $i++;?>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="Detalle" style="" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" id="panel_detalle">

        </div>
    </div>
@endsection
