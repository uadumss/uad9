<div class="modal-content border-bottom-info">
    <form action="{{url('n_diario')}}" method="post">
        @csrf
        <div class="modal-header bg-info">
            <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel">Nuevo reporte diario</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="border rounded pl-1">
                <table class="table table-sm ">
                    <tr>
                        <td><span class="font-weight-bold text-dark">Tarea :</span></td>
                        <td colspan="4">{{$tarea['tar_nombre']}}</td>
                    </tr>
                    <tr>
                        <td><span class="font-weight-bold text-dark">Fecha inicio :</span></td>
                        <td>{{date('d/m/Y',strtotime($tarea['tar_fi']))}}</td>
                        <td><span class="font-weight-bold text-dark">Fecha conclusión :</span></td>
                        <td>@if($tarea['tar_ff']!='')
                                {{$tarea['tar_ff']}}
                            @endif
                        </td>
                        <td>@if($tarea['tar_con']=='t')
                                <span class="text-danger font-weight-bolder font-italic">Esta tarea ya esta concluida</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <br/>
            <div class="rounded pl-3 pr-4"><br/>
                <h6 class="text-primary text-right text-uppercase font-italic font-weight-bolder">Formulario de reporte diario</h6>
                <table class="table table-sm">
                    <tr>
                        <th class="text-dark">Fecha :</th>
                        <td><input type="date" name="fecha" class="form-control form-control-sm form-control-user"/>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-dark">Reporte :</th>
                        <td><textarea name="desc" class="form-control" rows="10"></textarea>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="it" value="{{$tarea['id_tar']}}">
                <input type="hidden" name="id_des" value="{{$des['id_des']}}">
            </div>

        </div>
        <div class="modal-footer bg-gray-200">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <input class="btn btn-info" type="submit" value="Aceptar"/>
        </div>
    </form>
</div>
