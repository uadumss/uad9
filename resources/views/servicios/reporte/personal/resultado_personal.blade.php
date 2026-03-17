<div>
    <h4 class="text-center text-primary">Resultado de la consulta</h4>
    <div>
        @if(sizeof($resultado)==0)
            <span class="text-danger font-weight-bold font-italic">* No se encontraron resultados</span>
        @endif
        @if($uno==1)
            <div>
                <table class="table table-sm" style="font-size: 12px">
                    <tr class="bg-verde-oscuro text-white">
                        <th>#</th>
                        <th>Trámite</th>
                        <th>#Trámite</th>
                        <th>Tipo</th>
                        <th>Fecha de ingreso</th>
                        <th>Fecha recojo</th>
                        <th>Opciones</th>
                    </tr>
                    <?php $i=1;?>
                    @foreach($resultado as $r)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$r->tre_nombre}}</td>
                            <td>{{$r->dtra_numero_tramite."/".$r->dtra_gestion_tramite}}</td>
                            <td><span class="bg-danger text-white pl-1 pr-1 rounded font-italic font-weight-bold">{{\App\Models\Funciones::tipo_tramite($r->dtra_tipo)}}</span></td>
                            <td>{{date('d/m/Y', strtotime($r->tra_fecha_solicitud))}}</td>
                            <td>
                                @if($r->dtra_fecha_recojo!='')
                                    {{date('d/m/Y',strtotime($r->dtra_fecha_recojo))}}
                                @endif
                            </td>
                            <td>
                                @if($r->dtra_entregado!='')
                                    <a class="btn btn-sm btn-light btn-circle text-primary" data-toggle="modal" data-target="#detalle"
                                       onclick="cargarDatos('{{url('detalle tramite reporte/'.$r->cod_dtra)}}','panel_detalle')">
                                        <i class="fas fas fa-arrow-alt-circle-right"></i>
                                    </a>
                                    @can('imprimir legalizacion docleg - srv')
                                        <a href="{{url('generar pdf/'.$r->cod_dtra)}}" class="btn btn-light btn-sm" target="{{rand(1,20)}}" title="Ver PDF"><i class="text-dark fas fa-file-pdf" ></i></a>
                                    @endcan
                                @else
                                    @if($r->dtra_falso=='t')
                                        <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#detalle" data-toggle="modal" onclick="cargarDatos('{{url("obs_docleg/".$r->cod_dtra)}}','panel_detalle')"
                                           title="Observado"> <i class="fas fa-eye text-danger"></i></a>

                                        <span class="text-danger">Observado</span>

                                    @else
                                        @if($r->dtra_generado=='t')
                                            <span class="text-success">Por entregar</span>
                                            <a class="btn btn-sm btn-light btn-circle text-primary" data-toggle="modal" data-target="#detalle" title="Mostrar trámite"
                                               onclick="cargarDatos('{{url('detalle tramite reporte/'.$r->cod_dtra)}}','panel_detalle')">
                                                <i class="fas fas fa-arrow-alt-circle-right"></i>
                                            </a>
                                            @can('imprimir legalizacion docleg - srv')
                                                <a href="{{url('generar pdf/'.$r->cod_dtra)}}" class="btn btn-light btn-sm" target="{{rand(1,20)}}" title="Ver PDF"><i class="text-dark fas fa-file-pdf" ></i></a>
                                            @endcan
                                        @else
                                            <span class="text-primary">En proceso...</span>
                                        @endif
                                    @endif

                                @endif
                            </td>
                        </tr>
                        <?php $i++;?>
                    @endforeach
                </table>
            </div>
        @else
            <div>
                <span class="text-danger font-italic font-weight-bold">* Coincidencias encontradas, seleccione una persona</span>
                <table class="table table-sm" style="font-size: 12px">
                    <tr class="bg-dark text-white">
                        <th>#</th>
                        <th>Nombre</th>
                        <th>CI</th>
                        <th>Opciones</th>
                    </tr>
                    <?php $i=1;?>
                    @foreach($resultado as $r)
                        <tr>
                            <td>{{$i}}

                            </td>
                            <td>{{$r->per_nombre." ".$r->per_apellido}}</td>
                            <td>{{$r->per_ci}}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-light btn-circle text-primary" onclick="enviar('persona{{$i}}','{{url("reporte servicios personal")}}','panel_resultado')"
                                   title="Ver trámites"><i class="fas fa-hand-point-right"></i></a>
                                <form id="persona{{$i}}">
                                    @csrf
                                    <input type="hidden" name="ip" value="{{$r->id_per}}"/>
                                    <input type="hidden" name="ci" value="-"/>
                                </form>
                            </td>
                        </tr>
                        <?php $i++;?>
                    @endforeach
                </table>
            </div>
        @endif
    </div>
</div>
<script>

</script>
