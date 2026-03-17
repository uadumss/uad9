<?php //$fecha=date('Y-m-d',strtotime($apostilla->apos_fecha_ingreso))?>
<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-verde-oscuro">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Apostilla </h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body" style="font-size: smaller">
        <div class="bg-verde-oscuro centrar_bloque p-1 col-md-7 rounded shadow">
            <h6 class="text-white text-center">Detalle de la busqueda</h6>
        </div>
        <hr class="sidebar-divider"/>
        <div class="row">
            <div class="col-md-4">
                        <table class="col-md-12 text-dark table table-sm">
                            <tr>
                                <th colspan="2" class="text-right text-primary"><br/>* DATOS PERSONALES</th>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">CI : </th>
                                <td class="border-bottom border-dark">{{$persona->per_ci}}</td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Nombre : </th>
                                <td class="border-bottom border-dark">{{$persona->per_nombre." ".$persona->per_apellido}}</td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Telefono celular : </th>
                                <td class="border-bottom border-dark">{{$persona->per_celular}}</td>
                            </tr>

                            <tr>
                                <th colspan="2" class="text-right text-primary">* DATOS DEL TRÁMITE</th>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">N° Trámite: </th>
                                <td class="border-bottom border-dark">{{$apostilla->apos_numero."/".$apostilla->apos_gestion}}</td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Fecha de ingreso : </th>
                                <td class="border-bottom border-dark">{{date('d/m/Y',strtotime($apostilla->apos_fecha_ingreso))}}</td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Fecha de firma : </th>
                                <td class="border-bottom border-dark">
                                    @if($apostilla->apos_fecha_firma!='')
                                        {{date('d/m/Y',strtotime($apostilla->apos_fecha_firma))}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Fecha de recojo : </th>
                                <td class="border-bottom border-dark">
                                    @if($apostilla->apos_fecha_recojo!='')
                                        {{date('d/m/Y',strtotime($apostilla->apos_fecha_recojo))}}</td>
                                    @endif
                            </tr>
                            <tr>
                                <th class="text-right font-italic text-danger">Entregado a : </th>
                                @if($apostilla->apos_entregado=='T')
                                    <td class="border-bottom border-dark font-weight-bold">{{$persona->per_nombre." ".$persona->per_apellido}}</td>
                                @else
                                    @if($apostilla->apos_entregado=='A')
                                        @if($apostilla->apos_apoderado=='d')
                                            <td class="border-bottom border-dark font-weight-bold">{{$apoderado->apo_nombre." ".$apoderado->apo_apellido}}
                                                <br/><span class="text-white rounded border bg-danger p1 font-weight-bold font-italic">&nbsp;Declaración Jurada&nbsp;</span>
                                            </td>

                                        @else
                                            <td class="border-bottom border-dark font-weight-bold">{{$apoderado->apo_nombre." ".$apoderado->apo_apellido}}
                                                <br/><span class="text-white rounded border bg-success p1 font-weight-bold font-italic">&nbsp;Poder notariado&nbsp;</span>
                                            </td>

                                      @endif

                                    @endif
                                @endif
                            </tr>
                            @if($apoderado)
                                <tr>
                                    <th colspan="2" class="text-right text-primary">* DATOS DEL APODERADO</th>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">CI apoderado: </th>
                                    <td class="border-bottom border-dark">{{$apoderado->apo_ci}}</td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Nombre : </th>
                                    <td class="border-bottom border-dark">{{$apoderado->apo_nombre." ".$apoderado->apo_apellido}}</td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic" valign="top">Tipo de apoderado : </th>
                                    <td class="border-bottom border-dark">

                                        @if($apostilla->apos_apoderado=='d')
                                            &nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" checked> Declaración jurada<br/>
                                        @else
                                            @if($apostilla->apos_apoderado=='p')
                                                &nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" checked> Poder notariado
                                             @endif
                                    @endif
                                </tr>
                            @endif

                        </table>
                <br/>
            </div>
            <!-- ================================LISTA DE DOCUMENTOS====================================-->
                <div class="col-md-7 pl-3 border shadow pt-2">
                    <table class="col-md-12 table table-sm table-hover rounded" style="font-size: 12px">
                        <tr class="bg-gradient-danger text-white p-2">
                            <th>Nº</th>
                            <th>Sitra</th>
                            <th>Nombre</th>
                            <th>N° trámite</th>
                            <th>N° Documento</th>
                        </tr>
                        <?php $i=1?>
                        @foreach($detalle_apostilla as $d)
                            <tr>
                                <td>{{$i}}</td>
                                <td></td>
                                <td class="font-italic font-weight-bold text-dark">{{$d->lis_nombre}}</td>
                                <td>{{$d->dapo_numero}}</td>
                                <td>{{$d->dapo_numero_documento."/".$d->dapo_gestion_documento}}</td>
                            </tr>
                                <?php $i+=1?>
                        @endforeach
                    </table>
                </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
    </div>
</div>
