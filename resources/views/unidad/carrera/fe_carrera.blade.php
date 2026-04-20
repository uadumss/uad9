@php
    $acredNac = isset($acreditacionNacional) ? $acreditacionNacional : null;
    $acredInt = isset($acreditacionInternacional) ? $acreditacionInternacional : null;

    $nacHabilitada = true;
    $intHabilitada = $acredInt ? true : false;

    $nacAcred = ($acredNac && (int)$acredNac->acreditada === 1) ? 'SI' : 'NO';
    $nacAnio = $acredNac ? ($acredNac->anio ?? '') : '';
    $nacSc = $acredNac ? ($acredNac->proc_sc ?? '') : '';
    $nacNc = $acredNac ? ($acredNac->proc_nc ?? '') : '';
    $nacTotal = $acredNac ? ($acredNac->proc_total ?? '') : '';
    if(($nacTotal === null || $nacTotal === '') && ($nacSc !== '' || $nacNc !== '')){
        $nacTotal = (int)($nacSc ?: 0) + (int)($nacNc ?: 0);
    }
    $nacFechaAcred = ($acredNac && $acredNac->fecha_acreditacion) ? date('Y-m-d', strtotime($acredNac->fecha_acreditacion)) : '';
    $nacFechaVenc = ($acredNac && $acredNac->fecha_vencimiento) ? date('Y-m-d', strtotime($acredNac->fecha_vencimiento)) : '';
    $nacEstado = $acredNac ? trim((string)$acredNac->estado) : '';
    $nacPuntajeGuardado = $acredNac ? trim((string)$acredNac->puntaje) : '';
    $nacPuntajeModo = '';
    if(in_array($nacPuntajeGuardado,['Cumple','Homologado','S/D'])){
        $nacPuntajeModo = $nacPuntajeGuardado;
    }elseif($nacPuntajeGuardado !== ''){
        $nacPuntajeModo = 'NUMERO';
    }
    $nacPuntajeNumero = $nacPuntajeModo === 'NUMERO' ? $nacPuntajeGuardado : '';
    $nacCertificado = ($acredNac && (int)$acredNac->certificado === 1) ? 'SI' : 'NO';

    $intAcred = ($acredInt && (int)$acredInt->acreditada === 1) ? 'SI' : 'NO';
    $intAnio = $acredInt ? ($acredInt->anio ?? '') : '';
    $intSc = $acredInt ? ($acredInt->proc_sc ?? '') : '';
    $intNc = $acredInt ? ($acredInt->proc_nc ?? '') : '';
    $intTotal = $acredInt ? ($acredInt->proc_total ?? '') : '';
    if(($intTotal === null || $intTotal === '') && ($intSc !== '' || $intNc !== '')){
        $intTotal = (int)($intSc ?: 0) + (int)($intNc ?: 0);
    }
    $intFechaAcred = ($acredInt && $acredInt->fecha_acreditacion) ? date('Y-m-d', strtotime($acredInt->fecha_acreditacion)) : '';
    $intFechaVenc = ($acredInt && $acredInt->fecha_vencimiento) ? date('Y-m-d', strtotime($acredInt->fecha_vencimiento)) : '';
    $intEstado = $acredInt ? trim((string)$acredInt->estado) : '';
    $intPuntajeGuardado = $acredInt ? trim((string)$acredInt->puntaje) : '';
    $intPuntajeModo = '';
    if(in_array($intPuntajeGuardado,['Cumple','Homologado','S/D'])){
        $intPuntajeModo = $intPuntajeGuardado;
    }elseif($intPuntajeGuardado !== ''){
        $intPuntajeModo = 'NUMERO';
    }
    $intPuntajeNumero = $intPuntajeModo === 'NUMERO' ? $intPuntajeGuardado : '';
    $intCertificado = ($acredInt && (int)$acredInt->certificado === 1) ? 'SI' : 'NO';
@endphp

<form id="form_carrera">
    @csrf
    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-university"></i> Carrera</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">x</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="rounded p-2">
                <div id="form_carrera_error" class="alert alert-danger d-none mb-3">
                    Revise los campos obligatorios y corrija las fechas antes de guardar.
                </div>
                <div class="bg-primary centrar_bloque p-1 col-md-8 rounded shadow">
                    <h5 class="text-white text-center">{{$cod_car==0 ? 'Formulario para nueva Carrera' : 'Formulario para editar Carrera'}}</h5>
                </div>
                <br/>
                <br/>
                <span class="font-weight-bold font-italic text-dark">Facultad : {{$facultad->fac_nombre}}</span>
                <hr class="sidebar-divider"/>

                <div class="shadow p-3 mb-3">
                    <div class="text-primary font-weight-bold font-italic" style="font-size: 0.82em">* Datos de la carrera</div>
                        <div class="form-row mt-2">
                        <div class="form-group col-md-8 mb-2">
                            <label class="font-italic mb-1">Nombre de la carrera</label>
                            <input type="text" class="form-control form-control-sm" required name="nombre" value="{{$cod_car==0 ? '' : $carrera->car_nombre}}" />
                        </div>
                        <div class="form-group col-md-4 mb-2">
                            <label class="font-italic mb-1">Nombre corto</label>
                            <input type="text" class="form-control form-control-sm" required name="corto_c" value="{{$cod_car==0 ? '' : $carrera->car_abreviacion}}" />
                        </div>
                    </div>
                </div>

                <div class="shadow p-3">
                    <div class="text-primary font-weight-bold font-italic mb-3" style="font-size: 0.82em">* Datos de acreditacion</div>

                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h6 class="mb-0 text-dark"><i class="fas fa-flag"></i>&nbsp;Acreditacion Nacional (CEUB)</h6>
                            <div class="custom-control custom-switch mt-1 mt-md-0">
                                <input type="checkbox" class="custom-control-input" id="nac_habilitada" name="nac_habilitada" value="1" {{$nacHabilitada ? 'checked' : ''}}>
                                <label class="custom-control-label" for="nac_habilitada">Registrar</label>
                            </div>
                        </div>

                        <div id="nac_panel" class="mt-3">
                            <div class="form-row">
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">ACRED.</label>
                                    <select class="form-control form-control-sm campos-acred" name="nac_acred" id="nac_acred" required>
                                        <option value="SI" {{$nacAcred==='SI' ? 'selected' : ''}}>SI</option>
                                        <option value="NO" {{$nacAcred==='NO' ? 'selected' : ''}}>NO</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">Tipo</label>
                                    <input type="text" class="form-control form-control-sm campos-acred" id="nac_tipo" value="Nacional" readonly required />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">Sistema</label>
                                    <input type="text" class="form-control form-control-sm campos-acred" id="nac_sistema" value="CEUB" readonly required />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">Año</label>
                                    <input type="number" class="form-control form-control-sm campos-acred" name="nac_anio_base" id="nac_anio_base" min="1900" max="2200" value="{{$nacAnio}}" required />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">S/C</label>
                                    <input type="number" class="form-control form-control-sm campos-acred" name="nac_proc_sc" id="nac_proc_sc" min="0" step="1" value="{{$nacSc}}" required />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">N/C</label>
                                    <input type="number" class="form-control form-control-sm campos-acred" name="nac_proc_nc" id="nac_proc_nc" min="0" step="1" value="{{$nacNc}}" required />
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">Total</label>
                                    <input type="number" class="form-control form-control-sm campos-acred" name="nac_proc_total" id="nac_proc_total" value="{{$nacTotal}}" readonly required />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">Acreditacion</label>
                                    <input type="date" class="form-control form-control-sm campos-acred" name="nac_fecha_acreditacion" id="nac_fecha_acreditacion" value="{{$nacFechaAcred}}" required />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">Vencimiento</label>
                                    <input type="date" class="form-control form-control-sm campos-acred" name="nac_fecha_vencimiento" id="nac_fecha_vencimiento" value="{{$nacFechaVenc}}" required />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">Estado</label>
                                    <input type="text" class="form-control form-control-sm campos-acred" id="nac_estado_texto" value="{{$nacEstado}}" readonly required />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">Puntaje</label>
                                    <select class="form-control form-control-sm campos-acred" name="nac_puntaje_modo" id="nac_puntaje_modo" required>
                                        <option value="" {{$nacPuntajeModo==='' ? 'selected' : ''}}></option>
                                        <option value="Cumple" {{$nacPuntajeModo==='Cumple' ? 'selected' : ''}}>Cumple</option>
                                        <option value="Homologado" {{$nacPuntajeModo==='Homologado' ? 'selected' : ''}}>Homologado</option>
                                        <option value="S/D" {{$nacPuntajeModo==='S/D' ? 'selected' : ''}}>S/D</option>
                                        <option value="NUMERO" {{$nacPuntajeModo==='NUMERO' ? 'selected' : ''}}>Numero</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">Puntaje numerico</label>
                                    <input type="text" class="form-control form-control-sm campos-acred" name="nac_puntaje_numero" id="nac_puntaje_numero" value="{{$nacPuntajeNumero}}" placeholder="Ej: 84.5/100" required />
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-2 mb-0">
                                    <label class="mb-1">Certificados</label>
                                    <select class="form-control form-control-sm campos-acred" name="nac_certificado" id="nac_certificado" required>
                                        <option value="SI" {{$nacCertificado==='SI' ? 'selected' : ''}}>SI</option>
                                        <option value="NO" {{$nacCertificado==='NO' ? 'selected' : ''}}>NO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded p-3 mb-2">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h6 class="mb-0 text-dark"><i class="fas fa-globe"></i>&nbsp;Acreditacion Internacional (ARCU SUR)</h6>
                            <div class="custom-control custom-switch mt-1 mt-md-0">
                                <input type="checkbox" class="custom-control-input" id="int_habilitada" name="int_habilitada" value="1" {{$intHabilitada ? 'checked' : ''}}>
                                <label class="custom-control-label" for="int_habilitada">Habilitar acreditacion internacional</label>
                            </div>
                        </div>

                        <div id="int_panel" class="mt-3">
                            <div class="form-row">
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">ACRED.</label>
                                    <select class="form-control form-control-sm campos-acred" name="int_acred" id="int_acred" required>
                                        <option value="SI" {{$intAcred==='SI' ? 'selected' : ''}}>SI</option>
                                        <option value="NO" {{$intAcred==='NO' ? 'selected' : ''}}>NO</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">Tipo</label>
                                    <input type="text" class="form-control form-control-sm campos-acred" id="int_tipo" value="Internacional" readonly required />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">Sistema</label>
                                    <input type="text" class="form-control form-control-sm campos-acred" id="int_sistema" value="ARCU SUR" readonly required />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">Año</label>
                                    <input type="number" class="form-control form-control-sm campos-acred" name="int_anio_base" id="int_anio_base" min="1900" max="2200" value="{{$intAnio}}" required />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">S/C</label>
                                    <input type="number" class="form-control form-control-sm campos-acred" name="int_proc_sc" id="int_proc_sc" min="0" step="1" value="{{$intSc}}" required />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">N/C</label>
                                    <input type="number" class="form-control form-control-sm campos-acred" name="int_proc_nc" id="int_proc_nc" min="0" step="1" value="{{$intNc}}" required />
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">Total</label>
                                    <input type="number" class="form-control form-control-sm campos-acred" name="int_proc_total" id="int_proc_total" value="{{$intTotal}}" readonly required />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">Acreditacion</label>
                                    <input type="date" class="form-control form-control-sm campos-acred" name="int_fecha_acreditacion" id="int_fecha_acreditacion" value="{{$intFechaAcred}}" required />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">Vencimiento</label>
                                    <input type="date" class="form-control form-control-sm campos-acred" name="int_fecha_vencimiento" id="int_fecha_vencimiento" value="{{$intFechaVenc}}" required />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">Estado</label>
                                    <input type="text" class="form-control form-control-sm campos-acred" id="int_estado_texto" value="{{$intEstado}}" readonly required />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">Puntaje</label>
                                    <select class="form-control form-control-sm campos-acred" name="int_puntaje_modo" id="int_puntaje_modo" required>
                                        <option value="" {{$intPuntajeModo==='' ? 'selected' : ''}}></option>
                                        <option value="Cumple" {{$intPuntajeModo==='Cumple' ? 'selected' : ''}}>Cumple</option>
                                        <option value="Homologado" {{$intPuntajeModo==='Homologado' ? 'selected' : ''}}>Homologado</option>
                                        <option value="S/D" {{$intPuntajeModo==='S/D' ? 'selected' : ''}}>S/D</option>
                                        <option value="NUMERO" {{$intPuntajeModo==='NUMERO' ? 'selected' : ''}}>Numero</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1">Puntaje numerico</label>
                                    <input type="text" class="form-control form-control-sm campos-acred" name="int_puntaje_numero" id="int_puntaje_numero" value="{{$intPuntajeNumero}}" placeholder="Ej: 84.5/100" required />
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-2 mb-0">
                                    <label class="mb-1">Certificados</label>
                                    <select class="form-control form-control-sm campos-acred" name="int_certificado" id="int_certificado" required>
                                        <option value="SI" {{$intCertificado==='SI' ? 'selected' : ''}}>SI</option>
                                        <option value="NO" {{$intCertificado==='NO' ? 'selected' : ''}}>NO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-light border mt-3 mb-0">
                        <strong>Referencia:</strong> S/C = Si cumple | N/C = No cumple.
                    </div>
                </div>

                <input type="hidden" name="cf" value="{{$facultad->cod_fac}}">
                @if($cod_car!=0)
                    <input type="hidden" name="cc" value="{{$carrera->cod_car}}">
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary" type="button" onclick="enviar('form_carrera','g_carrera')">Guardar</button>
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

    function actualizarTotalYEstado(prefijo){
        if(!$('#' + prefijo + '_habilitada').is(':checked')){
            $('#' + prefijo + '_proc_total').val('');
            $('#' + prefijo + '_estado_texto').val('');
            return;
        }

        var sc = toNullableInt($('#' + prefijo + '_proc_sc').val());
        var nc = toNullableInt($('#' + prefijo + '_proc_nc').val());

        if(sc === null && nc === null){
            $('#' + prefijo + '_proc_total').val('');
        }else{
            $('#' + prefijo + '_proc_total').val((sc || 0) + (nc || 0));
        }

        var fechaAcred = $('#' + prefijo + '_fecha_acreditacion').val();
        var fechaVenc = $('#' + prefijo + '_fecha_vencimiento').val();
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

        $('#' + prefijo + '_estado_texto').val(estado);
    }

    function actualizarModoPuntaje(prefijo){
        var habilitada = $('#' + prefijo + '_habilitada').is(':checked');
        var modo = $('#' + prefijo + '_puntaje_modo').val();
        var inputNumero = $('#' + prefijo + '_puntaje_numero');

        if(!habilitada){
            inputNumero.prop('required', false);
            inputNumero.prop('disabled', true);
            return;
        }

        if(modo === 'NUMERO'){
            inputNumero.prop('disabled', false);
            inputNumero.prop('required', true);
            return;
        }

        inputNumero.prop('required', false);
        inputNumero.prop('disabled', true);
    }

    function actualizarPanelAcreditacion(prefijo){
        var habilitada = $('#' + prefijo + '_habilitada').is(':checked');
        $('#' + prefijo + '_panel .campos-acred').prop('disabled', !habilitada);

        if(!habilitada){
            $('#' + prefijo + '_estado_texto').val('');
            $('#' + prefijo + '_proc_total').val('');
            return;
        }

        actualizarModoPuntaje(prefijo);
        actualizarTotalYEstado(prefijo);
    }

    function inicializarAcreditacion(prefijo){
        $('#' + prefijo + '_habilitada').on('change', function(){
            actualizarPanelAcreditacion(prefijo);
        });

        $('#' + prefijo + '_proc_sc,#' + prefijo + '_proc_nc,#' + prefijo + '_fecha_acreditacion,#' + prefijo + '_fecha_vencimiento').on('input change', function(){
            actualizarTotalYEstado(prefijo);
            if(typeof window.validarRangoFechas === 'function'){
                window.validarRangoFechas(prefijo);
            }
        });

        $('#' + prefijo + '_puntaje_modo').on('change', function(){
            actualizarModoPuntaje(prefijo);
        });

        actualizarPanelAcreditacion(prefijo);
    }

    $(document).ready(function(){
        inicializarAcreditacion('nac');
        inicializarAcreditacion('int');
    });
</script>
