<div class="modal-dialog modal-lg" role="document">
    <form action="{{url('guardarActividad')}}" method="POST">
        @csrf
        <div class="modal-content  border-bottom-primary">
            <div class="modal-header bg-primary">
                <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-edit"></i> Actividad</h5>
                <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @if($cod_act!=0)
                    <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                        <h6 class="text-white text-center">Formulario para editar actividad</h6>
                    </div>
                    <hr class="sidebar-divider"/>
                    <table class="col-md-12">
                        <tr>
                            <th class="text-dark text-right" width="200">Nombre actividad : </th>
                            <td class="border-bottom border-dark" ><input class="form-control form-control-sm border-0" placeholder="Ingrese el nombre de la actividad" required
                                       name="nombre" value="{{$a['act_nombre']}}" /></td>
                        </tr>
                        <tr>
                            <th class="text-dark text-right">Fecha Inicio : </th>
                            <td class="border-bottom border-dark"><input class="form-control form-control-sm border-0" placeholder="Ingrese fecha de inicio" type="date" name="fi" value="{{$a['act_inicio']}}"/></td>
                        </tr>
                        <tr>
                            <th class="text-dark text-right">Fecha Conclusión : </th>
                            <td class="border-bottom border-dark"><input class="form-control form-control-sm border-0" placeholder="Ingrese fecha de conclusion" type="date" name="ff"
                                       value="{{$a['act_fin']}}"/></td>
                        </tr>
                        <tr>
                            <th class="text-dark text-right">Tarea cotidiana</th>
                            <td class="border-bottom border-dark">
                                @if($a['act_cot']=='t')
                                    <i class="fas fa-check text-success"></i>
                                @else
                                    <i class="text-danger font-weight-bolder">X</i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-dark text-right">Descripción: </th>
                            <td><textarea class="form-control form-control-sm border-0" rows="5" name="desc" id="desc">{{$a['act_desc']}}</textarea>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="ca" value="{{$a['cod_act']}}">
                @else
                    <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                        <h6 class="text-white text-center">Formulario para nueva actividad</h6>
                    </div>
                    <hr class="sidebar-divider"/>
                    <table class="table-hover col-md-12">
                        <tr>
                            <th class="text-right font-italic text-dark" width="200">Nombre actividad : </th>
                            <td class="border-bottom border-dark">
                                <input class="form-control form-control-sm border-0" placeholder="Ingrese el nombre de la actividad" required
                                       name="nombre"/></td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark">Fecha Inicio : </th>
                            <td class="border-bottom border-dark">
                                <input class="form-control form-control-sm border-0" placeholder="Ingrese fecha de inicio" type="date" name="fi"/></td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark">Fecha Conclusión : </th>
                            <td class="border-bottom border-dark">
                                <input class="form-control form-control-sm border-0" placeholder="Ingrese fecha de conclusion" type="date" name="ff"/></td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark">Actividad cotidiana: </th>
                            <td>&nbsp;&nbsp;<input class="" type="checkbox" name="cotidiano"/></td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark">Descripción: </th>
                            <td class="border-bottom border-dark">
                                <textarea class="form-control form-control-sm border-0" rows="5" name="desc" id="desc"></textarea>
                            </td>
                        </tr>
                    </table>
                @endif

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
                <input class="btn btn-primary btn-sm" type="submit" value="Aceptar"/>
            </div>
        </div>
    </form>
</div>
<script>
    tinymce.init({
        selector: '#desc',
    });
</script>
