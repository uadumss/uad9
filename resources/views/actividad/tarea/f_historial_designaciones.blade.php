<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-bottom-info">
        <div class="modal-header bg-info">
            <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"><i class="fas fa-history"></i> Historial de Designaciones</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-info">
                <strong>Tarea:</strong> {{$tarea->tar_nombre}}
            </div>

            <div class="bg-info centrar_bloque p-2 mb-3 col-md-8 rounded shadow">
                <h6 class="text-white text-center">Designaciones Actuales (Activas)</h6>
            </div>

            @if($designacionesActuales->count() > 0)
                <table class="table table-sm table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Funcionario</th>
                            <th>Fecha Asignación</th>
                            <th>% Alcanzado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($designacionesActuales as $d)
                            <tr>
                                <td>
                                    @if($d->foto)
                                        <img src="{{url('img/foto/'.$d->foto)}}" width="30" height="30" class="imgRedonda mr-2"/>
                                    @else
                                        <img src="{{url('img/icon/sin foto'.$d->sexo.'.png')}}" width="30" height="30" class="imgRedonda mr-2"/>
                                    @endif
                                    <strong>{{$d->name}}</strong>
                                </td>
                                <td>{{date('d/m/Y',strtotime($d->des_fech_asig))}}</td>
                                <td class="text-center">
                                    @php
                                        $porcentajeAlcanzado = DB::select("SELECT COALESCE(SUM(dia_porcen), 0) as total FROM diarios WHERE cod_des = ? AND cod_tar = ?", [$d->cod_des, $tarea->cod_tar]);
                                        $porcentaje = $porcentajeAlcanzado[0]->total ?? 0;
                                    @endphp
                                    <span class="badge badge-success">{{$porcentaje}}%</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning text-white" data-toggle="modal" data-target="#marcarRetiro"
                                            onclick="prepararRetiro({{$d->cod_des}}, '{{$d->name}}', {{$porcentaje}})">
                                        <i class="fas fa-sign-out-alt"></i> Marcar Retiro
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-warning">No hay designaciones activas</div>
            @endif

            <hr class="my-4"/>

            <div class="bg-danger centrar_bloque p-2 mb-3 col-md-8 rounded shadow">
                <h6 class="text-white text-center">Historial de Retiros</h6>
            </div>

            @if($designacionesRetiradas->count() > 0)
                <div class="overflow-auto" style="max-height: 300px;">
                    <table class="table table-sm table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th>Funcionario</th>
                                <th>Fecha Asignación</th>
                                <th>Fecha Retiro</th>
                                <th>% Alcanzado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($designacionesRetiradas as $d)
                                <tr>
                                    <td>
                                        @if($d->foto)
                                            <img src="{{url('img/foto/'.$d->foto)}}" width="30" height="30" class="imgRedonda mr-2"/>
                                        @else
                                            <img src="{{url('img/icon/sin foto'.$d->sexo.'.png')}}" width="30" height="30" class="imgRedonda mr-2"/>
                                        @endif
                                        {{$d->name}}
                                    </td>
                                    <td>{{date('d/m/Y',strtotime($d->des_fech_asig))}}</td>
                                    <td>{{date('d/m/Y',strtotime($d->des_fech_ret))}}</td>
                                    <td class="text-center">
                                        <span class="badge badge-secondary">{{$d->des_porcen_alcanzado}}%</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-secondary">No hay retiros registrados</div>
            @endif

            <hr class="my-4"/>

            <div class="bg-success centrar_bloque p-2 mb-3 col-md-8 rounded shadow">
                <h6 class="text-white text-center">Asignar Nuevo Funcionario</h6>
            </div>

            <form id="form_nueva_asignacion" method="POST" action="{{url('guardar_nueva_designacion')}}">
                @csrf
                <div class="form-group">
                    <label for="nuevoFuncionario" class="font-weight-bold">Seleccionar funcionario:</label>
                    <select class="form-control form-control-sm" name="id_funcionario" id="nuevoFuncionario" required>
                        <option value="">-- Seleccione un funcionario --</option>
                        @foreach($funcionariosDisponibles as $f)
                            <option value="{{$f->id}}">{{$f->name}}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="cod_tar" value="{{$tarea->cod_tar}}">
                <input type="hidden" name="cod_act" value="{{$tarea->cod_act}}">
                <button type="submit" class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i> Asignar
                </button>
            </form>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>

<!-- MODAL PARA MARCAR RETIRO -->
<div class="modal fade" id="marcarRetiro" tabindex="-1" role="dialog" aria-labelledby="marcarRetiroLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content border-bottom-warning">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-dark font-weight-bolder" id="marcarRetiroLabel"><i class="fas fa-sign-out-alt"></i> Marcar Retiro</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form_retiro" method="POST" action="{{url('guardar_retiro_designacion')}}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <strong>Funcionario:</strong> <span id="nomFuncionario"></span>
                    </div>

                    <div class="form-group">
                        <label for="fechaRetiro" class="font-weight-bold">Fecha de Retiro:</label>
                        <input type="date" class="form-control form-control-sm" name="fecha_retiro" id="fechaRetiro" required>
                    </div>

                    <div class="form-group">
                        <label for="porcentajeAlcanzado" class="font-weight-bold">Porcentaje Alcanzado (%):</label>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control bg-light" name="porcentaje_alcanzado" id="porcentajeAlcanzado" min="0" max="100" step="0.01" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <small class="form-text text-muted">*Calculado automaticamente desde los reportes registrados</small>
                    </div>

                    <input type="hidden" name="cod_des" id="codDes" value="">
                    <input type="hidden" name="cod_tar" value="{{$tarea->cod_tar}}">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-warning btn-sm text-dark" type="submit">
                        <i class="fas fa-check"></i> Confirmar Retiro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function prepararRetiro(codDes, nombre, porcentaje) {
        document.getElementById('codDes').value = codDes;
        document.getElementById('nomFuncionario').textContent = nombre;
        document.getElementById('porcentajeAlcanzado').value = porcentaje;
        document.getElementById('fechaRetiro').value = new Date().toISOString().split('T')[0];
    }
</script>
