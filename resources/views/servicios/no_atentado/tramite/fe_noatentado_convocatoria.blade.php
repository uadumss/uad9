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
                    {{ session('exitoModal') }}
                </div>
            @endif
            @if(Session::has('errorModal'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ session('errorModal') }}
                </div>
            @endif
            <div id="noa_feedback_js" class="mb-2" style="display:none;"></div>

            <div class="d-flex justify-content-center">
                <div class="card-body" style="font-size: 14px;">
                    @if(!$tramite_noatentado)
                        <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                            <h5 class="text-white text-center">Nuevo trámite No Atentado</h5>
                        </div>
                        <hr class="sidebar-divider text-bg-dark">

                        <div class="text-right mb-2">
                            <button class="btn btn-outline-primary btn-sm" type="button"
                                    data-toggle="modal" data-target="#Noatentado_agregar"
                                    data-url="{{url('lista escala precios noatentado')}}"
                                    onclick="cargarDatos(this.dataset.url,'panel_agregar')">
                                <i class="fas fa-table"></i> Ver escala de precios
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-12 table">
                                <form id="form_tramite">
                                    @csrf

                                    <div class="card shadow-sm border-0 mb-2">
                                        <div class="card-header bg-white py-2">
                                            <span class="font-weight-bold text-primary">Paso 1. Candidatos</span>
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="row">
                                                <div class="col-md-3 mb-1">
                                                    <label class="font-italic mb-0">CI</label>
                                                    <input class="form-control form-control-sm" id="noa_ci" onchange="cargarDatosPersonalesNoa(this.value)">
                                                </div>
                                                <div class="col-md-3 mb-1">
                                                    <label class="font-italic mb-0">Nombres</label>
                                                    <input class="form-control form-control-sm" id="noa_nombre">
                                                </div>
                                                <div class="col-md-3 mb-1">
                                                    <label class="font-italic mb-0">Apellidos</label>
                                                    <input class="form-control form-control-sm" id="noa_apellido">
                                                </div>
                                                <div class="col-md-3 mb-1">
                                                    <label class="font-italic mb-0">Cod. SIS</label>
                                                    <input class="form-control form-control-sm" id="noa_cod_sis">
                                                </div>

                                                <div class="col-md-4 mb-1">
                                                    <label class="font-italic mb-0">Cargo texto</label>
                                                    <input class="form-control form-control-sm" id="noa_cargo">
                                                </div>
                                                <div class="col-md-5 mb-1">
                                                    <label class="font-italic mb-0">Cargo convocatoria</label>
                                                    <select class="custom-select custom-select-sm" id="noa_cargo_convocatoria">
                                                        <option value="">Seleccione</option>
                                                        @foreach($cargos as $cargo)
                                                            <option value="{{$cargo->cod_carg}}">{{$cargo->carg_nombre}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-1 d-flex align-items-end justify-content-end">
                                                    <button class="btn btn-sm btn-primary" type="button" onclick="agregarCandidatoNoAtentado()"><i class="fas fa-user-plus"></i> Agregar</button>
                                                </div>

                                                <div class="col-md-12 mb-1 mt-2">
                                                    <div class="input-group input-group-sm">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="excel_candidatos_noa" accept=".xlsx,.xls" onchange="actualizarNombreExcelNoatentado(this)">
                                                            <label class="custom-file-label" id="label_excel_candidatos_noa" for="excel_candidatos_noa">Seleccionar archivo Excel</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-success" type="button" onclick="importarExcelCandidatosNoAtentado()"><i class="fas fa-file-import"></i> Importar Excel</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="table-responsive border rounded" style="max-height: 220px; overflow:auto;">
                                                <table class="table table-sm table-hover mb-0" id="tabla_candidatos_noa" style="font-size: 12px;">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>N°</th>
                                                            <th>Apellidos y nombres</th>
                                                            <th>CI</th>
                                                            <th>Cod. SIS</th>
                                                            <th>Cargo</th>
                                                            <th>Opciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr id="fila_vacia_candidatos_noa">
                                                            <td colspan="6" class="text-center text-secondary">No hay candidatos registrados.</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="candidatos_json" id="candidatos_json_noa">

                                    <div class="card shadow-sm border-0 mb-2" id="bloque_pago_noa">
                                        <div class="card-header bg-white py-2">
                                            <span class="font-weight-bold text-primary">Paso 2. Datos del trámite y pago</span>
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label class="font-italic mb-0">Convocatoria</label>
                                                    <input class="form-control form-control-sm" value="{{$convocatoria->con_nombre}}" readonly>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label class="font-italic mb-0">Trámite</label>
                                                    <select class="custom-select custom-select-sm" name="tramite" id="tramite_noa" disabled>
                                                        <option value="">Seleccione</option>
                                                        @foreach($tramites as $t)
                                                            <option value="{{$t->cod_tre}}">{{$t->tre_nombre}}</option>
                                                        @endforeach
                                                    </select>
                                                    <small id="ayuda_tramite_noa" class="text-secondary">Se define al validar el pago.</small>
                                                </div>

                                                <div class="col-md-3 mb-2">
                                                    <label class="font-italic mb-0 d-block">Tipo de trámite</label>
                                                    <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-center flex-wrap">
                                                        <label class="mb-0 font-weight-normal">
                                                            <input type="radio" name="tipo_tramite" checked value="t"> INTERNO
                                                        </label>
                                                        <label class="mb-0 font-weight-normal ml-2">
                                                            <input type="radio" name="tipo_tramite" value="f"> EXTERNO
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-5 mb-2">
                                                    <div class="border rounded p-2 bg-light h-100">
                                                        <label class="font-italic mb-1 d-block text-primary">Pago principal</label>
                                                        <div class="form-row">
                                                            <div class="col-md-7 mb-2 mb-md-0">
                                                                <label class="font-italic mb-0">Nro. Control</label>
                                                                <div class="input-group input-group-sm">
                                                                    <input class="form-control form-control-sm" required name="control" id="control_noa" oninput="programarValidacionPagoNoAtentado();">
                                                                    <div class="input-group-append">
                                                                        <a href="#" class="btn btn-light btn-circle btn-sm text-secondary noa-estado-pago-icon" data-campo="estado-pago-control-icon" data-pago-campo="control" title="Ver detalle de validación de pago" onclick="abrirDetallePagoNoatentado(this); return false;"><i class="fas fa-minus-circle"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <label class="font-italic mb-0">Preimpreso (principal)</label>
                                                                <div class="input-group input-group-sm">
                                                                    <input class="form-control form-control-sm" name="preimpreso_pago" id="preimpreso_pago_noa" oninput="programarValidacionPagoNoAtentado();" disabled>
                                                                    <div class="input-group-append">
                                                                        <a href="#" class="btn btn-light btn-circle btn-sm text-muted noa-estado-pago-icon" data-campo="estado-pago-preimpreso-icon" data-pago-campo="preimpreso" title="Ver detalle de validación de pago" onclick="abrirDetallePagoNoatentado(this); return false;"><i class="fas fa-minus-circle"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <small class="text-secondary d-block mt-1">Con varios candidatos, el preimpreso es obligatorio para el control principal.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <div class="border rounded p-2 bg-light h-100">
                                                        <label class="font-italic mb-1 d-block text-primary">Reintegro (opcional)</label>
                                                        <label class="font-italic mb-0">Nro. Control Reintegro</label>
                                                        <div class="input-group input-group-sm">
                                                            <input class="form-control form-control-sm" name="reintegro" id="reintegro_noa" oninput="programarValidacionPagoNoAtentado();">
                                                            <div class="input-group-append">
                                                                <a href="#" class="btn btn-light btn-circle btn-sm text-muted noa-estado-pago-icon" data-campo="estado-pago-reintegro-icon" data-pago-campo="reintegro" title="Ver detalle de validación de pago" onclick="abrirDetallePagoNoatentado(this); return false;"><i class="fas fa-minus-circle"></i></a>
                                                            </div>
                                                        </div>
                                                        <small class="text-secondary d-block mt-1">Se valida en recaudaciones con N° control + CI del pagador principal (sin preimpreso).</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="cc" value="{{$convocatoria->cod_con}}">
                                </form>

                                <div class="col-md-12 mt-1">
                                    <button class="btn btn-primary btn-sm float-right" id="btn_guardar_noa" type="button" onclick="guardarTramiteNoAtentado()"><i class="fas fa-save"></i> Guardar trámite</button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                            <h5 class="text-white text-center">Editar trámite No Atentado</h5>
                        </div>
                        <hr class="sidebar-divider text-bg-dark">

                        <div class="text-right mb-2">
                            <button class="btn btn-outline-primary btn-sm" type="button"
                                    data-toggle="modal" data-target="#Noatentado_agregar"
                                    data-url="{{url('lista escala precios noatentado')}}"
                                    onclick="cargarDatos(this.dataset.url,'panel_agregar')">
                                <i class="fas fa-table"></i> Ver escala de precios
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <div class="border rounded bg-light p-2 d-flex justify-content-between align-items-center flex-wrap shadow-sm">
                                    <div>
                                        <span class="text-secondary font-italic">Trámite N°</span>
                                        <span class="badge badge-primary ml-1">{{$tramite_noatentado->dtra_numero_tramite}}</span>
                                    </div>
                                    <div>
                                        <span class="text-secondary font-italic">Fecha registro:</span>
                                        <span class="font-weight-bold text-dark"><?php if($tramite_noatentado->dtra_fecha_registro!=''){echo date('d/m/Y',strtotime($tramite_noatentado->dtra_fecha_registro));} ?></span>
                                    </div>
                                    <div>
                                        @if($tramite_noatentado->dtra_generado=='')
                                            <span class="badge badge-warning">En edición</span>
                                        @else
                                            <span class="badge badge-success">Generado</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 table">
                                <form id="form_tramite">
                                    @csrf
                                    <span class="text-primary font-weight-bold font-italic" style="font-size: 14px">* Paso 1: Datos del trámite (pago bloqueado)</span>
                                    <div class="border rounded p-2 mb-2">
                                        <table class="col-md-12 text-dark table table-sm mb-0">
                                            <tbody>
                                            <tr>
                                                <th class="text-right font-italic" style="padding-top: 7px">Convocatoria :</th>
                                                <td class="border-bottom border-dark" style="padding-top: 7px">
                                                    <span class="text-secondary font-italic font-weight-bold">{{$convocatoria->con_nombre}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic">Trámite :</th>
                                                <td class="border-bottom border-dark">
                                                    <span class="font-weight-bold">{{$tramite_noatentado->tre_nombre}}</span>
                                                    <small class="text-secondary d-block">El tipo de trámite está definido por el pago validado.</small>
                                                    <input type="hidden" id="tramite_noa_edit" value="{{$tramite_noatentado->cod_tre}}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic">Tipo de trámite :</th>
                                                <td class="border-bottom border-dark">
                                                    @if($tramite_noatentado->dtra_interno=='t')
                                                        <input type="radio" name="tipo_tramite" checked value="t"> INTERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <input type="radio" name="tipo_tramite" value="f"> EXTERNO
                                                    @else
                                                        <input type="radio" name="tipo_tramite" value="t"> INTERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <input type="radio" name="tipo_tramite" checked value="f"> EXTERNO
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic">Nro. Control:</th>
                                                <td class="border-bottom input-group">
                                                    <div class="input-group">
                                                        <input class="form-control form-control-sm border" required name="control" id="control_noa_edit" value="{{$tramite_noatentado->dtra_control}}" readonly/>
                                                        <span class="text-primary font-weight-bold font-italic"> Nro. Control Reintegro : &nbsp;</span>
                                                        <input class="form-control form-control-sm border" name="reintegro" id="reintegro_noa_edit" value="{{$tramite_noatentado->dtra_valorado_reintegro}}" readonly/>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-2 border rounded p-2 bg-light shadow-sm">
                                        <small class="text-secondary">El pago fue validado y bloqueado al crear el trámite. En edición no se puede modificar.</small>
                                    </div>

                                    <input type="hidden" name="cd" value="{{$tramite_noatentado->cod_dtra}}">
                                    <input type="hidden" name="cc" value="{{$tramite_noatentado->cod_con}}">
                                </form>

                                @can('editar tramite - noa')
                                    <div class="col-md-12 mt-2">
                                        <button class="btn btn-primary btn-sm float-right" id="btn_guardar_noa_edit" type="button" onclick="guardarEdicionTramiteNoAtentado()">Guardar cambios</button>
                                    </div>
                                @endcan
                            </div>

                            <div class="col-md-12 shadow border rounded p-2 mt-2">
                                <span class="font-weight-bold text-primary font-italic">* Paso 2: Edición de candidatos</span>
                                <small class="text-secondary d-block mb-1">Solo se permite actualizar datos de candidatos ya registrados.</small>
                                <div class="overflow-auto" style="height: 380px" id="panel_candidato">
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
                                                               data-url="{{url('ver datos resolucion/'.$sancionado->cod_res)}}"
                                                               onclick="cargarDatos(this.dataset.url,'panel_agregar')" title="Ver detalle de la resolución"> <i class="fas fa-file-pdf"></i>
                                                            </a>
                                                        @endif
                                                        @if($tramite_noatentado->dtra_generado=='')
                                                            <a href="#" class="btn btn-sm btn-light btn-circle border" data-toggle="modal" data-target="#Noatentado_agregar" title="Editar candidato"
                                                               data-url="{{url('editar candidato convocatoria/'.$tramite_noatentado->cod_dtra.'/'.$n->cod_noa)}}"
                                                               onclick="cargarDatos(this.dataset.url,'panel_agregar');">
                                                                <i class="fas fa-pencil-alt text-primary"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
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

<style>
.noa-estado-pago-icon {
    cursor: pointer;
    transition: transform 0.18s ease, color 0.18s ease, opacity 0.18s ease;
}

.noa-estado-pago-icon.noa-anim-pop {
    animation: noaIconPop 0.28s ease-out;
}

.noa-estado-pago-icon.noa-anim-alert {
    animation: noaIconAlert 0.32s ease-out;
}

@keyframes noaIconPop {
    0% { transform: scale(0.82); }
    55% { transform: scale(1.16); }
    100% { transform: scale(1); }
}

@keyframes noaIconAlert {
    0% { transform: translateX(0); }
    25% { transform: translateX(-2px); }
    50% { transform: translateX(2px); }
    75% { transform: translateX(-1px); }
    100% { transform: translateX(0); }
}

.noa-cupo-panel {
    background: linear-gradient(180deg,#ffffff 0%,#f8fbff 100%);
}

#tabla_candidatos_noa tbody tr.noa-candidato-permitido {
    background-color: #e9f9ef;
}

#tabla_candidatos_noa tbody tr.noa-candidato-exceso {
    background-color: #fff1f1;
}

@media (prefers-reduced-motion: reduce) {
    .noa-estado-pago-icon {
        transition: none;
    }

    .noa-estado-pago-icon.noa-anim-pop,
    .noa-estado-pago-icon.noa-anim-alert {
        animation: none;
    }
}
</style>

<script type="application/json" id="noa_escala_candidatos_json">{!! json_encode($escalaCandidatosNoa ?? [], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>

<script>
    var candidatosNoAtentado=[];
    var pagoNoAtentadoValidado=false;
    var controlValidadoNoAtentado='';
    var reintegroValidadoNoAtentado='';
    var tramiteValidadoNoAtentado='';
    var detalleValidacionPagoNoAtentado=null;
    var opcionesOriginalesTramiteNoa='';
    var estadoControlPagoNoa={
        resumen:'Pendiente',
        clase:'badge-warning',
        detalle:'Antes de guardar debe validar el número de control.',
        codigo:'',
    };
    var detalleExtendidoPagoNoa='';
    var montoPrincipalValidadoNoa=0;
    var montoReintegroValidadoNoa=0;
    var montoTotalValidadoNoa=0;
    var escalaCandidatosNoa=[];
    var escalaCandidatosNoaNormalizada=[];
    var codTramitePlanchaNoa=Number("{{ (int)($codTramitePlanchaNoa ?? 0) }}");
    var timerValidacionPagoNoa=null;
    var secuenciaValidacionPagoNoa=0;
    var xhrValidacionPagoNoa=null;
    var validacionPagoNoaEnCurso=false;
    var guardandoTramiteNoaEnCurso=false;

    function reiniciarMontosValidadosNoatentado(){
        montoPrincipalValidadoNoa=0;
        montoReintegroValidadoNoa=0;
        montoTotalValidadoNoa=0;
        window.noaMontosValidados={
            principal:0,
            reintegro:0,
            total:0,
        };
        actualizarControlCupoCandidatosNoatentado();
    }

    function asignarMontosValidadosNoatentado(resp){
        const principal=Number(resp && resp.monto_principal_validado ? resp.monto_principal_validado : 0);
        const reintegro=Number(resp && resp.monto_reintegro_validado ? resp.monto_reintegro_validado : 0);
        const total=Number(resp && resp.monto_total_validado ? resp.monto_total_validado : (principal+reintegro));

        montoPrincipalValidadoNoa=isFinite(principal) ? principal : 0;
        montoReintegroValidadoNoa=isFinite(reintegro) ? reintegro : 0;
        montoTotalValidadoNoa=isFinite(total) ? total : 0;

        window.noaMontosValidados={
            principal:montoPrincipalValidadoNoa,
            reintegro:montoReintegroValidadoNoa,
            total:montoTotalValidadoNoa,
        };
        actualizarControlCupoCandidatosNoatentado();
    }

    function formatoMontoNoatentado(valor){
        const numero=Number(valor);
        return isFinite(numero) ? numero.toFixed(2) : '0.00';
    }

    function esTramitePlanchaNoatentado(codTramite){
        const cod=Number(codTramite || 0);
        const codPlancha=Number(codTramitePlanchaNoa || 0);
        if(!isFinite(cod) || cod<=0 || !isFinite(codPlancha) || codPlancha<=0){
            return false;
        }

        return cod===codPlancha;
    }

    function obtenerEscalaCandidatosConfigNoatentado(){
        const nodo=document.getElementById('noa_escala_candidatos_json');
        if(!nodo){
            return [];
        }

        try{
            const data=JSON.parse(nodo.textContent || '[]');
            return Array.isArray(data) ? data : [];
        }catch(e){
            return [];
        }
    }

    function rangoTextoEscalaNoatentado(regla){
        if(!regla){
            return '';
        }

        const min=Number(regla.cantidad_min || 0);
        const max=Number(regla.cantidad_max || 0);
        if(!isFinite(min) || !isFinite(max) || max<=0){
            return '';
        }

        if(min===max){
            return String(max);
        }

        return String(min)+' a '+String(max);
    }

    function normalizarEscalaCandidatosNoatentado(){
        if(!Array.isArray(escalaCandidatosNoa)){
            return [];
        }

        const salida=[];
        for(let i=0;i<escalaCandidatosNoa.length;i++){
            const fila=escalaCandidatosNoa[i] || {};
            const cantidadMin=Number(fila.cantidad_min || 0);
            const cantidadMax=Number(fila.cantidad_max || 0);
            let montoTotal=Number(fila.monto_total || 0);
            const costo=Number(fila.costo || 0);
            const aporte=Number(fila.aporte_umss || 0);

            if(!isFinite(montoTotal) || montoTotal<=0){
                montoTotal=(isFinite(costo) ? costo : 0)+(isFinite(aporte) ? aporte : 0);
            }

            if(!isFinite(montoTotal) || montoTotal<=0 || !isFinite(cantidadMax) || cantidadMax<=0){
                continue;
            }

            const minFinal=(isFinite(cantidadMin) && cantidadMin>0) ? cantidadMin : 1;
            const maxFinal=Math.max(minFinal,cantidadMax);

            salida.push({
                cantidad_min:minFinal,
                cantidad_max:maxFinal,
                monto_total:montoTotal,
                costo:isFinite(costo) ? costo : 0,
                aporte_umss:isFinite(aporte) ? aporte : 0,
            });
        }

        salida.sort(function(a,b){
            if(a.monto_total===b.monto_total){
                return a.cantidad_max-b.cantidad_max;
            }
            return a.monto_total-b.monto_total;
        });

        return salida;
    }

    function resolverCupoCandidatosPorMontoNoatentado(montoTotal){
        const escala=escalaCandidatosNoaNormalizada;
        const monto=Number(montoTotal || 0);

        if(!Array.isArray(escala) || escala.length===0){
            return {
                ok:false,
                maxPermitidos:0,
                resumen:'Sin escala',
                detalle:'No hay escala de precios configurada para controlar candidatos.',
                regla:null,
            };
        }

        if(!isFinite(monto) || monto<=0){
            return {
                ok:false,
                maxPermitidos:0,
                resumen:'Monto pendiente',
                detalle:'Valide el pago para calcular la cantidad permitida de candidatos.',
                regla:null,
            };
        }

        let regla=null;
        const tolerancia=0.01;
        for(let i=0;i<escala.length;i++){
            if((monto+tolerancia)>=escala[i].monto_total){
                regla=escala[i];
                continue;
            }
            break;
        }

        if(!regla){
            return {
                ok:false,
                maxPermitidos:0,
                resumen:'Monto insuficiente',
                detalle:'El monto validado Bs '+formatoMontoNoatentado(monto)+' es menor al mínimo de escala Bs '+formatoMontoNoatentado(escala[0].monto_total)+'.',
                regla:null,
            };
        }

        return {
            ok:true,
            maxPermitidos:parseInt(regla.cantidad_max,10) || 0,
            resumen:'Hasta '+(parseInt(regla.cantidad_max,10) || 0)+' candidato(s)',
            detalle:'Escala aplicada: rango '+rangoTextoEscalaNoatentado(regla)+' para Bs '+formatoMontoNoatentado(regla.monto_total)+'.',
            regla:regla,
        };
    }

    function aplicarSemaforoFilasCandidatosNoatentado(maxPermitidos,activar){
        const filas=$('#tabla_candidatos_noa tbody tr[data-candidato-index]');
        filas.removeClass('noa-candidato-permitido noa-candidato-exceso');

        if(!activar){
            return;
        }

        const limite=Math.max(0,parseInt(maxPermitidos,10) || 0);
        filas.each(function(){
            const fila=$(this);
            const indice=parseInt(fila.attr('data-candidato-index'),10);
            if(!isFinite(indice)){
                return;
            }

            if(indice<limite){
                fila.addClass('noa-candidato-permitido');
            }else{
                fila.addClass('noa-candidato-exceso');
            }
        });
    }

    function actualizarControlCupoCandidatosNoatentado(){
        const panel=$('#noa_cupo_candidatos_panel');
        if(panel.length===0){
            return;
        }

        const badge=$('#noa_cupo_resumen');
        const detalle=$('#noa_cupo_detalle');
        const montos=$('#noa_cupo_montos');
        const progress=$('#noa_cupo_progress');
        const cantidad=candidatosNoAtentado.length;

        badge.removeClass('badge-success badge-danger badge-warning badge-info badge-secondary').addClass('badge-secondary').text('Pendiente');
        detalle.text('Valide el pago para calcular cuántos candidatos permite el monto principal + reintegro.');
        montos.text('Monto validado: principal Bs '+formatoMontoNoatentado(montoPrincipalValidadoNoa)+' + reintegro Bs '+formatoMontoNoatentado(montoReintegroValidadoNoa)+' = total Bs '+formatoMontoNoatentado(montoTotalValidadoNoa)+'.');
        progress.removeClass('bg-success bg-danger bg-warning bg-info bg-secondary').addClass('bg-secondary').css('width','0%');

        if(cantidad===0){
            detalle.text('Aún no hay candidatos registrados.');
            aplicarSemaforoFilasCandidatosNoatentado(0,false);
            return;
        }

        const cupo=resolverCupoCandidatosPorMontoNoatentado(montoTotalValidadoNoa);
        if(!cupo.ok){
            badge.removeClass('badge-secondary').addClass('badge-warning').text(cupo.resumen || 'Pendiente');
            detalle.text(cupo.detalle || 'Valide el pago para controlar el cupo de candidatos.');
            progress.removeClass('bg-secondary').addClass('bg-warning').css('width','0%');
            aplicarSemaforoFilasCandidatosNoatentado(0,false);
            return;
        }

        const permitidos=Math.max(0,parseInt(cupo.maxPermitidos,10) || 0);
        const porcentaje=permitidos>0 ? Math.min(100,Math.round((cantidad/permitidos)*100)) : 0;

        if(cantidad<=permitidos){
            badge.removeClass('badge-secondary').addClass('badge-success').text(cantidad+' / '+permitidos+' OK');
            detalle.text('Cantidad registrada dentro del límite. '+(cupo.detalle || ''));
            progress.removeClass('bg-secondary').addClass('bg-success').css('width',String(porcentaje)+'%');
        }else{
            badge.removeClass('badge-secondary').addClass('badge-danger').text(cantidad+' / '+permitidos+' Excede');
            detalle.text('La lista excede el límite para el monto validado. '+(cupo.detalle || ''));
            progress.removeClass('bg-secondary').addClass('bg-danger').css('width','100%');
        }

        aplicarSemaforoFilasCandidatosNoatentado(permitidos,true);
    }

    function validarCantidadCandidatosPorMontoNoatentadoUI(){
        const cantidad=candidatosNoAtentado.length;
        if(cantidad===0){
            return {
                ok:false,
                message:'Debe agregar al menos un candidato antes de guardar.',
            };
        }

        const tramiteSeleccionado=limpiarTextoNoAtentado($('#tramite_noa').val());
        if(tramiteSeleccionado===''){
            return {
                ok:false,
                message:'Debe seleccionar el tipo de trámite antes de guardar.',
            };
        }

        if(!esTramitePlanchaNoatentado(tramiteSeleccionado)){
            if(cantidad>1){
                return {
                    ok:false,
                    message:'Para este tipo de trámite solo se permite registrar un candidato por cuenta/pago.',
                };
            }

            return {
                ok:true,
                message:'Cantidad de candidatos válida para este tipo de trámite.',
            };
        }

        const cupo=resolverCupoCandidatosPorMontoNoatentado(montoTotalValidadoNoa);
        if(!cupo.ok){
            return {
                ok:false,
                message:cupo.detalle || 'No se pudo determinar el cupo de candidatos con el monto validado.',
            };
        }

        const permitidos=Math.max(0,parseInt(cupo.maxPermitidos,10) || 0);
        if(cantidad>permitidos){
            return {
                ok:false,
                message:'Con el monto validado (Bs '+formatoMontoNoatentado(montoTotalValidadoNoa)+') solo se permiten hasta '+permitidos+' candidato(s). Registró '+cantidad+'.',
            };
        }

        return {
            ok:true,
            message:'Cantidad de candidatos válida para el monto pagado.',
        };
    }

    function programarValidacionPagoNoAtentado(inmediata=false){
        const controlActual=limpiarTextoNoAtentado($('#control_noa').val());
        if(controlActual==='' || controlActual!==controlValidadoNoAtentado){
            limpiarSeleccionTramiteNoatentado('Se define al validar el pago.');
        }

        if(timerValidacionPagoNoa){
            clearTimeout(timerValidacionPagoNoa);
            timerValidacionPagoNoa=null;
        }

        if(inmediata===true){
            validarPagoNoAtentado();
            return;
        }

        timerValidacionPagoNoa=setTimeout(function(){
            validarPagoNoAtentado();
        },450);
    }

    function limpiarTextoNoAtentado(valor){
        return (valor || '').toString().trim();
    }

    function normalizarNumeroNoAtentado(valor){
        return (valor || '').toString().replace(/\D+/g,'');
    }

    function normalizarDocumentoNoAtentado(valor){
        return limpiarTextoNoAtentado(valor).toUpperCase().replace(/[^A-Z0-9]/g,'');
    }

    function escaparHtmlNoa(valor){
        return $('<div>').text((valor || '').toString()).html();
    }

    function limpiarMensajeNoatentado(){
        const contenedor=$('#noa_feedback_js');
        if(contenedor.length===0){
            return;
        }
        contenedor.stop(true,true).hide().html('');
    }

    function mostrarMensajeNoatentado(tipo,mensaje,enfocarSelector=''){
        const contenedor=$('#noa_feedback_js');
        const texto=limpiarTextoNoAtentado(mensaje);
        if(contenedor.length===0 || texto===''){
            return;
        }

        const mapaClases={
            error:'alert-danger',
            warning:'alert-warning',
            success:'alert-success',
            info:'alert-info',
        };
        const clase=mapaClases[tipo] || 'alert-warning';

        contenedor.html(
            '<div class="alert '+clase+' alert-dismissible fade show py-2 mb-0" role="alert">'+
                '<span>'+escaparHtmlNoa(texto)+'</span>'+
                '<button type="button" class="close" data-dismiss="alert" aria-label="close">'+
                    '<span aria-hidden="true">&times;</span>'+
                '</button>'+
            '</div>'
        ).show();

        const modalBody=contenedor.closest('.modal-body');
        if(modalBody.length>0){
            modalBody.stop(true).animate({scrollTop:0},180);
        }

        if(enfocarSelector!==''){
            const campo=$(enfocarSelector);
            if(campo.length>0 && !campo.prop('disabled')){
                campo.trigger('focus');
                campo.addClass('is-invalid');
                setTimeout(function(){
                    campo.removeClass('is-invalid');
                },1200);
            }
        }

        if(tipo==='success' || tipo==='info'){
            setTimeout(function(){
                contenedor.find('.alert').alert('close');
            },4500);
        }
    }

    function obtenerMensajeAjaxNoatentado(xhr,mensajePorDefecto){
        let mensaje=limpiarTextoNoAtentado(mensajePorDefecto || 'Error interno al procesar la solicitud.');

        if(xhr && xhr.status===422 && xhr.responseJSON){
            if(xhr.responseJSON.errors){
                const errores=[];
                Object.keys(xhr.responseJSON.errors).forEach(function(campo){
                    const lista=xhr.responseJSON.errors[campo] || [];
                    if(Array.isArray(lista)){
                        for(let i=0;i<lista.length;i++){
                            const texto=limpiarTextoNoAtentado(lista[i]);
                            if(texto!==''){
                                errores.push(texto);
                            }
                        }
                    }
                });
                if(errores.length>0){
                    mensaje=errores.join(' ');
                }
            }else if(xhr.responseJSON.message){
                mensaje=limpiarTextoNoAtentado(xhr.responseJSON.message);
            }
        }else if(xhr && xhr.status===419){
            mensaje='La sesión expiró. Recargue la página e intente nuevamente.';
        }else if(xhr && xhr.status===403){
            mensaje='No tiene permisos para esta acción.';
        }else if(xhr && xhr.status===404){
            mensaje='No se encontró la ruta solicitada.';
        }else if(xhr && xhr.responseJSON && xhr.responseJSON.message){
            mensaje=limpiarTextoNoAtentado(xhr.responseJSON.message);
        }

        return mensaje;
    }

    function actualizarEstadoGuardadoNoatentado(enCurso){
        guardandoTramiteNoaEnCurso=enCurso===true;
        const botones=$('#btn_guardar_noa, #btn_guardar_noa_edit');

        botones.each(function(){
            const boton=$(this);
            if(!boton.data('texto-original')){
                boton.data('texto-original',boton.html());
            }

            boton.prop('disabled',guardandoTramiteNoaEnCurso);
            if(guardandoTramiteNoaEnCurso){
                boton.html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
            }else{
                boton.html(boton.data('texto-original'));
            }
        });
    }

    function enviarFormularioTramiteNoAtentado(){
        if(guardandoTramiteNoaEnCurso){
            return;
        }

        const form=$('#form_tramite');
        if(form.length===0){
            return;
        }

        actualizarEstadoGuardadoNoatentado(true);
        limpiarMensajeNoatentado();

        const datosSerializados=form.serializeArray().filter(function(item){
            if(item.name==='reintegro'){
                return normalizarNumeroNoAtentado(item.value)!=='';
            }
            return true;
        });

        $.ajax({
            type: 'POST',
            url: "{{url('guardar tramite convocatoria noatentado')}}",
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json, text/html;q=0.9, */*;q=0.8'
            },
            data: $.param(datosSerializados),
            success: function(resp){
                if(resp && typeof resp==='object'){
                    if(resp.ok===false){
                        mostrarMensajeNoatentado('error',limpiarTextoNoAtentado(resp.message || 'No se pudo guardar el trámite.'));
                        return;
                    }

                    if(resp.ok===true && resp.cerrar_modal===true){
                        const urlLista=limpiarTextoNoAtentado(resp.refresh_url || "{{url('actualizar lista tramite convocatoria/'.$cod_con)}}");
                        if(urlLista!==''){
                            cargarDatos(urlLista,'panel_lista_tramites');
                        }
                        $('#Noatentado').modal('hide');
                        return;
                    }

                    if(resp.ok===true && resp.redirect){
                        cargarDatos(resp.redirect,'panel_noatentado');
                        cargarDatos("{{url('actualizar lista tramite convocatoria/'.$cod_con)}}",'panel_lista_tramites');
                        return;
                    }
                }

                $('#panel_noatentado').html(resp);
                cargarDatos("{{url('actualizar lista tramite convocatoria/'.$cod_con)}}",'panel_lista_tramites');
            },
            error: function(xhr){
                const mensaje=obtenerMensajeAjaxNoatentado(xhr,'No se pudo guardar el trámite. Revise los datos e intente nuevamente.');
                mostrarMensajeNoatentado('error',mensaje);
            },
            complete: function(){
                actualizarEstadoGuardadoNoatentado(false);
            }
        });
    }

    function sincronizarEstadoCandidatosNoAtentado(){
        const tabla=$('#tabla_candidatos_noa tbody');
        if(tabla.length===0){
            return;
        }

        const filas=tabla.find('tr');
        const sinFilasReales=filas.length===0 || (filas.length===1 && filas.first().attr('id')==='fila_vacia_candidatos_noa');
        if(sinFilasReales && Array.isArray(candidatosNoAtentado) && candidatosNoAtentado.length>0){
            candidatosNoAtentado=[];
            $('#candidatos_json_noa').val('[]');
        }
    }

    function obtenerResumenCandidatosPagoNoAtentado(){
        sincronizarEstadoCandidatosNoAtentado();

        const documentos={};
        for(let i=0;i<candidatosNoAtentado.length;i++){
            const doc=normalizarDocumentoNoAtentado(candidatosNoAtentado[i].ci);
            if(doc!==''){
                documentos[doc]=true;
            }
        }

        const lista=Object.keys(documentos);
        return {
            cantidad: lista.length,
            ciUnico: lista.length===1 ? lista[0] : '',
            documentos: lista,
        };
    }

    function actualizarFiltroPreimpresoNoAtentado(){
        const inputCrear=$('#preimpreso_pago_noa');
        if(inputCrear.length){
            const resumenCrear=obtenerResumenCandidatosPagoNoAtentado();
            const habilitarCrear=resumenCrear.cantidad>1;
            inputCrear.prop('disabled',!habilitarCrear);
            if(!habilitarCrear){
                inputCrear.val('');
            }
        }
    }

    function actualizarEstadoPagoNoAtentado(estado,clase,detalle,codigo=''){
        estadoControlPagoNoa={
            resumen: limpiarTextoNoAtentado(estado || 'Pendiente'),
            clase: limpiarTextoNoAtentado(clase || 'badge-warning'),
            detalle: limpiarTextoNoAtentado(detalle || ''),
            codigo: limpiarTextoNoAtentado(codigo || ''),
        };
        refrescarEstadoControlPagoNoatentado();
    }

    function normalizarClavePagoNoatentado(valor){
        return limpiarTextoNoAtentado(valor)
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g,'');
    }

    function selectorIconosPagoNoatentado(){
        return '[data-campo="estado-pago-control-icon"],'
            +'[data-campo="estado-pago-reintegro-icon"],'
            +'[data-campo="estado-pago-preimpreso-icon"]';
    }

    function cerrarPopoversPagoNoatentado(excepto){
        $(selectorIconosPagoNoatentado()).each(function(){
            if(excepto && this===excepto){
                return;
            }
            $(this).popover('hide').removeAttr('data-popover-visible');
        });
    }

    function abrirDetallePagoNoatentado(trigger){
        const icono=$(trigger);
        if(!icono.length){
            return false;
        }

        const visible=icono.attr('data-popover-visible')==='1';
        if(visible){
            icono.popover('hide').removeAttr('data-popover-visible');
            return false;
        }

        cerrarPopoversPagoNoatentado(icono.get(0));

        icono.popover('dispose');
        icono.popover({
            container:'body',
            trigger:'manual',
            placement:'top',
            content:(icono.attr('data-detalle-pago') || 'Sin detalle disponible').toString(),
            html:false,
        }).popover('show');
        icono.attr('data-popover-visible','1');
        return false;
    }

    function tipoEstadoPagoNoatentadoDesdeClase(clase,resumen){
        const claseNorm=limpiarTextoNoAtentado(clase).toLowerCase();
        const resumenNorm=limpiarTextoNoAtentado(resumen).toLowerCase();
        if(claseNorm==='badge-success'){
            return 'ok';
        }
        if(claseNorm==='badge-danger'){
            return 'error';
        }
        if(claseNorm==='badge-info'){
            if(resumenNorm==='validando'){
                return 'loading';
            }
            return 'info';
        }
        if(claseNorm==='badge-warning'){
            if(resumenNorm==='pendiente'){
                return 'pending';
            }
            if(resumenNorm==='no aplica'){
                return 'no_aplica';
            }
            return 'warning';
        }
        return 'pending';
    }

    function categoriaEstadoPagoNoatentado(tipo,resumen,detalle,codigo){
        const codigoNorm=limpiarTextoNoAtentado(codigo).toUpperCase();
        if(tipo==='loading'){
            return 'loading';
        }
        if(tipo==='ok'){
            return 'ok';
        }
        if(tipo==='pending'){
            return 'pending';
        }
        if(tipo==='no_aplica'){
            return 'na';
        }

        if(codigoNorm==='RATE_LIMIT'){
            return 'rate_limit';
        }
        if(codigoNorm==='SISTEMA_NO_CONFIGURADO'){
            return 'not_configured';
        }
        if(codigoNorm==='API_NO_DISPONIBLE'){
            return 'connection';
        }
        if(codigoNorm==='PAGO_YA_USADO'){
            return 'used';
        }
        if(codigoNorm==='CONTROL_NO_ENCONTRADO'){
            return 'not_found';
        }
        if(codigoNorm==='CUENTA_NO_CORRESPONDE' || codigoNorm==='CUENTA_SIN_TRAMITE_HABILITADO' || codigoNorm==='CUENTA_NO_IDENTIFICADA'){
            return 'not_match';
        }

        if(codigoNorm.indexOf('REINTEGRO_')===0){
            const codigoBase=codigoNorm.replace(/^REINTEGRO_/, '');
            if(codigoBase==='RATE_LIMIT') return 'rate_limit';
            if(codigoBase==='SISTEMA_NO_CONFIGURADO') return 'not_configured';
            if(codigoBase==='API_NO_DISPONIBLE' || codigoBase==='API_RESPUESTA_INVALIDA') return 'connection';
            if(codigoBase==='PAGO_YA_USADO') return 'used';
            if(codigoBase==='CONTROL_NO_ENCONTRADO') return 'not_found';
            return 'not_match';
        }

        const texto=normalizarClavePagoNoatentado((resumen || '')+' '+(detalle || ''));
        if(texto.indexOf('too many')!==-1 || texto.indexOf('demasiadas solicitudes')!==-1 || texto.indexOf('429')!==-1 || texto.indexOf('rate limit')!==-1){
            return 'rate_limit';
        }
        if(texto.indexOf('no esta configurado')!==-1 || texto.indexOf('no esta configurada')!==-1 || texto.indexOf('sistema_no_configurado')!==-1){
            return 'not_configured';
        }
        if(texto.indexOf('sin conexion')!==-1 || texto.indexOf('sin conexión')!==-1 || texto.indexOf('no hay conexion')!==-1 || texto.indexOf('no se pudo conectar')!==-1 || texto.indexOf('api_no_disponible')!==-1 || texto.indexOf('timeout')!==-1){
            return 'connection';
        }
        if(texto.indexOf('ya fue utilizado')!==-1 || texto.indexOf('ya usado')!==-1){
            return 'used';
        }
        if(texto.indexOf('no se encontro')!==-1 || texto.indexOf('no se encontró')!==-1 || texto.indexOf('boleta no encontrada')!==-1){
            return 'not_found';
        }
        if(texto.indexOf('no corresponde')!==-1){
            return 'not_match';
        }
        return 'error';
    }

    function resumenCategoriaPagoNoatentado(categoria,resumenFallback){
        if(categoria==='ok') return 'Validado';
        if(categoria==='loading') return 'Validando';
        if(categoria==='pending') return 'Pendiente';
        if(categoria==='na') return 'No aplica';
        if(categoria==='rate_limit') return 'Demasiadas solicitudes';
        if(categoria==='not_configured') return 'API no configurada';
        if(categoria==='connection') return 'Sin conexión';
        if(categoria==='used') return 'Ya utilizado';
        if(categoria==='not_found') return 'No encontrado';
        if(categoria==='not_match') return 'No corresponde';
        return limpiarTextoNoAtentado(resumenFallback || 'No válido');
    }

    function limpiarDetalleConResumenPagoNoatentado(resumen,detalle){
        let detalleTxt=limpiarTextoNoAtentado(detalle || '');
        if(detalleTxt===''){
            return '';
        }

        const resumenNorm=normalizarClavePagoNoatentado(resumen || '');
        const detalleNorm=normalizarClavePagoNoatentado(detalleTxt);
        if(resumenNorm!=='' && (detalleNorm===resumenNorm || detalleNorm.indexOf(resumenNorm+' ')===0 || detalleNorm.indexOf(resumenNorm+':')===0 || detalleNorm.indexOf(resumenNorm+'.')===0)){
            const escaped=(resumen || '').replace(/[.*+?^${}()|[\]\\]/g,'\\$&');
            detalleTxt=detalleTxt.replace(new RegExp('^'+escaped+'[\\s:\\.-]*','i'),'').trim();
        }

        return detalleTxt.replace(/^detalle\s*:\s*/i,'').trim();
    }

    function aplicarVisualCategoriaPagoNoatentado(icono,categoria){
        icono.removeClass('text-success text-danger text-secondary text-muted text-info text-warning');

        if(categoria==='ok'){
            icono.addClass('text-success').html('<i class="fas fa-check-circle"></i>');
            return;
        }
        if(categoria==='loading'){
            icono.addClass('text-info').html('<i class="fas fa-spinner fa-spin"></i>');
            return;
        }
        if(categoria==='rate_limit'){
            icono.addClass('text-warning').html('<i class="fas fa-clock"></i>');
            return;
        }
        if(categoria==='used'){
            icono.addClass('text-warning').html('<i class="fas fa-ban"></i>');
            return;
        }
        if(categoria==='connection'){
            icono.addClass('text-warning').html('<i class="fas fa-plug"></i>');
            return;
        }
        if(categoria==='not_configured'){
            icono.addClass('text-muted').html('<i class="fas fa-cog"></i>');
            return;
        }
        if(categoria==='pending'){
            icono.addClass('text-secondary').html('<i class="fas fa-minus-circle"></i>');
            return;
        }
        if(categoria==='na'){
            icono.addClass('text-muted').html('<i class="fas fa-minus-circle"></i>');
            return;
        }
        if(categoria==='not_found'){
            icono.addClass('text-warning').html('<i class="fas fa-exclamation-circle"></i>');
            return;
        }
        if(categoria==='not_match'){
            icono.addClass('text-warning').html('<i class="fas fa-exclamation-circle"></i>');
            return;
        }
        icono.addClass('text-danger').html('<i class="fas fa-times-circle"></i>');
    }

    function animarIconoPagoNoatentado(icono,categoria){
        if(!icono || !icono.length){
            return;
        }

        const nodo=icono.get(0);
        if(!nodo){
            return;
        }

        icono.removeClass('noa-anim-pop noa-anim-alert');
        void nodo.offsetWidth;

        if(categoria==='ok'){
            icono.addClass('noa-anim-pop');
            return;
        }

        if(categoria==='loading' || categoria==='pending' || categoria==='na'){
            return;
        }

        icono.addClass('noa-anim-alert');
    }

    function actualizarIconoPagoNoatentado(campo,tipo,resumen,detalle,codigo=''){
        const icono=$('[data-campo="estado-pago-'+campo+'-icon"]');
        if(!icono.length){
            return;
        }

        let etiqueta='Control principal';
        if(campo==='reintegro'){
            etiqueta='Control de reintegro';
        }else if(campo==='preimpreso'){
            etiqueta='Preimpreso control principal';
        }

        const resumenTxt=limpiarTextoNoAtentado(resumen || 'Pendiente');
        const detalleTxt=limpiarTextoNoAtentado(detalle || '');
        const categoria=categoriaEstadoPagoNoatentado(tipo,resumenTxt,detalleTxt,codigo);
        const resumenVisible=resumenCategoriaPagoNoatentado(categoria,resumenTxt);
        const detalleVisible=limpiarDetalleConResumenPagoNoatentado(resumenVisible,detalleTxt);

        aplicarVisualCategoriaPagoNoatentado(icono,categoria);
        animarIconoPagoNoatentado(icono,categoria);

        let contenido=etiqueta+': '+resumenVisible+'.';
        if(detalleVisible!==''){
            contenido+=' Detalle: '+detalleVisible;
        }

        icono.attr('title','Ver detalle de validación de pago');
        icono.attr('aria-label',etiqueta+': '+resumenVisible);
        icono.attr('data-detalle-pago',contenido);
        icono.removeAttr('data-popover-visible');
        icono.popover('hide');
    }

    function detalleDocumentoReintegroNoatentado(validacionReintegro){
        if(!validacionReintegro || validacionReintegro.aplica!==true){
            return '';
        }
        return 'Verificación de titular aplicada internamente.';
    }

    function mostrarIconoPagoNoatentado(campo,mostrar){
        const icono=$('[data-campo="estado-pago-'+campo+'-icon"]');
        if(!icono.length){
            return;
        }

        if(mostrar){
            icono.removeClass('invisible').attr('aria-hidden','false').css('pointer-events','auto');
            return;
        }

        icono.popover('hide').removeAttr('data-popover-visible');
        icono.addClass('invisible').attr('aria-hidden','true').css('pointer-events','none');
    }

    function actualizarVisibilidadIconosPagoNoatentado(){
        const resumen=obtenerResumenCandidatosPagoNoAtentado();
        const esMulti=resumen.cantidad>1;

        // Regla UX: candidato único -> icono en control. Multi -> icono en preimpreso.
        mostrarIconoPagoNoatentado('control',!esMulti);
        mostrarIconoPagoNoatentado('preimpreso',esMulti);
    }

    function detalleControlPagoNoatentado(){
        const partes=[];
        const principal=limpiarTextoNoAtentado(estadoControlPagoNoa.detalle || '');
        const extendido=limpiarTextoNoAtentado(detalleExtendidoPagoNoa || '');

        if(principal!==''){
            partes.push(principal);
        }
        if(extendido!==''){
            partes.push(extendido);
        }
        return partes.join(' ');
    }

    function refrescarEstadoControlPagoNoatentado(){
        const tipo=tipoEstadoPagoNoatentadoDesdeClase(estadoControlPagoNoa.clase,estadoControlPagoNoa.resumen);
        actualizarIconoPagoNoatentado('control',tipo,estadoControlPagoNoa.resumen,detalleControlPagoNoatentado(),estadoControlPagoNoa.codigo || '');
    }

    function refrescarEstadoCamposPagoNoatentado(){
        const resumen=obtenerResumenCandidatosPagoNoAtentado();
        const controlActual=limpiarTextoNoAtentado($('#control_noa').val());
        const reintegro=limpiarTextoNoAtentado($('#reintegro_noa').val());
        const preimpreso=limpiarTextoNoAtentado($('#preimpreso_pago_noa').val());
        const codigoControl=limpiarTextoNoAtentado(estadoControlPagoNoa.codigo || '').toUpperCase();
        const tipoControl=tipoEstadoPagoNoatentadoDesdeClase(estadoControlPagoNoa.clase,estadoControlPagoNoa.resumen);
        const detalleControl=detalleControlPagoNoatentado();
        const validando=validacionPagoNoaEnCurso===true && controlActual!=='';
        actualizarVisibilidadIconosPagoNoatentado();
        const validacionReintegro=(detalleValidacionPagoNoAtentado && detalleValidacionPagoNoAtentado.validacion_reintegro) ? detalleValidacionPagoNoAtentado.validacion_reintegro : null;

        if(resumen.cantidad===0){
            actualizarIconoPagoNoatentado('reintegro','no_aplica','No aplica','Sin candidatos registrados.');
            actualizarIconoPagoNoatentado('preimpreso','no_aplica','No aplica','Sin candidatos registrados.');
            return;
        }

        if(validando){
            if(resumen.cantidad>1){
                actualizarIconoPagoNoatentado('preimpreso','loading','Validando','Consultando recaudaciones para confirmar control y preimpreso...');
            }else{
                actualizarIconoPagoNoatentado('preimpreso','no_aplica','No aplica','Con candidato único no se requiere preimpreso.');
            }

            if(reintegro!==''){
                actualizarIconoPagoNoatentado('reintegro','loading','Validando','Consultando recaudaciones para validar reintegro...');
            }else{
                actualizarIconoPagoNoatentado('reintegro','no_aplica','Opcional','Sin reintegro.');
            }
            return;
        }

        if(pagoNoAtentadoValidado && controlActual!=='' && controlActual===controlValidadoNoAtentado){
            if(reintegro!==''){
                const reintegroCoincideRespuesta=validacionReintegro && reintegro===reintegroValidadoNoAtentado;
                if(reintegroCoincideRespuesta && validacionReintegro.ok===true){
                    const detalleDocumento=detalleDocumentoReintegroNoatentado(validacionReintegro);
                    const mensajeOkBase=limpiarTextoNoAtentado(validacionReintegro.message || 'Control de reintegro validado en recaudaciones con control y CI del pagador principal.');
                    const mensajeOk=(mensajeOkBase+' '+detalleDocumento).trim();
                    actualizarIconoPagoNoatentado('reintegro','ok','Validado',mensajeOk);
                }else if(reintegroCoincideRespuesta && validacionReintegro.ok===false){
                    const estadoReintegro=resolverEstadoErrorPagoNoatentado(validacionReintegro,0);
                    const detalleDocumento=detalleDocumentoReintegroNoatentado(validacionReintegro);
                    const detalleError=(limpiarTextoNoAtentado(estadoReintegro.detalle)+' '+detalleDocumento).trim();
                    actualizarIconoPagoNoatentado('reintegro','warning','No válido',detalleError,estadoReintegro.codigo);
                }else{
                    actualizarIconoPagoNoatentado('reintegro','pending','Pendiente','Reintegro modificado; valide nuevamente para confirmar control y CI del pagador principal.');
                }
            }else{
                actualizarIconoPagoNoatentado('reintegro','no_aplica','Opcional','Sin reintegro.');
            }

            if(resumen.cantidad>1){
                actualizarIconoPagoNoatentado('preimpreso','ok','Validado','Preimpreso del control principal validado para identificar pago.');
            }else{
                actualizarIconoPagoNoatentado('preimpreso','no_aplica','No aplica','Con candidato único no se requiere preimpreso.');
            }
            return;
        }

        if(reintegro!==''){
            actualizarIconoPagoNoatentado('reintegro','pending','Pendiente','Reintegro ingresado; se validará en recaudaciones con control y CI del pagador principal.');
        }else{
            actualizarIconoPagoNoatentado('reintegro','no_aplica','Opcional','Sin reintegro.');
        }

        if(resumen.cantidad>1){
            if(preimpreso!==''){
                const hayErrorPrincipalConCodigo=codigoControl!=='' && codigoControl.indexOf('REINTEGRO_')!==0;
                if(hayErrorPrincipalConCodigo && tipoControl!=='ok' && tipoControl!=='loading' && tipoControl!=='pending'){
                    const estadoPrincipal=resolverEstadoErrorPagoNoatentado({
                        code:codigoControl,
                        message:detalleControl!=='' ? detalleControl : limpiarTextoNoAtentado(estadoControlPagoNoa.detalle || estadoControlPagoNoa.resumen || 'No se pudo validar el pago principal.'),
                    },0);
                    const tipoIcono=estadoPrincipal.clase==='badge-danger' ? 'error' : 'warning';
                    actualizarIconoPagoNoatentado('preimpreso',tipoIcono,estadoPrincipal.resumen || 'No válido',estadoPrincipal.detalle,estadoPrincipal.codigo || codigoControl);
                    return;
                }

                actualizarIconoPagoNoatentado('preimpreso','pending','Pendiente','Preimpreso del control principal ingresado; valide pago para confirmar.');
            }else{
                actualizarIconoPagoNoatentado('preimpreso','pending','Pendiente','Ingrese preimpreso para seleccionar el valorado correcto del control principal.');
            }
            return;
        }

        actualizarIconoPagoNoatentado('preimpreso','no_aplica','No aplica','Con candidato único no se requiere preimpreso.');
    }

    function actualizarContextoControlPagoNoAtentado(){
        const control=$('#control_noa');
        if(control.length===0){
            return;
        }

        const resumen=obtenerResumenCandidatosPagoNoAtentado();
        const sinContexto=resumen.cantidad===0;
        const reintegro=$('#reintegro_noa');
        const preimpreso=$('#preimpreso_pago_noa');
        const controlActual=limpiarTextoNoAtentado(control.val());

        if(sinContexto){
            control.val('').prop('disabled',true);
            reintegro.val('').prop('disabled',true);
            preimpreso.val('').prop('disabled',true);

            if(xhrValidacionPagoNoa && xhrValidacionPagoNoa.readyState!==4){
                xhrValidacionPagoNoa.abort();
            }

            pagoNoAtentadoValidado=false;
            controlValidadoNoAtentado='';
            reintegroValidadoNoAtentado='';
            tramiteValidadoNoAtentado='';
            detalleValidacionPagoNoAtentado=null;
            detalleExtendidoPagoNoa='';
            limpiarSeleccionTramiteNoatentado('Primero registre candidatos para habilitar validación de pago.');

            actualizarEstadoPagoNoAtentado('Sin contexto','badge-warning','Agregue candidatos para validar con carnet.');
            refrescarEstadoCamposPagoNoatentado();
            return;
        }

        control.prop('disabled',false);
        reintegro.prop('disabled',false);

        if(resumen.cantidad>1){
            preimpreso.prop('disabled',false);
        }else{
            preimpreso.val('').prop('disabled',true);
        }

        if(controlActual===''){
            actualizarEstadoPagoNoAtentado('Pendiente','badge-warning','Ingrese número de control y valide.');
            limpiarSeleccionTramiteNoatentado('Se define al validar el pago.');
        }else if(validacionPagoNoaEnCurso===true){
            actualizarEstadoPagoNoAtentado('Validando','badge-info','Consultando recaudaciones...');
        }

        refrescarEstadoCamposPagoNoatentado();
    }

    function inicializarOpcionesTramiteNoatentado(){
        const select=$('#tramite_noa');
        if(!select.length){
            return;
        }

        if(opcionesOriginalesTramiteNoa===''){
            opcionesOriginalesTramiteNoa=select.html();
        }
    }

    function restaurarOpcionesTramiteNoatentado(){
        const select=$('#tramite_noa');
        if(!select.length){
            return;
        }

        inicializarOpcionesTramiteNoatentado();
        const actual=limpiarTextoNoAtentado(select.val());
        select.html(opcionesOriginalesTramiteNoa);
        if(actual!=='' && select.find('option[value="'+actual+'"]').length){
            select.val(actual);
        }
        select.prop('disabled',true);
        select.find('option').prop('disabled',false).show();
    }

    function limpiarSeleccionTramiteNoatentado(mensaje='Se define al validar el pago.'){
        const select=$('#tramite_noa');
        if(!select.length){
            return;
        }

        restaurarOpcionesTramiteNoatentado();
        select.val('');
        select.prop('disabled',true);

        const ayuda=$('#ayuda_tramite_noa');
        if(ayuda.length){
            ayuda.text(limpiarTextoNoAtentado(mensaje));
        }
    }

    function obtenerTiposPermitidosNoatentado(resp){
        const lista=(resp && Array.isArray(resp.tipos_noatentado_permitidos)) ? resp.tipos_noatentado_permitidos : [];
        const tipos=[];
        for(let i=0;i<lista.length;i++){
            const item=lista[i] || {};
            const cod=limpiarTextoNoAtentado(item.cod_tre);
            if(cod===''){
                continue;
            }
            tipos.push({
                cod_tre: cod,
                tre_nombre: limpiarTextoNoAtentado(item.tre_nombre),
            });
        }
        return tipos;
    }

    function renderDetallePagoNoatentado(resp){
        if(!resp || !resp.ok){
            detalleExtendidoPagoNoa='';
            refrescarEstadoControlPagoNoatentado();
            return;
        }

        const partes=[];
        const principal=Number(resp && resp.monto_principal_validado ? resp.monto_principal_validado : 0);
        const reintegro=Number(resp && resp.monto_reintegro_validado ? resp.monto_reintegro_validado : 0);
        const total=Number(resp && resp.monto_total_validado ? resp.monto_total_validado : (principal+reintegro));
        const tipoSugerido=limpiarTextoNoAtentado(resp.nombre_tipo_noatentado_sugerido);
        const tiposPermitidos=obtenerTiposPermitidosNoatentado(resp);

        if(isFinite(total) && total>0){
            partes.push('Monto total validado: Bs '+total.toFixed(2));
        }

        if(tiposPermitidos.length>1){
            const nombres=tiposPermitidos.map(function(item){
                return item.tre_nombre!=='' ? item.tre_nombre : item.cod_tre;
            }).join(', ');
            if(nombres!==''){
                partes.push('Tipos permitidos: '+nombres);
            }
        }else if(tipoSugerido!==''){
            partes.push('Tipo sugerido: '+tipoSugerido);
        }

        detalleExtendidoPagoNoa=partes.join('. ');
        refrescarEstadoControlPagoNoatentado();
    }

    function aplicarEstadoReintegroDesdeRespuestaNoatentado(resp){
        const reintegroActual=limpiarTextoNoAtentado($('#reintegro_noa').val());
        if(reintegroActual===''){
            actualizarIconoPagoNoatentado('reintegro','no_aplica','Opcional','Sin reintegro.');
            return;
        }

        const validacionReintegro=(resp && resp.validacion_reintegro) ? resp.validacion_reintegro : null;
        if(!validacionReintegro){
            actualizarIconoPagoNoatentado('reintegro','pending','Pendiente','Reintegro ingresado; se validará en recaudaciones con control y CI del pagador principal.');
            return;
        }

        if(validacionReintegro.ok===true){
            const detalleDocumento=detalleDocumentoReintegroNoatentado(validacionReintegro);
            const mensajeBase=limpiarTextoNoAtentado(validacionReintegro.message || 'Control de reintegro validado en recaudaciones con control y CI del pagador principal.');
            const mensaje=(mensajeBase+' '+detalleDocumento).trim();
            actualizarIconoPagoNoatentado('reintegro','ok','Validado',mensaje);
            return;
        }

        if(validacionReintegro.ok===false){
            const estadoReintegro=resolverEstadoErrorPagoNoatentado(validacionReintegro,0);
            const detalleDocumento=detalleDocumentoReintegroNoatentado(validacionReintegro);
            const detalle=(limpiarTextoNoAtentado(estadoReintegro.detalle)+' '+detalleDocumento).trim();
            actualizarIconoPagoNoatentado('reintegro','warning','No válido',detalle,estadoReintegro.codigo);
            return;
        }

        actualizarIconoPagoNoatentado('reintegro','pending','Pendiente','Reintegro ingresado; se validará en recaudaciones con control y CI del pagador principal.');
    }

    function aplicarAutoseleccionTramiteNoatentado(resp){
        const select=$('#tramite_noa');
        const ayuda=$('#ayuda_tramite_noa');
        if(!select.length){
            return;
        }

        restaurarOpcionesTramiteNoatentado();

        const tiposPermitidos=obtenerTiposPermitidosNoatentado(resp);
        const sugerido=limpiarTextoNoAtentado(resp && resp.tipo_noatentado_sugerido);
        const requiereSeleccionManual=!!(resp && resp.requiere_seleccion_manual===true);

        if(tiposPermitidos.length>0){
            const permitidosMap={};
            for(let i=0;i<tiposPermitidos.length;i++){
                permitidosMap[tiposPermitidos[i].cod_tre]=true;
            }

            select.find('option').each(function(){
                const opcion=$(this);
                const valor=limpiarTextoNoAtentado(opcion.val());
                if(valor===''){
                    opcion.prop('disabled',false).show();
                    return;
                }

                if(permitidosMap[valor]){
                    opcion.prop('disabled',false).show();
                }else{
                    opcion.prop('disabled',true).hide();
                }
            });

            if(requiereSeleccionManual){
                const valorActual=limpiarTextoNoAtentado(select.val());
                if(valorActual!=='' && !permitidosMap[valorActual]){
                    select.val('');
                }

                select.prop('disabled',false);
                if(ayuda.length){
                    ayuda.text('Existen varios tipos de trámite con el mismo monto total. Seleccione una opción manualmente.');
                }
                return;
            }

            let sugeridoFinal=sugerido;
            if(sugeridoFinal==='' || !select.find('option[value="'+sugeridoFinal+'"]').length){
                sugeridoFinal=tiposPermitidos[0].cod_tre;
            }

            if(sugeridoFinal!=='' && select.find('option[value="'+sugeridoFinal+'"]').length){
                select.val(sugeridoFinal);
            }else if(!permitidosMap[limpiarTextoNoAtentado(select.val())]){
                select.val('');
            }

            select.prop('disabled',true);
            if(ayuda.length){
                ayuda.text('Tipo de trámite autoseleccionado automáticamente según la cuenta del pago.');
            }
            return;
        }

        if(sugerido!=='' && select.find('option[value="'+sugerido+'"]').length){
            select.val(sugerido);
            if(ayuda.length){
                ayuda.text('Tipo de trámite sugerido automáticamente desde la validación de pago.');
            }
            select.prop('disabled',true);
            return;
        }

        select.val('');
        select.prop('disabled',true);
        if(ayuda.length){
            ayuda.text('No se pudo autoseleccionar el tipo de trámite.');
        }
    }

    function intentarActivarPagoValidadoNoatentado(control){
        const tramite=limpiarTextoNoAtentado($('#tramite_noa').val());
        if(tramite===''){
            return false;
        }
        if(!detalleValidacionPagoNoAtentado || !(detalleValidacionPagoNoAtentado.ok)){
            return false;
        }

        const permitidos=obtenerTiposPermitidosNoatentado(detalleValidacionPagoNoAtentado);
        if(permitidos.length>0){
            let encontrado=false;
            for(let i=0;i<permitidos.length;i++){
                if(permitidos[i].cod_tre===tramite){
                    encontrado=true;
                    break;
                }
            }
            if(!encontrado){
                return false;
            }
        }

        pagoNoAtentadoValidado=true;
        controlValidadoNoAtentado=control;
        tramiteValidadoNoAtentado=tramite;
        return true;
    }

    function onCambioTramiteNoa(){
        if(!detalleValidacionPagoNoAtentado){
            return;
        }

        const controlActual=limpiarTextoNoAtentado($('#control_noa').val());
        if(controlActual==='' || controlActual!==controlValidadoNoAtentado){
            pagoNoAtentadoValidado=false;
            tramiteValidadoNoAtentado='';
            actualizarEstadoPagoNoAtentado('Pendiente','badge-warning','Valide nuevamente el pago para confirmar el trámite.');
            return;
        }

        if(intentarActivarPagoValidadoNoatentado(controlActual)){
            actualizarEstadoPagoNoAtentado('Pago válido','badge-success',detalleValidacionPagoNoAtentado.message || 'Pago validado correctamente.');
        }else{
            pagoNoAtentadoValidado=false;
            tramiteValidadoNoAtentado='';
            actualizarEstadoPagoNoAtentado('Autoselección pendiente','badge-warning','No se pudo definir automáticamente el tipo de trámite.');
        }

        programarValidacionPagoNoAtentado();
    }

    function resetValidacionPagoNoAtentado(){
        pagoNoAtentadoValidado=false;
        controlValidadoNoAtentado='';
        reintegroValidadoNoAtentado='';
        tramiteValidadoNoAtentado='';
        detalleValidacionPagoNoAtentado=null;
        detalleExtendidoPagoNoa='';
        reiniciarMontosValidadosNoatentado();
        actualizarEstadoPagoNoAtentado('Pendiente','badge-warning','Antes de guardar debe validar el número de control.');
        limpiarSeleccionTramiteNoatentado('Se define al validar el pago.');
        actualizarContextoControlPagoNoAtentado();

        const controlActual=limpiarTextoNoAtentado($('#control_noa').val());
        if(controlActual!==''){
            programarValidacionPagoNoAtentado();
        }
    }

    function renderTablaCandidatosNoAtentado(){
        const tabla=$('#tabla_candidatos_noa tbody');
        if(tabla.length===0){
            return;
        }

        tabla.html('');
        if(candidatosNoAtentado.length===0){
            tabla.append('<tr id="fila_vacia_candidatos_noa"><td colspan="6" class="text-center text-secondary">No hay candidatos registrados.</td></tr>');
        }else{
            for(let i=0;i<candidatosNoAtentado.length;i++){
                const candidato=candidatosNoAtentado[i];
                const cargo=candidato.cargo!=='' ? candidato.cargo : candidato.cargo_nombre;
                const nombreCompleto=escaparHtmlNoa((candidato.apellido || '')+' '+(candidato.nombre || ''));
                const ciSeguro=escaparHtmlNoa(candidato.ci || '');
                const codSisSeguro=escaparHtmlNoa(candidato.cod_sis || '');
                const cargoSeguro=escaparHtmlNoa(cargo || '-');
                tabla.append(
                    '<tr data-candidato-index="'+i+'">'+
                    '<td>'+(i+1)+'</td>'+
                    '<td>'+nombreCompleto+'</td>'+
                    '<td>'+ciSeguro+'</td>'+
                    '<td>'+codSisSeguro+'</td>'+
                    '<td>'+cargoSeguro+'</td>'+
                    '<td><button type="button" class="btn btn-sm btn-light btn-circle border" title="Quitar" onclick="quitarCandidatoNoAtentado('+i+')"><i class="fas fa-trash-alt text-danger"></i></button></td>'+
                    '</tr>'
                );
            }
        }

        $('#candidatos_json_noa').val(JSON.stringify(candidatosNoAtentado));
        actualizarFiltroPreimpresoNoAtentado();
        if($('#control_noa').length>0){
            resetValidacionPagoNoAtentado();
            return;
        }

        actualizarControlCupoCandidatosNoatentado();
    }

    function limpiarFormularioCandidatoNoAtentado(){
        $('#noa_ci').val('');
        $('#noa_nombre').val('');
        $('#noa_apellido').val('');
        $('#noa_cod_sis').val('');
        $('#noa_cargo').val('');
        $('#noa_cargo_convocatoria').val('');
    }

    function agregarCandidatoNoAtentado(){
        sincronizarEstadoCandidatosNoAtentado();

        const ci=normalizarDocumentoNoAtentado($('#noa_ci').val());
        const nombre=limpiarTextoNoAtentado($('#noa_nombre').val()).toUpperCase();
        const apellido=limpiarTextoNoAtentado($('#noa_apellido').val()).toUpperCase();
        const codSis=limpiarTextoNoAtentado($('#noa_cod_sis').val());
        let cargo=limpiarTextoNoAtentado($('#noa_cargo').val()).toUpperCase();
        const cargoConvocatoria=limpiarTextoNoAtentado($('#noa_cargo_convocatoria').val());
        const cargoNombreSeleccionado=limpiarTextoNoAtentado($('#noa_cargo_convocatoria option:selected').text()).toUpperCase();

        if(cargo==='SELECCIONE' || cargo==='SELECCIONAR'){
            cargo='';
        }

        let cargoNombre='';
        if(cargoConvocatoria!==''){
            cargoNombre=cargoNombreSeleccionado;
            if(cargoNombre==='SELECCIONE' || cargoNombre==='SELECCIONAR'){
                cargoNombre='';
            }
        }

        if(ci==='' || nombre==='' || apellido===''){
            mostrarMensajeNoatentado('warning','Complete CI, nombres y apellidos del candidato para agregarlo.','#noa_ci');
            return;
        }

        let duplicado=false;
        for(let i=0;i<candidatosNoAtentado.length;i++){
            if(normalizarDocumentoNoAtentado(candidatosNoAtentado[i].ci)===ci){
                duplicado=true;
                break;
            }
        }

        if(duplicado){
            mostrarMensajeNoatentado('info','El CI '+ci+' ya está registrado en la lista de candidatos.','#noa_ci');
            return;
        }

        const tramiteSeleccionado=limpiarTextoNoAtentado($('#tramite_noa').val());
        if(tramiteSeleccionado!=='' && !esTramitePlanchaNoatentado(tramiteSeleccionado) && candidatosNoAtentado.length>=1){
            mostrarMensajeNoatentado('warning','Para este tipo de trámite solo se permite registrar un candidato.','#noa_ci');
            return;
        }

        candidatosNoAtentado.push({
            ci:ci,
            nombre:nombre,
            apellido:apellido,
            cod_sis:codSis,
            unidad:'',
            cargo:cargo,
            cargo_convocatoria:cargoConvocatoria,
            cargo_nombre:cargoNombre,
        });

        renderTablaCandidatosNoAtentado();
        limpiarFormularioCandidatoNoAtentado();
        mostrarMensajeNoatentado('success','Candidato agregado correctamente.');
    }

    function quitarCandidatoNoAtentado(indice){
        if(indice<0 || indice>=candidatosNoAtentado.length){
            return;
        }

        candidatosNoAtentado.splice(indice,1);
        renderTablaCandidatosNoAtentado();
    }

    function cargarDatosPersonalesNoa(ci){
        ci=limpiarTextoNoAtentado(ci);
        if(ci===''){
            return;
        }

        const link="{{url('datos_per/')}}"+'/'+ci;
        $.ajax({
            url: link,
            type: 'GET',
            success: function (resp) {
                if(resp==='No'){
                    $('#noa_nombre').val('');
                    $('#noa_apellido').val('');
                    $('#noa_cod_sis').val('');
                }else{
                    const datos=JSON.parse(resp);
                    $('#noa_nombre').val(datos['per_nombre'] || '');
                    $('#noa_apellido').val(datos['per_apellido'] || '');
                    $('#noa_cod_sis').val(datos['per_cod_sis'] || '');
                }
            }
        });
    }

    function actualizarNombreExcelNoatentado(input){
        const label=$('#label_excel_candidatos_noa');
        if(label.length===0){
            return;
        }

        if(input && input.files && input.files.length>0){
            label.text(input.files[0].name);
        }else{
            label.text('Seleccionar archivo Excel');
        }
    }

    function importarExcelCandidatosNoAtentado(){
        const controlArchivo=$('#excel_candidatos_noa');
        if(controlArchivo.length===0 || !controlArchivo[0].files || controlArchivo[0].files.length===0){
            mostrarMensajeNoatentado('warning','Seleccione un archivo Excel antes de importar.','#excel_candidatos_noa');
            return;
        }

        const archivo=controlArchivo[0].files[0];
        const data=new FormData();
        data.append('_token','{{csrf_token()}}');
        data.append('lista',archivo);

        $.ajax({
            url: "{{url('importar candidato excel temporal noatentado/'.$cod_con)}}",
            type: 'POST',
            processData: false,
            contentType: false,
            data: data,
            success: function(resp){
                if(!resp || !resp.ok){
                    mostrarMensajeNoatentado('error',(resp && resp.message) ? resp.message : 'No se pudo importar el archivo de candidatos.');
                    return;
                }

                const lista=Array.isArray(resp.candidatos) ? resp.candidatos : [];
                let agregados=0;
                for(let i=0;i<lista.length;i++){
                    const candidato=lista[i] || {};
                    const ci=normalizarDocumentoNoAtentado(candidato.ci);
                    if(ci===''){
                        continue;
                    }

                    let existe=false;
                    for(let j=0;j<candidatosNoAtentado.length;j++){
                        if(normalizarDocumentoNoAtentado(candidatosNoAtentado[j].ci)===ci){
                            existe=true;
                            break;
                        }
                    }
                    if(existe){
                        continue;
                    }

                    candidatosNoAtentado.push({
                        ci:ci,
                        nombre:limpiarTextoNoAtentado(candidato.nombre).toUpperCase(),
                        apellido:limpiarTextoNoAtentado(candidato.apellido).toUpperCase(),
                        cod_sis:limpiarTextoNoAtentado(candidato.cod_sis),
                        unidad:limpiarTextoNoAtentado(candidato.unidad).toUpperCase(),
                        cargo:(function(){
                            const valor=limpiarTextoNoAtentado(candidato.cargo).toUpperCase();
                            return (valor==='SELECCIONE' || valor==='SELECCIONAR') ? '' : valor;
                        })(),
                        cargo_convocatoria:limpiarTextoNoAtentado(candidato.cargo_convocatoria),
                        cargo_nombre:(function(){
                            const valor=limpiarTextoNoAtentado(candidato.cargo_nombre).toUpperCase();
                            return (valor==='SELECCIONE' || valor==='SELECCIONAR') ? '' : valor;
                        })(),
                    });
                    agregados++;
                }

                renderTablaCandidatosNoAtentado();
                controlArchivo.val('');
                $('#label_excel_candidatos_noa').text('Seleccionar archivo Excel');

                let mensaje='Importación completada. Candidatos agregados: '+agregados+'.';
                if(Array.isArray(resp.errores) && resp.errores.length>0){
                    mensaje+=' Observaciones: '+resp.errores.join(' ');
                }
                mostrarMensajeNoatentado('success',mensaje);
            },
            error: function(xhr){
                let mensaje='No se pudo importar el archivo de candidatos.';
                if(xhr && xhr.responseJSON){
                    if(xhr.responseJSON.message){
                        mensaje=xhr.responseJSON.message;
                    }else if(xhr.responseJSON.errors){
                        const claves=Object.keys(xhr.responseJSON.errors);
                        if(claves.length>0 && xhr.responseJSON.errors[claves[0]].length>0){
                            mensaje=xhr.responseJSON.errors[claves[0]][0];
                        }
                    }
                }
                mostrarMensajeNoatentado('error',mensaje);
            }
        });
    }

    function inferirCodigoErrorPagoNoatentado(mensaje,statusCode=0){
        const texto=normalizarClavePagoNoatentado(mensaje || '');
        if(parseInt(statusCode,10)===429 || texto.indexOf('too many')!==-1 || texto.indexOf('demasiadas solicitudes')!==-1 || texto.indexOf('rate limit')!==-1){
            return 'RATE_LIMIT';
        }
        if(texto.indexOf('no esta configurado')!==-1 || texto.indexOf('no esta configurada')!==-1 || texto.indexOf('sistema_no_configurado')!==-1 || texto.indexOf('services/.env')!==-1){
            return 'SISTEMA_NO_CONFIGURADO';
        }
        if(texto.indexOf('sin conexion')!==-1 || texto.indexOf('sin conexión')!==-1 || texto.indexOf('no se pudo conectar')!==-1 || texto.indexOf('api_no_disponible')!==-1 || texto.indexOf('timeout')!==-1){
            return 'API_NO_DISPONIBLE';
        }
        if(texto.indexOf('no se encontro')!==-1 || texto.indexOf('no se encontró')!==-1 || texto.indexOf('boleta no encontrada')!==-1){
            return 'CONTROL_NO_ENCONTRADO';
        }
        if(texto.indexOf('ya fue utilizado')!==-1 || texto.indexOf('ya usado')!==-1){
            return 'PAGO_YA_USADO';
        }
        if(texto.indexOf('no corresponde')!==-1){
            return 'CUENTA_NO_CORRESPONDE';
        }
        return 'API_RECAUDACIONES_ERROR';
    }

    function resolverEstadoErrorPagoNoatentado(resp,statusCode=0){
        const mensaje=(resp && resp.message) ? limpiarTextoNoAtentado(resp.message) : 'No se pudo validar el pago.';
        let codigo=(resp && resp.code) ? limpiarTextoNoAtentado(resp.code).toUpperCase() : '';
        if(codigo===''){
            codigo=inferirCodigoErrorPagoNoatentado(mensaje,statusCode);
        }

        if(codigo==='RATE_LIMIT'){
            return {
                resumen:'Demasiadas solicitudes',
                clase:'badge-warning',
                detalle:mensaje!=='' ? mensaje : 'El sistema está recibiendo muchas solicitudes. Intente nuevamente en unos segundos.',
                codigo:codigo,
            };
        }

        if(codigo==='SISTEMA_NO_CONFIGURADO'){
            return {
                resumen:'API no configurada',
                clase:'badge-warning',
                detalle:mensaje!=='' ? mensaje : 'Recaudaciones no está configurado. Contacte al área de sistemas.',
                codigo:codigo,
            };
        }

        if(codigo==='API_NO_DISPONIBLE' || codigo==='API_RESPUESTA_INVALIDA'){
            return {
                resumen:'Sin conexión',
                clase:'badge-warning',
                detalle:mensaje!=='' ? mensaje : 'Sin conexión con recaudaciones. Intente nuevamente.',
                codigo:'API_NO_DISPONIBLE',
            };
        }

        if(codigo==='CONTROL_NO_ENCONTRADO'){
            return {
                resumen:'No encontrado',
                clase:'badge-danger',
                detalle:mensaje!=='' ? mensaje : 'No se encontró información del número de control en recaudaciones.',
                codigo:codigo,
            };
        }

        if(codigo==='PAGO_YA_USADO'){
            return {
                resumen:'Ya utilizado',
                clase:'badge-warning',
                detalle:mensaje!=='' ? mensaje : 'Este pago ya fue utilizado en otro trámite.',
                codigo:codigo,
            };
        }

        if(codigo==='PREIMPRESO_REQUERIDO_MULTI_CANDIDATO'){
            return {
                resumen:'Preimpreso requerido',
                clase:'badge-warning',
                detalle:mensaje,
                codigo:codigo,
            };
        }

        if(codigo==='CONTEXTO_CANDIDATOS_REQUERIDO'){
            return {
                resumen:'Sin contexto',
                clase:'badge-warning',
                detalle:mensaje,
                codigo:codigo,
            };
        }

        if(codigo.indexOf('REINTEGRO_')===0){
            return {
                resumen:'Reintegro no válido',
                clase:'badge-warning',
                detalle:mensaje,
                codigo:codigo,
            };
        }

        if(codigo==='CI_CANDIDATO_NO_COINCIDE' || codigo==='CARNET_CANDIDATO_NO_COINCIDE' || codigo==='DOCUMENTO_PAGO_NO_COINCIDE' || codigo==='FILTRO_PAGO_SIN_COINCIDENCIA' || codigo==='PREIMPRESO_PAGO_NO_COINCIDE' || codigo==='CUENTA_NO_CORRESPONDE' || codigo==='CUENTA_SIN_TRAMITE_HABILITADO' || codigo==='CUENTA_NO_IDENTIFICADA'){
            return {
                resumen:'No corresponde',
                clase:'badge-warning',
                detalle:mensaje,
                codigo:codigo,
            };
        }

        return {
            resumen:'Pago no válido',
            clase:'badge-danger',
            detalle:mensaje,
            codigo:codigo,
        };
    }

    function validarPagoNoAtentado(){
        const control=limpiarTextoNoAtentado($('#control_noa').val());
        const tramite=limpiarTextoNoAtentado($('#tramite_noa').val());
        const preimpreso=limpiarTextoNoAtentado($('#preimpreso_pago_noa').val());
        const resumenCandidatos=obtenerResumenCandidatosPagoNoAtentado();
        const esPreconsultaMulti=resumenCandidatos.cantidad>1 && preimpreso==='';

        if(control!==controlValidadoNoAtentado){
            pagoNoAtentadoValidado=false;
            reintegroValidadoNoAtentado='';
            tramiteValidadoNoAtentado='';
            limpiarSeleccionTramiteNoatentado('Se define al validar el pago.');
        }

        if(resumenCandidatos.cantidad===0){
            if(xhrValidacionPagoNoa && xhrValidacionPagoNoa.readyState!==4){
                xhrValidacionPagoNoa.abort();
            }
            validacionPagoNoaEnCurso=false;
            limpiarSeleccionTramiteNoatentado('Primero registre candidatos para habilitar validación de pago.');
            actualizarEstadoPagoNoAtentado('Sin contexto','badge-warning','Primero agregue candidatos para poder validar el pago.');
            refrescarEstadoCamposPagoNoatentado();
            return;
        }

        if(control===''){
            if(xhrValidacionPagoNoa && xhrValidacionPagoNoa.readyState!==4){
                xhrValidacionPagoNoa.abort();
            }
            validacionPagoNoaEnCurso=false;
            reiniciarMontosValidadosNoatentado();
            limpiarSeleccionTramiteNoatentado('Se define al validar el pago.');
            actualizarEstadoPagoNoAtentado('Pendiente','badge-warning','Ingrese el número de control para validar.');
            refrescarEstadoCamposPagoNoatentado();
            return;
        }

        const secuencia=((secuenciaValidacionPagoNoa || 0)+1);
        secuenciaValidacionPagoNoa=secuencia;

        if(xhrValidacionPagoNoa && xhrValidacionPagoNoa.readyState!==4){
            xhrValidacionPagoNoa.abort();
        }

        validacionPagoNoaEnCurso=true;
        actualizarEstadoPagoNoAtentado('Validando','badge-info','Consultando recaudaciones...');
        refrescarEstadoCamposPagoNoatentado();
        xhrValidacionPagoNoa=$.ajax({
            url: "{{url('validar pago noatentado/'.$cod_con)}}",
            type: 'POST',
            data: {
                _token: "{{csrf_token()}}",
                control: control,
                tramite: tramite,
                reintegro: limpiarTextoNoAtentado($('#reintegro_noa').val()),
                preimpreso_pago: preimpreso,
                preconsulta_control: esPreconsultaMulti ? 1 : 0,
                documento_pago: resumenCandidatos.ciUnico,
                cantidad_candidatos: resumenCandidatos.cantidad,
                ci_candidato_unico: resumenCandidatos.ciUnico,
                ci_candidatos: JSON.stringify(resumenCandidatos.documentos),
            },
            success: function(resp){
                if(secuenciaValidacionPagoNoa!==secuencia){
                    return;
                }

                if(limpiarTextoNoAtentado($('#control_noa').val())!==control){
                    return;
                }

                validacionPagoNoaEnCurso=false;
                const reintegroActual=limpiarTextoNoAtentado($('#reintegro_noa').val());

                if(resp && resp.ok){
                    detalleValidacionPagoNoAtentado=resp;
                    controlValidadoNoAtentado=control;
                    reintegroValidadoNoAtentado=reintegroActual;
                    asignarMontosValidadosNoatentado(resp);
                    aplicarAutoseleccionTramiteNoatentado(resp);
                    renderDetallePagoNoatentado(resp);

                    if(intentarActivarPagoValidadoNoatentado(control)){
                        actualizarEstadoPagoNoAtentado('Pago válido','badge-success',resp.message || 'Pago validado correctamente.');
                        if(resumenCandidatos.cantidad>1){
                            actualizarIconoPagoNoatentado('preimpreso','ok','Validado','Preimpreso del control principal validado para identificar pago.');
                        }else{
                            actualizarIconoPagoNoatentado('preimpreso','no_aplica','No aplica','Con candidato único no se requiere preimpreso.');
                        }
                    }else{
                        pagoNoAtentadoValidado=false;
                        tramiteValidadoNoAtentado='';
                        actualizarEstadoPagoNoAtentado('Autoselección pendiente','badge-warning',resp.message || 'Pago validado, pero no se pudo autoseleccionar el trámite.');
                        refrescarEstadoCamposPagoNoatentado();
                    }

                    aplicarEstadoReintegroDesdeRespuestaNoatentado(resp);
                }else{
                    const estadoError=resolverEstadoErrorPagoNoatentado(resp || {},0);
                    const validacionPrincipal=(resp && resp.validacion_principal && resp.validacion_principal.ok) ? resp.validacion_principal : null;
                    const codigoError=limpiarTextoNoAtentado((resp && resp.code) ? resp.code : '').toUpperCase();
                    const pendientePreimpreso=esPreconsultaMulti && (codigoError==='PREIMPRESO_REQUERIDO_MULTI_CANDIDATO' || codigoError==='PAGO_AMBIGUO');

                    if(pendientePreimpreso){
                        pagoNoAtentadoValidado=false;
                        controlValidadoNoAtentado='';
                        reintegroValidadoNoAtentado='';
                        tramiteValidadoNoAtentado='';
                        detalleValidacionPagoNoAtentado=null;
                        detalleExtendidoPagoNoa='';
                        reiniciarMontosValidadosNoatentado();
                        renderDetallePagoNoatentado(null);
                        limpiarSeleccionTramiteNoatentado('Ingrese preimpreso para poder definir el tipo de trámite.');
                        actualizarEstadoPagoNoAtentado('Pendiente','badge-info',resp && resp.message ? resp.message : 'Control encontrado. Ingrese preimpreso para seleccionar el valorado correcto.');
                        refrescarEstadoCamposPagoNoatentado();
                        aplicarEstadoReintegroDesdeRespuestaNoatentado(resp || {});
                        return;
                    }

                    if(validacionPrincipal){
                        detalleValidacionPagoNoAtentado=$.extend({},validacionPrincipal,{
                            validacion_reintegro:(resp && resp.validacion_reintegro) ? resp.validacion_reintegro : null,
                        });
                        controlValidadoNoAtentado=control;
                        reintegroValidadoNoAtentado=reintegroActual;
                        asignarMontosValidadosNoatentado(resp || {});
                        aplicarAutoseleccionTramiteNoatentado(validacionPrincipal);
                        renderDetallePagoNoatentado(validacionPrincipal);

                        const detallePrincipal=limpiarTextoNoAtentado(validacionPrincipal.message || 'Pago principal validado correctamente.');
                        if(intentarActivarPagoValidadoNoatentado(control)){
                            actualizarEstadoPagoNoAtentado('Pago principal válido','badge-success',detallePrincipal);
                        }else{
                            pagoNoAtentadoValidado=false;
                            tramiteValidadoNoAtentado='';
                            actualizarEstadoPagoNoAtentado('Autoselección pendiente','badge-warning',estadoError.detalle,estadoError.codigo);
                        }
                    }else{
                        pagoNoAtentadoValidado=false;
                        controlValidadoNoAtentado='';
                        reintegroValidadoNoAtentado='';
                        tramiteValidadoNoAtentado='';
                        detalleValidacionPagoNoAtentado=null;
                        detalleExtendidoPagoNoa='';
                        reiniciarMontosValidadosNoatentado();
                        renderDetallePagoNoatentado(null);
                        limpiarSeleccionTramiteNoatentado('Se define al validar el pago.');
                        actualizarEstadoPagoNoAtentado(estadoError.resumen,estadoError.clase,estadoError.detalle,estadoError.codigo);
                    }

                    if(codigoError.indexOf('REINTEGRO_')===0){
                        refrescarEstadoCamposPagoNoatentado();
                        aplicarEstadoReintegroDesdeRespuestaNoatentado(resp || {});
                        return;
                    }
                    refrescarEstadoCamposPagoNoatentado();
                    aplicarEstadoReintegroDesdeRespuestaNoatentado(resp || {});
                }
            },
            error: function(xhr){
                if(secuenciaValidacionPagoNoa!==secuencia){
                    return;
                }
                if(xhr && xhr.statusText==='abort'){
                    return;
                }
                if(limpiarTextoNoAtentado($('#control_noa').val())!==control){
                    return;
                }

                validacionPagoNoaEnCurso=false;

                pagoNoAtentadoValidado=false;
                controlValidadoNoAtentado='';
                reintegroValidadoNoAtentado='';
                tramiteValidadoNoAtentado='';
                detalleValidacionPagoNoAtentado=null;
                detalleExtendidoPagoNoa='';
                reiniciarMontosValidadosNoatentado();
                renderDetallePagoNoatentado(null);
                limpiarSeleccionTramiteNoatentado('Se define al validar el pago.');

                let mensaje='No se pudo validar el pago.';
                if(xhr && xhr.responseJSON){
                    if(xhr.responseJSON.message){
                        mensaje=xhr.responseJSON.message;
                    }else if(xhr.responseJSON.errors){
                        const claves=Object.keys(xhr.responseJSON.errors);
                        if(claves.length>0 && xhr.responseJSON.errors[claves[0]].length>0){
                            mensaje=xhr.responseJSON.errors[claves[0]][0];
                        }
                    }
                }

                const estadoError=resolverEstadoErrorPagoNoatentado(xhr && xhr.responseJSON ? xhr.responseJSON : {message:mensaje},xhr && xhr.status ? xhr.status : 0);
                actualizarEstadoPagoNoAtentado(estadoError.resumen,estadoError.clase,estadoError.detalle,estadoError.codigo);
                refrescarEstadoCamposPagoNoatentado();
            },
            complete: function(){
                validacionPagoNoaEnCurso=false;
                if(secuenciaValidacionPagoNoa===secuencia){
                    xhrValidacionPagoNoa=null;
                }
                actualizarContextoControlPagoNoAtentado();
            }
        });
    }

    function guardarEdicionTramiteNoAtentado(){
        const form=$('#form_tramite');
        if(form.length===0){
            return;
        }

        enviarFormularioTramiteNoAtentado();
    }

    function guardarTramiteNoAtentado(){
        if($('#form_tramite').length===0){
            return;
        }

        if(candidatosNoAtentado.length===0){
            mostrarMensajeNoatentado('warning','Debe agregar al menos un candidato antes de guardar.','#noa_ci');
            return;
        }

        const control=limpiarTextoNoAtentado($('#control_noa').val());
        const tramite=limpiarTextoNoAtentado($('#tramite_noa').val());
        if(control==='' || tramite===''){
            mostrarMensajeNoatentado('warning','Complete el número de control y confirme el trámite sugerido antes de guardar.','#control_noa');
            return;
        }

        if(!pagoNoAtentadoValidado && detalleValidacionPagoNoAtentado && control===controlValidadoNoAtentado){
            intentarActivarPagoValidadoNoatentado(control);
        }

        if(!pagoNoAtentadoValidado || controlValidadoNoAtentado!==control || tramiteValidadoNoAtentado!==tramite){
            mostrarMensajeNoatentado('warning','No se guardó el trámite: primero valide correctamente el pago principal.','#control_noa');
            return;
        }

        const reintegro=limpiarTextoNoAtentado($('#reintegro_noa').val());
        if(reintegro!==''){
            const validacionReintegro=(detalleValidacionPagoNoAtentado && detalleValidacionPagoNoAtentado.validacion_reintegro)
                ? detalleValidacionPagoNoAtentado.validacion_reintegro
                : null;
            const reintegroOk=!!(validacionReintegro && validacionReintegro.ok===true && validacionReintegro.aplica===true && reintegro===reintegroValidadoNoAtentado);

            if(!reintegroOk){
                mostrarMensajeNoatentado('warning','No se guardó el trámite: el reintegro informado no fue validado. Corrija el control de reintegro o deje el campo vacío si no aplica.','#reintegro_noa');
                return;
            }
        }

        const controlCantidad=validarCantidadCandidatosPorMontoNoatentadoUI();
        if(!controlCantidad.ok){
            mostrarMensajeNoatentado('warning',controlCantidad.message,'#control_noa');
            return;
        }

        $('#candidatos_json_noa').val(JSON.stringify(candidatosNoAtentado));
        enviarFormularioTramiteNoAtentado();
    }

    $(function(){
        escalaCandidatosNoa=obtenerEscalaCandidatosConfigNoatentado();
        escalaCandidatosNoaNormalizada=normalizarEscalaCandidatosNoatentado();

        $('#tramite_noa').off('change.noaTramite').on('change.noaTramite',onCambioTramiteNoa);

        $(document).off('click.noaPagoPopover').on('click.noaPagoPopover', function(e){
            if($(e.target).closest(selectorIconosPagoNoatentado()+', .popover').length===0){
                cerrarPopoversPagoNoatentado();
            }
        });

        $('#Noatentado').off('hidden.bs.modal.noaPagoPopover').on('hidden.bs.modal.noaPagoPopover', function(){
            cerrarPopoversPagoNoatentado();
        });

        if($('#tabla_candidatos_noa').length>0){
            renderTablaCandidatosNoAtentado();
            inicializarOpcionesTramiteNoatentado();
            resetValidacionPagoNoAtentado();
            actualizarFiltroPreimpresoNoAtentado();
            actualizarContextoControlPagoNoAtentado();
            actualizarControlCupoCandidatosNoatentado();
        }

        if($('#control_noa_edit').length>0){
            actualizarFiltroPreimpresoNoAtentado();
        }
    });
</script>

