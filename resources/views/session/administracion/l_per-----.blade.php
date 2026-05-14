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
        <div class="card-header py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class="m-0 font-weight-bold text-dark">Lista del personal</h5>
                <a href="{{url('n_personal')}}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
                    <i class="fas fa-user fa-sm"></i> Nuevo personal</a>
            </div>
        </div>
        <div class="card-body">
            <div class="alert-info centrar_bloque col-md-6 p-2 mb-2 rounded shadow">
                <h5 class="text-dark text-center">Lista de Responsables y Funcionarios</h5>
            </div>
            <br/>

            <div class="">
                <table class="table table-sm table-hover centrar_bloque shadow-sm rounded">
                    <tr class="bg-info text-white rounded shadow-sm">
                        <th>Nº</th>
                        <th>CI</th>
                        <th>Nombre</th>
                        <th>Celular</th>
                        <th>Habilitado</th>
                        <th>Responsable</th>
                        <th>Opciones</th>
                    </tr>
                    <?php $i=1;?>
                    @foreach($per as $p)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$p->per_ci}}</td>
                            @if($p->per_fot!='')
                                <td>    <img src="{{url('img/foto/'.$p->per_fot)}}" width="40" height="40" class="imgRedonda"/>
                                    {{$p->per_ape." ".$p->per_nom}}
                                </td>
                            @else
                                <td class="pl-5">
                                    {{$p->per_ape." ".$p->per_nom}}
                                </td>
                            @endif
                            <td>{{$p->per_telf}}</td>
                            <td>
                                @if($p->fun_hab=='t')
                                    <a href="#" class="btn btn-light btn-circle btn-sm text-success" data-target="#df{{$i}}" data-toggle="modal">
                                        <i class="fas fa-user-check"></i>
                                    </a>
                                    <!-- =============MODAL DESHABILITAR FUNCIONARIO-->
                                    <div class="modal fade" id="df{{$i}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content border-bottom-danger">
                                                <div class="modal-header bg-danger">
                                                    <h5 class="modal-title text-white" id="exampleModalLabel">Deshabilitar funcionario</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body row">

                                                    Esta seguro de deshabilitar al funcionario:
                                                    <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-8 p-3">
                                                        {{$p->per_ape." ".$p->per_nom}}
                                                    </div>
                                                    <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2>?</h2></div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                    <form action="{{url('h_fun')}}" method="POST">
                                                        @csrf
                                                        <input class="btn btn-danger" type="submit" value="Aceptar"/>
                                                        <input type="hidden" name="ip" value="{{$p->id_per}}">
                                                        <input type="hidden" name="val" value="f">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ====================END ==============-->
                                @else
                                    <a href="#" class="btn btn-light btn-circle btn-sm text-dark" data-target="#hf{{$i}}" data-toggle="modal">
                                        <i class="fas fa-user-lock"></i>
                                    </a>
                                    <!-- =============MODAL HABILITAR FUNCIONARIO-->
                                    <div class="modal fade" id="hf{{$i}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content  border-bottom-primary">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-primary" id="exampleModalLabel">Habilitar funcionario</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body row">
                                                    Esta seguro de habilitar al funcionario: <br/><br/>
                                                    <div class="font-weight-bold alert-primary shadow text-center centrar_bloque col-md-8 p-3">
                                                        {{$p->per_ape." ".$p->per_nom}}
                                                    </div>
                                                    <div class="pt-2 col-md-2 text-primary font-weight-bolder text-left"><h2>?</h2></div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                    <form action="{{url('h_fun')}}" method="POST">
                                                        @csrf
                                                        <input class="btn btn-primary" type="submit" value="Aceptar"/>
                                                        <input type="hidden" name="ip" value="{{$p->id_per}}">
                                                        <input type="hidden" name="val" value="t">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ====================END ==============-->
                                @endif
                            </td>
                            <td>
                            @if($p->res_hab=='t')
                                    <a href="#" class="btn btn-light btn-circle btn-sm text-success" data-target="#ap{{$i}}" data-toggle="modal">
                                        <i class="fas fa-user-check"></i>
                                    </a>
                                    <!-- =============MODAL QUITAR CARGO DE RESPONSABLE-->
                                    <div class="modal fade" id="ap{{$i}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content border-bottom-danger">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-danger" id="exampleModalLabel">Permisos de Responsable</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body row">
                                                    Esta seguro de quitar permisos de responsable a: <br/><br/>
                                                    <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-8 p-3">
                                                        {{$p->per_ape." ".$p->per_nom}}
                                                    </div>
                                                    <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2>?</h2></div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                    <form action="{{url('h_res')}}" method="POST">
                                                        @csrf
                                                        <input class="btn btn-danger" type="submit" value="Aceptar"/>
                                                        <input type="hidden" name="ip" value="{{$p->id_per}}">
                                                        <input type="hidden" name="val" value="f">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ====================END ==============-->
                                @else
                                    <a href="#" class="btn btn-light btn-circle btn-sm text-dark" data-target="#qp{{$i}}" data-toggle="modal">
                                        <i class="fas fa-user-lock"></i>
                                    </a>
                                    <!-- =============MODAL HABILITAR CARGO DE RESPONSABLE-->
                                    <div class="modal fade" id="qp{{$i}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content border-bottom-primary">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-primary" id="exampleModalLabel">Permisos de Responsable</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body row">
                                                    Esta seguro de habilitar permisos de responsable a: <br/><br/>

                                                    <div class="font-weight-bold alert-primary shadow text-center centrar_bloque col-md-8 p-3">
                                                        {{$p->per_ape." ".$p->per_nom}}
                                                    </div>
                                                    <div class="pt-2 col-md-2 text-primary font-weight-bolder text-left"><h2>?</h2></div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                    <form action="{{url('h_res')}}" method="POST">
                                                        @csrf
                                                        <input class="btn btn-primary" type="submit" value="Aceptar"/>
                                                        <input type="hidden" name="ip" value="{{$p->id_per}}">
                                                        <input type="hidden" name="val" value="t">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ====================END ==============-->
                            @endif
                            </td>
                            <td>
                                <a href="{{url('m_c_usuario/'.$p->id_per)}}" class="btn btn-light btn-circle btn-sm text-primary">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                        <?php $i++;?>
                    @endforeach
                </table>
            </div>
        </div>

    </div>

@endsection
