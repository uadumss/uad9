<div class="modal-content border-bottom-primary">
    <form action="{{url('g_informe')}}" method="post">
        @csrf
        <div class="modal-header bg-primary">
            <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel">Reporte periódico</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
            @if($cod_rt==0)
                <div class="col-md-5 shadow-sm border">
                    <br/>
                    <div class="alert-primary centrar_bloque col-md-8 rounded shadow-sm">
                        <h6 class="font-weight-bold text-center">Lista de reportes diarios</h6>
                    </div>
                    <span class="mensaje">* Reportes registrados en el rango de fechas</span>
                    <div id="diarios0">
                    </div>
                </div>
                <div class="col-md-7 border shadow-sm">
                    <div>
                        <br/>
                        <div class="alert-primary centrar_bloque col-md-8 rounded shadow-sm">
                            <h6 class="font-weight-bold text-center">Formulario de edición de reporte diario</h6>
                        </div>
                        <table class="col-md-12">
                            <tr>
                                <th class="text-dark">Fecha :</th>
                                <td class="text-dark">
                                    <div class="form-group row">
                                        <label for="fi" class="col-md-1 control-label">Del</label>
                                        <div class="col-md-4">
                                            <input type="date" class="form-control" id="fi0" name="fi" required onchange="obtDiario(0)">
                                        </div>
                                        <label for="ff" class="col-md-1 control-label">Al</label>
                                        <div class="col-md-4">
                                            <input type="date" class="form-control" id="ff0" name="ff" required onchange="obtDiario(0)">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <textarea name="desc" placeholder="Ingrese su reporte ....." class="form-control" rows="15" required></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>
                    @else
                        <?php $i=0;?>
                            <div class="col-md-5 shadow-sm border">
                                <br/>
                                <div class="alert-primary centrar_bloque col-md-8 rounded shadow-sm">
                                    <h6 class="font-weight-bold text-center">Lista de reportes diarios</h6>
                                </div>
                                <span class="mensaje">* Reportes registrados en el rango de fechas</span>
                                <div id="diarios{{$i}}">

                                </div>
                            </div>
                            <div class="col-md-7 shadow-sm border">
                                <br/>
                                <div class="alert-primary centrar_bloque col-md-8 rounded shadow-sm">
                                    <h6 class="font-weight-bold text-center">Formulario de edición de reporte diario</h6>
                                </div>
                                @if($reporte_final['rt_apr']=='t')
                                    <table class="col-md-12">
                                        <tr>
                                            <th class="text-primary ">Periodo :</th>
                                            <td class="text-dark">
                                                <span class="font-weight-bold text-dark">Del </span> &nbsp;&nbsp;&nbsp;
                                                <span class="text-dark font-italic">{{date('d/m/Y',strtotime($reporte_final['rt_fech_ini']))}}</span> &nbsp;&nbsp;&nbsp;
                                                <span class="font-weight-bold text-dark"> Al </span> &nbsp;&nbsp;&nbsp;
                                                <span class="text-dark font-italic">{{date('d/m/Y',strtotime($reporte_final['rt_fech_fin']))}}</span>&nbsp;&nbsp;&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <span class="font-weight-bold text-primary">REPORTE : </span><br/>
                                                {{$reporte_final['rt_desc']}}
                                            </td>
                                        </tr>
                                    </table>
                                    <hr class="sidebar-divider"/>
                                    <div>
                                        <i class="fas fa-check-square text-success"> &nbsp;</i><span class="text-primary font-weight-bold">INFORME ACEPTADO</span><br/><br/>
                                        <span class="text-dark font-italic font-weight-bold">Calificación : </span> &nbsp;&nbsp;&nbsp;{{$reporte_final->rt_cal}}
                                    </div>
                                @else
                                    <table class="table table-sm">
                                        <tr>
                                            <th class="text-dark">Fecha :</th>
                                            <td class="text-dark">
                                                <div class="form-group row">
                                                    <label for="fi" class="col-md-1 control-label">Del</label>
                                                    <div class="col-md-4">
                                                        <input type="date" class="form-control" value="{{$reporte_final['rt_fech_ini']}}" id="fi{{$i}}" name="fi" required onchange="obtDiario({{$i}})">
                                                    </div>
                                                    <label for="ff" class="col-md-1 control-label">Al</label>
                                                    <div class="col-md-4">
                                                        <input type="date" class="form-control" value="{{$reporte_final['rt_fech_fin']}}" id="ff{{$i}}" name="ff" required onchange="obtDiario({{$i}})">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <textarea name="desc" required class="form-control" placeholder="Ingrese su reporte ....." rows="15" required>{{$reporte_final['rt_desc']}}</textarea>
                                            </td>
                                        </tr>
                                    </table>
                            </div>

                            <input type="hidden" name="cr" value="{{$reporte_final['cod_rt']}}">
                                @endif


                    @endif
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
            @if($cod_rt==0)
                <input class="btn btn-primary" type="submit" value="Aceptar"/>
            @endif
            @if(isset($reporte_final['rt_apr']) && $reporte_final['rt_apr']!='t')
                <input class="btn btn-primary" type="submit" value="Aceptar"/>
            @endif
        </div>
    </form>
</div>
