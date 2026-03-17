
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-verde-oscuro">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-file"></i> Editar temas</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">

            <div class="alert-info p-3 rounded border">
                <table>
                    <tr>
                        <td class="font-italic font-weight-bold text-dark">Tipo de plan</td>
                        <td>: {{$plan->plan_titulo}}</td>
                    </tr>
                    <tr>
                        <td class="font-italic font-weight-bold text-dark">Número de código</td>
                        <td>: {{$plan->plan_numero."/".$codigo->carch_numero}}</td>
                    </tr>
                    <tr>
                        <td class="font-italic font-weight-bold text-dark">Nombre de código</td>
                        <td>: {{$codigo->carch_titulo}}</td>
                    </tr>
                </table>
            </div>
            <br/>
            <br/>
            <span class="text-danger font-weight-bold font-italic" style="font-size: 12px">* Elija un tema para la resolución</span>
            <table class="table table-sm">
                <?php $i=0;?>
                @foreach($detalle as $d)
                    <tr>
                        <td>{{($i+=1)}}</td>
                        <td>
                            <a href="#" onclick="cargarDatos('{{url('guardar temas resolucion/'.$cod_res.'/'.$d->cod_det)}}','archivados');$('#DatosTemas').modal('hide')">{{$d->det_nombre}}</a>
                        </td>
                        <td>
                            <a href="#" onclick="cargarDatos('{{url('guardar temas resolucion/'.$cod_res.'/'.$d->cod_det)}}','archivados');$('#DatosTemas').modal('hide')"><i class="fas fa-check-circle"></i></a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" onclick="$('#DatosTemas').modal('hide')">Cerrar</button>
        </div>
    </div>
</div>


