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
    $nacResolucionInicio = ($acredNac && $acredNac->resolucion_inicio) ? date('Y-m-d', strtotime($acredNac->resolucion_inicio)) : '';
    $nacResolucionFin = ($acredNac && $acredNac->resolucion_fin) ? date('Y-m-d', strtotime($acredNac->resolucion_fin)) : '';
    $nacResolucionFechaEmision = ($acredNac && $acredNac->resolucion_fecha_emision) ? date('Y-m-d', strtotime($acredNac->resolucion_fecha_emision)) : '';
    $nacResolucionNumero = $acredNac ? trim((string)($acredNac->resolucion_numero ?? '')) : '';
    $nacResolucionAnio = $acredNac ? trim((string)($acredNac->resolucion_anio ?? '')) : '';
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
    $intResolucionInicio = ($acredInt && $acredInt->resolucion_inicio) ? date('Y-m-d', strtotime($acredInt->resolucion_inicio)) : '';
    $intResolucionFin = ($acredInt && $acredInt->resolucion_fin) ? date('Y-m-d', strtotime($acredInt->resolucion_fin)) : '';
    $intResolucionFechaEmision = ($acredInt && $acredInt->resolucion_fecha_emision) ? date('Y-m-d', strtotime($acredInt->resolucion_fecha_emision)) : '';
    $intResolucionNumero = $acredInt ? trim((string)($acredInt->resolucion_numero ?? '')) : '';
    $intResolucionAnio = $acredInt ? trim((string)($acredInt->resolucion_anio ?? '')) : '';
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
    <div class="modal-content border-0 shadow-lg overflow-hidden">
        <div class="modal-header text-white" style="background: linear-gradient(135deg, #184e77 0%, #1e6091 45%, #2a6f97 100%);">
            <div>
                <h5 class="modal-title font-weight-bolder mb-0" id="exampleModalLabel"><i class="fas fa-university mr-1"></i> Carrera</h5>
                <small class="text-white-50">Registro y acreditación académica</small>
            </div>
            <button class="close text-white opacity-100" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body p-0" style="background: linear-gradient(180deg, #f8fbff 0%, #eef5fb 100%);">
            <div class="p-3 p-md-4">
                <div id="form_carrera_error" class="alert alert-danger d-none mb-3">
                    Revise los campos obligatorios y corrija las fechas antes de guardar.
                </div>
                <div class="centrar_bloque col-md-8 px-0 mb-3">
                    <div class="rounded-lg shadow-sm text-white text-center py-2" style="background: linear-gradient(135deg, #2a6f97 0%, #457b9d 100%);">
                        <h5 class="mb-0 font-weight-bolder">{{$cod_car==0 ? 'Formulario para nueva Carrera' : 'Formulario para editar Carrera'}}</h5>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <div class="font-weight-bold text-dark">Facultad: <span class="text-primary">{{$facultad->fac_nombre}}</span></div>
                    <span class="badge px-3 py-2" style="background: #dbeafe; color: #1d4ed8;">{{$cod_car==0 ? 'Nuevo registro' : 'Edición activa'}}</span>
                </div>

                <div class="border rounded-lg shadow-sm bg-white p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <div class="text-primary font-weight-bold text-uppercase" style="letter-spacing: .04em; font-size: .78rem">* Datos de la carrera</div>
                        <span class="badge badge-light border text-primary px-3 py-2">Información general</span>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-8 mb-2">
                            <label class="font-italic mb-1 text-secondary">Nombre de la carrera</label>
                            <input type="text" class="form-control form-control-sm shadow-sm" required name="nombre" value="{{$cod_car==0 ? '' : $carrera->car_nombre}}" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                        </div>
                        <div class="form-group col-md-4 mb-2">
                            <label class="font-italic mb-1 text-secondary">Nombre corto</label>
                            <input type="text" class="form-control form-control-sm shadow-sm" required name="corto_c" value="{{$cod_car==0 ? '' : $carrera->car_abreviacion}}" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                        </div>
                    </div>
                </div>

                <div class="border rounded-lg shadow-sm bg-white p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <div class="text-primary font-weight-bold text-uppercase" style="letter-spacing: .04em; font-size: .78rem">* Datos de acreditación</div>
                        <span class="badge badge-light border text-primary px-3 py-2">CEUB / ARCU SUR</span>
                    </div>

                    <div class="border rounded-lg p-3 mb-3" style="background: linear-gradient(180deg, #f9fbff 0%, #ffffff 100%);">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h6 class="mb-0 text-dark font-weight-bolder"><i class="fas fa-flag text-primary"></i>&nbsp;Acreditación Nacional (CEUB)</h6>
                            <div class="custom-control custom-switch mt-1 mt-md-0">
                                <input type="checkbox" class="custom-control-input" id="nac_habilitada" name="nac_habilitada" value="1" {{$nacHabilitada ? 'checked' : ''}}>
                                <label class="custom-control-label" for="nac_habilitada">Registrar</label>
                            </div>
                        </div>

                        <div id="nac_panel" class="mt-3">
                            <div class="form-row">
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">ACRED.</label>
                                    <select class="form-control form-control-sm campos-acred shadow-sm" name="nac_acred" id="nac_acred" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                        <option value="SI" {{$nacAcred==='SI' ? 'selected' : ''}}>SI</option>
                                        <option value="NO" {{$nacAcred==='NO' ? 'selected' : ''}}>NO</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Tipo</label>
                                    <input type="text" class="form-control form-control-sm campos-acred shadow-sm" id="nac_tipo" value="Nacional" readonly required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Sistema</label>
                                    <input type="text" class="form-control form-control-sm campos-acred shadow-sm" id="nac_sistema" value="CEUB" readonly required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Año</label>
                                    <input type="number" class="form-control form-control-sm campos-acred shadow-sm" name="nac_anio_base" id="nac_anio_base" min="1900" max="2200" value="{{$nacAnio}}" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">S/C</label>
                                    <input type="number" class="form-control form-control-sm campos-acred shadow-sm" name="nac_proc_sc" id="nac_proc_sc" min="0" step="1" value="{{$nacSc}}" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">N/C</label>
                                    <input type="number" class="form-control form-control-sm campos-acred shadow-sm" name="nac_proc_nc" id="nac_proc_nc" min="0" step="1" value="{{$nacNc}}" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Total</label>
                                    <input type="number" class="form-control form-control-sm campos-acred shadow-sm" name="nac_proc_total" id="nac_proc_total" value="{{$nacTotal}}" readonly required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #f8fbff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Acreditación</label>
                                    <input type="date" class="form-control form-control-sm campos-acred shadow-sm" name="nac_fecha_acreditacion" id="nac_fecha_acreditacion" value="{{$nacFechaAcred}}" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Vencimiento</label>
                                    <input type="date" class="form-control form-control-sm campos-acred shadow-sm" name="nac_fecha_vencimiento" id="nac_fecha_vencimiento" value="{{$nacFechaVenc}}" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Estado</label>
                                    <input type="text" class="form-control form-control-sm campos-acred shadow-sm" id="nac_estado_texto" value="{{$nacEstado}}" readonly required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #f8fbff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Puntaje</label>
                                    <select class="form-control form-control-sm campos-acred shadow-sm" name="nac_puntaje_modo" id="nac_puntaje_modo" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                        <option value="" {{$nacPuntajeModo==='' ? 'selected' : ''}}></option>
                                        <option value="Cumple" {{$nacPuntajeModo==='Cumple' ? 'selected' : ''}}>Cumple</option>
                                        <option value="Homologado" {{$nacPuntajeModo==='Homologado' ? 'selected' : ''}}>Homologado</option>
                                        <option value="S/D" {{$nacPuntajeModo==='S/D' ? 'selected' : ''}}>S/D</option>
                                        <option value="NUMERO" {{$nacPuntajeModo==='NUMERO' ? 'selected' : ''}}>Numero</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Puntaje numérico</label>
                                    <input type="text" class="form-control form-control-sm campos-acred shadow-sm" name="nac_puntaje_numero" id="nac_puntaje_numero" value="{{$nacPuntajeNumero}}" placeholder="Ej: 84.5" inputmode="decimal" autocomplete="off" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Resol. inicio</label>
                                    <input type="date" class="form-control form-control-sm campos-acred shadow-sm" name="nac_resolucion_inicio" id="nac_resolucion_inicio" value="{{$nacResolucionInicio}}" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Resol. fin</label>
                                    <input type="date" class="form-control form-control-sm campos-acred shadow-sm" name="nac_resolucion_fin" id="nac_resolucion_fin" value="{{$nacResolucionFin}}" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Fecha emisión</label>
                                    <input type="date" class="form-control form-control-sm campos-acred shadow-sm" name="nac_resolucion_fecha_emision" id="nac_resolucion_fecha_emision" value="{{$nacResolucionFechaEmision}}" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group col-md-4 mb-2">
                                    <label class="mb-1 text-secondary">Nro. de resolución</label>
                                    <div class="d-flex align-items-center">
                                        <input type="text" class="form-control form-control-sm campos-acred shadow-sm" name="nac_resolucion_numero" id="nac_resolucion_numero" value="{{$nacResolucionNumero}}" maxlength="10" pattern="\d+" inputmode="numeric" placeholder="XXX" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                        <span class="px-2 text-muted">/</span>
                                        <input type="text" class="form-control form-control-sm campos-acred shadow-sm" name="nac_resolucion_anio" id="nac_resolucion_anio" value="{{$nacResolucionAnio}}" maxlength="4" pattern="\d{4}" inputmode="numeric" placeholder="AÑO" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-2 mb-0">
                                    <label class="mb-1 text-secondary">Certificados</label>
                                    <select class="form-control form-control-sm campos-acred shadow-sm" name="nac_certificado" id="nac_certificado" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                        <option value="SI" {{$nacCertificado==='SI' ? 'selected' : ''}}>SI</option>
                                        <option value="NO" {{$nacCertificado==='NO' ? 'selected' : ''}}>NO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded-lg p-3 mb-2" style="background: linear-gradient(180deg, #f9fbff 0%, #ffffff 100%);">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h6 class="mb-0 text-dark font-weight-bolder"><i class="fas fa-globe text-primary"></i>&nbsp;Acreditación Internacional (ARCU SUR)</h6>
                            <div class="custom-control custom-switch mt-1 mt-md-0">
                                <input type="checkbox" class="custom-control-input" id="int_habilitada" name="int_habilitada" value="1" {{$intHabilitada ? 'checked' : ''}}>
                                <label class="custom-control-label" for="int_habilitada">Habilitar acreditación internacional</label>
                            </div>
                        </div>

                        <div id="int_panel" class="mt-3">
                            <div class="form-row">
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">ACRED.</label>
                                    <select class="form-control form-control-sm campos-acred shadow-sm" name="int_acred" id="int_acred" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                        <option value="SI" {{$intAcred==='SI' ? 'selected' : ''}}>SI</option>
                                        <option value="NO" {{$intAcred==='NO' ? 'selected' : ''}}>NO</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Tipo</label>
                                    <input type="text" class="form-control form-control-sm campos-acred shadow-sm" id="int_tipo" value="Internacional" readonly required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Sistema</label>
                                    <input type="text" class="form-control form-control-sm campos-acred shadow-sm" id="int_sistema" value="ARCU SUR" readonly required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Año</label>
                                    <input type="number" class="form-control form-control-sm campos-acred shadow-sm" name="int_anio_base" id="int_anio_base" min="1900" max="2200" value="{{$intAnio}}" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">S/C</label>
                                    <input type="number" class="form-control form-control-sm campos-acred shadow-sm" name="int_proc_sc" id="int_proc_sc" min="0" step="1" value="{{$intSc}}" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">N/C</label>
                                    <input type="number" class="form-control form-control-sm campos-acred shadow-sm" name="int_proc_nc" id="int_proc_nc" min="0" step="1" value="{{$intNc}}" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Total</label>
                                    <input type="number" class="form-control form-control-sm campos-acred shadow-sm" name="int_proc_total" id="int_proc_total" value="{{$intTotal}}" readonly required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #f8fbff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Acreditación</label>
                                    <input type="date" class="form-control form-control-sm campos-acred shadow-sm" name="int_fecha_acreditacion" id="int_fecha_acreditacion" value="{{$intFechaAcred}}" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Vencimiento</label>
                                    <input type="date" class="form-control form-control-sm campos-acred shadow-sm" name="int_fecha_vencimiento" id="int_fecha_vencimiento" value="{{$intFechaVenc}}" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Estado</label>
                                    <input type="text" class="form-control form-control-sm campos-acred shadow-sm" id="int_estado_texto" value="{{$intEstado}}" readonly required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #f8fbff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Puntaje</label>
                                    <select class="form-control form-control-sm campos-acred shadow-sm" name="int_puntaje_modo" id="int_puntaje_modo" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                        <option value="" {{$intPuntajeModo==='' ? 'selected' : ''}}></option>
                                        <option value="Cumple" {{$intPuntajeModo==='Cumple' ? 'selected' : ''}}>Cumple</option>
                                        <option value="Homologado" {{$intPuntajeModo==='Homologado' ? 'selected' : ''}}>Homologado</option>
                                        <option value="S/D" {{$intPuntajeModo==='S/D' ? 'selected' : ''}}>S/D</option>
                                        <option value="NUMERO" {{$intPuntajeModo==='NUMERO' ? 'selected' : ''}}>Numero</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Puntaje numérico</label>
                                    <input type="text" class="form-control form-control-sm campos-acred shadow-sm" name="int_puntaje_numero" id="int_puntaje_numero" value="{{$intPuntajeNumero}}" placeholder="Ej: 84.5" inputmode="decimal" autocomplete="off" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Resol. inicio</label>
                                    <input type="date" class="form-control form-control-sm campos-acred shadow-sm" name="int_resolucion_inicio" id="int_resolucion_inicio" value="{{$intResolucionInicio}}" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Resol. fin</label>
                                    <input type="date" class="form-control form-control-sm campos-acred shadow-sm" name="int_resolucion_fin" id="int_resolucion_fin" value="{{$intResolucionFin}}" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group col-md-2 mb-2">
                                    <label class="mb-1 text-secondary">Fecha emisión</label>
                                    <input type="date" class="form-control form-control-sm campos-acred shadow-sm" name="int_resolucion_fecha_emision" id="int_resolucion_fecha_emision" value="{{$intResolucionFechaEmision}}" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group col-md-4 mb-2">
                                    <label class="mb-1 text-secondary">Nro. de resolución</label>
                                    <div class="d-flex align-items-center">
                                        <input type="text" class="form-control form-control-sm campos-acred shadow-sm" name="int_resolucion_numero" id="int_resolucion_numero" value="{{$intResolucionNumero}}" maxlength="10" pattern="\d+" inputmode="numeric" placeholder="XXX" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                        <span class="px-2 text-muted">/</span>
                                        <input type="text" class="form-control form-control-sm campos-acred shadow-sm" name="int_resolucion_anio" id="int_resolucion_anio" value="{{$intResolucionAnio}}" maxlength="4" pattern="\d{4}" inputmode="numeric" placeholder="AÑO" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-2 mb-0">
                                    <label class="mb-1 text-secondary">Certificados</label>
                                    <select class="form-control form-control-sm campos-acred shadow-sm" name="int_certificado" id="int_certificado" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                        <option value="SI" {{$intCertificado==='SI' ? 'selected' : ''}}>SI</option>
                                        <option value="NO" {{$intCertificado==='NO' ? 'selected' : ''}}>NO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-light border mt-3 mb-0 shadow-sm">
                        <strong>Referencia:</strong> S/C = Si cumple | N/C = No cumple.
                    </div>
                </div>

                <input type="hidden" name="cf" value="{{$facultad->cod_fac}}">
                @if($cod_car!=0)
                    <input type="hidden" name="cc" value="{{$carrera->cod_car}}">
                @endif
            </div>
        </div>
        <div class="modal-footer border-0" style="background: #f8fbff;">
            <button class="btn btn-light border px-4" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary px-4 shadow-sm" type="button" onclick="enviar('form_carrera','g_carrera')" style="background: linear-gradient(135deg, #1d4e89 0%, #2c7da0 100%); border: none;">Guardar</button>
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

    function normalizarPuntajeNumerico(prefijo){
        var inputNumero = $('#' + prefijo + '_puntaje_numero');
        var valor = String(inputNumero.val() || '').trim().replace(',', '.');

        if(valor === ''){
            inputNumero.val('');
            inputNumero.get(0).setCustomValidity('');
            return;
        }

        if(!/^\d+(\.\d+)?$/.test(valor)){
            inputNumero.get(0).setCustomValidity('Ingrese un numero valido. Use coma o punto para decimales.');
            return;
        }

        var numero = parseFloat(valor);
        if(isNaN(numero)){
            inputNumero.get(0).setCustomValidity('Ingrese un numero valido.');
            return;
        }

        if(numero < 0 || numero > 100){
            inputNumero.get(0).setCustomValidity('El puntaje numerico debe estar entre 0 y 100.');
            return;
        }

        inputNumero.get(0).setCustomValidity('');
        inputNumero.val(numero.toFixed(2).replace(/\.00$/, '').replace(/(\.\d*[1-9])0+$/, '$1'));
    }

    function normalizarResolucion(prefijo){
        var inputNumero = $('#' + prefijo + '_resolucion_numero');
        var inputAnio = $('#' + prefijo + '_resolucion_anio');
        var numero = String(inputNumero.val() || '').replace(/\D/g, '');
        var anio = String(inputAnio.val() || '').replace(/\D/g, '');

        inputNumero.val(numero);
        inputAnio.val(anio);

        var elementoAnio = inputAnio.get(0);
        if(elementoAnio){
            if(anio !== '' && anio.length !== 4){
                elementoAnio.setCustomValidity('El año de la resolucion debe tener 4 digitos.');
            }else{
                elementoAnio.setCustomValidity('');
            }
        }
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

        $('#' + prefijo + '_puntaje_numero').on('blur change', function(){
            normalizarPuntajeNumerico(prefijo);
        });

        $('#' + prefijo + '_resolucion_numero,#' + prefijo + '_resolucion_anio').on('input blur change', function(){
            normalizarResolucion(prefijo);
        });

        actualizarPanelAcreditacion(prefijo);
        normalizarPuntajeNumerico(prefijo);
        normalizarResolucion(prefijo);
    }

    $(document).ready(function(){
        inicializarAcreditacion('nac');
        inicializarAcreditacion('int');
    });
</script>
