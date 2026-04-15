<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-file-alt"></i> TRAMITE CONVOCATORIA</h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>

    <!-- Formulario Convocatoria -->
    <div class="card shadow">
        <div class="modal-body">
            @if(Session::has('exitoModal'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {!! session('exitoModal') !!}
                </div>
            @endif
            @if(Session::has('errorModal'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {!! session('errorModal') !!}
                </div>
            @endif

            <div class="d-flex justify-content-center">
                <div class="card-body" style="font-size: 14px;">
                    @if(!$tramite_noatentado)
                        <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                            <h5 class="text-white text-center">Nuevo trámite No Atentado</h5>
                        </div>
                        <hr class="sidebar-divider text-bg-dark">

                        <div class="row">
                            <div class="col-md-12 table">
                                <form id="form_tramite">
                                    @csrf

                                    <span class="text-primary font-weight-bold font-italic" style="font-size: 14px">* Paso 1: Registrar candidatos</span>
                                    <div class="border rounded p-2 mb-2">
                                        <div class="row">
                                            <div class="col-md-2 mb-1">
                                                <label class="font-italic mb-0">CI</label>
                                                <input class="form-control form-control-sm" id="noa_ci" onchange="cargarDatosPersonalesNoa(this.value)">
                                            </div>
                                            <div class="col-md-2 mb-1">
                                                <label class="font-italic mb-0">Nombres</label>
                                                <input class="form-control form-control-sm" id="noa_nombre">
                                            </div>
                                            <div class="col-md-2 mb-1">
                                                <label class="font-italic mb-0">Apellidos</label>
                                                <input class="form-control form-control-sm" id="noa_apellido">
                                            </div>
                                            <div class="col-md-2 mb-1">
                                                <label class="font-italic mb-0">Cod. SIS</label>
                                                <input class="form-control form-control-sm" id="noa_cod_sis">
                                            </div>
                                            <div class="col-md-2 mb-1">
                                                <label class="font-italic mb-0">Unidad</label>
                                                <input class="form-control form-control-sm" id="noa_unidad">
                                            </div>
                                            <div class="col-md-2 mb-1">
                                                <label class="font-italic mb-0">Cargo texto</label>
                                                <input class="form-control form-control-sm" id="noa_cargo">
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label class="font-italic mb-0">Cargo convocatoria</label>
                                                <select class="custom-select custom-select-sm" id="noa_cargo_convocatoria">
                                                    <option value="">Seleccione</option>
                                                    @foreach($cargos as $cargo)
                                                        <option value="{{$cargo->cod_carg}}">{{$cargo->carg_nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-1 d-flex align-items-end justify-content-end">
                                                <button class="btn btn-sm btn-primary" type="button" onclick="agregarCandidatoNoAtentado()">+ Agregar candidato</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive border rounded" style="max-height: 200px; overflow:auto;">
                                        <table class="table table-sm table-hover mb-0" id="tabla_candidatos_noa" style="font-size: 12px;">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Apellidos y nombres</th>
                                                    <th>CI</th>
                                                    <th>Cod. SIS</th>
                                                    <th>Cargo</th>
                                                    <th>Unidad</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr id="fila_vacia_candidatos_noa">
                                                    <td colspan="7" class="text-center text-secondary">No hay candidatos registrados.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <input type="hidden" name="candidatos_json" id="candidatos_json_noa">
                                    <br/>

                                    <span class="text-primary font-weight-bold font-italic" style="font-size: 14px">* Paso 2: Datos del trámite y validación de pago</span>
                                    <table class="col-md-12">
                                        <tbody>
                                            <tr>
                                                <th class="text-right font-italic" style="padding-top: 7px">Convocatoria :</th>
                                                <td class="border-bottom border-dark" style="padding-top: 7px">
                                                    <span class="text-secondary font-italic font-weight-bold">{{$convocatoria->con_nombre}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic">Trámite :</th>
                                                <td class="border-bottom border-dark">
                                                    <select class="custom-select custom-select-sm border-0" name="tramite" id="tramite_noa" onchange="resetValidacionPagoNoAtentado()">
                                                        @foreach($tramites as $t)
                                                            <option value="{{$t->cod_tre}}">{{$t->tre_nombre}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic">Tipo de trámite :</th>
                                                <td class="border-bottom border-dark">
                                                    <input type="radio" name="tipo_tramite" checked value="t"> INTERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <input type="radio" name="tipo_tramite" value="f"> EXTERNO
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic">Nro. Control:</th>
                                                <td class="border-bottom input-group">
                                                    <div class="input-group">
                                                        <input class="form-control form-control-sm" required name="control" id="control_noa" onchange="resetValidacionPagoNoAtentado()">&nbsp;&nbsp;
                                                        <span class="text-primary font-weight-bold font-italic">Nro. Control Reintegro : &nbsp;</span>
                                                        <input class="form-control form-control-sm" name="reintegro" id="reintegro_noa">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="mt-2 border rounded p-2 bg-light">
                                        <div class="d-flex align-items-center flex-wrap">
                                            <button class="btn btn-info btn-sm" type="button" onclick="validarPagoNoAtentado()">Validar pago</button>
                                            <span id="estado_pago_noa" class="badge badge-warning ml-2">Pendiente</span>
                                        </div>
                                        <small id="detalle_pago_noa" class="text-secondary">Antes de guardar debe validar el número de control.</small>
                                    </div>

                                    <input type="hidden" name="cc" value="{{$convocatoria->cod_con}}">
                                </form>

                                <br/>
                                <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm float-right" type="button" onclick="guardarTramiteNoAtentado()">Guardar</button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center">
                            <h4 class="text-primary font-weight-bold">Editar Convocatoria</h4>
                        </div>
                        <hr class="sidebar-divider text-bg-dark">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="shadow-sm p-2 col-md-5 float-md-right">
                                    <h1 class="text-danger pr-3 text-center">{{$tramite_noatentado->dtra_numero_tramite}}</h1>
                                    <span class="font-italic text-dark text-center"><?php if($tramite_noatentado->dtra_fecha_registro!=''){echo date('d/m/Y',strtotime($tramite_noatentado->dtra_fecha_registro));} ?></span>
                                </div>
                                <span class="text-primary font-weight-bold text-primary font-italic" style="font-size: 14px">* Datos de la convocatoria</span>
                                <form id="form_tramite">
                                    @csrf
                                <table class="col-md-12 text-dark table">
                                    <tbody>
                                    <tr>
                                        <th class="text-right font-italic" style=" padding-top: 7px">Convocatoria :</th>
                                        <td class="border-bottom border-dark" style=" padding-top: 7px">
                                            <span class="text-secondary font-italic font-weight-bold">{{$convocatoria->con_nombre}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Trámite :</th>
                                        <td class="border-bottom border-dark">
                                            <span class="font-weight-bold">{{$tramite_noatentado->tre_nombre}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic ">Tipo de trámite :</th>
                                        <td class="border-bottom border-dark">
                                            @if($tramite_noatentado->dtra_interno=='t')
                                            <input type="radio" name="tipo_tramite" checked value="t">  INTERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="tipo_tramite" value="f"> EXTERNO
                                            @else
                                                <input type="radio" name="tipo_tramite" value="t"> INTERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="radio" name="tipo_tramite" checked value="f">  EXTERNO
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic ">Nro. Control:</th>
                                        <td class="border-bottom  input-group">
                                            <div class="input-group">
                                                <input class="form-control form-control-sm border" required name="control"  value="{{$tramite_noatentado->dtra_control}}"/>
                                                <span class="text-primary font-weight-bold font-italic"> Nro. Control Reintegro : &nbsp;</span>
                                                <input class="form-control form-control-sm border" required name="reintegro"  value="{{$tramite_noatentado->dtra_valorado_reintegro}}"/>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                    <input type="hidden" name="cd" value="{{$tramite_noatentado->cod_dtra}}">
                                    <input type="hidden" name="cc" value="{{$tramite_noatentado->cod_con}}">
                                </form>
                                @can('editar tramite - noa')
                                <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm float-right" type="button" onclick="enviar('form_tramite','{{url('guardar tramite convocatoria noatentado')}}','panel_noatentado');cargarDatos('{{url('actualizar lista tramite convocatoria/'.$cod_con)}}','panel_lista_tramites')"> Guardar</button>
                                </div>
                                @endcan
                            </div>
                            <div class="col-md-7 shadow border rounded p-2" >
                                    <span class="font-weight-bold text-primary font-italic">* Datos personales</span>
                                    <div class="overflow-auto" style="height: 400px" id="panel_candidato">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover" id="lista" width="100%" cellspacing="0" style="font-size: 12px">

                                                <tr>
                                                    <th>N°</th>
                                                    <th>Nombre</th>
                                                    <th>CI</th>
                                                    <th>COD SIS</th>
                                                    <th>Cargo</th>
                                                    <th>Unidad</th>
                                                    <th>Opciones</th>
                                                </tr>
                                                    <?php $i=1;?>
                                                @foreach($noatentados as $n)
                                                    <?php $sancionado=App\Http\Controllers\Noatentado\SancionadosController::verificarSancionado($n->id_per);?>
                                                    @if($sancionado)
                                                    <tr class="alert-danger">
                                                        @else
                                                        <tr>
                                                        @endif
                                                        <td>{{$i++}}</td>
                                                        <td>{{$n->per_nombre." ".$n->per_apellido}}</td>
                                                        <td>{{$n->per_ci}}</td>
                                                        <td>{{$n->per_cod_sis}}</td>
                                                        <td>{{$n->carg_nombre}}</td>
                                                        <td>{{$n->noa_unidad}}</td>
                                                        <td>
                                                            @if($sancionado && $sancionado->cod_res!='')
                                                                <a href="" class="btn btn-circle btn-light btn-sm text-danger border" data-toggle="modal" data-target="#Noatentado_agregar"
                                                                   onclick="cargarDatos('{{url('ver datos resolucion/'.$sancionado->cod_res)}}','panel_agregar')" title="Ver detalle de la resolución"> <i class="fas fa-file-pdf"></i>
                                                                </a>
                                                            @endif
                                                            @if($tramite_noatentado->dtra_generado=='')
                                                                <a href="#" class="btn btn-sm btn-light btn-circle border" data-toggle="modal" data-target="#Noatentado_agregar" title="Editar candidato"
                                                                   onclick="cargarDatos('{{url('editar candidato convocatoria/'.$tramite_noatentado->cod_dtra.'/'.$n->cod_noa)}}','panel_agregar');">
                                                                    <i class="fas fa-pencil-alt text-primary"></i>
                                                                </a>
                                                                <a href="#"  class="btn btn-sm btn-light btn-circle" data-toggle="modal" data-target="#Noatentado_agregar" title="Eliminar candidato"
                                                                   onclick="cargarDatos('{{url('formulario eliminar candidato/'.$n->cod_noa)}}','panel_agregar');
                                                                    cargarDatos('{{url('actualizar lista tramite convocatoria/'.$cod_con)}}','panel_lista_tramites');">
                                                                    <i class="fas fa-trash-alt text-danger"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                <div class="input-group col-md-12 justify-content-center">
                                    @can('editar tramite - noa')
                                        @if($tramite_noatentado->dtra_qr=='')
                                            <a href="#Noatentado_agregar" class="btn btn-sm btn-primary" data-toggle="modal"
                                               onclick="cargarDatos('{{url('editar candidato convocatoria/'.$tramite_noatentado->cod_dtra.'/0')}}','panel_agregar')">+ Candidato</a> &nbsp; &nbsp;
                                            <a href="#Noatentado_agregar" class="btn btn-sm btn-success" data-toggle="modal"
                                               onclick="cargarDatos('{{url('agregar candidato excel convocatoria/'.$tramite_noatentado->cod_dtra)}}','panel_agregar')">+ Exportar de exel</a> &nbsp; &nbsp;
                                        @endif
                                    @endcan
                                </div>
                            </div>
                       @endif
                    </div>
                </div>
                <input type="hidden" name="cc" value="{{$convocatoria->cod_con}}">
            </div>
        </div><!-- End Formulario Convocatoria -->
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>

<script>
    let candidatosNoAtentado=[];
    let pagoNoAtentadoValidado=false;
    let controlValidadoNoAtentado='';
    let tramiteValidadoNoAtentado='';

    function limpiarTextoNoAtentado(valor){
        return (valor || '').toString().trim();
    }

    function actualizarEstadoPagoNoAtentado(estado,clase,detalle){
        const badge=$('#estado_pago_noa');
        const detallePago=$('#detalle_pago_noa');
        if(badge.length===0 || detallePago.length===0){
            return;
        }

        badge.removeClass('badge-warning badge-success badge-danger badge-info').addClass(clase).text(estado);
        detallePago.text(detalle || '');
    }

    function resetValidacionPagoNoAtentado(){
        pagoNoAtentadoValidado=false;
        controlValidadoNoAtentado='';
        tramiteValidadoNoAtentado='';
        actualizarEstadoPagoNoAtentado('Pendiente','badge-warning','Antes de guardar debe validar el número de control.');
    }

    function renderTablaCandidatosNoAtentado(){
        const tabla=$('#tabla_candidatos_noa tbody');
        if(tabla.length===0){
            return;
        }

        tabla.html('');
        if(candidatosNoAtentado.length===0){
            tabla.append('<tr id="fila_vacia_candidatos_noa"><td colspan="7" class="text-center text-secondary">No hay candidatos registrados.</td></tr>');
        }else{
            for(let i=0;i<candidatosNoAtentado.length;i++){
                const candidato=candidatosNoAtentado[i];
                const cargo=candidato.cargo!=='' ? candidato.cargo : candidato.cargo_nombre;
                tabla.append(
                    '<tr>'+
                    '<td>'+(i+1)+'</td>'+
                    '<td>'+candidato.apellido+' '+candidato.nombre+'</td>'+
                    '<td>'+candidato.ci+'</td>'+
                    '<td>'+candidato.cod_sis+'</td>'+
                    '<td>'+(cargo || '')+'</td>'+
                    '<td>'+(candidato.unidad || '')+'</td>'+
                    '<td><button type="button" class="btn btn-sm btn-light btn-circle border" title="Quitar" onclick="quitarCandidatoNoAtentado('+i+')"><i class="fas fa-trash-alt text-danger"></i></button></td>'+
                    '</tr>'
                );
            }
        }

        $('#candidatos_json_noa').val(JSON.stringify(candidatosNoAtentado));
    }

    function limpiarFormularioCandidatoNoAtentado(){
        $('#noa_ci').val('');
        $('#noa_nombre').val('');
        $('#noa_apellido').val('');
        $('#noa_cod_sis').val('');
        $('#noa_unidad').val('');
        $('#noa_cargo').val('');
        $('#noa_cargo_convocatoria').val('');
    }

    function agregarCandidatoNoAtentado(){
        const ci=limpiarTextoNoAtentado($('#noa_ci').val()).toUpperCase();
        const nombre=limpiarTextoNoAtentado($('#noa_nombre').val()).toUpperCase();
        const apellido=limpiarTextoNoAtentado($('#noa_apellido').val()).toUpperCase();
        const codSis=limpiarTextoNoAtentado($('#noa_cod_sis').val());
        const unidad=limpiarTextoNoAtentado($('#noa_unidad').val()).toUpperCase();
        const cargo=limpiarTextoNoAtentado($('#noa_cargo').val()).toUpperCase();
        const cargoConvocatoria=$('#noa_cargo_convocatoria').val();
        const cargoNombre=$('#noa_cargo_convocatoria option:selected').text();

        if(ci==='' || nombre==='' || apellido===''){
            alert('Debe ingresar CI, nombre y apellido del candidato.');
            return;
        }

        let duplicado=false;
        for(let i=0;i<candidatosNoAtentado.length;i++){
            if(candidatosNoAtentado[i].ci===ci){
                duplicado=true;
                break;
            }
        }

        if(duplicado){
            alert('El candidato ya fue agregado en la lista.');
            return;
        }

        candidatosNoAtentado.push({
            ci:ci,
            nombre:nombre,
            apellido:apellido,
            cod_sis:codSis,
            unidad:unidad,
            cargo:cargo,
            cargo_convocatoria:cargoConvocatoria,
            cargo_nombre:limpiarTextoNoAtentado(cargoNombre),
        });

        renderTablaCandidatosNoAtentado();
        limpiarFormularioCandidatoNoAtentado();
    }

    function quitarCandidatoNoAtentado(indice){
        if(indice<0 || indice>=candidatosNoAtentado.length){
            return;
        }

        candidatosNoAtentado.splice(indice,1);
        renderTablaCandidatosNoAtentado();
    }

    function cargarDatosPersonalesNoa(ci){
        ci=limpiarTextoNoAtentado(ci);
        if(ci===''){
            return;
        }

        const link="{{url('datos_per/')}}"+'/'+ci;
        $.ajax({
            url: link,
            type: 'GET',
            success: function (resp) {
                if(resp==='No'){
                    $('#noa_nombre').val('');
                    $('#noa_apellido').val('');
                    $('#noa_cod_sis').val('');
                }else{
                    const datos=JSON.parse(resp);
                    $('#noa_nombre').val(datos['per_nombre'] || '');
                    $('#noa_apellido').val(datos['per_apellido'] || '');
                    $('#noa_cod_sis').val(datos['per_cod_sis'] || '');
                }
            }
        });
    }

    function validarPagoNoAtentado(){
        const control=limpiarTextoNoAtentado($('#control_noa').val());
        const tramite=limpiarTextoNoAtentado($('#tramite_noa').val());

        if(control===''){
            alert('Ingrese el número de control para validar.');
            return;
        }
        if(tramite===''){
            alert('Seleccione el tipo de trámite.');
            return;
        }

        actualizarEstadoPagoNoAtentado('Validando','badge-info','Consultando recaudaciones...');
        $.ajax({
            url: "{{url('validar pago noatentado/'.$cod_con)}}",
            type: 'POST',
            data: {
                _token: "{{csrf_token()}}",
                control: control,
                tramite: tramite,
                reintegro: limpiarTextoNoAtentado($('#reintegro_noa').val()),
            },
            success: function(resp){
                if(resp && resp.ok){
                    pagoNoAtentadoValidado=true;
                    controlValidadoNoAtentado=control;
                    tramiteValidadoNoAtentado=tramite;
                    actualizarEstadoPagoNoAtentado('Pago válido','badge-success',resp.message || 'Pago validado correctamente.');
                }else{
                    pagoNoAtentadoValidado=false;
                    controlValidadoNoAtentado='';
                    tramiteValidadoNoAtentado='';
                    actualizarEstadoPagoNoAtentado('Pago no válido','badge-danger',(resp && resp.message) ? resp.message : 'No se pudo validar el pago.');
                }
            },
            error: function(xhr){
                pagoNoAtentadoValidado=false;
                controlValidadoNoAtentado='';
                tramiteValidadoNoAtentado='';

                let mensaje='No se pudo validar el pago.';
                if(xhr && xhr.responseJSON){
                    if(xhr.responseJSON.message){
                        mensaje=xhr.responseJSON.message;
                    }else if(xhr.responseJSON.errors){
                        const claves=Object.keys(xhr.responseJSON.errors);
                        if(claves.length>0 && xhr.responseJSON.errors[claves[0]].length>0){
                            mensaje=xhr.responseJSON.errors[claves[0]][0];
                        }
                    }
                }

                actualizarEstadoPagoNoAtentado('Pago no válido','badge-danger',mensaje);
            }
        });
    }

    function guardarTramiteNoAtentado(){
        if($('#form_tramite').length===0){
            return;
        }

        if(candidatosNoAtentado.length===0){
            alert('Debe agregar al menos un candidato antes de guardar.');
            return;
        }

        const control=limpiarTextoNoAtentado($('#control_noa').val());
        const tramite=limpiarTextoNoAtentado($('#tramite_noa').val());
        if(control==='' || tramite===''){
            alert('Complete los datos de trámite y número de control.');
            return;
        }

        if(!pagoNoAtentadoValidado || controlValidadoNoAtentado!==control || tramiteValidadoNoAtentado!==tramite){
            alert('Debe validar el pago del número de control antes de guardar.');
            return;
        }

        $('#candidatos_json_noa').val(JSON.stringify(candidatosNoAtentado));
        enviar('form_tramite','{{url('guardar tramite convocatoria noatentado')}}','panel_noatentado');
        cargarDatos('{{url('actualizar lista tramite convocatoria/'.$cod_con)}}','panel_lista_tramites');
    }

    $(function(){
        if($('#tabla_candidatos_noa').length>0){
            renderTablaCandidatosNoAtentado();
            resetValidacionPagoNoAtentado();
        }
    });
</script>

