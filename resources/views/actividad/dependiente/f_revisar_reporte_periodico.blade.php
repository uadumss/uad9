<?php use App\Models\Funciones;
    $funciones=new Funciones();
?>
<div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary">
            <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel">Reporte periódico</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                            <?php $i=0;?>
                            <div class="col-md-5 shadow-sm border">
                                <br/>
                                <div class="alert-primary centrar_bloque col-md-8 rounded shadow-sm">
                                    <h6 class="font-weight-bold text-center">Lista de reportes diarios</h6>
                                </div>
                                <span class="mensaje">* Reportes registrados en el rango de fechas</span>
                                <div id="diarios">
                                    @if(sizeof($reportes)>0)
                                        <div class="rounded overflow-auto " style="height: 450px;">
                                            <table class="table table-sm">
                                                @foreach($reportes as $dd)
                                                    <tr>
                                                        <td class="text-justify" style="font-size: 0.8em">
                                                            <span class="text-danger font-italic">{{$funciones->dia($dd->dia_fech)}} {{date('d/m/Y',strtotime($dd->dia_fech))}} </span><br/>
                                                            {{$dd->dia_reporte}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    @else
                                        <br/>
                                        <div class="alert-danger col-md-8 font-weight-bolder p-1 centrar_bloque text-center">
                                            No se encontraron reportes
                                        </div>
                                    @endif
                                    <?php

                                    ?>
                                </div>
                            </div>
                            <div class="col-md-7 shadow-sm border">
                                <br/>
                                <div class="alert-primary centrar_bloque col-md-8 rounded shadow-sm">
                                    <h6 class="font-weight-bold text-center">Formulario de edición de reporte diario</h6>
                                </div>
                                <table class="col-md-12">
                                    <tr>
                                        <th class="text-primary ">Periodo :</th>
                                        <td class="text-dark">
                                                <span class="font-weight-bold text-dark">Del </span>
                                                    <span class="text-dark font-italic">{{date('d/m/Y',strtotime($reporte_periodico['rt_fech_ini']))}}</span>
                                                <span class="font-weight-bold text-dark"> Al </span>
                                                    <span class="text-dark font-italic">{{date('d/m/Y',strtotime($reporte_periodico['rt_fech_fin']))}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <span class="font-weight-bold text-primary">REPORTE : </span><br/>
                                            {{$reporte_periodico['rt_desc']}}
                                        </td>
                                    </tr>
                                </table>
                                <div>
                                    <br/>
                                    <hr class="sidebar-divider"/>


                                        @if($reporte_periodico->rt_apr=='t')
                                            @if($reporte_periodico->tr_obs!='')
                                                <span class="text-danger font-weight-bold">OBSERVACION</span>
                                                <div class="text-danger">
                                                    {{$reporte_periodico['rt_obs']}}
                                                </div>
                                            @endif
                                        @else
                                        <span class="text-danger font-weight-bold">OBSERVACION</span>
                                        <form action="{{url('g_observacion_periodico')}}" method="post">
                                            @csrf
                                            <textarea class="form-control-sm form-control" name="observacion">{{$reporte_periodico['rt_obs']}}</textarea>
                                            <br/>
                                            <input type="hidden" name="cr" value="{{$reporte_periodico['cod_rt']}}"/>
                                            <input type="hidden" name="obs" value="1"/>
                                            <input class="btn btn-primary btn-sm float-right" type="submit" value="Guardar"/>
                                        </form>
                                        @endif

                                </div>
                                <br/>
                                <div class="p-2 col-md-12">
                                    <br/>
                                    <hr class="sidebar-divider"/>

                                            @if($reporte_periodico->rt_apr=='t')
                                                <div>
                                                    <i class="fas fa-check-square text-success"> &nbsp;</i><span class="text-primary font-weight-bold">INFORME ACEPTADO</span><br/><br/>
                                                    <span class="text-dark font-italic font-weight-bold">Calificación : </span> &nbsp;&nbsp;&nbsp;{{$reporte_periodico->rt_cal}}
                                                </div>

                                            @else
                                            <form action="{{url('g_observacion_periodico')}}" method="post">
                                                @csrf
                                                <span class="text-primary font-weight-bold">ACEPTAR INFORME</span>
                                                <div class="input-group centrar_bloque text-center col-md-12">
                                                    Calificación : &nbsp;&nbsp;<select class="col-md-2" name="calificacion">
                                                        <option value="1">1</option><option value="2">2</option><option value="3">3</option>
                                                        <option value="4">4</option><option value="5">5</option><option value="6">6</option>
                                                        <option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option>
                                                    </select> &nbsp;&nbsp;&nbsp;
                                                    <input type="hidden" name="cr" value="{{$reporte_periodico['cod_rt']}}"/>
                                                    <input type="hidden" name="obs" value="0"/>
                                                    <input class="btn btn-primary btn-sm float-right" type="submit" value="Guardar"/>
                                                </div>
                                            </form>
                                            @endif
                                </div>
                            </div>

                                <hr class="sidebar-divider"/>

                            <input type="hidden" name="cr" value="{{$reporte_periodico['cod_rt']}}">
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" data-dismiss="modal">Cerrar</button>

            </div>

</div>
