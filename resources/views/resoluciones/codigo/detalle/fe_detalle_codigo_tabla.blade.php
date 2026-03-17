@if(sizeof($detalle)<1)
    <div class="alert-danger border-danger rounded p-2 centrar_bloque">
        No hay datos para mostrar
    </div>
@else
    <table class="table table-sm">
        <tr class="text-dark bg-light">
            <th>Número</th>
            <th>Nombre</th>
            <th>Opciones</th>
        </tr>
            <?php $i=0;?>
        @foreach($detalle as $d)
            <tr>
                <td>{{$i+=1}}</td>
                <td>{{$d->det_nombre}}</td>
                <td>
                    <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#editarPlan" data-toggle="modal" onclick="cargarDatos('{{url("editar tema codigo/".$d->cod_det)}}','panel_editar_plan')"
                       title="Editar código"><i class="fas fa-edit"></i></a>
                    <a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-target="#editarPlan" data-toggle="modal" onclick="cargarDatos('{{url("f_eliminar tema codigo/".$d->cod_det)}}','panel_editar_plan')"
                       title="Eliminar código"><i class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
        @endforeach
    </table>
@endif
