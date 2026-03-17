<div class="row  shadow-lg">
    <div class="col-md-6">
        <div class="border border-info mb-2 rounded p-2 col-md-12">
            @if($diario['dia_aceptado']=='t')
                <span class="mensaje"> * Informe concluido</span>
                <div class="input-group mb-2 col-md-12 centrar_bloque align-content-center">
                    <table class="col-md-8 centrar_bloque" border="0">
                        <tr>
                            <td><span class="text-dark font-weight-bolder">Calificación:</span>
                            <span class="text-primary font-weight-bolder" style="font-size: 1.3em">&nbsp;{{$diario->dia_cal}}</span></td>
                            <td><span class="text-dark font-weight-bolder">Avance:</span>
                            <span class="text-primary font-weight-bolder" style="font-size: 1.3em">&nbsp;{{$diario->dia_porcen}} %</span></td>
                        </tr>
                    </table>
                </div>
            @else
                <span class="mensaje"> * Estado</span>
                <div class="mb-2 col-md-12 text-danger font-weight-bolder text-center">
                    Aun no fue aceptado
                </div>
            @endif
        </div>
        <div class="card border-info">

            <div class="card-body" id="d_personales">
                @if($diario['dia_aceptado']!='t' && $diario['dia_corregir']!='f')
                <h6 class="bg-danger shadow rounded text-white col-md-5 centrar_bloque text-center mb-2 font-weight-bolder">Observación</h6>
                <div style="height: 300px;" class="overflow-auto border mb-1 shadow-sm">

                    <table class="table table-sm">
                        <tr>
                            <th class="text-dark">Fecha:</th>
                            <td>{{date('d/m/Y',strtotime($diario['dia_obs']))}}</td>
                        </tr>
                        <tr>
                           <td colspan="2" class="text-justify"><span class="font-weight-bolder text-dark">Observación:</span> <br/>{{$diario['dia_obs']}}</td>
                        </tr>
                    </table>
                </div>
                @endif
                <div>
                    @if($diario['dia_aceptado']=='t')
                        <h6 class="bg-primary shadow rounded text-white col-md-5 centrar_bloque text-center mb-2 font-weight-bolder">Reporte Aceptado</h6>
                    <div class="overflow-auto border rounded" style="height:600px">
                        {{$diario['dia_reporte']}}
                    </div>
                    @else
                        <h6 class="bg-primary shadow rounded text-white col-md-5 centrar_bloque text-center mt-2 font-weight-bolder">Reporte para modificación</h6>
                            <span class="mensaje-peligro">* Realize la corrección</span>
                            @if($diario['dia_aceptado']!='t' && $diario['dia_corregir']!='f')
                                <textarea name="desc" required placeholder="Ingrese su reporte ......" class="form-control mb-2 shadow-sm" rows="8">{{$diario['dia_reporte']}}</textarea>
                                @else
                                <textarea name="desc" required placeholder="Ingrese su reporte ......" class="form-control mb-2 shadow-sm" rows="20">{{$diario['dia_reporte']}}</textarea>
                            @endif

                            <input type="hidden" name="id" value="{{$diario['id_dia']}}">
                            <input type="hidden" name="it" value="{{$diario['id_tar']}}">
                            <input type="hidden" name="fecha" value="{{$diario['dia_fech']}}">
                            <input type="hidden" name="id_des" value="{{$diario['id_des']}}">
                    @endif
                </div>

            </div>

        </div>


    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body bg-light">
                <h6 class="bg-primary shadow rounded text-white col-md-5 centrar_bloque text-center mb-2">Observaciones anteriores</h6>
                @if(sizeof($observaciones)>0)
                    <div>
                        <div id="panelObs">
                            <?php $i=1;?>
                            @foreach($observaciones as $o)
                            <div>
                                <div id="po{{$i}}" <?php if($i>1){echo 'style="display:none"';}?>
                                    class="border rounded">
                                    <div class="overflow-auto" style="height: 310px;">
                                        <table class="table table-sm">
                                            <tr>
                                                <th class="text-dark">Fecha Registro:</th>
                                                <td class="mensaje text-left">{{date('d/m/Y',strtotime($o['od_fech_mod']))}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-justify"><span class="font-weight-bolder text-dark">Reporte anterior:</span> <br/>{{$o['od_rep']}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="overflow-auto" style="height: 310px;">
                                        <table class="table table-sm">
                                            <tr>
                                                <th class="text-dark">Fecha de observación:</th>
                                                <td class="mensaje text-left">{{date('d/m/Y',strtotime($o['od_fech']))}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-justify"><span class=" mensaje-peligro">Observación:</span> <br/>{{$o['od_obs']}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <?php $i++;?>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                        <?php $i=1;?>
                        @foreach($observaciones as $o)
                                <a class="btn btn-circle btn-sm <?php if($i==1){echo 'btn-primary text-white';}else{echo 'btn-light text-dark';}?>"
                                   id="o{{$i}}" onclick="cambiarObs({{$i}})"><i class="fas" id="i{{$i}}">{{$i}}</i></a>
                            <?php $i++;?>
                        @endforeach
                        </div>
                    </div>
                @else
                    <div style="height: 630px;">
                        <span class="mensaje-peligro">No existen observaciones</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm rounded shadow-sm" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>
<script>
function cambiarObs(panel){
    var tam={{sizeof($observaciones)}}
    for(var i=1;i<(tam+1);i++){
        $('#po'+i).css('display','none');
        $('#o'+i).removeClass('btn-primary');
        $('#o'+i).removeClass('text-white');
        $('#o'+i).addClass('btn-light');
        $('#o'+i).addClass('text-dark');
    }
    $('#po'+panel).css('display','block');
    $('#o'+panel).removeClass('btn-light');
    $('#o'+panel).removeClass('text-dark');
    $('#o'+panel).addClass('btn-primary');
    $('#o'+panel).addClass('text-white');
}
</script>
