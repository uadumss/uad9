<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-bottom-danger">
        <form action="{{url('e_resolucion')}}" method="post">
            @csrf
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="{{url('img/icon/eliminar.png')}}">&nbsp;&nbsp;Eliminar resolución</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @if($enlace[0]->enlace>0)
                    <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-3" id="panel_e_titulo">
                        No se puede eliminar la Resolución debido a que hay copias en otros tomos
                    </div>
                @else
                    <span class="font-italic">Esta seguro de eliminar la resolución :</span> <br/><br/>
                    <div class="row">
                        <div class="alert-danger shadow text-center centrar_bloque col-md-9 p-3" id="panel_e_titulo">
                            <div>
                                <table>
                                    <tr>
                                        <th class="text-right">Nro. Resolución:</th>
                                        <td class="text-dark text-left border-bottom border-danger pl-3">{{$resolucion['res_numero']."/".date('y',strtotime($resolucion['res_fecha']))}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Fecha:</th>
                                        <td class="text-dark text-left border-bottom border-danger pl-3">{{date('d/m/Y',strtotime($resolucion['res_fecha']))}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Descripción:</th>
                                        <td class="text-dark text-left border-bottom border-danger pl-3">{{$resolucion['res_desc']}}</td>
                                    </tr>
                                </table>
                                <input type="hidden" name="cr" value="{{$resolucion['cod_res']}}">
                                <input type="hidden" name="ct" value="{{$resolucion['cod_tom']}}">
                            </div>
                        </div>
                        <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2><i class="fas fa-question-circle"></i></h2></div>
                    </div>
                @endif
                <br/>
                <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <input class="btn btn-danger" type="submit" value="Aceptar" />
            </div>
        </form>
    </div>
</div>


