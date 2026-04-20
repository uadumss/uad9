@if(Session::has('exito'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        {!! session('exito') !!}
    </div>
@endif
@if(Session::has('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        {!! session('error') !!}
    </div>
@endif

<div>
    <hr class="sidebar-divider">
    <div>
    @can('crear editar carrera - f')
        <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm text-white float-right" data-toggle="modal" data-target="#facultad"
           onclick="cargarDatos('fe_carrera/{{$facultad->cod_fac}}/0','panel_contenido')">
            + Carrera
        </a>
    @endcan
    </div>
    @if(sizeof($carreras)>0)
        <span class="font-weight-bold text-danger font-italic">* {{$facultad->fac_nombre}}</span>

        <br/><br/>
        <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered" width="100%" cellspacing="0" style="font-size: 0.74em; min-width: 1900px;">
                <thead>
                <tr class="bg-gray-600 text-white text-center align-middle">
                    <th rowspan="2">Nº</th>
                    <th rowspan="2" class="text-left">Nombre</th>
                    <th rowspan="2">Nombre corto</th>
                    <th rowspan="2">ACRED.</th>
                    <th rowspan="2">Tipo</th>
                    <th rowspan="2">Sistema</th>
                    <th colspan="4">NRO. TOTAL DE PROCESOS</th>
                    <th colspan="5">ULTIMA ACREDITACION</th>
                    <th rowspan="2" style="min-width: 170px;">Opciones</th>
                </tr>
                <tr class="bg-gray-600 text-white text-center align-middle">
                    <th>Año</th>
                    <th>S/C</th>
                    <th>N/C</th>
                    <th>Total</th>
                    <th>Acreditacion</th>
                    <th>Vencimiento</th>
                    <th>Estado</th>
                    <th>Puntaje</th>
                    <th>Certificados</th>
                </tr>
                </thead>
                <tbody>
                <?php $j=1;?>
                @foreach($carreras as $c)
                    <?php
                    $listaAcreditaciones = collect();
                    if(isset($acreditacionesCarrera[$c['cod_car']])){
                        $listaAcreditaciones = $acreditacionesCarrera[$c['cod_car']]->values();
                    }

                    if($listaAcreditaciones->count()===0){
                        $listaAcreditaciones = collect([null]);
                    }
                    $rowspan = $listaAcreditaciones->count();
                    ?>
                    @foreach($listaAcreditaciones as $indiceAcred => $acreditacion)
                        <?php
                        $tipo='';
                        if($acreditacion){
                            if(in_array($acreditacion->tipo,['Nacional','Internacional'])){
                                $tipo=$acreditacion->tipo;
                            }elseif(strtoupper(trim((string)$acreditacion->sistema))==='CEUB'){
                                $tipo='Nacional';
                            }elseif(strtoupper(trim((string)$acreditacion->sistema))==='ARCU SUR'){
                                $tipo='Internacional';
                            }
                        }

                        $sistema='';
                        if($tipo==='Nacional'){
                            $sistema='CEUB';
                        }
                        if($tipo==='Internacional'){
                            $sistema='ARCU SUR';
                        }
                        if($sistema==='' && $acreditacion){
                            $sistema=$acreditacion->sistema ? $acreditacion->sistema : '';
                        }

                        $total = $acreditacion ? $acreditacion->proc_total : null;
                        if($acreditacion && $total === null && ($acreditacion->proc_sc !== null || $acreditacion->proc_nc !== null)){
                            $total = (int)($acreditacion->proc_sc ?? 0) + (int)($acreditacion->proc_nc ?? 0);
                        }

                        $estado = $acreditacion ? trim((string)$acreditacion->estado) : '';
                        $puntaje = $acreditacion ? trim((string)$acreditacion->puntaje) : '';
                        ?>
                        <tr>
                            @if($indiceAcred===0)
                                <th class="border-right font-weight-bolder text-primary" rowspan="{{$rowspan}}">{{$j}}</th>
                                <td class="text-left" rowspan="{{$rowspan}}">{{$c['car_nombre']}}</td>
                                <td class="text-left" rowspan="{{$rowspan}}">{{$c['car_abreviacion']}}</td>
                            @endif
                            <td class="text-center">{{$acreditacion ? ($acreditacion->acreditada===null ? '' : ($acreditacion->acreditada ? 'SI' : 'NO')) : ''}}</td>
                            <td class="text-center">{{$tipo}}</td>
                            <td class="text-center">{{$sistema}}</td>
                            <td class="text-center">{{$acreditacion && $acreditacion->anio ? $acreditacion->anio : ''}}</td>
                            <td class="text-center">{{$acreditacion && $acreditacion->proc_sc !== null ? $acreditacion->proc_sc : ''}}</td>
                            <td class="text-center">{{$acreditacion && $acreditacion->proc_nc !== null ? $acreditacion->proc_nc : ''}}</td>
                            <td class="text-center">{{$total !== null ? $total : ''}}</td>
                            <td class="text-center">{{$acreditacion && $acreditacion->fecha_acreditacion ? date('d/m/Y', strtotime($acreditacion->fecha_acreditacion)) : ''}}</td>
                            <td class="text-center">{{$acreditacion && $acreditacion->fecha_vencimiento ? date('d/m/Y', strtotime($acreditacion->fecha_vencimiento)) : ''}}</td>
                            <td class="text-center">{{$estado}}</td>
                            <td class="text-center">{{$puntaje}}</td>
                            <td class="text-center">{{$acreditacion ? ($acreditacion->certificado===null ? '' : ($acreditacion->certificado ? 'SI' : 'NO')) : ''}}</td>
                            @if($indiceAcred===0)
                                <td class="text-center align-middle" style="min-width: 170px;" rowspan="{{$rowspan}}">
                                    <div class="d-inline-flex align-items-center justify-content-center flex-nowrap">
                                        @can('crear editar carrera - f')
                                        <a href="#" class="btn btn-light btn-circle btn-sm text-primary mr-1" data-target="#facultad" data-toggle="modal"
                                           onclick="cargarDatos('fe_carrera/0/{{$c['cod_car']}}','panel_contenido')" title="Editar carrera">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('eliminar carrera - f')
                                        <a href="#" class="btn btn-light btn-circle btn-sm text-danger mr-1" data-target="#efacultad" data-toggle="modal"
                                           onclick="cargarDatos('f_eli_carrera/0/{{$c['cod_car']}}','panel_econtenido')" title="Eliminar carrera">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        @endcan
                                        @canany(['crear editar carrera - f','eliminar carrera - f'])
                                        <a href="#" class="btn btn-light btn-circle btn-sm text-info" data-target="#efacultad" data-toggle="modal"
                                           onclick="cargarDatos('f_historial_carrera/{{$facultad->cod_fac}}/{{$c['cod_car']}}','panel_econtenido')" title="Ver historial y acreditacion">
                                            <i class="fas fa-history"></i>
                                        </a>
                                        @endcanany
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    <?php $j++;?>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <br/><br/>
        <div class="alert-info p-3">
            La facultad <span class="font-weight-bold">{{$facultad->fac_nombre}}</span>, no tiene carreras registradas
        </div>
    @endif
</div>
<script>
    function validarRangoFechas(prefijo){
        var fechaAcred = document.getElementById(prefijo + '_fecha_acreditacion');
        var fechaVenc = document.getElementById(prefijo + '_fecha_vencimiento');
        var habilitada = document.getElementById(prefijo + '_habilitada');

        if(!fechaAcred || !fechaVenc){
            return true;
        }

        if(habilitada && !habilitada.checked){
            fechaAcred.setCustomValidity('');
            fechaVenc.setCustomValidity('');
            return true;
        }

        if(fechaAcred.value && fechaVenc.value && fechaAcred.value > fechaVenc.value){
            fechaAcred.setCustomValidity('La fecha de acreditacion no puede ser posterior a la de vencimiento.');
            fechaVenc.setCustomValidity('La fecha de vencimiento debe ser posterior o igual a la de acreditacion.');
            return false;
        }

        fechaAcred.setCustomValidity('');
        fechaVenc.setCustomValidity('');
        return true;
    }

    function enviar(formulario,accion){
        var formElement = document.getElementById(formulario);
        var fechasOk = validarRangoFechas('nac') && validarRangoFechas('int');

        if(formElement){
            formElement.classList.add('was-validated');
        }

        if(formElement && typeof formElement.reportValidity === 'function'){
            if(!fechasOk || !formElement.reportValidity()){
                var errorBox = document.getElementById('form_carrera_error');
                if(errorBox){
                    errorBox.classList.remove('d-none');
                }
                return;
            }
        }
        var errorBox = document.getElementById('form_carrera_error');
        if(errorBox){
            errorBox.classList.add('d-none');
        }
        var link = "{{url('/')}}/"+accion+"/";
        var token = "{{csrf_token()}}";
        var form = new FormData($('#'+formulario).get(0));
        $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
        $.ajax({
            url: link,
            type: 'POST',
            contentType: false,
            processData: false,
            data:form,
            //data:$('#form_editar').serialize(),
            success: function (resp) {
                cargarDatos('l_carrera/{{$facultad['cod_fac']}}','panel_carrera');
                $('#facultad').modal('hide');
            },
            error: function (data) {
                alert('Error al crear la carrera')
            }
        });
    }
</script>


