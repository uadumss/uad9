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
<table class="col-md-12 table table-sm table-hover table-info rounded" style="font-size: 12px">
    <tr class="bg-gradient-info text-white p-2">
        <th>Nº</th>
        <th>Sitra</th>
        <th>Nombre</th>
        <th>N° trámite</th>
        <th>N° Documento</th>
        <th>Opciones</th>
    </tr>
    <?php $i=1?>
    @foreach($detalle_apostilla as $d)
        <tr>
            <td>{{$i}}</td>
            <td></td>
            <td>{{$d->lis_nombre}}</td>
            <td>{{$d->dapo_numero}}</td>
            <td>{{$d->dapo_numero_documento."/".$d->dapo_gestion_documento}}</td>
            <td>
                @can('quitar doumento - apo')
                    @if($tramite_apostilla->apos_estado<=1)
                        <a href="#" class="btn btn-light btn-circle btn-sm text-dark"
                           onclick="cargarDatos('{{url("eliminar tramite agregado apostilla/$d->cod_dapo")}}','panel_lista_tramites_apostilla');cargarDatos('{{url("listar tramite apostilla tabla/".$fecha)}}','panel_tabla_tramites')"
                           title="Eliminar trámite"> <i class="fas fa-trash-alt"></i>
                        </a>
                    @else
                        <i class="fas fa-minus"></i>
                    @endif
                @endcan
            </td>
        </tr>
            <?php $i+=1?>
    @endforeach
</table>

