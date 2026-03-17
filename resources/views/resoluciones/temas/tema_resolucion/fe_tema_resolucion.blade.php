<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary ">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-institution"></i> Tema de interés</h5>
    </div>
    <div class="modal-body">

        <span class="text-dark" style="font-size: 12px">
                            <span class="font-weight-bold">Tema : </span><span class="font-italic">{{$tema->tem_titulo}}</span><br/>
                            <span class="font-weight-bold">Descripción : </span><span class="font-italic">{{$tema->tem_des}}</span>
                        </span>
        <hr class="sidebar-divider"/>
        <div class="row">
            <div class="col-md-5">
                <div class="border-bottom-primary rounded shadow p-2">
                    <form id="form_busqueda">
                        @csrf
                        <table class="col-md-12 text-dark">
                            <tr>
                                <th class="text-right font-italic">Palabra clave:</th>
                                <td class="border-bottom">
                                    <input type="text" class="form-control form-control-sm" required name="clave" />
                                </td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Nro. resolución</th>
                                <td class="border-bottom">
                                    <div class="input-group">
                                        &nbsp;&nbsp;&nbsp;<input type="text" class="form-control form-control-sm" required name="numero" />
                                        <span>Tipo :</span>
                                        &nbsp;&nbsp;&nbsp;<select class="form-control form-control-sm col-md-12 border border-info"  name="tipo">
                                            <option value="rcu">RCU</option>
                                            <option value="rr">RR</option>
                                            <option value="rvr">RVR</option>
                                            <option value="rs">RS</option>
                                            <option value="rc">RC</option>
                                            <option value="rcf">RCF</option>
                                            <option value="rcc">RCC</option>
                                        </select>
                                        <span>Gestión:</span>
                                        <select class="form-control form-control-sm col-md-12 border border-info"  name="gestion">
                                            <?php $año=date('Y');?>
                                            @for($i=$año;$i>1927;$i--)
                                                <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-primary font-italic" colspan="2" style="font-size: 12px">* INCLUIR</th>
                            </tr>
                            <tr>
                                <td colspan="2" class="font-italic text-dark">
                                    <span>Vistos :</span>
                                    &nbsp;&nbsp;<input type="checkbox" class="" name="vistos" />
                                    <span>Considerando</span>
                                    &nbsp;&nbsp;<input type="checkbox" class="" name="considerando" />
                                    <span>Resuelve</span>
                                    &nbsp;&nbsp;<input type="checkbox" class="" name="resuelve" />
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" name="te" value="t"/>
                        <input type="hidden" name="ct" value="{{$tema->cod_tem}}"/>
                    </form>
                    <div class="text-right">
                        <button class="btn btn-sm btn-primary" onclick="enviar('form_busqueda','{{url('buscar resolucion')}}','panel_resultado')">Enviar</button>
                    </div>

                </div>
                <hr class="sidebar-divider" />

                <span class="text-danger font-weight-bold font-italic" style="font-size: 12px;">* RESULTADO</span>
                <div class="rounded shadow p-1">
                    <div id="panel_resultado" style="height: 400px;" class="overflow-auto">

                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <span class="font-weight-bold text-danger font-italic">* RESOLUCIONES DEL TEMA DE INTERES</span>
                <div id="panel_resolucion" style="height: 600px;" class="overflow-auto border shadow rounded pb-2 pt-2">
                    @include('resoluciones.temas.tema_resolucion.l_tema_resolucion_modal')
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <a class="btn btn-secondary btn-sm text-white" href="{{url("tema resolucion/".$tema->cod_tem)}}" type="button" onclick="">Cerrar</a>
    </div>
</div>
