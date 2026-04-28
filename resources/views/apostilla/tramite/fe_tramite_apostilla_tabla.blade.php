<table class="e-tbl">
    <thead>
        <tr>
            <th class="td-num">#</th>
            <th>Nombre</th>
            <th>N° trámite</th>
            <th>N° Documento&nbsp;/&nbsp;Gestión</th>
            <th>Valorado&nbsp;/&nbsp;Gestión</th>
            <th style="width:90px;">SITRA</th>
            <th style="width:36px;"></th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        @foreach($detalle_apostilla as $d)
            <tr>
                <td class="td-num">{{ $i }}</td>
                <td class="td-main" style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                    title="{{ $d->lis_nombre }}">{{ $d->lis_nombre }}</td>
                <td>{{ $d->dapo_numero }}</td>
                <td>
                    <span class="bold">{{ $d->dapo_numero_documento }}</span>
                    <span class="td-sub">{{ $d->dapo_gestion_documento }}</span>
                </td>
                <td>
                    <span class="bold">{{ $d->dapo_valorado_preimpreso }}</span>
                    <span class="td-sub">{{ $d->dapo_valorado_gestion }}</span>
                </td>
                <td class="center">
                    @if(($d->dapo_verificacion_sitra ?? '') === '0')
                        <a href="#" class="e-pill ok"
                           data-target="#docleg" data-toggle="modal"
                           data-url="{{ url('verificacion sitra apostilla/' . $d->cod_dapo) }}"
                           onclick="cargarDatos(this.dataset.url,'panel_docleg');$('#docleg').modal('show');return false;"
                           style="text-decoration:none;" title="Coincide en SITRA">
                            <i class="fas fa-check-circle" style="font-size:12px;"></i>
                        </a>
                    @elseif(($d->dapo_verificacion_sitra ?? '') === '1' || ($d->dapo_verificacion_sitra ?? '') === '2')
                        <a href="#" class="e-pill err"
                           data-target="#docleg" data-toggle="modal"
                           data-url="{{ url('verificacion sitra apostilla/' . $d->cod_dapo) }}"
                           onclick="cargarDatos(this.dataset.url,'panel_docleg');$('#docleg').modal('show');return false;"
                           style="text-decoration:none;" title="No coincide / no existe">
                            <i class="fas fa-times-circle" style="font-size:12px;"></i>
                        </a>
                    @else
                        <span class="e-pill idle" style="cursor:default;" title="Sin verificación">
                            <i class="fas fa-minus-circle" style="font-size:12px;"></i>
                        </span>
                    @endif
                </td>
                <td class="center">
                    @can('quitar doumento - apo')
                        @if($tramite_apostilla->apos_estado <= 1)
                            <a href="#" class="e-icon-del"
                               onclick="cargarDatos('{{ url('eliminar tramite agregado apostilla/' . $d->cod_dapo) }}','panel_lista_tramites_apostilla');cargarDatos('{{ url('listar tramite apostilla tabla/' . date('Y-m-d', strtotime($tramite_apostilla->apos_fecha_ingreso))) }}','panel_tabla_tramites')"
                               title="Eliminar trámite">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        @endif
                    @endcan
                </td>
            </tr>
            <?php $i++; ?>
        @endforeach
        @if(count($detalle_apostilla) === 0)
            <tr>
                <td colspan="7" style="text-align:center;padding:16px;color:var(--e-s400);font-size:12px;">
                    Sin registros agregados
                </td>
            </tr>
        @endif
    </tbody>
</table>
