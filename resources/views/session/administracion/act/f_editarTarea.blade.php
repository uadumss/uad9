<!-- =============================ADMINISTRACIO -->
<div class="modal-content shadow-lg" >
    <div class="modal-header bg-verde-oscuro" >
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-edit"></i>Tarea</h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
            <div class="modal-body">
                <div class="bg-verde-oscuro centrar_bloque col-md-6 p-2 mb-2 rounded shadow">
                    <h5 class="text-white text-center" >Formulario para editar tarea</h5>
                </div>
                <hr class="sidebar-divider"/>
                <form id="form_tarea" method="POST" action="{{url('a_guardarTarea')}}">
                    @csrf
                    <table class="col-md-12">
                        <tr>
                            <th class="text-dark text-right font-italic">Nombre : </th>
                            <td class="border-bottom border-dark">
                                <input class="form-control border-0" placeholder="Ingrese el nombre de la actividad" required
                                       name="nombre" value="{{$tarea['tar_nombre']}}" /></td>
                        </tr>
                        <tr>
                            <th class="text-dark text-right font-italic">Fecha Inicio : </th>
                            <td class="border-bottom border-dark">
                                <input class="form-control border-0" placeholder="Ingrese fecha de inicio" type="date" name="fi" value="{{$tarea['tar_fi']}}"/></td>

                        </tr>
                        <tr>
                            <th class="text-dark text-right font-italic">Fecha Conclusión : </th>
                            <td class="border-bottom border-dark">
                                <input class="form-control border-0" placeholder="Ingrese fecha de conclusion" type="date" name="ff"
                                       value="{{$tarea['tar_ff']}}"/></td>
                        </tr>
                        @if($tarea->tar_cotidiano!='t')
                        <tr>
                            <th class="text-dark text-right font-italic">Porcentaje </th>
                            <td class="border-bottom border-dark">
                                <select name="por" class="custom-select custom-select-sm border-0">
                                    <option value="{{$tarea['tar_por']}}">{{$tarea['tar_por']}} %</option>
                                    @for($j=5;$j<101;$j+=5)
                                        <option value="{{$j}}">{{$j}} % </option>
                                    @endfor
                                </select>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th class="text-dark text-right font-italic">Descripción: </th>
                            <td class="border-bottom border-dark">
                                <textarea class="form-control form-control-sm border-0" rows="5" name="desc">{{$tarea['tar_desc']}}</textarea>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="ct" value="{{$tarea['cod_tar']}}">
                </form>

            </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
            <button class="btn btn-sm bg-verde-oscuro text-white" type="button" data-dismiss="modal" onclick="enviar('form_tarea','{{url('a_guardarTarea')}}','panel_actividad')">Guardar</button>
        </div>

</div>
