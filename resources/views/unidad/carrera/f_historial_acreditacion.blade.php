<div class="modal-content border-0 shadow-lg overflow-hidden">
    <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f4c5c 0%, #1d6f8a 48%, #3aa6b9 100%);">
        <div>
            <h5 class="modal-title font-weight-bolder mb-0">
                <i class="fas fa-history mr-1"></i> Historial de acreditación
            </h5>
            <small class="text-white-50">Cambios registrados sobre una misma acreditación</small>
        </div>
        <button class="close text-white opacity-100" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body p-0" style="background: linear-gradient(180deg, #f7fbfc 0%, #eef7fa 100%);">
        <div class="p-3 p-md-4">
            <div class="alert alert-light border shadow-sm d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div class="mb-2 mb-md-0">
                    <div class="text-secondary text-uppercase font-weight-bold" style="letter-spacing: .04em; font-size: .75rem;">Acreditación seleccionada</div>
                    <div class="font-weight-bold text-dark">Facultad: {{ $facultad->fac_nombre ?? '-' }}</div>
                    <div class="text-dark">Carrera: {{ $carrera->car_nombre }} <span class="text-muted">({{ $carrera->car_abreviacion ?: '-' }})</span></div>
                    <div class="text-dark">Tipo: {{ $tipoVista }} | Estado: {{ $estadoVista }}</div>
                </div>
                <span class="badge px-3 py-2" style="background: #dbeafe; color: #1d4ed8;">{{ $historial->count() }} cambios</span>
            </div>

            <div class="border rounded-lg shadow-sm bg-white p-3 mb-3" style="background: linear-gradient(180deg, #f9fbff 0%, #ffffff 100%);">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <h6 class="text-info font-weight-bolder mb-0"><i class="fas fa-award text-primary"></i>&nbsp;Datos actuales</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0" style="font-size:.82em;">
                        <tbody>
                            <tr><th class="bg-light" style="width: 220px;">Acreditada</th><td>{{ $acreditacion->acreditada === null ? '' : ($acreditacion->acreditada ? 'SI' : 'NO') }}</td></tr>
                            <tr><th class="bg-light">Tipo</th><td>{{ $tipoVista }}</td></tr>
                            <tr><th class="bg-light">Sistema</th><td>{{ $acreditacion->sistema ?? '' }}</td></tr>
                            <tr><th class="bg-light">Año</th><td>{{ $acreditacion->anio ?? '' }}</td></tr>
                            <tr><th class="bg-light">S/C | N/C | Total</th><td>{{ $acreditacion->proc_sc ?? '' }} | {{ $acreditacion->proc_nc ?? '' }} | {{ $acreditacion->proc_total ?? '' }}</td></tr>
                            <tr><th class="bg-light">Acreditación / Vencimiento</th><td>{{ $acreditacion->fecha_acreditacion ? date('d/m/Y', strtotime($acreditacion->fecha_acreditacion)) : '' }} / {{ $acreditacion->fecha_vencimiento ? date('d/m/Y', strtotime($acreditacion->fecha_vencimiento)) : '' }}</td></tr>
                            <tr><th class="bg-light">Resolución</th><td>{{ $acreditacion->resolucion_inicio ? date('d/m/Y', strtotime($acreditacion->resolucion_inicio)) : '' }} - {{ $acreditacion->resolucion_fin ? date('d/m/Y', strtotime($acreditacion->resolucion_fin)) : '' }} | {{ $acreditacion->resolucion_fecha_emision ? date('d/m/Y', strtotime($acreditacion->resolucion_fecha_emision)) : '' }} | {{ $acreditacion->resolucion_numero ? $acreditacion->resolucion_numero : '' }}{{ $acreditacion->resolucion_anio ? '/'.$acreditacion->resolucion_anio : '' }}</td></tr>
                            <tr><th class="bg-light">Estado / Puntaje / Certif.</th><td>{{ $acreditacion->estado ?? '' }} / {{ $acreditacion->puntaje ?? '' }} / {{ $acreditacion->certificado === null ? '' : ($acreditacion->certificado ? 'SI' : 'NO') }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="border rounded-lg shadow-sm bg-white p-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <h6 class="text-info font-weight-bolder mb-0"><i class="fas fa-stream text-primary"></i>&nbsp;Versiones de edición</h6>
                    <span class="badge badge-light border text-info px-3 py-2">Más reciente primero</span>
                </div>
                @if($historial->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0" width="100%" cellspacing="0" style="font-size: 0.80em">
                            <thead style="background: linear-gradient(135deg, #e0fbfc 0%, #cdeffd 100%);">
                            <tr class="text-secondary">
                                <th class="border-0">Vers.</th>
                                <th class="border-0">Fecha</th>
                                <th class="border-0">Estado</th>
                                <th class="border-0">Acred.</th>
                                <th class="border-0">Venc.</th>
                                <th class="border-0">Total</th>
                                <th class="border-0">Resolución</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($historial as $h)
                                <tr>
                                    <th class="border-right font-weight-bolder text-primary bg-light">{{ $h->version }}</th>
                                    <td class="text-nowrap">{{ $h->fecha_cambio ? date('d/m/Y H:i', strtotime($h->fecha_cambio)) : '' }}</td>
                                    <td>{{ $h->estado ?? '' }}</td>
                                    <td class="text-nowrap">{{ $h->fecha_acreditacion ? date('d/m/Y', strtotime($h->fecha_acreditacion)) : '' }}</td>
                                    <td class="text-nowrap">{{ $h->fecha_vencimiento ? date('d/m/Y', strtotime($h->fecha_vencimiento)) : '' }}</td>
                                    <td>{{ $h->proc_total ?? '' }}</td>
                                    <td>{{ $h->resolucion_numero ? $h->resolucion_numero : '' }}{{ $h->resolucion_anio ? '/'.$h->resolucion_anio : '' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info border-0 shadow-sm mb-0" style="border-left: 4px solid #17a2b8;">
                        Esta acreditación todavía no tiene cambios registrados.
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal-footer border-0" style="background: #f7fbfc;">
        <button class="btn btn-light border px-4" type="button" data-dismiss="modal">Cerrar</button>
    </div>
</div>
