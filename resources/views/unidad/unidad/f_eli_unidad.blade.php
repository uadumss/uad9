<div class="modal-content border-bottom-danger">
    <form action="{{url('eliminar unidad')}}" method="post">
        @csrf
        <div class="modal-header bg-danger">
            <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="{{url('img/icon/eliminar.png')}}">&nbsp;&nbsp;Eliminar unidad</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            @if($eliminar==1)
                <span class="font-italic">Esta seguro de eliminar la unidad :</span> <br/><br/>
                <div class="row">
                    <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-3" id="panel_e_titulo">
                        <table>
                            <tr>
                                <th class="text-right">Nombre : </th>
                                <th class="text-dark text-left border-bottom border-danger pl-3">{{$unidad->uni_nombre}}</th>
                            </tr>
                            <tr>
                                <th class="text-right"> Nombre corto: </th>
                                <th class="text-dark text-left border-bottom border-danger pl-3">{{$unidad->uni_abreviacion}}</th>
                            </tr>
                            <tr>
                                <th class="text-right"> Tipo de unidad: </th>
                                <th class="text-dark text-left border-bottom border-danger pl-3">{{$unidad->uni_nivel}}</th>
                            </tr>
                        </table>
                    </div>
                    <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2>?</h2></div>
                </div>
                <br/>
                <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
            @else
                <div class="alert-danger p-3 col-md-10 centrar_bloque rounded">
                    La Unidad <span class="font-weight-bold">{{$unidad->uni_nombre}}</span>, tiene dependencias asignadas, no se puede eliminar
                </div>
            @endif
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            @if($eliminar==1)
                <input class="btn btn-danger" type="submit" value="Aceptar" />
            @endif
        </div>
        <input type="hidden" name="cu" value="{{$unidad->cod_uni}}">
    </form>
</div>
