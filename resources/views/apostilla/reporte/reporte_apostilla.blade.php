@extends('marco/pagina')
@section('contenido')
    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class=""><i class="fas fa-chart-area"></i>&nbsp;&nbsp;REPORTE </h5>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-target="#reporte" data-toggle="modal">
                    <i class="fas fa-chart-line"></i> Nuevo reporte</a>
            </div>
        </div>
        <div class="card-body">
            <div>
                <div class="">
                    <div class="card-body">
                        <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                            <h6 class="text-white text-center">Reporte</h6>
                        </div>
                        <hr class="sidebar-divider"/>
                        <div class="table-responsive" id="panel_grafico">
                            <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr class="bg-gradient-secondary text-white text-center" style="font-size: 0.9em">
                                    <th>Nº</th>
                                    <th>Documento</th>
                                    <th class="text-right">Cantidad</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1; $total=0;?>
                                @foreach($resultado as $r)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$r->nombre}}</td>
                                        <td class="text-right">{{$r->cantidad}}</td>
                                    </tr>
                                        <?php $i++; $total+=$r->cantidad;?>
                                @endforeach
                                <tr class="bg-light">
                                    <th colspan="2" class="text-center">TOTAL</th>
                                    <th class="text-right">{{$total}}</th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--==========================MODAL REPORTE==============-->
        <!--=================================EDITAR TITULO========================-->
        <div class="modal fade" id="reporte" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">

                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-chart-area"></i>&nbsp;&nbsp;Reporte</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                            <h6 class="text-white text-center">Reporte</h6>
                        </div>
                        <hr class="sidebar-divider"/>
                        <form action="{{url('ver reporte apostilla')}}" method="POST" id="form_reporte" enctype="multipart/form-data">
                            @csrf
                            <div class="centrar_bloque text-dark col-md-12">
                                <span class="text-primary font-weight-bold font-italic">* Parámetros del reporte</span>
                                <br/>
                                <br/>
                                <table class="mr-5 col-md-6">
                                    <tr>
                                        <th class="font-italic border-bottom">Tipo de documento : </th>
                                        <td>
                                            <select class="custom-select custom-select-sm" name="documento">
                                                <option value=""></option>
                                                <option value="tramites"> TRÁMITES INGRESADOS</option>
                                                @foreach($lista as $l)
                                                    <option value="{{$l->cod_lis}}">{{$l->lis_alias}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <th class="font-italic border-bottom">Fecha inicio : </th>
                                        <td><div class="input-group">
                                                <select class="custom-select custom-select-sm border"  name="dia">
                                                    <option></option>
                                                    @for($i=1;$i<=31;$i++)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                                &nbsp;/ :&nbsp;&nbsp;<select class="custom-select custom-select-sm border"  name="mes">
                                                    <option></option>
                                                    <option value="1">ENERO</option><option value="2">FEBRERO</option><option value="3">MARZO</option>
                                                    <option value="4">ABRIL</option><option value="5">MAYO</option><option value="6">JUNIO</option>
                                                    <option value="7">JULIO</option><option value="8">AGOSTO</option><option value="9">SEPTIEMBRE</option>
                                                    <option value="10">OCTUBRE</option><option value="11">NOVIEMBRE</option><option value="12">DICIEMBRE</option>
                                                </select>

                                                &nbsp;/ &nbsp;&nbsp;<select class="custom-select custom-select-sm border"  name="gestion">
                                                    <option></option>
                                                    <?php $año=2017;?>
                                                    @for($i=date('Y');$i>2017;$i--)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="font-italic border-bottom">Fecha final : </th>
                                        <td><div class="input-group">
                                                <select class="custom-select custom-select-sm border"  name="dia_final">

                                                    <option></option>
                                                    @for($i=1;$i<=31;$i++)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                                &nbsp;/ :&nbsp;&nbsp;<select class="custom-select custom-select-sm border"  name="mes_final">
                                                    <option></option>
                                                    <option value="1">ENERO</option><option value="2">FEBRERO</option><option value="3">MARZO</option>
                                                    <option value="4">ABRIL</option><option value="5">MAYO</option><option value="6">JUNIO</option>
                                                    <option value="7">JULIO</option><option value="8">AGOSTO</option><option value="9">SEPTIEMBRE</option>
                                                    <option value="10">OCTUBRE</option><option value="11">NOVIEMBRE</option><option value="12">DICIEMBRE</option>
                                                </select>

                                                &nbsp; / &nbsp;&nbsp;<select class="custom-select custom-select-sm border"  name="gestion_final">
                                                    <option></option>
                                                    <?php $año=2017;?>
                                                    @for($i=date('Y');$i>2017;$i--)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="font-italic border-bottom">Reporte en PDF : </th>
                                        <td>
                                            <input type="hidden" name="pdf" class="pdf">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary btn-sm" type="button" onclick="$('#form_reporte .pdf').val('');enviar('form_reporte','{{url('ver reporte apostilla')}}','panel_grafico');$('#reporte').modal('hide')">Generar</button>
                        <a class="btn btn-danger btn-sm text-white" type="button" target="nuevo" onclick="$('#form_reporte .pdf').val('on');$('#form_reporte').submit();$('#reporte').modal('hide')"><i class="fas fa-file-pdf"></i> Generar </a>
                    </div>
                </div>

            </div>
        </div>
        <!--============================END======================-->
@endsection
