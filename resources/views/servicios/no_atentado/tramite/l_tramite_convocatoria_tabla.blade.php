<div>
    <div class="table-responsive">
        <div id="panel_tabla_tramites" class="col-md-12">
            <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: smaller">
                <thead>
                <tr class="bg-gray-600 text-white" style="font-size: 0.9em">
                    <th>Nº</th>
                    <th class="text-left">Nro. Trámite</th>
                    <th>Trámite</th>
                    <th>Nombres</th>
                    <th class="text-left">Fecha solicitud</th>
                    <th class="text-center">Opciones</th>
                    <th class="text-center">Entrega</th>
                </tr>
                </thead>
                <tbody id="cuerpo">
                <?php $i=1;?>
                @foreach($tramites as $t)
                    <tr>
                        <th class="border-right font-weight-bolder">{{$i}}</th>
                        <td class="text-right"><span class="text-primary font-weight-bold">{{$t->dtra_numero_tramite}}</span>/{{$t->dtra_gestion_tramite}}</td>
                        <td class="">{{$t->tre_nombre}}</td>
                        <td><span class="font-weight-bold text-dark" style="font-size: 12px; font-family: 'Times New Roman'">
                                                        <?php echo \App\Http\Controllers\Noatentado\TramiteNoAtentadoController::listaCandidatos($t->cod_dtra)?>
                                                    </span>
                        </td>
                        <td class="text-right">{{date('d/m/Y',strtotime($t->dtra_fecha_registro))}}</td>
                        <td class="text-right">
                            @can('editar tramite - noa')
                                <a href="#Noatentado" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" data-target="#Noatentado"
                                   onclick="cargarDatos('{{url("editar tramite convocatoria/".$t->cod_con."/".$t->cod_dtra)}}','panel_noatentado')"
                                   title="Editar trámite"><i class="fas fa-edit"></i>
                                </a>
                            @endcan

                            @if($t->dtra_generado!='t')
                                @can('generar glosa - noa')
                                    <a href="#Noatentado" onclick="cargarDatos('{{url("formulario glosa noatentado/$t->cod_dtra")}}','panel_noatentado')"
                                       class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal"
                                       title="Generar glosa"> <i class="fas fa-file-pdf"></i>
                                    </a>
                                @endcan
                            @else
                                @can('imprimir pdf - noa')
                                    <a href="{{url('generar pdf noatentado/'.$t->cod_dtra)}}" target="No-atentado"
                                       class="btn btn-primary btn-sm btn-circle btn-light text-danger" title="Generar glosa"> <i class="fas fa-file-pdf"></i>
                                    </a>
                                @endcan
                            @endif
                                @can('rehacer tramite - noa')
                                    <a href="#" class="btn btn-light btn-circle btn-sm text-info" data-target="#Noatentado" data-toggle="modal" onclick="cargarDatos('{{url("formulario corregir tramite noatentado/$t->cod_dtra")}}','panel_noatentado')" title="Corregir tramite">
                                        <i class="fas fa-arrow-circle-left"></i>
                                    </a>
                                @endcan
                            @can('eliminar tramite - noa')
                                <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#Noatentado" data-toggle="modal" onclick="cargarDatos('{{url("formulario eliminar tramite noatentado/$t->cod_dtra")}}','panel_noatentado')"
                                   title="Eliminar trámite"> <i class="fas fa-trash-alt"></i>
                                </a>
                            @endcan

                        </td>
                        <td class="text-right">
                            @if($t->dtra_entregado=='a' && $t->dtra_entregado!='t' && $t->dtra_entregado!='c')
                                <span class="border-danger rounded text-success"><i class="fas fa-check"></i></span>
                                <span class="font-weight-bold text-success font-italic">Apoderado </span>
                            @else
                                @if($t->dtra_entregado=='c')
                                    <span class="border-danger rounded text-success"><i class="fas fa-check"></i></span>
                                    <span class="font-weight-bold text-success font-italic">Apoderado </span>
                                @else
                                    @if($t->dtra_entregado=='t')
                                        <span class="border-danger rounded text-success"><i class="fas fa-check"></i></span>
                                    @endif
                                @endif
                            @endif
                        </td>
                    </tr>
                        <?php $i++;?>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
