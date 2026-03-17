<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-file-alt"></i> TRAMITE CONVOCATORIA</h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>

    <!-- Formulario Convocatoria -->
    <div class="card shadow">
        <div class="modal-body">
            @if(Session::has('exitoModal'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {!! session('exitoModal') !!}
                </div>
            @endif
            @if(Session::has('errorModal'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {!! session('errorModal') !!}
                </div>
            @endif

            <div class="d-flex justify-content-center">
                <div class="card-body" style="font-size: 14px;">
                    @if(!$tramite_noatentado)

                            <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                                <h5 class="text-white text-center">Nueva trámite no atentado</h5>
                            </div>
                            <hr class="sidebar-divider text-bg-dark">

                            <div class="row">
                                <div class="col-md-6 table">
                                    <form id="form_tramite">
                                        @csrf
                                    <span class="text-primary font-weight-bold text-primary font-italic" style="font-size: 14px">* Datos de la convocatoria</span>
                                    <table class="col-md-12">
                                        <tbody>
                                        <tr>
                                            <th class="text-right font-italic" style=" padding-top: 7px">Convocatoria :</th>
                                            <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                <span class="text-secondary font-italic font-weight-bold">{{$convocatoria->con_nombre}}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Trámite :</th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0" name="tramite">
                                                    @foreach($tramites as $t)
                                                        <option value="{{$t->cod_tre}}">{{$t->tre_nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic ">Tipo de trámite :</th>
                                            <td class="border-bottom border-dark">
                                                <input type="radio" name="tipo_tramite" checked value="t">  INTERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="radio" name="tipo_tramite" value="f"> EXTERNO
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic ">Nro. Control:</th>
                                            <td class="border-bottom input-group">
                                                <div class="input-group">
                                                    <input class="form-control form-control-sm" required name="control" />&nbsp;&nbsp;
                                                    <span class="text-primary font-weight-bold font-italic"> Nro. Control Reintegro : &nbsp;</span>
                                                    <input class="form-control form-control-sm" required name="reintegro" />
                                                </div>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                        <input type="hidden" name="cc" value="{{$convocatoria->cod_con}}">
                                    </form>
                                    <br/>

                                    <div class="col-md-12">
                                        <button class="btn btn-primary btn-sm float-right" type="button" onclick="enviar('form_tramite','{{url('guardar tramite convocatoria noatentado')}}','panel_noatentado');cargarDatos('{{url('actualizar lista tramite convocatoria/'.$cod_con)}}','panel_lista_tramites')"> Guardar</button>
                                    </div>
                                </div>
                            </div>
                    @else
                        <div class="text-center">
                            <h4 class="text-primary font-weight-bold">Editar Convocatoria</h4>
                        </div>
                        <hr class="sidebar-divider text-bg-dark">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="shadow-sm p-2 col-md-5 float-md-right">
                                    <h1 class="text-danger pr-3 text-center">{{$tramite_noatentado->dtra_numero_tramite}}</h1>
                                    <span class="font-italic text-dark text-center"><?php if($tramite_noatentado->dtra_fecha_registro!=''){echo date('d/m/Y',strtotime($tramite_noatentado->dtra_fecha_registro));} ?></span>
                                </div>
                                <span class="text-primary font-weight-bold text-primary font-italic" style="font-size: 14px">* Datos de la convocatoria</span>
                                <form id="form_tramite">
                                    @csrf
                                <table class="col-md-12 text-dark table">
                                    <tbody>
                                    <tr>
                                        <th class="text-right font-italic" style=" padding-top: 7px">Convocatoria :</th>
                                        <td class="border-bottom border-dark" style=" padding-top: 7px">
                                            <span class="text-secondary font-italic font-weight-bold">{{$convocatoria->con_nombre}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Trámite :</th>
                                        <td class="border-bottom border-dark">
                                            <span class="font-weight-bold">{{$tramite_noatentado->tre_nombre}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic ">Tipo de trámite :</th>
                                        <td class="border-bottom border-dark">
                                            @if($tramite_noatentado->dtra_interno=='t')
                                            <input type="radio" name="tipo_tramite" checked value="t">  INTERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="tipo_tramite" value="f"> EXTERNO
                                            @else
                                                <input type="radio" name="tipo_tramite" value="t"> INTERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="radio" name="tipo_tramite" checked value="f">  EXTERNO
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic ">Nro. Control:</th>
                                        <td class="border-bottom  input-group">
                                            <div class="input-group">
                                                <input class="form-control form-control-sm border" required name="control"  value="{{$tramite_noatentado->dtra_control}}"/>
                                                <span class="text-primary font-weight-bold font-italic"> Nro. Control Reintegro : &nbsp;</span>
                                                <input class="form-control form-control-sm border" required name="reintegro"  value="{{$tramite_noatentado->dtra_valorado_reintegro}}"/>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                    <input type="hidden" name="cd" value="{{$tramite_noatentado->cod_dtra}}">
                                    <input type="hidden" name="cc" value="{{$tramite_noatentado->cod_con}}">
                                </form>
                                @can('editar tramite - noa')
                                <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm float-right" type="button" onclick="enviar('form_tramite','{{url('guardar tramite convocatoria noatentado')}}','panel_noatentado');cargarDatos('{{url('actualizar lista tramite convocatoria/'.$cod_con)}}','panel_lista_tramites')"> Guardar</button>
                                </div>
                                @endcan
                            </div>
                            <div class="col-md-7 shadow border rounded p-2" >
                                    <span class="font-weight-bold text-primary font-italic">* Datos personales</span>
                                    <div class="overflow-auto" style="height: 400px" id="panel_candidato">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover" id="lista" width="100%" cellspacing="0" style="font-size: 12px">

                                                <tr>
                                                    <th>N°</th>
                                                    <th>Nombre</th>
                                                    <th>CI</th>
                                                    <th>COD SIS</th>
                                                    <th>Cargo</th>
                                                    <th>Unidad</th>
                                                    <th>Opciones</th>
                                                </tr>
                                                    <?php $i=1;?>
                                                @foreach($noatentados as $n)
                                                    <?php $sancionado=App\Http\Controllers\Noatentado\SancionadosController::verificarSancionado($n->id_per);?>
                                                    @if($sancionado)
                                                    <tr class="alert-danger">
                                                        @else
                                                        <tr>
                                                        @endif
                                                        <td>{{$i++}}</td>
                                                        <td>{{$n->per_nombre." ".$n->per_apellido}}</td>
                                                        <td>{{$n->per_ci}}</td>
                                                        <td>{{$n->per_cod_sis}}</td>
                                                        <td>{{$n->carg_nombre}}</td>
                                                        <td>{{$n->noa_unidad}}</td>
                                                        <td>
                                                            @if($sancionado && $sancionado->cod_res!='')
                                                                <a href="" class="btn btn-circle btn-light btn-sm text-danger border" data-toggle="modal" data-target="#Noatentado_agregar"
                                                                   onclick="cargarDatos('{{url('ver datos resolucion/'.$sancionado->cod_res)}}','panel_agregar')" title="Ver detalle de la resolución"> <i class="fas fa-file-pdf"></i>
                                                                </a>
                                                            @endif
                                                            @if($tramite_noatentado->dtra_generado=='')
                                                                <a href="#"  class="btn btn-sm btn-light btn-circle" data-toggle="modal" data-target="#Noatentado_agregar" title="Eliminar candidato"
                                                                   onclick="cargarDatos('{{url('formulario eliminar candidato/'.$n->cod_noa)}}','panel_agregar');
                                                                    cargarDatos('{{url('actualizar lista tramite convocatoria/'.$cod_con)}}','panel_lista_tramites');">
                                                                    <i class="fas fa-trash-alt text-danger"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                <div class="input-group col-md-12 justify-content-center">
                                    @can('editar tramite - noa')
                                        @if($tramite_noatentado->dtra_qr=='')
                                            <a href="#Noatentado_agregar" class="btn btn-sm btn-primary" data-toggle="modal"
                                               onclick="cargarDatos('{{url('editar candidato convocatoria/'.$tramite_noatentado->cod_dtra.'/0')}}','panel_agregar')">+ Candidato</a> &nbsp; &nbsp;
                                            <a href="#Noatentado_agregar" class="btn btn-sm btn-success" data-toggle="modal"
                                               onclick="cargarDatos('{{url('agregar candidato excel convocatoria/'.$tramite_noatentado->cod_dtra)}}','panel_agregar')">+ Exportar de exel</a> &nbsp; &nbsp;
                                        @endif
                                    @endcan
                                </div>
                            </div>
                       @endif
                    </div>
                </div>
                <input type="hidden" name="cc" value="{{$convocatoria->cod_con}}">
            </div>
        </div><!-- End Formulario Convocatoria -->
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>

