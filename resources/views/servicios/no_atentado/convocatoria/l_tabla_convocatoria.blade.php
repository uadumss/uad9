<table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: smaller">
    <thead>
    <tr class="bg-gray-600 text-white" style="font-size: 0.9em">
        <th>N°</th>
        <th>Título</th>
        <th>Publicación</th>
        <th>Entrega de Documentos</th>
        <th>Gestión</th>
        <th>Opciones</th>
    </tr>
    </thead>
    <?php $i = 0;?>
    <tbody>
    @foreach($convocatorias as $c)
        <tr>
            <td>{{$i+=1}}</td>
            <td>{{$c->con_nombre}} </td>
            <td>@if($c->con_fecha_publicacion!='')
                    {{date('d/m/Y',strtotime($c->con_fecha_publicacion))}}
                @endif
            </td>
            <td>@if($c->con_fecha_entrega!='')
                {{date('d/m/Y',strtotime($c->con_fecha_entrega))}}
            @endif

            <td>{{$c->con_gestion}}</td>
            <td class="text-right">
                @can('editar convocatoria - noa')
                    <a href="" class="btn btn-sm btn-light btn-circle" data-toggle="modal" title="Editar convocatoria" data-target="#modal_convocatoria"
                       onclick="cargarDatos('{{url("editar convocatoria noatentado/".$c->cod_con)}}','panel_convocatoria')">
                        <i class="fas fa-edit text-primary"></i>
                    </a>
                @endcan
                @if($c->con_pdf!='')
                    <a href="{{url("PDF_convocatoria/".$c->cod_con)}}" class="btn btn-sm btn-light btn-circle" title="Descargar convocatoria"
                       data-target="#modal_noAtentado" target="_blank">
                        <i class="far fa-file-pdf"></i>
                    </a>
                @else
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                @endif
                @can('eliminar convocatoria - noa')
                    <a href="" class="btn btn-sm btn-light btn-circle" data-toggle="modal" title="Eliminar onvocatoria"
                       data-target="#modal_agregar" onclick="cargarDatos('{{url("formulario eliminar convocatoria noatentado/".$c->cod_con)}}','panel_agregar')">
                        <i class="fas fa-trash-alt text-danger"></i>
                    </a>
                @endcan
                &nbsp;&nbsp;&nbsp;
                <a href="{{url('listar tramite convocatoria/'.$c->cod_con)}}" class="btn btn-sm btn-light btn-circle" title="Mostrar trámites">
                    <i class="fas fa-arrow-circle-right text-primary"></i>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
