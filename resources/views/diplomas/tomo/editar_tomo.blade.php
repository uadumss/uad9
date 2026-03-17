
    <form action="{{url('g_tomo')}}" method="POST" id="e_form">
        @csrf
        <div class="modal-content border-bottom-primary">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"><i class="fas fa-book"></i> Editar Tomo</h5>
                <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="panelRevision">
                <table class="table table-hover">
                    <tr>
                        <th class="text-dark text-right" width=" 200">Número de tomo : </th>
                        <td><input class="form-control form-control-sm" placeholder="Ingrese el número del tomo"
                                   required name="n_tomo" pattern="[0-9]{1,3}" value="{{$tomo['tom_numero']}}"/></td>
                    </tr>
                    <tr>
                        <th class="text-dark text-right">gestion: </th>
                        <td>
                            <span class="text-danger font-italic font-weight-bold">{{$tomo['tom_gestion']}}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-dark text-right">Rango: </th>
                        <td><div class="form-group row">
                                &nbsp;&nbsp; De: &nbsp;&nbsp;
                                <?php $rango=explode('-',$tomo['tom_rango'])?>
                                <input name="r_menor" required class="form-control col-md-3 form-control-sm" pattern="[0-9]{1,5}" value="{{$rango[0]}}">
                                &nbsp;&nbsp;
                                Hasta: &nbsp;&nbsp;
                                @if(isset($rango[1]))
                                <input name="r_mayor" required class="form-control col-md-3 form-control-sm" pattern="[0-9]{1,5}" value="{{$rango[1]}}">
                                @else
                                <input name="r_mayor" required class="form-control col-md-3 form-control-sm" pattern="[0-9]{1,5}" value="">
                                @endif
                            </div>
                        </td>

                    </tr>
                    <tr>
                        <th class="text-dark text-right">Observación: </th>
                        <td><textarea class="form-control" rows="5" name="obs" id="obs">{{$tomo['tom_obs']}}</textarea>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="tipo" value="{{$tipo}}">
                <input type="hidden" name="ct" value="{{$tomo['cod_tom']}}">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <input class="btn btn-primary" type="submit" value="Guardar">
            </div>
        </div>

    </form>

