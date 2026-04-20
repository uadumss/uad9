<div class="modal-content border-bottom-info">
    <div class="modal-header bg-info">
        <h5 class="modal-title text-white" id="exampleModalLabel">
            <i class="fas fa-history"></i>&nbsp;Historial de cambios de facultad
        </h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="alert alert-light border">
            Facultad actual:
            <span class="font-weight-bold text-dark">{{$facultad->fac_nombre}}</span>
        </div>

        @if(sizeof($historial) > 0)
            <div class="table-responsive">
                <table class="table table-sm table-hover" width="100%" cellspacing="0" style="font-size: 0.85em">
                    <thead>
                    <tr class="bg-gray-600 text-white">
                        <th>Nº</th>
                        <th>Nombre anterior</th>
                        <th>Nombre nuevo</th>
                        <th>Nombre corto anterior</th>
                        <th>Nombre corto nuevo</th>
                        <th>Fecha de cambio</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1; ?>
                    @foreach($historial as $h)
                        <tr>
                            <th class="border-right font-weight-bolder text-primary">{{$i}}</th>
                            <td class="text-left">{{$h->nombre_anterior}}</td>
                            <td class="text-left">{{$h->nombre_nuevo}}</td>
                            <td class="text-left">{{$h->abreviacion_anterior ?? '-'}}</td>
                            <td class="text-left">{{$h->abreviacion_nueva ?? '-'}}</td>
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
                Esta facultad no tiene cambios de nombre o nombre corto registrados.
            </div>
        @endif
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
    </div>
</div>
