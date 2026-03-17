<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-user"></i> Nuevo plan</h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        @if(Session::has('exito'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="font-weight-bold">{!! session('exito') !!}</span>
            </div>
        @endif
        <div class="row">
            <!--==================LISTA DE PLANES-->
            <div class="col-md-6 mr-2">
                <div class="card">

                    <div class="card-body">
                        <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                            <h6 class="text-white text-center">Lista de planes de archivos</h6>
                        </div>
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr class="bg-gray-600 text-white">
                                    <th>Nº</th>
                                    <th class="">Código</th>
                                    <th class="">Título</th>
                                    <th class="text-right">Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php  $i=1;?>
                                @foreach($plan as $p)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$p->plan_numero}}</td>
                                        <td>{{$p->plan_titulo}}</td>
                                        <td class="text-right">
                                            @can('editar plan - rr')
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#editarPlan" data-toggle="modal" onclick="cargarDatos('{{url('fe_plan/'.$p->cod_plan)}}','panel_editar_plan')"
                                               title="Editar plan"><i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            @can('eliminar plan - rr')
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-target="#eliminarPlan" data-toggle="modal" onclick="cargarDatos('{{url('f_eli_plan/'.$p->cod_plan)}}','panel_eliminar_plan')"
                                               title="Eliminar plan"><i class="fas fa-trash-alt"></i>
                                            </a>
                                            @endcan
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
        @can('crear plan - rr')
            <!--==================PARA UN NUEVO PLAN-->
            <div class="col-md-5">
                <div class="card">
                    <form id="form_enviar_plan" action="{{url('g_plan')}}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                                <h6 class="text-white text-center">Formulario de nuevo plan</h6>
                            </div>
                            <br/>
                            <table class="table-hover col-md-12" >
                                <tr>
                                    <th class="text-right font-italic" width="200">Numero de código:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="numero" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic"> Título :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="titulo" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                    </div>
                    </form>
                </div>
            </div>
            @endcan
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-danger" type="button" data-dismiss="modal">Cerrar</button>
    </div>
</div>

