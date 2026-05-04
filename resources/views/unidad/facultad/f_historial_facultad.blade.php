<div class="modal-content border-0 shadow-lg overflow-hidden">
    <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f4c5c 0%, #1d6f8a 48%, #3aa6b9 100%);">
        <div>
            <h5 class="modal-title font-weight-bolder mb-0" id="exampleModalLabel">
                <i class="fas fa-history mr-1"></i> Historial de cambios de facultad
            </h5>
            <small class="text-white-50">Registro de modificaciones de nombre y abreviación</small>
        </div>
        <button class="close text-white opacity-100" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body p-0" style="background: linear-gradient(180deg, #f7fbfc 0%, #eef7fa 100%);">
        <div class="p-3 p-md-4">
            <div class="alert alert-light border shadow-sm d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div>
                    <div class="text-secondary text-uppercase font-weight-bold" style="letter-spacing: .04em; font-size: .75rem;">Facultad actual</div>
                    <div class="font-weight-bold text-dark">{{$facultad->fac_nombre}}</div>
                </div>
                <span class="badge px-3 py-2" style="background: #dbeafe; color: #1d4ed8;">{{$historial->count()}} cambios</span>
            </div>

            @if(sizeof($historial) > 0)
                <div class="table-responsive shadow-sm rounded-lg border bg-white">
                    <table class="table table-sm table-hover mb-0" width="100%" cellspacing="0" style="font-size: 0.85em">
                        <thead style="background: linear-gradient(135deg, #e0fbfc 0%, #cdeffd 100%);">
                        <tr class="text-secondary">
                            <th class="border-0">Nº</th>
                            <th class="border-0">Nombre anterior</th>
                            <th class="border-0">Nombre nuevo</th>
                            <th class="border-0">Nombre corto anterior</th>
                            <th class="border-0">Nombre corto nuevo</th>
                            <th class="border-0">Fecha de cambio</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; ?>
                        @foreach($historial as $h)
                            <tr>
                                <th class="border-right font-weight-bolder text-primary bg-light">{{$i}}</th>
                                <td class="text-left">{{$h->nombre_anterior}}</td>
                                <td class="text-left font-weight-bold text-dark">{{$h->nombre_nuevo}}</td>
                                <td class="text-left">{{$h->abreviacion_anterior ?? '-'}}</td>
                                <td class="text-left font-weight-bold text-dark">{{$h->abreviacion_nueva ?? '-'}}</td>
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
                    Esta facultad no tiene cambios de nombre o nombre corto registrados.
                </div>
            @endif
        </div>
    </div>
    <div class="modal-footer border-0" style="background: #f7fbfc;">
        <button class="btn btn-light border px-4" type="button" data-dismiss="modal">Cerrar</button>
    </div>
</div>
