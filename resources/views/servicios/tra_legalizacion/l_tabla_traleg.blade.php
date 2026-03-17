
                        <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: smaller">
                            <thead>
                            <tr class="bg-gray-600 text-white">
                                <th>Nº</th>
                                <td>Tipo</td>
                                <th class="text-left">Número</th>
                                <th class="text-left">CI</th>
                                <th class="text-left">Nombre</th>
                                <th class="text-left">Fecha solicitud</th>
                                <th class="text-left">Fecha de firma</th>
                                <th class="text-right">Fecha de recojo</th>
                                <th class="text-right">Opciones</th>
                                <th class="text-center">Entrega</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=1;?>
                            @foreach($tramitas as $t)
                                @if($t->tra_anulado=='t')
                                    <tr class="alert-danger">
                                @else
                                    <tr>
                                        @endif
                                        <th class="border-right font-weight-bolder">
                                            <span class="text-primary">{{$i}}</span>
                                        </th>
                                        <td>
                                            @php   $tipo_tramite['L']='LEGALIZACIÓN'; $tipo_tramite['LC']='bg-info text-white';
                                                    $tipo_tramite['F']='CONFRONTACIÓN';$tipo_tramite['FC']='bg-danger text-white';
                                                    $tipo_tramite['C']='CERTIFICACIÓN';$tipo_tramite['CC']='bg-warning text-dark';
                                                    $tipo_tramite['B']='BÚSQUEDA';$tipo_tramite['BC']='bg-success text-white';
                                                    $tipo_tramite['E']='CONSEJO';$tipo_tramite['EC']='bg-secondary text-white';
                                            @endphp
                                            <span class="font-weight-bold rounded pl-2 pr-2 {{$tipo_tramite[$t->tra_tipo_tramite.'C']}}" style="font-size: 0.75em">
                                                {{$tipo_tramite[$t->tra_tipo_tramite]}}
                                            </span>
                                            @if($t->tra_obs=='t')
                                                &nbsp;<i class="fas fa-info-circle text-danger"></i>
                                            @endif
                                        </td>
                                        <td class="text-left">{{$t->tra_numero}}</td>
                                        <td class="text-left">{{$t->per_ci}}</td>
                                        <td class="text-left">{{$t->per_apellido." ".$t->per_nombre}}
                                            @if($t->tra_tipo_apoderado=='p')
                                                <span class="text-white bg-danger rounded" style="font-size: 0.7em"> &nbsp;Pod&nbsp; </span>
                                            @else
                                                @if($t->tra_tipo_apoderado=='d')
                                                    <span class="text-white bg-success rounded" style="font-size: 0.7em"> &nbsp;Dec&nbsp; </span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-right">{{date('d/m/Y',strtotime($t->tra_fecha_solicitud))}}</td>
                                        <td class="text-right">@php if($t->tra_fecha_firma!=''){echo date('d/m/Y',strtotime($t->tra_fecha_firma));} @endphp </td>
                                        <td class="text-right">@php if($t->tra_fecha_recojo!=''){echo date('d/m/Y',strtotime($t->tra_fecha_recojo));} @endphp </td>
                                        <td class="text-right">
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#traleg" data-toggle="modal" onclick="cargarDatos('{{url("datos tramite legalizacion/".$t->cod_tra)}}','panel_traleg')"
                                               title="Editar legalización"><i class="fas fa-pen-alt"></i>
                                            </a>

                                            <a href="#traleg" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" onclick="cargarDatos('{{url("f_cambiar tipo tramite/$t->cod_tra")}}','panel_traleg')"
                                               title="Cambiar tipo de trámite"><i class="fas fa-arrows-alt"></i>
                                            </a>

                                            @can('eliminar traleg - srv')
                                            <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#traleg" data-toggle="modal" onclick="cargarDatos('{{url("f_eli_tra_legalizacion/".$t->cod_tra)}}','panel_traleg')"
                                               title="Eliminar trámite"> <i class="fas fa-trash-alt"></i>
                                            </a>
                                            @endcan

                                        </td>
                                        <td class="text-right">
                                            @if($t->id_per!='')
                                                <a class="btn btn-light btn-circle btn-sm text-success" data-target="#traleg" data-toggle="modal" onclick="cargarDatos('{{url("panel entrega legalizacion/".$t->cod_tra)}}','panel_traleg')"
                                                   title="Entregar legalizaciones"> <i class="fas fa-hand-point-right"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                    <?php $i++;?>
                                    @endforeach
                            </tbody>
                        </table>
                        <script src="{{url('vendor/datatables/jquery.dataTables.min.js')}}"></script>
                        <script src="{{url('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
                        <!-- Page level custom scripts -->
                        <script src="{{url('js/demo/datatables-demo.js')}}"></script>

                        <script>
                            $('#dataTable').dataTable( {
                                "pageLength": 500
                            });
                        </script>
