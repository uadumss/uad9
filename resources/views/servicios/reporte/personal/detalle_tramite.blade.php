<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content border-bottom-primary shadow-lg">
        <input  type="hidden" name="ct" id="ct" value=""/>
        <div class="modal-header bg-verde-oscuro">
            <h5 class="modal-title text-white" id="exampleModalLabel"> <i class="fas fa-chart-line"></i>&nbsp;&nbsp;Reporte</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <form id="form_reporte">
            @csrf
            <div class="modal-body" style="font-size: 13px">
                <div class="bg-verde-oscuro centrar_bloque p-1 col-md-8 rounded shadow">
                    <h5 class="text-white text-center">Detalle del trámite</h5>
                </div>
                <br/>
                <span>
                    <span class="text-primary font-italic font-weight-bold">Nombre : </span><span class="text-dark font-italic font-weight-bold">{{$persona->per_nombre." ".$persona->per_apellido}}</span> |
                    <span class="text-primary font-italic font-weight-bold">Ci : </span><span class="text-dark font-italic font-weight-bold">{{$persona->per_ci}}</span>
                </span>
                <hr class="sidebar-divider"/>
                <div class="row">
                    <div class="col-md-4">
                        <span class="text-primary font-weight-bold font-italic">* Datos del trámite</span>
                        <table class="col-md-12">
                            <tr>
                                <th class="text-right font-italic">Trámite : </th>
                                <td class="border-bottom border-dark">{{$tramite->tre_nombre}}</td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Tipo de trámite : </th>
                                <td class="border-bottom border-dark">
                                    <span class="bg-danger text-white p-1 rounded font-italic font-weight-bold">{{\App\Models\Funciones::tipo_tramite($tramite->tre_tipo)}}</span>

                                </td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Fecha de solicitud : </th>
                                <td class="border-bottom border-dark">
                                    @if($tramita->tra_fecha_solicitud!='')
                                        {{date('d/m/Y',strtotime($tramita->tra_fecha_solicitud))}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Fecha de firma : </th>
                                <td class="border-bottom border-dark">
                                    @if($d_tramita->dtra_fecha_firma!='')
                                        {{date('d/m/Y',strtotime($d_tramita->dtra_fecha_firma))}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Fecha de entrega : </th>
                                <td class="border-bottom border-dark">
                                    @if($d_tramita->dtra_fecha_recojo!='')
                                        {{date('d/m/Y H:i:s',strtotime($d_tramita->dtra_fecha_recojo))}}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th class="text-right font-italic">Destino de trámite : </th>
                                <td class="border-bottom border-dark">
                                    @if($d_tramita->dtra_interno=='t')
                                        Interno
                                    @else
                                        Externo
                                    @endif
                                </td>
                            </tr>
                            @if($titulo)
                                <tr>
                                    <td class="text-primary font-italic font-weight-bold text-left">* Datos del titulo</td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Número de documento : </th>
                                    <td class="border-bottom border-dark">
                                        {{$titulo->tit_nro_titulo."/".$titulo->tit_gestion}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Tipo de documento : </th>
                                    <td class="border-bottom border-dark">
                                         {{\App\Models\Funciones::nombre_titulo($titulo->tit_tipo)}}
                                   </td>
                                </tr>
                            @endif

                            @if(sizeof($confrontacion)>0)
                                <tr>
                                    <td class="text-primary font-italic font-weight-bold text-left">* Datos de otros documentos</td>
                                </tr>
                                    <tr>
                                    <th class="text-right font-italic">Documentos : </th>
                                    <td class="border-bottom border-dark">
                                        @foreach($confrontacion as $c)
                                            @if($tramita->tra_tipo_tramite=='F')
                                                {{\App\Models\Funciones::nombre_documento($c->dcon_doc)}}<br/>
                                            @else
                                                {{$c->dcon_doc}}<br/>
                                            @endif

                                        @endforeach
                                    </td>
                                </tr>
                            @endif

                        </table>

                    </div>
                    <div class="col-md-8">
                        <div id="panel_bitacora">
                            <span class="text-danger font-italic font-weight-bold text-left">* Bitacora</span>
                            <table class="table-sm table col-md-12" >
                                <tr>
                                   <th>#</th>
                                    <th>Operación</th>
                                    <th>Antiguo</th>
                                    <th>Nuevo</th>
                                    <th>Fecha y hora</th>
                                    <th>Usuario</th>
                                </tr>
                                <?php $i=1;?>
                                @foreach($bitacora as $b)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{\App\Models\Funciones::operacion_bitacora($b->eve_operacion)}}</td>
                                        <td>{{$b->eve_antiguo}}</td>
                                        <td>{{json_encode($b->eve_nuevo)}}</td>
                                        <td>{{date('d/m/Y H:i:s',strtotime($b->created_at))}}</td>
                                        <td><span class="text-primary">* {{$b->bit_usuario}}</span></td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </form>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary btn-sm" type="submit" value="Aceptar">Generar PDF</button>
        </div>
    </div>
</div>
