<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-bottom-info">
        <div class="modal-header bg-info">
            <h5 class="modal-title text-white font-weight-bolder"><i class="fas fa-history"></i> Historial de asignaciones de la actividad</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-info">
                <strong>Actividad:</strong> {{$act->act_nombre}}
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Nº</th>
                            <th>Tarea</th>
                            <th>Responsables</th>
                            <th>Historial</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tar as $t)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$t['tar_nombre']}}</td>
                                <td>
                                    @foreach($designados as $des)
                                        @if($des->cod_tar==$t['cod_tar'])
                                            <div class="small">
                                                @if($des->foto!='')
                                                    <img src="{{url('img/foto/'.$des->foto)}}" width="24" height="24" class="imgRedonda mr-1"/>
                                                @else
                                                    <img src="{{url('img/icon/sin foto'.$des->sexo.'.png')}}" width="24" height="24" class="imgRedonda mr-1"/>
                                                @endif
                                                {{$des->name}}
                                            </div>
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-target="#tarea" data-toggle="modal"
                                            onclick="cargarDatos('{{url("historial designaciones/".$t['cod_tar'])}}','panel_tarea')">
                                        <i class="fas fa-history"></i> Ver historial / reasignar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>