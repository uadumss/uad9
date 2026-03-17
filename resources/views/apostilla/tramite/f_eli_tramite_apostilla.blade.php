<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-bottom-danger">
        <form action="{{url('eliminar tramite apostilla')}}" method="post">
            @csrf
            <input  type="hidden" name="ct" id="ct" value=""/>
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="{{url('img/icon/eliminar.png')}}">&nbsp;&nbsp;Eliminar trámite de apostilla</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @if($eliminar)
                    <span class="font-italic">Esta seguro de eliminar el trámite : </span><br/><br/>
                    <div class="row">

                        <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-8 p-2" id="panel_eli">
                            <div>
                                <table class="text-dark col-md-12" >
                                    <tr>
                                        <th class="text-right">Nombre : </th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3">{{$persona->per_apellido." ".$persona->per_nombre}}</th>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Numero de tramite: </th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3">UAD{{$tramite_apostilla->apos_numero}}</th>
                                    </tr>

                                    <tr>
                                        <th class="text-right">Fecha de ingreso : </th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3">{{date('d/m/Y',strtotime($tramite_apostilla->apos_fecha_ingreso))}}</th>
                                    </tr>
                                </table>
                                <input type="hidden" name="ca" value="{{$tramite_apostilla->cod_apos}}">
                            </div>

                        </div>
                        <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
                    </div>
                    <br/>
                    <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
                @else
                    <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-3" id="panel_e_titulo">
                        No se puede eliminar el trámite, debido a que tiene documentos seleccionados para apostilla
                    </div>
                @endif

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                @if($eliminar)
                    <input class="btn btn-danger" type="submit" value="Aceptar"/>
                @endif
            </div>
        </form>
    </div>
</div>
