<form action="{{url('g_funcionario/')}}" method="POST" id="form_importar" enctype="multipart/form-data">
    @csrf

    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary ">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-user-alt"></i> Funcionario</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="shadow-sm rounded p-2">
                @if($cod_fun==0)
                    <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                        <h5 class="text-white text-center"> Formulario para nuevo funcionario</h5>
                    </div>
                    <hr class="sidebar-divider"/>
                    <span class="text-primary font-weight-bold font-italic" style="font-size: 0.8em"> * DATOS DEL FUNCIONARIO</span><br/><br/>
                    <div class="row">
                        <div class="col-md-5">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Apellidos y Nombres :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" required name="nombre" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Nº CI:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="ci"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Sexo:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="sexo" id="sexo">
                                            <option value="M">MASCULINO</option>
                                            <option value="F">FEMENINO</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Teléfonos:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="telefonos" id="telefonos" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Fecha ingreso:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="date" class="form-control form-control-sm border-0" name="fecha" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Email:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="email" class="form-control form-control-sm border-0" name="email" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Presentación de Folder:</th>
                                    <td class="border-bottom border-dark">
                                        &nbsp;<input type="checkbox" class="custom-checkbox" name="folder" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Tipo de funcionario:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="tipo">
                                            <option value="D">Docente</option>
                                            <option value="A">Administrativo</option>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-right font-italic text-dark">Carrera:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="carrera">
                                            <option value=""></option>
                                            @foreach($carreras as $ca)
                                                <option value="{{$ca->cod_car}}">{{$ca->fac_abreviacion." - ".$ca->car_nombre}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>

                            </table>
                        </div>
                        <div class="col-md-7">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Nacionalidad:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="nacionalidad">
                                            <option value="B">Boliviano</option>
                                            <option value="E">Extranjero</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">País origen:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="pais">
                                            <option value="29">Bolivia</option>
                                            @foreach($nacionalidad as $n)
                                                <option value="{{$n['cod_nac']}}">{{$n['nac_nombre']}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Facultad * </th>
                                    <td class="border-bottom border-dark">
                                        <textarea class="form-control form-control-sm border-0" name="facultad"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Carrera * </th>
                                    <td class="border-bottom border-dark">
                                        <textarea class="form-control form-control-sm border-0" name="carrera1"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Observaciones</th>
                                    <td class="border-bottom border-dark">
                                        <textarea class="form-control form-control-sm border-0" name="observacion"></textarea>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>

                @else
                    <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                        <h5 class="text-white text-center"> Formulario para editar Funcionario</h5>
                    </div>
                    <hr class="sidebar-divider"/>
                    <span class="text-primary font-weight-bold font-italic float-right" style="font-size: 0.8em"> * DATOS DEL FUNCIONARIO</span><br/><br/>

                    <div class="row">
                        <div class="col-md-5">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Apellidos y Nombres :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" value="{{$funcionario->fun_nombre}}" required name="nombre" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Nº CI:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" value="{{$funcionario->fun_ci}}" name="ci"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Sexo:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="sexo" id="sexo">
                                            @if($funcionario->fun_sexo=='M')
                                                <option value="M">MASCULINO</option>
                                                <option value="F">FEMENINO</option>
                                            @else
                                                <option value="F">FEMENINO</option>
                                                <option value="M">MASCULINO</option>
                                            @endif
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Teléfonos:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0"  value="{{$funcionario->fun_telefonos}}"name="telefonos" id="telefonos" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Fecha ingreso:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="date" class="form-control form-control-sm border-0"  value="{{$funcionario->fun_fecha_ingreso}}" name="fecha" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Email:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="email" class="form-control form-control-sm border-0"  value="{{$funcionario->fun_email}}" name="email" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Presentación de Folder:</th>
                                    <td class="border-bottom border-dark">
                                        &nbsp;
                                        @if($funcionario->fun_folder=='t')
                                            <i class="text-primary fas fa-check-square"></i>
                                        @else
                                            <input type="checkbox" class="" name="folder" />
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Tipo de funcionario:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="tipo">
                                            @if($funcionario->fun_doc_adm=='D')
                                                <option value="D">Docente</option>
                                                <option value="A">Administrativo</option>
                                            @else
                                                <option value="A">Administrativo</option>
                                                <option value="D">Docente</option>
                                            @endif
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark align-text-top">Carrera:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="custom-select-sm custom-select border " name="carrera">
                                            <option value=""></option>
                                            @foreach($carreras as $ca)
                                                <option value="{{$ca->cod_car}}">{{$ca->fac_abreviacion." - ".$ca->car_nombre}}</option>
                                            @endforeach
                                        </select>
                                        <span class="font-weight-bold" style="font-size: 12px">
                                            @if(sizeof($carrera)>0)
                                                @foreach($carrera as $c)
                                                    <a class="btn btn-light btn-circle btn-sm text-danger"onclick="cargarPlan('{{url('e_carrera funcionario/'.$c->cod_trb)}}','panel_docente')"><i class="fas fa-trash-alt"></i></a>
                                                    <span>{{$c->fac_abreviacion." - ".$c->car_nombre}}</span><br/>
                                                @endforeach
                                            @endif
                                        </span>
                                    </td>
                                </tr>

                            </table>
                        </div>
                        <div class="col-md-7">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Nacionalidad:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="nacionalidad">
                                            @if($funcionario->fun_nacionalidad=='B')
                                                <option value="B">Boliviano</option>
                                                <option value="E">Extranjero</option>
                                            @else
                                                <option value="E">Extranjero</option>
                                                <option value="B">Boliviano</option>
                                            @endif
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">País origen:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="pais">
                                            @if($pais)
                                                <option value="{{$pais->cod_nac}}">{{$pais->nac_nombre}}</option>
                                            @endif
                                            <option value="29">Bolivia</option>
                                            @foreach($nacionalidad as $n)
                                                <option value="{{$n['cod_nac']}}">{{$n['nac_nombre']}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Facultad * </th>
                                    <td class="border-bottom border-dark">
                                        <textarea class="form-control form-control-sm border-0" name="facultad">{{$funcionario->fun_facultad}}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Carrera * </th>
                                    <td class="border-bottom border-dark">
                                        <textarea class="form-control form-control-sm border-0" name="carrera1">{{$funcionario->fun_carrera}}</textarea>
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-right font-italic text-dark">Observaciones</th>
                                    <td class="border-bottom border-dark">
                                        <textarea class="form-control form-control-sm border-0" name="observacion">{{$funcionario->fun_obs_personal}}</textarea>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                    <input type="hidden" name="cf" value="{{$funcionario->cod_fun}}">
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
            <input class="btn btn-primary" type="submit" value="Guardar"/>
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

</script>
