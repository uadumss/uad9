@if(Session::has('exitoagregar'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <span class="font-weight-bold">{!! session('exitoagregar') !!}</span>
    </div>
@endif
@if(Session::has('erroragregar'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <span class="font-weight-bold text-dark">{!! session('erroragregar') !!}</span>
    </div>
@endif
<table class="table table-sm table-hover table-info rounded apo-table-responsive-text">
    <tr class="bg-gradient-info text-white p-2">
        <th>Nº</th>
        <th>Nombre</th>
        <th>N° trámite</th>
        <th>N° Documento</th>
        <th>Valorado</th>
        <th>SITRA</th>
        <th>Opciones</th>
    </tr>
    <?php $i=1?>
    @foreach($detalle_apostilla as $d)
        <tr>
            <td>{{$i}}</td>
            <td>{{$d->lis_nombre}}</td>
            <td>{{$d->dapo_numero}}</td>
            <td><span class="font-weight-bolder">{{$d->dapo_numero_documento}}</span>{{" / ".$d->dapo_gestion_documento}}</td>
            <td class="bg-gray-200 text-right"><span class="font-weight-bolder">{{$d->dapo_valorado_preimpreso}}</span>{{" / ".$d->dapo_valorado_gestion}}</td>
            <td>
                @if(($d->dapo_verificacion_sitra ?? '') === '0')
                    <a href="#" class="btn btn-light btn-circle btn-sm text-success"
                       data-target="#docleg" data-toggle="modal"
                       data-url="{{ url('verificacion sitra apostilla/' . $d->cod_dapo) }}"
                       onclick="cargarDatos(this.dataset.url,'panel_docleg');$('#docleg').modal('show');return false;"
                       title="Coincide en SITRA/SID"><i class="fas fa-check-circle"></i></a>
                @elseif(($d->dapo_verificacion_sitra ?? '') === '1' || ($d->dapo_verificacion_sitra ?? '') === '2')
                    <a href="#" class="btn btn-light btn-circle btn-sm text-danger"
                       data-target="#docleg" data-toggle="modal"
                       data-url="{{ url('verificacion sitra apostilla/' . $d->cod_dapo) }}"
                       onclick="cargarDatos(this.dataset.url,'panel_docleg');$('#docleg').modal('show');return false;"
                       title="No coincide o no existe en SITRA/SID"><i class="fas fa-times-circle"></i></a>
                @else
                    <span class="btn btn-light btn-circle btn-sm text-secondary" title="SITRA/SID pendiente"><i class="fas fa-minus-circle"></i></span>
                @endif
            </td>
            <td>
                @can('quitar doumento - apo')
                    @if($tramite_apostilla->apos_estado<=1)
                        <a href="#" class="btn btn-light btn-circle btn-sm text-dark"
                           onclick="cargarDatos('{{url("eliminar tramite agregado apostilla/$d->cod_dapo")}}','panel_lista_tramites_apostilla');cargarDatos('{{url("listar tramite apostilla tabla/".date('Y-m-d',strtotime($tramite_apostilla->apos_fecha_ingreso)))}}','panel_tabla_tramites')"
                           title="Eliminar trámite"> <i class="fas fa-trash-alt"></i>
                        </a>
                    @else
                        <i class="fas fa"></i>
                    @endif
                @endcan
            </td>
        </tr>
            <?php $i+=1?>
    @endforeach
</table>

