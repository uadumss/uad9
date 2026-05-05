<div class="modal-content border-0 shadow-lg overflow-hidden">
    <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f4c5c 0%, #1d6f8a 48%, #3aa6b9 100%);">
        <div>
            <h5 class="modal-title font-weight-bolder mb-0" id="exampleModalLabel">
                <i class="fas fa-history mr-1"></i> Historial y acreditación de carrera
            </h5>
            <small class="text-white-50">Cambios de nombre y registros de acreditación</small>
        </div>
        <button class="close text-white opacity-100" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body p-0" style="background: linear-gradient(180deg, #f7fbfc 0%, #eef7fa 100%);">
        <div class="p-3 p-md-4">
            <div class="alert alert-light border shadow-sm d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div class="mb-2 mb-md-0">
                    <div class="text-secondary text-uppercase font-weight-bold" style="letter-spacing: .04em; font-size: .75rem;">Contexto actual</div>
                    <div class="font-weight-bold text-dark">Facultad: {{$facultad->fac_nombre ?? '-'}}</div>
                    <div class="text-dark">Carrera: {{$carrera->car_nombre}} <span class="text-muted">({{$carrera->car_abreviacion ?: '-'}})</span></div>
                </div>
                <span class="badge px-3 py-2" style="background: #dbeafe; color: #1d4ed8;">{{$acreditacionesNacional->count() + $acreditacionesInternacional->count() + $historial->count()}} registros</span>
            </div>

            <div class="border rounded-lg shadow-sm bg-white p-3 mb-3" style="background: linear-gradient(180deg, #f9fbff 0%, #ffffff 100%);">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <h6 class="text-info font-weight-bolder mb-0"><i class="fas fa-award text-primary"></i>&nbsp;Historial de acreditaciones CEUB (Nacional)</h6>
                    <span class="badge badge-light border text-info px-3 py-2">Nacional</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0" width="100%" cellspacing="0" style="font-size: 0.80em">
                        <thead style="background: linear-gradient(135deg, #e0fbfc 0%, #cdeffd 100%);">
                        <tr class="text-secondary">
                            <th class="border-0">Nro</th>
                            <th class="border-0">Acred.</th>
                            <th class="border-0">Gestión / Procesos</th>
                            <th class="border-0">Acreditación / Venc.</th>
                            <th class="border-0">Resolución</th>
                            <th class="border-0">Estado</th>
                            <th class="border-0">Puntaje</th>
                            <th class="border-0">Certif.</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(sizeof($acreditacionesNacional) > 0)
                            <?php $k = 1; ?>
                            @foreach($acreditacionesNacional as $a)
                                <?php
                                $total = $a->proc_total;
                                $esActiva = (int)$a->cod_cac === (int)$codAcreditacionNacionalActiva;
                                $resolucionNumero = trim((string)($a->resolucion_numero ?? ''));
                                $resolucionAnio = trim((string)($a->resolucion_anio ?? ''));
                                $resolucionCompleta = '';
                                if($resolucionNumero !== '' && $resolucionAnio !== ''){
                                    $resolucionCompleta = $resolucionNumero.'/'.$resolucionAnio;
                                }elseif($resolucionNumero !== ''){
                                    $resolucionCompleta = $resolucionNumero;
                                }elseif($resolucionAnio !== ''){
                                    $resolucionCompleta = $resolucionAnio;
                                }
                                if($total === null && ($a->proc_sc !== null || $a->proc_nc !== null)){
                                    $total = (int)($a->proc_sc ?? 0) + (int)($a->proc_nc ?? 0);
                                }
                                ?>
                                <tr class="{{$esActiva ? 'table-success' : ''}}">
                                    <th class="border-right font-weight-bolder text-primary bg-light">{{$k}}</th>
                                    <td class="text-center">{{$a->acreditada === null ? '' : ($a->acreditada ? 'SI' : 'NO')}}</td>
                                    <td class="text-left">
                                        <div><span class="text-muted">Año:</span> {{$a->anio ?? ''}}</div>
                                        <div><span class="text-muted">S/C:</span> {{$a->proc_sc ?? ''}} | <span class="text-muted">N/C:</span> {{$a->proc_nc ?? ''}} | <span class="text-muted">Total:</span> {{$total ?? ''}}</div>
                                    </td>
                                    <td class="text-left">
                                        <div><span class="text-muted">Acred.:</span> {{$a->fecha_acreditacion ? date('d/m/Y', strtotime($a->fecha_acreditacion)) : ''}}</div>
                                        <div><span class="text-muted">Venc.:</span> {{$a->fecha_vencimiento ? date('d/m/Y', strtotime($a->fecha_vencimiento)) : ''}}</div>
                                    </td>
                                    <td class="text-left">
                                        <div><span class="text-muted">Inicio:</span> {{$a->resolucion_inicio ? date('d/m/Y', strtotime($a->resolucion_inicio)) : ''}}</div>
                                        <div><span class="text-muted">Fin:</span> {{$a->resolucion_fin ? date('d/m/Y', strtotime($a->resolucion_fin)) : ''}}</div>
                                        <div><span class="text-muted">Emisión:</span> {{$a->resolucion_fecha_emision ? date('d/m/Y', strtotime($a->resolucion_fecha_emision)) : ''}}</div>
                                        <div><span class="text-muted">Nro:</span> {{$resolucionCompleta}}</div>
                                    </td>
                                    <td class="text-left">{{$a->estado_vista ?? ''}}</td>
                                    <td class="text-left">{{$a->puntaje ?? ''}}</td>
                                    <td class="text-center">{{$a->certificado === null ? '' : ($a->certificado ? 'SI' : 'NO')}}</td>
                                </tr>
                                <?php $k++; ?>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center text-muted" colspan="8">Sin historial de acreditaciones CEUB.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="border rounded-lg shadow-sm bg-white p-3 mb-3" style="background: linear-gradient(180deg, #f9fbff 0%, #ffffff 100%);">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <h6 class="text-info font-weight-bolder mb-0"><i class="fas fa-award text-primary"></i>&nbsp;Historial de acreditaciones ARCU SUR (Internacional)</h6>
                    <span class="badge badge-light border text-info px-3 py-2">Internacional</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0" width="100%" cellspacing="0" style="font-size: 0.80em">
                        <thead style="background: linear-gradient(135deg, #e0fbfc 0%, #cdeffd 100%);">
                        <tr class="text-secondary">
                            <th class="border-0">Nro</th>
                            <th class="border-0">Acred.</th>
                            <th class="border-0">Gestión / Procesos</th>
                            <th class="border-0">Acreditación / Venc.</th>
                            <th class="border-0">Resolución</th>
                            <th class="border-0">Estado</th>
                            <th class="border-0">Puntaje</th>
                            <th class="border-0">Certif.</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(sizeof($acreditacionesInternacional) > 0)
                            <?php $m = 1; ?>
                            @foreach($acreditacionesInternacional as $a)
                                <?php
                                $total = $a->proc_total;
                                $esActiva = (int)$a->cod_cac === (int)$codAcreditacionInternacionalActiva;
                                $resolucionNumero = trim((string)($a->resolucion_numero ?? ''));
                                $resolucionAnio = trim((string)($a->resolucion_anio ?? ''));
                                $resolucionCompleta = '';
                                if($resolucionNumero !== '' && $resolucionAnio !== ''){
                                    $resolucionCompleta = $resolucionNumero.'/'.$resolucionAnio;
                                }elseif($resolucionNumero !== ''){
                                    $resolucionCompleta = $resolucionNumero;
                                }elseif($resolucionAnio !== ''){
                                    $resolucionCompleta = $resolucionAnio;
                                }
                                if($total === null && ($a->proc_sc !== null || $a->proc_nc !== null)){
                                    $total = (int)($a->proc_sc ?? 0) + (int)($a->proc_nc ?? 0);
                                }
                                ?>
                                <tr class="{{$esActiva ? 'table-success' : ''}}">
                                    <th class="border-right font-weight-bolder text-primary bg-light">{{$m}}</th>
                                    <td class="text-center">{{$a->acreditada === null ? '' : ($a->acreditada ? 'SI' : 'NO')}}</td>
                                    <td class="text-left">
                                        <div><span class="text-muted">Año:</span> {{$a->anio ?? ''}}</div>
                                        <div><span class="text-muted">S/C:</span> {{$a->proc_sc ?? ''}} | <span class="text-muted">N/C:</span> {{$a->proc_nc ?? ''}} | <span class="text-muted">Total:</span> {{$total ?? ''}}</div>
                                    </td>
                                    <td class="text-left">
                                        <div><span class="text-muted">Acred.:</span> {{$a->fecha_acreditacion ? date('d/m/Y', strtotime($a->fecha_acreditacion)) : ''}}</div>
                                        <div><span class="text-muted">Venc.:</span> {{$a->fecha_vencimiento ? date('d/m/Y', strtotime($a->fecha_vencimiento)) : ''}}</div>
                                    </td>
                                    <td class="text-left">
                                        <div><span class="text-muted">Inicio:</span> {{$a->resolucion_inicio ? date('d/m/Y', strtotime($a->resolucion_inicio)) : ''}}</div>
                                        <div><span class="text-muted">Fin:</span> {{$a->resolucion_fin ? date('d/m/Y', strtotime($a->resolucion_fin)) : ''}}</div>
                                        <div><span class="text-muted">Emisión:</span> {{$a->resolucion_fecha_emision ? date('d/m/Y', strtotime($a->resolucion_fecha_emision)) : ''}}</div>
                                        <div><span class="text-muted">Nro:</span> {{$resolucionCompleta}}</div>
                                    </td>
                                    <td class="text-left">{{$a->estado_vista ?? ''}}</td>
                                    <td class="text-left">{{$a->puntaje ?? ''}}</td>
                                    <td class="text-center">{{$a->certificado === null ? '' : ($a->certificado ? 'SI' : 'NO')}}</td>
                                </tr>
                                <?php $m++; ?>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center text-muted" colspan="8">Sin historial de acreditaciones ARCU SUR.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="border rounded-lg shadow-sm bg-white p-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <h6 class="text-info font-weight-bolder mb-0"><i class="fas fa-signature text-primary"></i>&nbsp;Historial de cambios de nombre</h6>
                    <span class="badge badge-light border text-info px-3 py-2">Nombre y abreviación</span>
                </div>
                @if(sizeof($historial) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0" width="100%" cellspacing="0" style="font-size: 0.82em">
                            <thead style="background: linear-gradient(135deg, #e0fbfc 0%, #cdeffd 100%);">
                            <tr class="text-secondary">
                                <th class="border-0">Nro</th>
                                <th class="border-0">Nombre de carrera</th>
                                <th class="border-0">Nombre corto</th>
                                <th class="border-0">Fecha de cambio</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            @foreach($historial as $h)
                                <?php
                                $etiquetaAnterior = $loop->last ? 'Primero' : 'Anterior';
                                $etiquetaNuevo = $loop->first ? 'Actual' : 'Nuevo';
                                ?>
                                <tr>
                                    <th class="border-right font-weight-bolder text-primary bg-light">{{$i}}</th>
                                    <td class="text-left">
                                        <div><span class="text-muted">{{$etiquetaAnterior}}:</span> {{$h->nombre_anterior}}</div>
                                        <div><span class="text-primary font-weight-bold">{{$etiquetaNuevo}}:</span> {{$h->nombre_nuevo}}</div>
                                    </td>
                                    <td class="text-left">
                                        <div><span class="text-muted">{{$etiquetaAnterior}}:</span> {{$h->abreviacion_anterior ?? '-'}}</div>
                                        <div><span class="text-primary font-weight-bold">{{$etiquetaNuevo}}:</span> {{$h->abreviacion_nueva ?? '-'}}</div>
                                    </td>
                                    <td class="text-left text-nowrap">
                                        {{date('d/m/Y H:i', strtotime($h->fecha_cambio ?? $h->created_at))}}
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info border-0 shadow-sm mb-0" style="border-left: 4px solid #17a2b8;">
                        Esta carrera no tiene cambios de nombre o nombre corto registrados.
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal-footer border-0" style="background: #f7fbfc;">
        <button class="btn btn-light border px-4" type="button" data-dismiss="modal">Cerrar</button>
    </div>
</div>
