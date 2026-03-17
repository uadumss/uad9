<div>
    <div class="table-responsive">
        <div id="panel_tabla_tramites" class="col-md-12">
            <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: smaller">
                <thead>
                <tr class="bg-gray-600 text-white" style="font-size: 0.9em">
                    <th>Nº</th>
                    <th class="text-left">Nombre</th>
                    <th class="text-right">CI</th>
                    <th class="text-left">Referencia</th>
                    <th class="text-center">Sentencia</th>
                    <th class="text-center">Resolucion</th>
                    <th class="text-center">PDF</th>
                    <th>Opciones</th>
                </tr>
                </thead>
                <tbody id="cuerpo">
                <?php $i=1;?>
                @foreach($sancionados as $s)
                    <tr>
                        <th class="border-right font-weight-bolder">{{$i}}</th>
                        <td>{{$s->per_apellido.' '.$s->per_nombre}}</td>
                        <td>{{$s->per_ci}}</td>
                        <td>{{$s->san_referencia}}</td>
                        <td>{{$s->san_sentencia}}</td>
                        <td>{{$s->san_resolucion}}</td>
                        <td>
                            @if($s->res_pdf!='')
                                <a class="text-danger" style="font-size: 20px">
                                    <a href="" class="btn btn-circle btn-light btn-sm text-danger float-right border" data-toggle="modal" data-target="#Modal"
                                       onclick="cargarDatos('{{url('ver datos resolucion/'.$s->cod_res)}}','panel_modal')" title="Ver detalle de la resolución"> <i class="fas fa-file-pdf"></i>
                                    </a>
                                </a>
                            @endif
                        </td>
                        <td class="text-right">
                            @can('editar sancionado - noa')
                                <a href="#Noatentado" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" data-target="#Modal"
                                   onclick="cargarDatos('{{url("editar sancionado/".$s->cod_san)}}','panel_modal')"
                                   title="Editar sancionado"><i class="fas fa-edit"></i>
                                </a>
                            @endcan

                            <a href="#Noatentado" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" data-target="#Modal"
                               onclick="cargarDatos('{{url("lista detalle sancion noatentado/".$s->cod_san)}}','panel_modal')"
                               title="Ver detalle de sanción"><i class="fas fa-align-justify"></i>
                            </a>
                            @can('eliminar sancionado - noa')
                                <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#Modal" data-toggle="modal" onclick="cargarDatos('{{url("formulario eliminar sancionado noatentado/".$s->cod_san)}}','panel_modal')"
                                   title="Eliminar sancionado"> <i class="fas fa-trash-alt"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                        <?php $i++;?>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
