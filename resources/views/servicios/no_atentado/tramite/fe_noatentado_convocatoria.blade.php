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
                            <h5 class="text-white text-center">Nuevo trámite No Atentado</h5>
                        </div>
                        <hr class="sidebar-divider text-bg-dark">

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
                                                    <select class="custom-select custom-select-sm" name="tramite" id="tramite_noa" onchange="onCambioTramiteNoa()">
                                                        <option value="">Seleccione</option>
                                                        @foreach($tramites as $t)
                                                            <option value="{{$t->cod_tre}}">{{$t->tre_nombre}}</option>
                                                        @endforeach
                                                    </select>
                                                    <small id="ayuda_tramite_noa" class="text-secondary">Se define al validar el pago.</small>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <label class="font-italic mb-0 d-block">Tipo de trámite</label>
                                                    <div class="border rounded p-2 bg-light">
                                                        <input type="radio" name="tipo_tramite" checked value="t"> INTERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <input type="radio" name="tipo_tramite" value="f"> EXTERNO
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <label class="font-italic mb-0">Nro. Control</label>
                                                    <input class="form-control form-control-sm" required name="control" id="control_noa" onchange="resetValidacionPagoNoAtentado()">
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <label class="font-italic mb-0">Control Reintegro</label>
                                                    <input class="form-control form-control-sm" name="reintegro" id="reintegro_noa" onchange="resetValidacionPagoNoAtentado()">
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <label class="font-italic mb-0">Preimpreso</label>
                                                    <input class="form-control form-control-sm" name="preimpreso_pago" id="preimpreso_pago_noa" onchange="resetValidacionPagoNoAtentado()" disabled>
                                                </div>
                                                <div class="col-md-9 mb-2 d-flex align-items-end">
                                                    <small class="text-secondary" id="ayuda_filtro_pago_noa">Con un candidato, el CI se valida automáticamente. Si hay varios, ingrese preimpreso.</small>
                                                </div>
                                            </div>

                                            <div class="mt-2 border rounded p-2 bg-light shadow-sm">
                                                <div class="d-flex align-items-center flex-wrap">
                                                    <button class="btn btn-info btn-sm" id="btn_validar_pago_noa" type="button" onclick="validarPagoNoAtentado()"><i class="fas fa-shield-alt"></i> Validar pago</button>
                                                    <span id="estado_pago_noa" class="badge badge-warning ml-2">Pendiente</span>
                                                </div>
                                                <small id="detalle_pago_noa" class="text-secondary">Antes de guardar debe validar el número de control.</small>
                                                <div id="detalle_pago_noa_extra" class="small text-muted mt-1"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="cc" value="{{$convocatoria->cod_con}}">
                                </form>

                                <div class="col-md-12 mt-1">
                                    <button class="btn btn-primary btn-sm float-right" type="button" onclick="guardarTramiteNoAtentado()"><i class="fas fa-save"></i> Guardar trámite</button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                            <h5 class="text-white text-center">Editar trámite No Atentado</h5>
                        </div>
                        <hr class="sidebar-divider text-bg-dark">

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
                                        <button class="btn btn-primary btn-sm float-right" type="button" onclick="guardarEdicionTramiteNoAtentado()">Guardar cambios</button>
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

<script>
    let candidatosNoAtentado=[];
    let pagoNoAtentadoValidado=false;
    let controlValidadoNoAtentado='';
    let tramiteValidadoNoAtentado='';
    let detalleValidacionPagoNoAtentado=null;
    let opcionesOriginalesTramiteNoa='';

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

    function obtenerResumenCandidatosPagoNoAtentado(){
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
        };
    }

    function actualizarFiltroPreimpresoNoAtentado(){
        const inputCrear=$('#preimpreso_pago_noa');
        const ayudaCrear=$('#ayuda_filtro_pago_noa');
        if(inputCrear.length){
            const resumenCrear=obtenerResumenCandidatosPagoNoAtentado();
            const habilitarCrear=resumenCrear.cantidad>1;
            inputCrear.prop('disabled',!habilitarCrear);
            if(!habilitarCrear){
                inputCrear.val('');
            }

            if(ayudaCrear.length){
                if(resumenCrear.cantidad===0){
                    ayudaCrear.text('Agregue candidatos para validar el pago.');
                }else if(resumenCrear.cantidad===1){
                    ayudaCrear.text('Con un candidato, el CI se valida automáticamente.');
                }else{
                    ayudaCrear.text('Hay varios candidatos: ingrese preimpreso para identificar el pago correcto.');
                }
            }
        }
    }

    function actualizarEstadoPagoNoAtentado(estado,clase,detalle){
        const badge=$('#estado_pago_noa');
        const detallePago=$('#detalle_pago_noa');
        if(badge.length===0 || detallePago.length===0){
            return;
        }

        badge.removeClass('badge-warning badge-success badge-danger badge-info').addClass(clase).text(estado);
        detallePago.text(detalle || '');
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
        select.prop('disabled',false);
        select.find('option').prop('disabled',false).show();
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

    function renderDetallePagoNoatentadoEnPanel(resp,panelId){
        const panel=$(panelId);
        if(!panel.length){
            return;
        }

        if(!resp || !resp.ok){
            panel.html('');
            return;
        }

        const partes=[];
        const cuenta=limpiarTextoNoAtentado(resp.cuenta);
        const codigoCuenta=normalizarNumeroNoAtentado(resp.codigo_cuenta);
        const documento=limpiarTextoNoAtentado(resp.documento);
        const nombre=limpiarTextoNoAtentado(resp.nombre_persona);
        const tipoSugerido=limpiarTextoNoAtentado(resp.nombre_tipo_noatentado_sugerido);
        const tiposPermitidos=obtenerTiposPermitidosNoatentado(resp);

        if(cuenta!==''){
            partes.push('<span class="mr-2"><strong>Cuenta API:</strong> '+escaparHtmlNoa(cuenta)+'</span>');
        }
        if(codigoCuenta!==''){
            partes.push('<span class="mr-2"><strong>Cód. cuenta:</strong> '+escaparHtmlNoa(codigoCuenta)+'</span>');
        }
        if(nombre!==''){
            partes.push('<span class="mr-2"><strong>Pagador:</strong> '+escaparHtmlNoa(nombre)+'</span>');
        }
        if(documento!==''){
            partes.push('<span class="mr-2"><strong>CI pago:</strong> '+escaparHtmlNoa(documento)+'</span>');
        }

        if(tiposPermitidos.length>1){
            const nombres=tiposPermitidos.map(function(item){
                return item.tre_nombre!=='' ? item.tre_nombre : item.cod_tre;
            }).join(', ');
            partes.push('<div><strong>Tipos permitidos:</strong> '+escaparHtmlNoa(nombres)+'</div>');
        }else if(tipoSugerido!==''){
            partes.push('<div><strong>Tipo sugerido:</strong> '+escaparHtmlNoa(tipoSugerido)+'</div>');
        }

        panel.html(partes.join(' '));
    }

    function renderDetallePagoNoatentado(resp){
        renderDetallePagoNoatentadoEnPanel(resp,'#detalle_pago_noa_extra');
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

            if(tiposPermitidos.length===1){
                select.val(tiposPermitidos[0].cod_tre);
                select.prop('disabled',true);
                if(ayuda.length){
                    ayuda.text('Tipo de trámite autoseleccionado según la cuenta del pago.');
                }
                return;
            }

            if(sugerido!=='' && select.find('option[value="'+sugerido+'"]').length){
                select.val(sugerido);
            }else if(!permitidosMap[limpiarTextoNoAtentado(select.val())]){
                select.val('');
            }

            select.prop('disabled',false);
            if(ayuda.length){
                ayuda.text('Seleccione manualmente uno de los trámites permitidos por la cuenta de pago.');
            }
            return;
        }

        if(sugerido!=='' && select.find('option[value="'+sugerido+'"]').length){
            select.val(sugerido);
            if(ayuda.length){
                ayuda.text('Tipo de trámite sugerido automáticamente desde la validación de pago.');
            }
        }else if(ayuda.length){
            ayuda.text('Se autoselecciona al validar el pago en recaudaciones.');
        }

        select.prop('disabled',false);
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

        const sugerido=limpiarTextoNoAtentado(detalleValidacionPagoNoAtentado.tipo_noatentado_sugerido);
        if(permitidos.length===0 && sugerido!=='' && sugerido!==tramite && !detalleValidacionPagoNoAtentado.requiere_seleccion_manual){
            return false;
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
            actualizarEstadoPagoNoAtentado('Selección requerida','badge-info','Seleccione un trámite permitido para completar la validación.');
        }
    }

    function resetValidacionPagoNoAtentado(){
        pagoNoAtentadoValidado=false;
        controlValidadoNoAtentado='';
        tramiteValidadoNoAtentado='';
        detalleValidacionPagoNoAtentado=null;
        actualizarEstadoPagoNoAtentado('Pendiente','badge-warning','Antes de guardar debe validar el número de control.');
        $('#detalle_pago_noa_extra').html('');
        restaurarOpcionesTramiteNoatentado();
        $('#ayuda_tramite_noa').text('Se define al validar el pago.');
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
                tabla.append(
                    '<tr>'+
                    '<td>'+(i+1)+'</td>'+
                    '<td>'+candidato.apellido+' '+candidato.nombre+'</td>'+
                    '<td>'+candidato.ci+'</td>'+
                    '<td>'+candidato.cod_sis+'</td>'+
                    '<td>'+(cargo || '')+'</td>'+
                    '<td><button type="button" class="btn btn-sm btn-light btn-circle border" title="Quitar" onclick="quitarCandidatoNoAtentado('+i+')"><i class="fas fa-trash-alt text-danger"></i></button></td>'+
                    '</tr>'
                );
            }
        }

        $('#candidatos_json_noa').val(JSON.stringify(candidatosNoAtentado));
        actualizarFiltroPreimpresoNoAtentado();
        if($('#control_noa').length>0){
            resetValidacionPagoNoAtentado();
        }
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
        const ci=limpiarTextoNoAtentado($('#noa_ci').val()).toUpperCase();
        const nombre=limpiarTextoNoAtentado($('#noa_nombre').val()).toUpperCase();
        const apellido=limpiarTextoNoAtentado($('#noa_apellido').val()).toUpperCase();
        const codSis=limpiarTextoNoAtentado($('#noa_cod_sis').val());
        const cargo=limpiarTextoNoAtentado($('#noa_cargo').val()).toUpperCase();
        const cargoConvocatoria=$('#noa_cargo_convocatoria').val();
        const cargoNombre=$('#noa_cargo_convocatoria option:selected').text();

        if(ci==='' || nombre==='' || apellido===''){
            alert('Debe ingresar CI, nombre y apellido del candidato.');
            return;
        }

        let duplicado=false;
        for(let i=0;i<candidatosNoAtentado.length;i++){
            if(candidatosNoAtentado[i].ci===ci){
                duplicado=true;
                break;
            }
        }

        if(duplicado){
            alert('El candidato ya fue agregado en la lista.');
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
            cargo_nombre:limpiarTextoNoAtentado(cargoNombre),
        });

        renderTablaCandidatosNoAtentado();
        limpiarFormularioCandidatoNoAtentado();
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
            alert('Seleccione un archivo Excel para importar candidatos.');
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
                    alert((resp && resp.message) ? resp.message : 'No se pudo importar el archivo de candidatos.');
                    return;
                }

                const lista=Array.isArray(resp.candidatos) ? resp.candidatos : [];
                let agregados=0;
                for(let i=0;i<lista.length;i++){
                    const candidato=lista[i] || {};
                    const ci=limpiarTextoNoAtentado(candidato.ci).toUpperCase();
                    if(ci===''){
                        continue;
                    }

                    let existe=false;
                    for(let j=0;j<candidatosNoAtentado.length;j++){
                        if((candidatosNoAtentado[j].ci || '')===ci){
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
                        cargo:limpiarTextoNoAtentado(candidato.cargo).toUpperCase(),
                        cargo_convocatoria:limpiarTextoNoAtentado(candidato.cargo_convocatoria),
                        cargo_nombre:limpiarTextoNoAtentado(candidato.cargo_nombre).toUpperCase(),
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
                alert(mensaje);
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
                alert(mensaje);
            }
        });
    }

    function validarPagoNoAtentado(){
        const control=limpiarTextoNoAtentado($('#control_noa').val());
        const tramite=limpiarTextoNoAtentado($('#tramite_noa').val());
        const boton=$('#btn_validar_pago_noa');
        const resumenCandidatos=obtenerResumenCandidatosPagoNoAtentado();

        if(control===''){
            alert('Ingrese el número de control para validar.');
            return;
        }

        actualizarEstadoPagoNoAtentado('Validando','badge-info','Consultando recaudaciones...');
        if(boton.length){
            boton.prop('disabled',true);
        }
        $.ajax({
            url: "{{url('validar pago noatentado/'.$cod_con)}}",
            type: 'POST',
            data: {
                _token: "{{csrf_token()}}",
                control: control,
                tramite: tramite,
                reintegro: limpiarTextoNoAtentado($('#reintegro_noa').val()),
                preimpreso_pago: limpiarTextoNoAtentado($('#preimpreso_pago_noa').val()),
                cantidad_candidatos: resumenCandidatos.cantidad,
                ci_candidato_unico: resumenCandidatos.ciUnico,
            },
            success: function(resp){
                if(resp && resp.ok){
                    detalleValidacionPagoNoAtentado=resp;
                    controlValidadoNoAtentado=control;
                    aplicarAutoseleccionTramiteNoatentado(resp);
                    renderDetallePagoNoatentado(resp);

                    if(intentarActivarPagoValidadoNoatentado(control)){
                        actualizarEstadoPagoNoAtentado('Pago válido','badge-success',resp.message || 'Pago validado correctamente.');
                    }else{
                        pagoNoAtentadoValidado=false;
                        tramiteValidadoNoAtentado='';
                        actualizarEstadoPagoNoAtentado('Selección requerida','badge-info',resp.message || 'Pago validado. Seleccione el trámite permitido.');
                    }
                }else{
                    pagoNoAtentadoValidado=false;
                    controlValidadoNoAtentado='';
                    tramiteValidadoNoAtentado='';
                    detalleValidacionPagoNoAtentado=null;
                    renderDetallePagoNoatentado(null);
                    restaurarOpcionesTramiteNoatentado();
                    actualizarEstadoPagoNoAtentado('Pago no válido','badge-danger',(resp && resp.message) ? resp.message : 'No se pudo validar el pago.');
                }
            },
            error: function(xhr){
                pagoNoAtentadoValidado=false;
                controlValidadoNoAtentado='';
                tramiteValidadoNoAtentado='';
                detalleValidacionPagoNoAtentado=null;
                renderDetallePagoNoatentado(null);
                restaurarOpcionesTramiteNoatentado();

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

                actualizarEstadoPagoNoAtentado('Pago no válido','badge-danger',mensaje);
            },
            complete: function(){
                if(boton.length){
                    boton.prop('disabled',false);
                }
            }
        });
    }

    function guardarEdicionTramiteNoAtentado(){
        const form=$('#form_tramite');
        if(form.length===0){
            return;
        }

        enviar('form_tramite',"{{url('guardar tramite convocatoria noatentado')}}",'panel_noatentado');
        cargarDatos("{{url('actualizar lista tramite convocatoria/'.$cod_con)}}",'panel_lista_tramites');
    }

    function guardarTramiteNoAtentado(){
        if($('#form_tramite').length===0){
            return;
        }

        if(candidatosNoAtentado.length===0){
            alert('Debe agregar al menos un candidato antes de guardar.');
            return;
        }

        const control=limpiarTextoNoAtentado($('#control_noa').val());
        const tramite=limpiarTextoNoAtentado($('#tramite_noa').val());
        if(control==='' || tramite===''){
            alert('Complete los datos de trámite y número de control.');
            return;
        }

        if(!pagoNoAtentadoValidado && detalleValidacionPagoNoAtentado && control===controlValidadoNoAtentado){
            intentarActivarPagoValidadoNoatentado(control);
        }

        if(!pagoNoAtentadoValidado || controlValidadoNoAtentado!==control || tramiteValidadoNoAtentado!==tramite){
            alert('Debe validar el pago del número de control antes de guardar.');
            return;
        }

        $('#candidatos_json_noa').val(JSON.stringify(candidatosNoAtentado));
        enviar('form_tramite',"{{url('guardar tramite convocatoria noatentado')}}",'panel_noatentado');
        cargarDatos("{{url('actualizar lista tramite convocatoria/'.$cod_con)}}",'panel_lista_tramites');
    }

    $(function(){
        if($('#tabla_candidatos_noa').length>0){
            renderTablaCandidatosNoAtentado();
            inicializarOpcionesTramiteNoatentado();
            resetValidacionPagoNoAtentado();
            actualizarFiltroPreimpresoNoAtentado();
        }

        if($('#control_noa_edit').length>0){
            actualizarFiltroPreimpresoNoAtentado();
        }
    });
</script>

