@php
    $acreditacion = isset($acreditacion) ? $acreditacion : null;
    $tipoDefecto = isset($tipoDefecto) ? $tipoDefecto : null;
    $mostrarTabs = $acreditacion ? false : true;

    $tipo = $acreditacion && trim((string)$acreditacion->tipo) !== '' ? $acreditacion->tipo : ($tipoDefecto ?: 'Nacional');
    $sistema = $tipo === 'Internacional' ? 'ARCU SUR' : 'CEUB';
    $sistemaColor = $tipo === 'Internacional' ? '#7c3aed' : '#0f766e';
    $sistemaBg = $tipo === 'Internacional' ? 'linear-gradient(135deg, #ede9fe 0%, #f5f3ff 100%)' : 'linear-gradient(135deg, #dcfce7 0%, #ecfeff 100%)';
    $acred = ($acreditacion && (int)$acreditacion->acreditada === 1) ? 'SI' : 'NO';
    $anio = $acreditacion ? ($acreditacion->anio ?? '') : '';
    $sc = $acreditacion ? ($acreditacion->proc_sc ?? '') : '';
    $nc = $acreditacion ? ($acreditacion->proc_nc ?? '') : '';
    $total = $acreditacion ? ($acreditacion->proc_total ?? '') : '';
    if(($total === null || $total === '') && ($sc !== '' || $nc !== '')){
        $total = (int)($sc ?: 0) + (int)($nc ?: 0);
    }
    $fechaAcred = ($acreditacion && $acreditacion->fecha_acreditacion) ? date('Y-m-d', strtotime($acreditacion->fecha_acreditacion)) : '';
    $fechaVenc = ($acreditacion && $acreditacion->fecha_vencimiento) ? date('Y-m-d', strtotime($acreditacion->fecha_vencimiento)) : '';
    $resolucionInicio = ($acreditacion && $acreditacion->resolucion_inicio) ? date('Y-m-d', strtotime($acreditacion->resolucion_inicio)) : '';
    $resolucionFin = ($acreditacion && $acreditacion->resolucion_fin) ? date('Y-m-d', strtotime($acreditacion->resolucion_fin)) : '';
    $resolucionFechaEmision = ($acreditacion && $acreditacion->resolucion_fecha_emision) ? date('Y-m-d', strtotime($acreditacion->resolucion_fecha_emision)) : '';
    $resolucionNumero = $acreditacion ? trim((string)($acreditacion->resolucion_numero ?? '')) : '';
    $resolucionAnio = $acreditacion ? trim((string)($acreditacion->resolucion_anio ?? '')) : '';
    $puntajeGuardado = $acreditacion ? trim((string)$acreditacion->puntaje) : '';
    $puntajeModo = '';
    if(in_array($puntajeGuardado,['Cumple','Homologado','S/D'])){
        $puntajeModo = $puntajeGuardado;
    }elseif($puntajeGuardado !== ''){
        $puntajeModo = 'NUMERO';
    }
    $puntajeNumero = $puntajeModo === 'NUMERO' ? $puntajeGuardado : '';
    $certificado = ($acreditacion && (int)$acreditacion->certificado === 1) ? 'SI' : 'NO';
@endphp

<form id="form_acreditacion">
    @csrf
    <input type="hidden" name="acred_habilitada" value="1">
    <div class="modal-content border-0 shadow-lg overflow-hidden">
        <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f4c5c 0%, #1d6f8a 48%, #3aa6b9 100%);">
            <div>
                <h5 class="modal-title font-weight-bolder mb-0">
                    <i class="fas fa-award mr-1"></i> {{ $acreditacion ? 'Editar acreditación' : 'Agregar acreditación' }}
                </h5>
                <small class="text-white-50">Registro individual de acreditación para la carrera</small>
            </div>
            <button class="close text-white opacity-100" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body p-0" style="background: linear-gradient(180deg, #f7fbfc 0%, #eef7fa 100%);">
            <div class="p-3 p-md-4">
                <div class="alert alert-light border shadow-sm mb-3">
                    <div class="font-weight-bold text-dark">Facultad: {{ $facultad->fac_nombre ?? '-' }}</div>
                    <div class="text-dark">Carrera: {{ $carrera->car_nombre ?? '-' }} <span class="text-muted">({{ $carrera->car_abreviacion ?: '-' }})</span></div>
                </div>

                @if($mostrarTabs)
                    <div id="acred_tabs" class="mb-3">
                        <ul class="nav nav-pills nav-fill" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ $tipo === 'Nacional' ? 'active' : '' }}" href="#tab_ceub" role="tab" data-toggle="tab" data-acred-tipo="Nacional">CEUB</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tipo === 'Internacional' ? 'active' : '' }}" href="#tab_arcu" role="tab" data-toggle="tab" data-acred-tipo="Internacional">Mercosur (ARCU SUR)</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-2">
                            <div class="tab-pane fade {{ $tipo === 'Nacional' ? 'show active' : '' }}" id="tab_ceub" role="tabpanel">
                                <div class="small text-muted">Registrar acreditación nacional (CEUB).</div>
                            </div>
                            <div class="tab-pane fade {{ $tipo === 'Internacional' ? 'show active' : '' }}" id="tab_arcu" role="tabpanel">
                                <div class="small text-muted">Registrar acreditación Mercosur (ARCU SUR).</div>
                            </div>
                        </div>
                    </div>
                @endif

                <div id="sistema_box" class="border rounded-lg shadow-sm p-3 mb-3" style="background: {{ $sistemaBg }}; border-color: {{ $sistemaColor }}33 !important;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <div class="text-uppercase text-secondary font-weight-bold" style="letter-spacing: .04em; font-size: .75rem;">Sistema de acreditación</div>
                            <div id="sistema_titulo" class="font-weight-bolder" style="color: {{ $sistemaColor }}; font-size: 1.05rem;">{{ $sistema }}</div>
                        </div>
                        <span id="sistema_badge" class="badge px-3 py-2" style="background: {{ $sistemaColor }}; color: #fff;">{{ $tipo }}</span>
                    </div>
                    <div class="small text-muted mt-2">Este registro pertenece a una sola acreditación. Si necesitas otra acreditación, crea un nuevo registro desde la carrera.</div>
                </div>

                <div class="border rounded-lg shadow-sm bg-white p-3 mb-3">
                    <div class="form-row">
                        <div class="form-group col-md-3 mb-2 {{ $mostrarTabs ? 'd-none' : '' }}">
                            <label class="mb-1 text-secondary">Tipo</label>
                            <select class="form-control form-control-sm shadow-sm" name="acred_tipo" id="acred_tipo" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                <option value="Nacional" {{ $tipo === 'Nacional' ? 'selected' : '' }}>Nacional</option>
                                <option value="Internacional" {{ $tipo === 'Internacional' ? 'selected' : '' }}>Internacional</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1 text-secondary">Sistema</label>
                            <input type="text" class="form-control form-control-sm shadow-sm" id="acred_sistema" value="{{ $sistema }}" readonly style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #f8fbff;" />
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1 text-secondary">Acreditada</label>
                            <select class="form-control form-control-sm shadow-sm" name="acred_acred" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                <option value="SI" {{ $acred === 'SI' ? 'selected' : '' }}>SI</option>
                                <option value="NO" {{ $acred === 'NO' ? 'selected' : '' }}>NO</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1 text-secondary">Año</label>
                            <input type="number" class="form-control form-control-sm shadow-sm" name="acred_anio_base" min="1900" max="2200" value="{{ $anio }}" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1 text-secondary">S/C</label>
                            <input type="number" class="form-control form-control-sm shadow-sm" name="acred_proc_sc" min="0" step="1" value="{{ $sc }}" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1 text-secondary">N/C</label>
                            <input type="number" class="form-control form-control-sm shadow-sm" name="acred_proc_nc" min="0" step="1" value="{{ $nc }}" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1 text-secondary">Total</label>
                            <input type="number" class="form-control form-control-sm shadow-sm" name="acred_proc_total" id="acred_proc_total" value="{{ $total }}" readonly required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #f8fbff;" />
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1 text-secondary">Estado</label>
                            <input type="text" class="form-control form-control-sm shadow-sm" id="acred_estado_texto" value="{{ $acreditacion ? trim((string)$acreditacion->estado) : '' }}" readonly style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #f8fbff;" />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1 text-secondary">Acreditación</label>
                            <input type="date" class="form-control form-control-sm shadow-sm" name="acred_fecha_acreditacion" id="acred_fecha_acreditacion" value="{{ $fechaAcred }}" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1 text-secondary">Vencimiento</label>
                            <input type="date" class="form-control form-control-sm shadow-sm" name="acred_fecha_vencimiento" id="acred_fecha_vencimiento" value="{{ $fechaVenc }}" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1 text-secondary">Puntaje</label>
                            <select class="form-control form-control-sm shadow-sm" name="acred_puntaje_modo" id="acred_puntaje_modo" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                <option value="" {{ $puntajeModo === '' ? 'selected' : '' }}></option>
                                <option value="Cumple" {{ $puntajeModo === 'Cumple' ? 'selected' : '' }}>Cumple</option>
                                <option value="Homologado" {{ $puntajeModo === 'Homologado' ? 'selected' : '' }}>Homologado</option>
                                <option value="S/D" {{ $puntajeModo === 'S/D' ? 'selected' : '' }}>S/D</option>
                                <option value="NUMERO" {{ $puntajeModo === 'NUMERO' ? 'selected' : '' }}>Número</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1 text-secondary">Puntaje numérico</label>
                            <input type="text" class="form-control form-control-sm shadow-sm" name="acred_puntaje_numero" id="acred_puntaje_numero" value="{{ $puntajeNumero }}" placeholder="Ej: 84.5" inputmode="decimal" autocomplete="off" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1 text-secondary">Resol. inicio</label>
                            <input type="date" class="form-control form-control-sm shadow-sm" name="acred_resolucion_inicio" value="{{ $resolucionInicio }}" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1 text-secondary">Resol. fin</label>
                            <input type="date" class="form-control form-control-sm shadow-sm" name="acred_resolucion_fin" value="{{ $resolucionFin }}" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1 text-secondary">Fecha emisión</label>
                            <input type="date" class="form-control form-control-sm shadow-sm" name="acred_resolucion_fecha_emision" value="{{ $resolucionFechaEmision }}" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1 text-secondary">Nro. de resolución</label>
                            <div class="d-flex align-items-center">
                                <input type="text" class="form-control form-control-sm shadow-sm" name="acred_resolucion_numero" value="{{ $resolucionNumero }}" maxlength="10" pattern="\d+" inputmode="numeric" placeholder="XXX" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                <span class="px-2 text-muted">/</span>
                                <input type="text" class="form-control form-control-sm shadow-sm" name="acred_resolucion_anio" value="{{ $resolucionAnio }}" maxlength="4" pattern="\d{4}" inputmode="numeric" placeholder="AÑO" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3 mb-0">
                            <label class="mb-1 text-secondary">Certificados</label>
                            <select class="form-control form-control-sm shadow-sm" name="acred_certificado" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                <option value="SI" {{ $certificado === 'SI' ? 'selected' : '' }}>SI</option>
                                <option value="NO" {{ $certificado === 'NO' ? 'selected' : '' }}>NO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="cf" value="{{ $facultad->cod_fac ?? 0 }}">
                <input type="hidden" name="cc" value="{{ $carrera->cod_car }}">
                @if((int)$cod_cac !== 0)
                    <input type="hidden" name="cod_cac" value="{{ $cod_cac }}">
                @endif
            </div>
        </div>
        <div class="modal-footer border-0" style="background: #f7fbfc;">
            <button class="btn btn-light border px-4" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary px-4 shadow-sm" type="button" onclick="enviar('form_acreditacion','g_acreditacion')" style="background: linear-gradient(135deg, #1d4e89 0%, #2c7da0 100%); border: none;">Guardar</button>
        </div>
    </div>
</form>

<script>
    function obtenerFechaActualTexto(){
        var hoy = new Date();
        var mes = ('0' + (hoy.getMonth() + 1)).slice(-2);
        var dia = ('0' + hoy.getDate()).slice(-2);
        return hoy.getFullYear() + '-' + mes + '-' + dia;
    }

    function toNullableInt(valor){
        var texto = String(valor || '').trim();
        if(texto === ''){
            return null;
        }
        var numero = parseInt(texto, 10);
        return isNaN(numero) ? null : numero;
    }

    function recalcularAcreditacion(){
        var tipo = $('#acred_tipo').val();
        $('#acred_sistema').val(tipo === 'Internacional' ? 'ARCU SUR' : 'CEUB');
        var sistemaColor = tipo === 'Internacional' ? '#7c3aed' : '#0f766e';
        var sistemaBg = tipo === 'Internacional' ? 'linear-gradient(135deg, #ede9fe 0%, #f5f3ff 100%)' : 'linear-gradient(135deg, #dcfce7 0%, #ecfeff 100%)';
        $('#sistema_badge').text(tipo).css({background: sistemaColor, color: '#fff'});
        $('#sistema_titulo').text(tipo === 'Internacional' ? 'ARCU SUR' : 'CEUB').css('color', sistemaColor);
        $('#sistema_box').css({background: sistemaBg, 'border-color': sistemaColor + '33'});

        var sc = toNullableInt($('#acred_proc_sc').val());
        var nc = toNullableInt($('#acred_proc_nc').val());
        if(sc === null && nc === null){
            $('#acred_proc_total').val('');
        }else{
            $('#acred_proc_total').val((sc || 0) + (nc || 0));
        }

        var fechaAcred = $('#acred_fecha_acreditacion').val();
        var fechaVenc = $('#acred_fecha_vencimiento').val();
        var hoy = obtenerFechaActualTexto();
        var estado = '';

        if((sc || 0) === 0 && (nc || 0) > 0){
            estado = 'No cumple';
        }else if(fechaAcred !== '' && fechaVenc !== ''){
            estado = (hoy >= fechaAcred && hoy <= fechaVenc) ? 'Vigente' : 'Vencido';
        }else if(fechaAcred !== '' && fechaVenc === ''){
            estado = hoy >= fechaAcred ? 'Vigente' : 'Vencido';
        }else if(fechaAcred === '' && fechaVenc !== ''){
            estado = hoy <= fechaVenc ? 'Vigente' : 'Vencido';
        }

        $('#acred_estado_texto').val(estado);

        var modo = $('#acred_puntaje_modo').val();
        var inputNumero = $('#acred_puntaje_numero');
        if(modo === 'NUMERO'){
            inputNumero.prop('disabled', false);
            inputNumero.prop('required', true);
        }else{
            inputNumero.prop('disabled', true);
            inputNumero.prop('required', false);
        }
    }

    $(function(){
        $('#acred_tabs [data-acred-tipo]').on('click', function(evento){
            evento.preventDefault();
            var tipo = $(this).data('acred-tipo');
            $('#acred_tipo').val(tipo);
            recalcularAcreditacion();
            $(this).tab('show');
        });
        $('#acred_tipo, #acred_proc_sc, #acred_proc_nc, #acred_fecha_acreditacion, #acred_fecha_vencimiento, #acred_puntaje_modo').on('change keyup', recalcularAcreditacion);
        recalcularAcreditacion();
    });
</script>
