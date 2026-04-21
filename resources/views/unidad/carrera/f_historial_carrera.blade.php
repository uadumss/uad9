<div class="modal-content border-bottom-info">
    <div class="modal-header bg-info">
        <h5 class="modal-title text-white" id="exampleModalLabel">
            <i class="fas fa-history"></i>&nbsp;Historial y acreditacion de carrera
        </h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="alert alert-light border mb-3">
            <div>
                Facultad actual:
                <span class="font-weight-bold text-dark">{{$facultad->fac_nombre ?? '-'}}</span>
            </div>
            <div>
                Carrera actual:
                <span class="font-weight-bold text-dark">{{$carrera->car_nombre}}</span>
                <span class="text-muted">({{$carrera->car_abreviacion ?: '-'}})</span>
            </div>
        </div>

        <h6 class="text-info font-weight-bold mb-2"><i class="fas fa-award"></i>&nbsp;Historial de acreditaciones CEUB (Nacional)</h6>
        <div class="table-responsive mb-3">
            <table class="table table-sm table-hover" width="100%" cellspacing="0" style="font-size: 0.80em">
                <thead>
                <tr class="bg-gray-600 text-white">
                    <th>Nro</th>
                    <th>Acred.</th>
                    <th>Gestion / Procesos</th>
                    <th>Acreditacion / Venc.</th>
                    <th>Resolucion</th>
                    <th>Estado</th>
                    <th>Puntaje</th>
                    <th>Certif.</th>
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
                            <th class="border-right font-weight-bolder text-primary">
                                <div>{{$k}}</div>
                            </th>
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
                                <div><span class="text-muted">Emision:</span> {{$a->resolucion_fecha_emision ? date('d/m/Y', strtotime($a->resolucion_fecha_emision)) : ''}}</div>
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

        <h6 class="text-info font-weight-bold mb-2"><i class="fas fa-award"></i>&nbsp;Historial de acreditaciones ARCU SUR (Internacional)</h6>
        <div class="table-responsive mb-4">
            <table class="table table-sm table-hover" width="100%" cellspacing="0" style="font-size: 0.80em">
                <thead>
                <tr class="bg-gray-600 text-white">
                    <th>Nro</th>
                    <th>Acred.</th>
                    <th>Gestion / Procesos</th>
                    <th>Acreditacion / Venc.</th>
                    <th>Resolucion</th>
                    <th>Estado</th>
                    <th>Puntaje</th>
                    <th>Certif.</th>
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
                            <th class="border-right font-weight-bolder text-primary">
                                <div>{{$m}}</div>
                            </th>
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
                                <div><span class="text-muted">Emision:</span> {{$a->resolucion_fecha_emision ? date('d/m/Y', strtotime($a->resolucion_fecha_emision)) : ''}}</div>
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

        <h6 class="text-info font-weight-bold mb-2"><i class="fas fa-signature"></i>&nbsp;Historial de cambios de nombre</h6>
        @if(sizeof($historial) > 0)
            <div class="table-responsive">
                <table class="table table-sm table-hover" width="100%" cellspacing="0" style="font-size: 0.82em">
                    <thead>
                    <tr class="bg-gray-600 text-white">
                        <th>Nro</th>
                        <th>Nombre de carrera</th>
                        <th>Nombre corto</th>
                        <th>Fecha de cambio</th>
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
                            <th class="border-right font-weight-bolder text-primary">{{$i}}</th>
                            <td class="text-left">
                                <div><span class="text-muted">{{$etiquetaAnterior}}:</span> {{$h->nombre_anterior}}</div>
                                <div><span class="text-primary">{{$etiquetaNuevo}}:</span> {{$h->nombre_nuevo}}</div>
                            </td>
                            <td class="text-left">
                                <div><span class="text-muted">{{$etiquetaAnterior}}:</span> {{$h->abreviacion_anterior ?? '-'}}</div>
                                <div><span class="text-primary">{{$etiquetaNuevo}}:</span> {{$h->abreviacion_nueva ?? '-'}}</div>
                            </td>
                            <td class="text-left">
                                {{date('d/m/Y H:i', strtotime($h->fecha_cambio ?? $h->created_at))}}
                            </td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info mb-0">
                Esta carrera no tiene cambios de nombre o nombre corto registrados.
            </div>
        @endif
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
    </div>
</div>
