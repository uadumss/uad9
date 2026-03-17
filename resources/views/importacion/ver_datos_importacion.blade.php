<div class="overflow-auto" style="height: 600px">
    <br/>
    <span style="font-size: 0.9em" class="font-italic">
        <span class="font-weight-bold">Nº Importación :</span> <span>{{$importacion->cod_imp}}</span> |
        <span class="font-weight-bold">Fecha :</span> <span>{{$importacion->imp_fecha}}</span> |
        <span class="font-weight-bold">Tipo :</span> <span>{{$importacion->imp_tipo}}</span> |
        <span class="font-weight-bold">Revocado :</span> <span>@if($importacion->imp_deshecho=='t') Si @else No @endif </span> |
        <span class="font-weight-bold">Identificador :</span> <span>{{$importacion->imp_identificador}}</span> |
        <span class="font-weight-bold">Usuario :</span> <span>{{$importacion->imp_usuario}}</span>

    </span>

    <hr class="sidebar-divider"/>
    <table class="table table-hover table-sm">
        <tr class="bg-light">
            <th>Nro.</th>
            <th>Nombre</th>
            <th>CI</th>
            <th>Título</th>
            <th>Fecha</th>
            <th>Tomo</th>
            <th>Gestión</th>
            <th>tipo</th>
            <th>Importado</th>
        </tr>
        <?php $i=1;?>
        <tbody>
        @foreach($titulosExcel[0] as $t)

            <tr style="font-size: 0.9em">
                <td>{{$i}}</td>
                <td>{{$t['apellido'].', '.$t['nombre']}}</td>
                <td>{{$t['ci']}}</td>
                <td>{{$t['numero']}}</td>
                <td>{{$t['fecha']}}</td>
                <td>{{$t['tomo']}}</td>
                <td>{{$t['ano']}}</td>
                <td>{{$t['tipo']}}</td>
                @if($t['importado']==0)
                    <td class="text-danger font-weight-bold font-italic">No importado</td>
                @else
                    <td class="text-success font-italic font-weight-bold">Si</td>
                @endif
            </tr>
            <?php $i++;?>
        @endforeach
        </tbody>

    </table>
</div>
