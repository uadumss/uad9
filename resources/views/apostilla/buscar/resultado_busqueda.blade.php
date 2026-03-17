
@if(sizeof($resultado)==0)
    <br/><br/>
    <div class="alert-danger border p-2 rounded col-md-8 text-center centrar_bloque font-italic font-weight-bold" style="font-size: 16px">
        No hay datos para mostrar
    </div>
@else
    <div style="height: 500px" class="overflow-auto">
        <table class="table table-sm">
            <tr>
                <th>N°</th>
                <th>Nombre</th>
                <th>CI</th>
                <th>N° Trámite</th>
                <th>Fecha ingreso</th>
                <th>Opciones</th>
            </tr>
            <tbody>
            <?php $i=0?>
                @foreach($resultado as $r)
                    <tr>
                        <td>{{$i+=1}}</td>
                        <td>{{$r->per_nombre." ".$r->per_apellido}}</td>
                        <td>{{$r->per_ci}}</td>
                        <td>{{$r->apos_numero."/".$r->apos_gestion}}</td>
                        <td>{{date('d/m/Y',strtotime($r->apos_fecha_ingreso))}}</td>
                        <td><a class="btn btn-sm btn-light btn-circle text-success" data-toggle="modal" data-target="#tramite_apostilla" title="Ver tramite de apostilla"
                               onclick="cargarDatos('{{url('ver datos apostilla/'.$r->cod_apos)}}','panel_tramite_apostilla')">
                                <i class="fas fa-hand-point-right"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

