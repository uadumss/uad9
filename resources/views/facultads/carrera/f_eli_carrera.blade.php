<div class="modal-content border-bottom-danger">
    <form action="{{url('eli_facultad')}}" method="post" id="form_eli_carrera">
        @csrf
        <div class="modal-header bg-danger">
            <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="{{url('img/icon/eliminar.png')}}">&nbsp;&nbsp;Eliminar carrera</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            @if($eliminar==1)
                <span class="font-italic">Esta seguro de eliminar la carrera :</span> <br/><br/>
                <div class="row shadow ml-3 mr-3 rounded p-3">
                    <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-3" id="panel_e_titulo">
                        <table class="">
                            <tr>
                                <th class="text-right"> Carrera: </th>
                                <th class="text-dark text-left border-bottom border-danger pl-3">{{$carrera->car_nombre}}</th>
                            </tr>
                            <tr>
                                <th class="text-right"> Nombre corto: </th>
                                <th class="text-dark text-left border-bottom border-danger pl-3">{{$carrera->car_abreviacion}}</th>
                            </tr>
                            <tr>
                                <th class="text-right"> Facultad : </th>
                                <th class="text-dark text-left border-bottom border-danger pl-3">{{$facultad->fac_nombre}}</th>
                            </tr>
                        </table>
                    </div>
                    <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2><i class="fas fa-question-circle"></i></h2></div>
                </div>
                <br/>
                <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
            @else
                <div class="alert-danger p-3 col-md-10 centrar_bloque rounded">
                    La carrera <span class="font-weight-bold">{{$carrera->car_nombre}}</span>, ha sido asignada a algunos tomos, no se puede eliminar
                </div>
            @endif
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            @if($eliminar==1)
                <button class="btn btn-danger" type="button" onclick="enviar('form_eli_carrera','eli_carrera')" data-dismiss="modal">Eliminar</button>
            @endif
        </div>
        <input type="hidden" name="cc" value="{{$carrera->cod_car}}">
    </form>
</div>
