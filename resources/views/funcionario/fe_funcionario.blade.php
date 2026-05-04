<form action="{{url('g_funcionario/')}}" method="POST" id="form_importar" enctype="multipart/form-data">
    @csrf

    <div class="modal-content border-0 shadow-lg overflow-hidden">
        <div class="modal-header text-white" style="background: linear-gradient(135deg, #184e77 0%, #1e6091 45%, #2a6f97 100%);">
            <div>
                <h5 class="modal-title font-weight-bolder mb-0" id="exampleModalLabel"><i class="fas fa-user-alt mr-1"></i> Funcionario</h5>
                <small class="text-white-50">Registro y actualización de datos personales</small>
            </div>
            <button class="close text-white opacity-100" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body p-0" style="background: linear-gradient(180deg, #f8fbff 0%, #eef5fb 100%);">
            <div class="p-3 p-md-4">
                @if($cod_fun==0)
                    <div class="centrar_bloque col-md-7 px-0 mb-3">
                        <div class="rounded-lg shadow-sm text-white text-center py-2" style="background: linear-gradient(135deg, #2a6f97 0%, #457b9d 100%);">
                            <h5 class="mb-0 font-weight-bolder">Formulario para nuevo funcionario</h5>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <div class="text-primary font-weight-bold text-uppercase" style="letter-spacing: .04em; font-size: .78rem;">* Datos del funcionario</div>
                        <span class="badge px-3 py-2" style="background: #dbeafe; color: #1d4ed8;">Nuevo registro</span>
                    </div>
                    <div id="mensajeDuplicado" class="alert alert-danger border-0 shadow-sm" style="display: none; border-left: 4px solid #dc3545;">
                        <i class="fas fa-exclamation-triangle"></i> Ya existe un funcionario con ese CI.
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="border rounded-lg shadow-sm bg-white p-3 h-100">
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Tipo de funcionario:</label>
                                    <select class="custom-select shadow-sm" name="tipo" id="tipo" onchange="verificarDuplicado()" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                        <option value="D">Docente</option>
                                        <option value="A">Administrativo</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Nº CI:</label>
                                    <input type="text" class="form-control form-control-sm shadow-sm" required name="ci" id="ci" oninput="verificarDuplicado()" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Apellidos y Nombres:</label>
                                    <input type="text" class="form-control form-control-sm shadow-sm" required name="nombre" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Sexo:</label>
                                    <select class="custom-select custom-select-sm shadow-sm" name="sexo" id="sexo" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                        <option value="M">MASCULINO</option>
                                        <option value="F">FEMENINO</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Teléfonos:</label>
                                    <input type="text" class="form-control form-control-sm shadow-sm" name="telefonos" id="telefonos" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Fecha ingreso:</label>
                                    <input type="date" class="form-control form-control-sm shadow-sm" name="fecha" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Email:</label>
                                    <input type="email" class="form-control form-control-sm shadow-sm" name="email" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-lg shadow-sm bg-white p-3 h-100">
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Presentación de Folder:</label>
                                    <div class="d-flex align-items-center pt-1">
                                        <input type="checkbox" class="custom-checkbox" name="folder" />
                                        <span class="ml-2 text-secondary">Marcado si ya presentó folder</span>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Carrera:</label>
                                    <select class="custom-select custom-select-sm shadow-sm" name="carrera" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                        <option value=""></option>
                                        @foreach($carreras as $ca)
                                            <option value="{{$ca->cod_car}}">{{$ca->fac_abreviacion." - ".$ca->car_nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Nacionalidad:</label>
                                    <select class="custom-select custom-select-sm shadow-sm" name="nacionalidad" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                        <option value="B">Boliviano</option>
                                        <option value="E">Extranjero</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">País origen:</label>
                                    <select class="custom-select custom-select-sm shadow-sm" name="pais" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                        <option value="29">Bolivia</option>
                                        @foreach($nacionalidad as $n)
                                            <option value="{{$n['cod_nac']}}">{{$n['nac_nombre']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Facultad *</label>
                                    <textarea class="form-control form-control-sm shadow-sm" name="facultad" rows="3" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;"></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Carrera *</label>
                                    <textarea class="form-control form-control-sm shadow-sm" name="carrera1" rows="3" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;"></textarea>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="font-italic text-secondary mb-1">Observaciones</label>
                                    <textarea class="form-control form-control-sm shadow-sm" name="observacion" rows="3" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    <div class="centrar_bloque col-md-7 px-0 mb-3">
                        <div class="rounded-lg shadow-sm text-white text-center py-2" style="background: linear-gradient(135deg, #1d4e89 0%, #2c7da0 100%);">
                            <h5 class="mb-0 font-weight-bolder">Formulario para editar Funcionario</h5>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <div class="text-primary font-weight-bold text-uppercase" style="letter-spacing: .04em; font-size: .78rem;">* Datos del funcionario</div>
                        <span class="badge px-3 py-2" style="background: #dbeafe; color: #1d4ed8;">Edición activa</span>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="border rounded-lg shadow-sm bg-white p-3 h-100">
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Apellidos y Nombres:</label>
                                    <input type="text" class="form-control form-control-sm shadow-sm" value="{{$funcionario->fun_nombre}}" required name="nombre" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Nº CI:</label>
                                    <input type="text" class="form-control form-control-sm shadow-sm" value="{{$funcionario->fun_ci}}" name="ci" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Sexo:</label>
                                    <select class="custom-select custom-select-sm shadow-sm" name="sexo" id="sexo" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                        @if($funcionario->fun_sexo=='M')
                                            <option value="M">MASCULINO</option>
                                            <option value="F">FEMENINO</option>
                                        @else
                                            <option value="F">FEMENINO</option>
                                            <option value="M">MASCULINO</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Teléfonos:</label>
                                    <input type="text" class="form-control form-control-sm shadow-sm" value="{{$funcionario->fun_telefonos}}" name="telefonos" id="telefonos" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Fecha ingreso:</label>
                                    <input type="date" class="form-control form-control-sm shadow-sm" value="{{$funcionario->fun_fecha_ingreso}}" name="fecha" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Email:</label>
                                    <input type="email" class="form-control form-control-sm shadow-sm" value="{{$funcionario->fun_email}}" name="email" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Presentación de Folder:</label>
                                    <div class="d-flex align-items-center pt-1">
                                        @if($funcionario->fun_folder=='t')
                                            <span class="badge badge-primary px-3 py-2"><i class="fas fa-check mr-1"></i> Presentado</span>
                                        @else
                                            <input type="checkbox" class="custom-checkbox" name="folder" />
                                            <span class="ml-2 text-secondary">Marcar si ya presentó folder</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="font-italic text-secondary mb-1">Tipo de funcionario:</label>
                                    <select class="custom-select custom-select-sm shadow-sm" name="tipo" id="tipo" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                        @if($funcionario->fun_doc_adm=='D')
                                            <option value="D">Docente</option>
                                            <option value="A">Administrativo</option>
                                        @else
                                            <option value="A">Administrativo</option>
                                            <option value="D">Docente</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-lg shadow-sm bg-white p-3 h-100">
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Nacionalidad:</label>
                                    <select class="custom-select custom-select-sm shadow-sm" name="nacionalidad" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                        @if($funcionario->fun_nacionalidad=='B')
                                            <option value="B">Boliviano</option>
                                            <option value="E">Extranjero</option>
                                        @else
                                            <option value="E">Extranjero</option>
                                            <option value="B">Boliviano</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">País origen:</label>
                                    <select class="custom-select custom-select-sm shadow-sm" name="pais" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                        @if($pais)
                                            <option value="{{$pais->cod_nac}}">{{$pais->nac_nombre}}</option>
                                        @endif
                                        <option value="29">Bolivia</option>
                                        @foreach($nacionalidad as $n)
                                            <option value="{{$n['cod_nac']}}">{{$n['nac_nombre']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Facultad *</label>
                                    <textarea class="form-control form-control-sm shadow-sm" name="facultad" rows="3" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">{{$funcionario->fun_facultad}}</textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Carrera *</label>
                                    <textarea class="form-control form-control-sm shadow-sm" name="carrera1" rows="3" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">{{$funcionario->fun_carrera}}</textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-italic text-secondary mb-1">Observaciones</label>
                                    <textarea class="form-control form-control-sm shadow-sm" name="observacion" rows="3" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">{{$funcionario->fun_obs_personal}}</textarea>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="font-italic text-secondary mb-1">Estado:</label>
                                    <select class="custom-select custom-select-sm shadow-sm" name="estado" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                        @if($funcionario->fun_habilitado === true || $funcionario->fun_habilitado === 1 || $funcionario->fun_habilitado === 't')
                                            <option value="1">Activo</option>
                                            <option value="0">Inactivo</option>
                                        @else
                                            <option value="0">Inactivo</option>
                                            <option value="1">Activo</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="cf" value="{{$funcionario->cod_fun}}">
                @endif
            </div>
        </div>
        <div class="modal-footer border-0" style="background: #f8fbff;">
            <button class="btn btn-light border px-4" type="button" data-dismiss="modal">Cerrar</button>
            <input class="btn btn-primary px-4 shadow-sm" type="submit" value="Guardar" id="btnGuardar" style="background: linear-gradient(135deg, #1d4e89 0%, #2c7da0 100%); border: none;"/>
        </div>
    </div>
</form>
<script>
    function cargarPlan(ruta,panel){
        $('#panel_error_archivo').hide();
        $.ajax({
            url: ruta,
            type: 'GET',
            data:'',
            success: function (resp) {
                $('#'+panel).html(resp);
            },
            error: function () {
                $('#'+panel).html("<br/><div class='alert-danger p-2 rounded'><span class='font-weight-bold'>Error: </span>Quiza no tenga permisos para esta acción </div>");
            }
        });
    }

    function verificarDuplicado(){
        var ci = $('#ci').val();
        var tipo = $('#tipo').val();
        if(ci && tipo){
            $.ajax({
                url: '{{url("verificar-duplicado-funcionario")}}',
                type: 'POST',
                data: {
                    ci: ci,
                    tipo: tipo,
                    _token: '{{csrf_token()}}'
                },
                success: function (resp) {
                    if(resp.existe){
                        $('#mensajeDuplicado').html('Ya existe un funcionario con este CI y tipo.').show();
                        $('#btnGuardar').prop('disabled', true);
                    } else {
                        $('#mensajeDuplicado').hide();
                        $('#btnGuardar').prop('disabled', false);
                        if(resp.autocompletar){
                            // Autocompletar campos
                            $('input[name="nombre"]').val(resp.datos.nombre);
                            $('#sexo').val(resp.datos.sexo);
                            $('#telefonos').val(resp.datos.telefonos);
                            $('input[name="email"]').val(resp.datos.email);
                            //$('input[name="fecha"]').val(resp.datos.fecha_ingreso);
                            $('select[name="nacionalidad"]').val(resp.datos.nacionalidad);
                            $('select[name="pais"]').val(resp.datos.cod_nac);
                            //$('textarea[name="facultad"]').val(resp.datos.facultad);
                            //$('textarea[name="carrera1"]').val(resp.datos.carrera);
                            //$('textarea[name="observacion"]').val(resp.datos.observacion);
                        }
                    }
                },
                error: function () {
                    $('#mensajeDuplicado').html('Error al verificar duplicado.').show();
                    $('#btnGuardar').prop('disabled', true);
                }
            });
        } else {
            $('#mensajeDuplicado').hide();
            $('#btnGuardar').prop('disabled', false);
        }
    }

</script>
