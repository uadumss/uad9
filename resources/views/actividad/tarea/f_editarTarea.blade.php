<div class="modal-dialog modal-lg" role="document">
        <form action="{{url('guardarTarea')}}" method="POST">
        @csrf
        <div class="modal-content border-bottom-primary">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"> Tarea</h5>
                <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                 @if($cod_tar!=0)
                    <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                        <h6 class="text-white text-center">Formulario para editar Tarea</h6>
                    </div>
                    <hr class="sidebar-divider"/>
                <table class="col-md-12">
                    <tr>
                        <th class="text-dark text-right font-italic" width=" 200">Nombre Tarea : </th>
                        <td class="border-bottom border-dark">
                        <input class="form-control form-control-sm border-0" placeholder="Ingrese el nombre de la actividad" required
                                   name="nombre" value="{{$tarea['tar_nombre']}}" /></td>
                    </tr>
                    <tr>
                        <th class="text-dark text-right font-italic">Fecha Inicio : </th>
                        <td class="border-bottom border-dark">
                            <input class="form-control form-control-sm border-0" placeholder="Ingrese fecha de inicio" type="date" name="fi" value="{{$tarea['tar_fi']}}"/></td>

                    </tr>
                    <tr>
                        <th class="text-dark text-right font-italic">Fecha Conclusión : </th>
                        <td class="border-bottom border-dark">
                            <input class="form-control form-control-sm border-0" placeholder="Ingrese fecha de conclusion" type="date" name="ff"
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
                    <input type="hidden" name="ct" value="{{$tarea->cod_tar}}">
                    <input type="hidden" name="ca" value="{{$tarea->cod_act}}">
                @else
                    <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                        <h6 class="text-white text-center">Formulario para Nueva Tarea</h6>
                    </div>
                    <hr class="sidebar-divider"/>
                    <table class="col-md-12">
                        <tr>
                            <th class="text-dark text-right font-italic" width=" 200">Nombre Tarea: </th>
                            <td class="border-bottom border-dark">
                                <input class="form-control form-control-sm border-0" placeholder="Ingrese el nombre de la tarea" required
                                       name="nombre"/></td>
                        </tr>
                        <tr>
                            <th class="text-dark text-right font-italic">Fecha Inicio : </th>
                            <td class="border-bottom border-dark">
                                <input class="form-control form-control-sm border-0" placeholder="Ingrese fecha de inicio" type="date" name="fi"/></td>
                        </tr>
                        <tr>
                            <th class="text-dark text-right font-italic">Fecha Conclusión : </th>
                            <td class="border-bottom border-dark">
                                <input class="form-control form-control-sm border-0" placeholder="Ingrese fecha de conclusion" type="date" name="ff"/></td>
                        </tr>
                        @if($actividad->act_cotidiano!='t')
                        <tr>
                            <th class="text-dark text-right font-italic">Porcentaje </th>
                            <td class="border-bottom border-dark">
                                <select name="por" class="custom-select-sm custom-select border-0">
                                    @for($j=5;$j<101;$j+=5)
                                        <option value="{{$j}}">{{$j}} % </option>
                                    @endfor
                                </select>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th class="text-dark text-right font-italic">Designado a:</th>
                            <td class="border-bottom border-dark">
                                <select class="custom-select-sm custom-select border-0" placeholder="Elija un nombre" name="fun">
                                    @foreach($p_acargo as $r)
                                        <option value="{{$r->id}}">{{$r->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-dark text-right font-italic">Descripción: </th>
                            <td class="border-bottom border-dark">
                                <textarea class="form-control border-0" rows="5" name="desc" id="desc"></textarea>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="ca" value="{{$actividad->cod_act}}">
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <input class="btn btn-primary" type="submit" value="Aceptar"/>

            </div>
        </div>
    </form>
</div>
