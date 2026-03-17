<?php //$fecha=date('Y-m-d',strtotime($apostilla->apos_fecha_ingreso))?>
<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> CORRECCION DE DUPLICADOS </h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body" style="font-size: smaller">
        @if(Session::has('exito'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="font-weight-bold">{!! session('exito') !!}</span>
            </div>
        @endif
            @if(Session::has('error_modal'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span class="font-weight-bold">{!! session('error_modal') !!}</span>
                </div>
            @endif
        <div class="bg-info centrar_bloque p-1 col-md-6 rounded shadow-lg">
            <h6 class="text-white text-center">Formulario de duplicados</h6>
        </div>
        <hr class="sidebar-divider"/>
                <div class="shadow-sm p-2 centrar_bloque">
                    <table class="table table-sm table-hover">
                        <tr>
                            <th>N°</th>
                            <th>CI</th>
                            <th>Nombre</th>
                            <th>Enlaces</th>
                            <th>Opciones</th>
                        </tr>
                        <?php $i=1;?>
                        @foreach($duplicados as $d)
                            <tr>
                                <td>{{$i}}</td>
                                <th>{{$d->per_ci}}
                                </th>
                                <td>{{$d->id_per." - ".$d->per_apellido." ".$d->per_nombre}}<br/>
                                    <span>
                                        <span class="font-weight-bold font-italic text-dark">Sistema:</span><span class="text-danger">{{$d->per_sistema}}</span> &nbsp;|
                                        <span class="font-weight-bold font-italic text-dark">Creado:</span><span class="text-danger">{{date('d/m/Y H:i:s',strtotime($d->created_at))}}</span> &nbsp;|
                                        <span class="font-weight-bold font-italic text-dark">Actualizado:</span><span class="text-danger">{{date('d/m/Y H:i:s',strtotime($d->updated_at))}}</span> &nbsp;|
                                    </span>
                                    @if($tipo=='ci')
                                    <div class="input-group">
                                            <form id="form_corregir{{$i}}">
                                                @csrf
                                                <div class="input-group pt-2">
                                                    <span class="font-weight-bold text-dark font-italic pt-2">CI : </span> &nbsp;
                                                    <input type="text" class="form-control form-control-sm rounded" name="ci" value="{{$d->per_ci}}">&nbsp;&nbsp;
                                                    <span class="font-weight-bold text-dark font-italic pt-2">Apellidos : &nbsp;</span><input type="text" class="form-control form-control-sm rounded" name="apellido" value="{{$d->per_apellido}}">&nbsp;&nbsp;
                                                    <span class="font-weight-bold text-dark font-italic pt-2">Nombres : &nbsp;</span><input type="text" class="form-control form-control-sm rounded" name="nombre" value="{{$d->per_nombre}}">&nbsp;&nbsp;
                                                </div>
                                                <input type="hidden" name="ip" value="{{$d->id_per}}">
                                                <input type="hidden" name="tipo" value="{{$tipo}}">
                                            </form>
                                    </div>
                                    @endif
                                </td>
                                <td><?php echo \App\Models\Persona::getDatosTablas($d->id_per)?></td>

                                <td>
                                    @if($tipo=='ci')
                                        <a href="#" class="btn btn-sm btn-success shadow-lg font-weight-bold" onclick="enviar('form_corregir{{$i}}','{{url('corregir duplicados ci')}}','nombre'+$('#fila').val());
                                            $('#fila'+$('#fila').val()).addClass('alert-info');$('#Modal').modal('hide');">Corregir</a>
                                    @else
                                        <form id="form_duplicado{{$i}}">
                                            @csrf
                                            <input type="hidden" name="ip" value="{{$d->id_per}}">
                                            <input type="hidden" name="ci" value="{{$d->per_ci}}">
                                            <input type="hidden" name="tipo" value="{{$tipo}}">
                                        </form>
                                        <a href="#" class="btn btn-sm btn-danger shadow-lg font-weight-bold"
                                           onclick="enviar('form_duplicado{{$i}}','{{url('corregir duplicados')}}','nombre'+$('#fila').val());$('#fila'+$('#fila').val()).addClass('alert-info');
                                           $('#Modal').modal('hide');
                                           ">Mantener</a>
                                    @endif
                                </td>
                            </tr>
                            <?php $i++;?>
                        @endforeach
                    </table>
                </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
    </div>
</div>
