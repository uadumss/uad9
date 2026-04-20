<div class="modal-dialog modal-lg" role="document" id="panel_escala_precios_noa">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white"><i class="fas fa-table"></i> Escala de precios No Atentado</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body" style="font-size: smaller">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                <span class="font-weight-bold text-primary">Rangos y montos vigentes</span>
                <small class="text-muted">Fuente: noatentado.escala_candidatos</small>
            </div>

            <div class="table-responsive border rounded" style="max-height: 420px; overflow:auto;">
                <table class="table table-sm table-striped table-hover mb-0" style="font-size: 12px;">
                    <thead class="thead-light">
                    <tr>
                        <th>N°</th>
                        <th>Rango candidatos</th>
                        <th>Costo (Bs)</th>
                        <th>Aporte UMSS (Bs)</th>
                        <th>Total (Bs)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($escalaCandidatosNoa as $i=>$filaEscala)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>
                                @if((int)($filaEscala['cantidad_min'] ?? 0)===(int)($filaEscala['cantidad_max'] ?? 0))
                                    {{ (int)($filaEscala['cantidad_max'] ?? 0) }}
                                @else
                                    {{ (int)($filaEscala['cantidad_min'] ?? 0) }} a {{ (int)($filaEscala['cantidad_max'] ?? 0) }}
                                @endif
                            </td>
                            <td>{{ number_format((float)($filaEscala['costo'] ?? 0),2,'.','') }}</td>
                            <td>{{ number_format((float)($filaEscala['aporte_umss'] ?? 0),2,'.','') }}</td>
                            <td class="font-weight-bold">{{ number_format((float)($filaEscala['monto_total'] ?? 0),2,'.','') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary">No existe escala de precios configurada en base de datos.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>
