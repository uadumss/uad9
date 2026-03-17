@extends('marco/pagina')
@section('contenido')
    <?php $tipo['db']="DIPLOMA DE BACHILLER";$tipo['ca']="CERTIFICADO ACADÉMICO";
        $tipo['da']="DIPLOMA ACADÉMICO"; $tipo['tp']="TÍTULO PROFESIONAL";
        $tipo['tpos']="TÍTULO POSGRADO";$tipo['di']="DIPLOMADO";
        $tipo['re']="REVÁLIDA"; $tipo['su']="CERTIFICADO SUPLETORIO";
    ?>

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
                                        <td>{{$tipo[$r->tom_tipo]}}</td>
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
                        <form action="{{url('ver reporte')}}" method="POST" id="form_reporte" enctype="multipart/form-data">
                            @csrf
                        <div class="centrar_bloque text-dark">
                            <table class="mr-5 col-md-6">
                                <tr>
                                    <th class="font-italic">Tipo de documento : </th>
                                    <td>
                                        <select class="custom-select custom-select-sm" name="tipo" onchange="cargarDatos('{{url('fe_reporte')}}/'+this.value,'panel_reporte')">
                                            <option value="todos"></option>
                                            <option value="db"> Diplomas de bachiller</option>
                                            <option value="ca">Certificado académico</option>
                                            <option value="da">Diploma académico</option>
                                            <option value="tp">Título profesional</option>
                                            <option value="di">Diplomado</option>
                                            <option value="tpos">Títulos de posgrado</option>
                                            <option value="re">Reválida</option>
                                            <option value="su">Certificado supletorio</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="font-italic border-bottom">Gestión : </th>
                                    <td><div class="input-group">
                                            De :&nbsp;&nbsp;<select class="custom-select custom-select-sm border"  name="inicio">
                                                <option value=""></option>
                                                <?php $año=date('Y');?>
                                                @for($i=$año;$i>1927;$i--)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                            &nbsp;&nbsp; A : &nbsp;&nbsp;
                                            <select class="custom-select custom-select-sm border"  name="fin">
                                                <option value=""></option>
                                                @for($i=$año;$i>1927;$i--)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                                <div id="panel_reporte">

                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary btn-sm" type="button" onclick="enviar('form_reporte','{{url('generar reporte')}}','panel_grafico');$('#reporte').modal('hide')">Generar</button>
                    </div>
                </div>

            </div>
        </div>
    <!--============================END======================-->
@endsection
