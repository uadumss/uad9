<div class="modal-dialog modal-xl" role="document">
        <div class="modal-content border-bottom-primary">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel">Nuevo reporte diario</h5>
                <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="bg-primary centrar_bloque col-md-6 p-1 rounded shadow">
                    <h5 class="text-white text-center">Lista de tareas</h5>
                </div>
                    <div>
                        @if($user->foto!='')
                            <img src="{{url('img/foto/'.$user->foto)}}" width="50" height="50" class="imgRedonda centrar_bloque"/>
                        @else
                            <img src="{{url('img/icon/sin foto'.$user->sexo.'.png')}}" class="imgRedonda centrar_bloque" width="50" height="50">
                        @endif
                        <span class="font-italic text-dark" style="font-size: 0.85em">{{$user->name}}</span>
                    </div>
                <br/>
                <br/>
                <div>
                    <table class="table table-sm table-hover">
                        <tr class="bg-gray-600 text-white">
                            <th>Nº</th>
                            <th>Actividad</th>
                            <th>Tarea</th>
                            <th>Opciones</th>
                        </tr>
                        <?php $i=1;?>
                        @foreach($tareas as $t)
                            <tr>
                                <th>{{$i}}</th>
                                <td>{{$t->act_nombre}}</td>
                                <td>{{$t->tar_nombre}}</td>
                                <td>
                                    <a href="{{url('listar reportes/'.$t->cod_tar.'/dependiente')}}" class="btn btn-light btn-circle btn-sm text-primary">
                                        <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php $i++;?>
                        @endforeach
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
</div>
<script>
    tinymce.init({
        selector: '#desc',
    });
</script>
