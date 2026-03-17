<div class="table-responsive">
    <div id="" class="col-md-12">
        <br/>
        <table class="table table-sm table-hover" id="dataTable2" width="100%" cellspacing="0" style="font-size: smaller">
            <thead>
            <tr class="bg-gray-600 text-white" style="font-size: 0.9em">
                <th>Nº</th>
                <th class="text-left">Tipo</th>
                <th class="text-left">Nro. Trámite</th>
                <th>Trámite</th>
                <th>Nombres</th>
                <th class="text-left">Fecha solicitud</th>
                <th class="text-center">Opciones</th>
            </tr>
            </thead>
            <tbody id="cuerpo">
            <?php $i=1;?>
            @foreach($noatentado as $t)
                <tr>
                    <th class="border-right font-weight-bolder">{{$i}}</th>
                    <td>
                        <span class="font-weight-bold rounded pl-2 pr-2 bg-primary text-white" style="font-size: 0.75em">NO-ATENTADO</span>
                    </td>
                    <td class="text-right"><span class="text-primary font-weight-bold">{{$t->dtra_numero_tramite}}</span>/{{$t->dtra_gestion_tramite}}</td>
                    <td class="">{{$t->tre_nombre}}</td>
                    <td><span class="font-weight-bold text-dark" style="font-size: 12px; font-family: 'Times New Roman'">
                                                        <?php echo \App\Http\Controllers\Noatentado\TramiteNoAtentadoController::listaCandidatos($t->cod_dtra)?>
                                                    </span>
                    </td>
                    <td class="text-right">{{date('d/m/Y',strtotime($t->dtra_fecha_registro))}}</td>
                    <td class="text-right">
                        @can('entregar tramite - noa')
                            <a class="btn btn-light btn-circle btn-sm text-success" data-target="#traleg" data-toggle="modal"
                               onclick="cargarDatos('{{url("formulario entrega tramite noatentado/$t->cod_dtra")}}','panel_traleg')"
                               title="Entregar trámite"> <i class="fas fa-hand-point-right"></i>
                            </a>
                        @endcan
                    </td>
                </tr>
                    <?php $i++;?>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<script src="{{url('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<!-- Page level custom scripts -->
<script src="{{url('js/demo/datatables-demo.js')}}"></script>

<script>
    $('#dataTable2').dataTable( {
        "pageLength": 500
    });
</script>
