<?php
    use App\Models\Funciones;
    $fun=new Funciones();
?>
    @if($tarea['tar_concluido']!='t')
        <div class="modal-dialog" role="document">
                <div class="modal-content border-bottom-danger">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white" id="exampleModalLabel">Alerta</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span class="text-dark font-italic">No puede registrar el reporte de conclusión</span><br/><br/>
                        <div class="font-weight-bold alert-danger centrar_bloque col-md-8 p-2 text-center">
                            Aún no se concluyo la tarea
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
        </div>
    @else
                <div class="modal-content border-bottom-primary">
                    <form action="{{url('g_conclusion')}}" method="post">
                        @csrf
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel">Reporte de conclusión de tarea</h5>
                            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="pl-1 col-sm-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><span class="font-weight-bold text-dark">Tarea :</span></td>
                                            <td colspan="4">{{$tarea['tar_nombre']}}</td>
                                        </tr>
                                        <tr>
                                            <td><span class="font-weight-bold text-dark">Fecha inicio :</span></td>
                                            <td>{{date('d/m/Y',strtotime($tarea['tar_fi']))}}</td>
                                        </tr>
                                        <tr>
                                            <td><span class="font-weight-bold text-dark">Fecha conclusión :</span></td>
                                            <td>@if($tarea['tar_ff'])
                                                    {{date('d/m/Y',strtotime($tarea['tar_ff']))}}
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="centrar_bloque shadow-sm col-md-8 alert-primary rounded">
                                        <h6 class="text-dark  text-center">Reportes diarios</h6>
                                    </div>
                                    <span class="mensaje">* Lista de reportes diarios registrados para esta tarea</span>

                                    <div class="overflow-auto col-md-12" style="height: 500px">
                                        <table class="table table-sm col-md-11">
                                            @foreach($diario as $dd)
                                                <tr>
                                                    <td class="text-justify">
                                                        <div class="border border-light rounded">
                                                            <span class="text-dark font-weight-bolder"> {{$fun->dia($dd ->dia_fech)}} - {{date('d/m/Y',strtotime($dd->dia_fech))}}</span><br/>
                                                            {{$dd->dia_reporte}}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>

                                </div>
                                <br/>
                                <div class="col-sm-6 rounded shadow-sm border"><br/>
                                    <div class="text-danger text-right text-uppercase font-italic font-weight-bolder">Formulario de reporte final</div>
                                    <table class="table table-sm">
                                        <tr>
                                            <td>
                                                <textarea name="desc" placeholder="Ingrese el reporte final ......" required class="form-control" rows="22">{{$des['des_rep_con']}}</textarea>
                                            </td>
                                        </tr>
                                    </table>
                                    <input type="hidden" name="ct" value="{{$tarea['cod_tar']}}">

                                    <input type="hidden" name="final" value="t">
                                    <input type="hidden" name="fecha" value="{{date('Y-m-d')}}">
                                    <div class="">
                                        <input class="float-right btn btn-primary m-2" type="submit" value="Enviar"/>
                                        <button class="float-right btn btn-secondary m-2" type="button" data-dismiss="modal">Cancelar</button>
                                    </div>
                                    <br/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
    @endif

