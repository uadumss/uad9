<div class="modal-dialog modal-lg" role="document" id="panel_actividad">
    <div class="modal-content  border-bottom-primary">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-edit"></i> Editar Actividad</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="bg-primary centrar_bloque col-md-6 p-2 mb-2 rounded shadow">
                <h5 class="text-white text-center">Formulario para editar actividad</h5>
            </div>
            <hr class="sidebar-divider"/>
            <form method="POST" id="form_actividad">
                @csrf
            <table class="col-md-12">
                <tr>
                    <th class="text-dark text-right font-italic">Nombre actividad : </th>
                    <td class="border-bottom border-dark">
                        <input class="form-control border-0" placeholder="Ingrese el nombre de la actividad" required
                               name="nombre" id="nombre" value="{{$act['act_nombre']}}" /></td>
                </tr>
                <tr>
                    <th class="text-dark text-right font-italic">Fecha Inicio : </th>
                    <td class="border-bottom border-dark">
                        <input class="form-control border-0" placeholder="Ingrese fecha de inicio" type="date" name="fi" id="fi" value="{{$act['act_inicio']}}"/></td>
                </tr>
                <tr>
                    <th class="text-dark text-right font-italic">Fecha Conclusión : </th>
                    <td class="border-bottom border-dark">
                        <input class="form-control border-0" placeholder="Ingrese fecha de conclusion" type="date" name="ff" id="ff"
                               value="{{$act['act_fin']}}"/></td>
                </tr>
                <tr>
                    <th class="text-dark text-right font-italic">Tarea cotidiana : </th>
                    <td class="border-bottom border-dark"> &nbsp;&nbsp;
                        @if($act['act_cot']=='t')
                            <i class="fas fa-check text-success"></i>
                        @else
                            <i class="text-danger font-weight-bolder">X</i>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="text-dark text-right font-italic">Descripción: </th>
                    <td class="border-bottom border-dark">
                        <textarea class="form-control border-0" rows="5" name="desc" id="desc" >{{$act['act_desc']}}</textarea>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="ia" id="ia" value="{{$act['cod_act']}}">
            <input type="hidden" name="id" value="{{$id_per}}">
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
            <button class="btn btn-primary btn-sm" type="button" data-dismiss="modal" onclick="enviar('form_actividad','{{url('guardar actividad adm')}}','panel')">Guardar</button>
        </div>
    </div>
</div>


