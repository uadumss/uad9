
    <form action="{{url('g_tomo_res')}}" method="POST" id="e_form">
        @csrf
        <div class="modal-content border-bottom-primary">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"><i class="fas fa-book"></i> Editar Tomo</h5>
                <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="panelRevision">
                <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                    <h6 class="text-white text-center">Formulario de edicion de tomo de resoluciones</h6>
                </div>
                <hr class="sidebar-divider"/>
                <table class="table-hover">
                    <tr>
                        <th class="text-right font-italic">Número de tomo : </th>
                        <td class="border-bottom border-dark">
                            <input class="form-control form-control-sm border-0" placeholder="Ingrese el número del tomo"
                                   required name="n_tomo" pattern="[0-9]{1,3}" value="{{$tomo['tom_numero']}}"/></td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic">Gestión : </th>
                        <td class="border-bottom border-dark">
                            &nbsp;<span class="text-danger font-italic font-weight-bold border-0">{{$tomo['tom_gestion']}}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic">Rango de páginas : </th>
                        <td class="border-bottom border-dark ">
                            <div class="form-group row">
                                &nbsp;&nbsp; De: &nbsp;&nbsp;
                                <?php $rango=explode('-',$tomo['tom_rango'])?>
                                <input name="r_menor" required class="form-control col-md-3 form-control-sm border-0" pattern="[0-9]{1,5}" value="{{$rango[0]}}">
                                &nbsp;&nbsp;
                                Hasta: &nbsp;&nbsp;
                                @if(isset($rango[1]))
                                <input name="r_mayor" required class="form-control col-md-3 form-control-sm border-0" pattern="[0-9]{1,5}" value="{{$rango[1]}}">
                                @else
                                <input name="r_mayor" required class="form-control col-md-3 form-control-sm border-0" pattern="[0-9]{1,5}" value="">
                                @endif
                            </div>
                        </td>

                    </tr>
                    <tr>
                        <th class="text-right font-italic">Observación: </th>
                        <td class="border-bottom border-dark">
                            <textarea class="form-control border-0" rows="5" name="obs" id="obs">{{$tomo['tom_obs']}}</textarea>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="tipo" value="res">
                <input type="hidden" name="ct" value="{{$tomo['cod_tom']}}">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <input class="btn btn-primary" type="submit" value="Guardar">
            </div>
        </div>

    </form>

