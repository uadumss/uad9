<div class="modal-content border-bottom-danger">
    <div class="modal-header bg-danger">
        <h5 class="modal-title text-white" id="exampleModalLabel"> &nbsp;&nbsp;Finalizar Asignación</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">

        Esta seguro de eliminar la asignación del funcionario : <br/><br/>
        <div class="row shadow ml-3 mr-3 rounded p-3">
            <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-3" id="panel_e_titulo">
                <table class="">
                    <tr>
                        <th class="text-right"> Nombre: </th>
                        <th class="text-dark text-left border-bottom border-danger pl-3">{{$user->name}}</th>
                    </tr>
                    <tr>
                        <th class="text-right"> Fecha de asignación: </th>
                        <th class="text-dark text-left border-bottom border-danger pl-3">{{date('d/m/Y',strtotime($a_cargo->ac_inicio))}}</th>
                    </tr>
                </table>
            </div>
            <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2><i class="fas fa-question-circle"></i></h2></div>
        </div>
        <br/>
        <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
    </div>
    <form id="form_eliminar_asignacion">
        @csrf
        <input type="hidden" name="cac" value="{{$a_cargo->cod_ac}}">
        <input type="hidden" name="tipo" value="e">
    </form>
    <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger" type="button" data-dismiss="modal" onclick="enviar('form_eliminar_asignacion','{{url("habilitar asignacion funcionario")}}','panel')">Aceptar</button>
    </div>
</div>
