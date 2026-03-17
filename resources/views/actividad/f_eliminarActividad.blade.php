<div class="modal-content border-bottom-danger">
    <div class="modal-header bg-danger">
        <h5 class="modal-title text-white" id="exampleModalLabel"><img src="{{url('img/icon/eliminar.png')}}"> Eliminar actividad</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        @if(sizeof($tareas)>0)
            <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-3" id="panel_e_titulo">
                No se puede eliminar la Actividad debido a que tiene tareas programadas
            </div>
        @else

        Esta seguro de eliminar la Actividad: <br/>
        <br/>
        <div class="row shadow ml-3 mr-3 rounded p-3">
            <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-3" id="panel_e_titulo">
                <table class="col-md-12">
                    <tr>
                        <th class="text-right"> Nombre: </th>
                        <th class="text-dark text-left border-bottom border-danger pl-3"> {{$a['act_nombre']}}</th>
                    </tr>
                    <tr>
                        <th class="text-right"> Fecha de inicio: </th>
                        <th class="text-dark text-left border-bottom border-danger pl-3">{{date('d/m/Y',strtotime($a->act_inicio))}}</th>
                    </tr>
                    <tr>
                        <th class="text-right"> Fecha de conclusión: </th>
                        <th class="text-dark text-left border-bottom border-danger pl-3">{{date('d/m/Y',strtotime($a->act_fin))}}</th>
                    </tr>
                </table>
            </div>
            <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2><i class="fas fa-question-circle"></i></h2></div>
        </div>
        @endif
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
        @if(sizeof($tareas)==0)
        <form action="{{url('eliminar actividad')}}" method="POST">
            @csrf
            <input class="btn btn-danger" type="submit" value="Aceptar"/>
            <input type="hidden" name="ca" value="{{$a['cod_act']}}">
        </form>
        @endif
    </div>
</div>
