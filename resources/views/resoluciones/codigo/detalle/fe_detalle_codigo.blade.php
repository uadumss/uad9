<div class="modal-dialog modal-xl" role="document" id="panel_editar">
    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-user"></i> Editar código de archivado</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="bg-primary centrar_bloque p-2 col-md-5 rounded shadow">
                <h6 class="text-white text-center font-weight-bold">Formulario de detalle de código</h6>
            </div>
            <hr/>
            <div class="row">
                <div class="col-md-5">
                    <table class="col-md-12">
                        <tr>
                            <th colspan="2" class="text-right font-italic text-primary font-weight-bold" width="200">* DATOS DEL CÓDIGO <br/><br/></th>
                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark">Plan : </th>
                            <td class="border-bottom border-dark text-dark">
                                &nbsp;&nbsp;{{$plan->plan_numero.". - ".$plan->plan_titulo}}
                            </td>

                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark">Código : </th>
                            <td class="border-bottom border-dark text-dark">
                                &nbsp;&nbsp;{{$plan->plan_numero."/".$codigo->carch_numero}}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark">Tema : </th>
                            <td class="border-bottom border-dark text-danger font-italic font-weight-bold">
                                &nbsp;&nbsp;{{$codigo->carch_titulo}}
                            </td>
                        </tr>

                    </table>
                    <br/>
                    <br/>
                    <div>
                        <br/>
                        <button id="otros" class="btn btn-sm btn-primary float-right" onclick="$('#detalle').show(500); $('#otros').hide(500);"> Nuevo tema</button>
                        <div id="detalle" class="border rounded shadow" style="display: none;">
                            <div class="p-3">
                                <a onclick="$('#detalle').hide(500);$('#otros').show(500); " id="ocultar" style="float:right">
                                    <i class="fas fa-minus-circle text-danger"></i></a>
                                <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Nuevo Tema</span>
                                <form id="form_detalle">
                                    @csrf
                                    <table class="table-hover col-md-12">
                                        <tr>
                                            <th class="text-right font-italic">Nuevo tema : </th>
                                            <td class="border-bottom border-dark">
                                                <input type="text" class="form-control form-control-sm border-0" placeholder="" name="tema"/></td>
                                        </tr>
                                    </table>
                                    <br/>
                                    <input type="hidden" name="cc" value="{{$codigo->cod_carch}}">
                                </form>
                                <a class="btn btn-primary btn-sm text-white float-right" onclick="enviar('form_detalle','{{url("guardar detalle codigo")}}','panel_detalle_codigo');" >Guardar</a><br/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 border rounded shadow">
                    <span class="text-danger font-italic font-weight-bold" style="font-size: 12px">
                        * Detalle del código
                    </span>
                    <div id="panel_detalle_codigo">
                        @if(sizeof($detalle)<1)
                            <div class="alert-danger border-danger rounded p-2 centrar_bloque">
                                No hay datos para mostrar
                            </div>
                        @else
                            <table class="table table-sm">
                                <tr class="text-dark bg-light">
                                    <th>Número</th>
                                    <th>Nombre</th>
                                    <th>Opciones</th>
                                </tr>
                                <?php $i=0;?>
                                @foreach($detalle as $d)
                                    <tr>
                                        <td>{{$i+=1}}</td>
                                        <td>{{$d->det_nombre}}</td>
                                        <td>
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#editarPlan" data-toggle="modal" onclick="cargarDatos('{{url("editar tema codigo/".$d->cod_det)}}','panel_editar_plan')"
                                                   title="Editar código"><i class="fas fa-edit"></i></a>
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-target="#editarPlan" data-toggle="modal" onclick="cargarDatos('{{url("f_eliminar tema codigo/".$d->cod_det)}}','panel_editar_plan')"
                                                   title="Eliminar código"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>
