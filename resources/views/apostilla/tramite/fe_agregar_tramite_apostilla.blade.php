<div class="modal-content border-bottom-primary shadow-lg apo-add-tramite-content">
        <div class="modal-header bg-verde-oscuro">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Apostilla </h5>
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
            <div class="bg-verde-oscuro centrar_bloque p-1 col-md-7 rounded shadow">
                <h6 class="text-white text-center">Formulario para agregar tramite de apostilla</h6>
            </div>
            <hr class="sidebar-divider"/>
            <div class="row apo-edit-layout apo-edit-layout--two-col">
                <div class="col-md-4 apo-col-left">

                   <div class="shadow-sm p-2 col-md-7 centrar_bloque">
                        <span class="text-primary font-weight-bold"> TRÁMITE</span>
                                        <h1 class="text-danger pr-3 text-center">UAD{{$tramite_apostilla->apos_numero}}</h1>
                   </div>
                   <table class="col-md-12 text-dark table table-sm">
                        <tr>
                            <th colspan="2" class="text-right text-primary"><br/>* DATOS PERSONALES</th>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">CI : </th>
                            <td class="border-bottom border-dark">{{$persona->per_ci}}</td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Passaporte : </th>
                            <td class="border-bottom border-dark">{{$persona->per_pasaporte}}</td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Nombre : </th>
                            <td class="border-bottom border-dark">{{$persona->per_nombre." ".$persona->per_apellido}}</td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Telefono celular : </th>
                            <td class="border-bottom border-dark">{{$persona->per_celular}}</td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Fecha de ingreso : </th>
                            <td class="border-bottom border-dark">{{date('d/m/Y',strtotime($tramite_apostilla->apos_fecha_ingreso))}}</td>
                        </tr>
                        @if($apoderado)
                        <tr>
                            <th colspan="2" class="text-right text-primary">* DATOS DEL APODERADO</th>
                        </tr>

                        <tr>
                            <th class="text-right font-italic">CI apoderado: </th>
                            <td class="border-bottom border-dark">{{$apoderado->apo_ci}}</td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Nombre : </th>
                            <td class="border-bottom border-dark">{{$apoderado->apo_nombre." ".$apoderado->apo_apellido}}</td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic" valign="top">Tipo de apoderado : </th>
                            <td class="border-bottom border-dark">
                                @if($tramite_apostilla->apos_apoderado=='d')
                                    &nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" checked> Declaración jurada<br/>
                                @else
                                    @if($tramite_apostilla->apos_apoderado=='p')
                                        &nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" checked> Poder notariado
                                @endif
                            @endif
                        </tr>
                        @endif
                   </table>

                    <br/>
                </div>
                <!-- ================================LISTA DE DOCUMENTOS====================================-->
                <div class="col-md-8 pl-3 border shadow pt-2 apo-col-center">
                    <span class="text-danger font-italic font-weight-bold" style="font-size: 16px">* Caracteristicas del trámite</span>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <form id="form_agregar_tramite">
                        @csrf
                        <table class="w-100">
                            <tr>
                                <th class="text-dark font-italic">Nombre del trámite : </th>
                                <td class="border-bottom border-dark">
                                    <input type="text" class="form-control form-control-sm" value="{{$apostilla->lis_nombre}}" readonly>
                                </td>
                            </tr>
                            @if($apostilla->lis_tipo=='sid')
                                <tr>
                                    <th class="text-dark font-italic">Numero del trámite : </th>
                                    <td class="border-bottom border-dark">
                                        <div class="input-group pt-1">
                                            <input type="text" class="form-control form-control-sm col-md-3" name="numero"> &nbsp;&nbsp;/&nbsp;&nbsp; Gestión : &nbsp;&nbsp;
                                            <input type="text" class="form-control form-control-sm col-md-3" pattern="[0-9]{4}" name="gestion">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-dark font-italic">N° Control del pago : </th>
                                    <td class="border-bottom border-dark">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm col-sm-6" name="nro_control" id="nro_control_sid">
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <th class="text-dark font-italic">Numero del título : </th>
                                    <td class="border-bottom border-dark">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm col-sm-3" name="numero"> &nbsp;&nbsp;/&nbsp;&nbsp; Gestión : &nbsp;&nbsp;
                                            <input type="text" class="form-control form-control-sm col-sm-3" pattern="[0-9]{4}" name="gestion">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-dark font-italic">N° Control del pago : </th>
                                    <td class="border-bottom border-dark">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm col-sm-6" name="nro_control" id="nro_control_other">
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </table>
                        <input type="hidden" name="cl" value="{{$cod_lis}}" data-campo="tipo-apostilla-hidden">
                        <input type="hidden" name="ca" value="{{$cod_apos}}">
                        <input type="hidden" name="gestion_valorado" value="" data-campo="gestion-api">
                        <input type="hidden" value="0" data-campo="validacion-recaudacion-ok">
                        <input type="hidden" value="" data-campo="preimpreso-api">
                    </form>
                    <div id="validacion-resultado" class="mt-2"></div>
                    <br/>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary btn-sm" onclick="return submitAgregarApostilla();">+ Agregar </button>
        </div>
    </div>

<script>
    let validacionControlOk=false;
    let controlValidadoValor='';
    let timerValidacionControl=null;

    function obtenerNroControlApostilla(){
        const sid=document.getElementById('nro_control_sid');
        const other=document.getElementById('nro_control_other');
        return ((sid ? sid.value : '') || (other ? other.value : '') || '').trim();
    }

    function normalizarTextoApostilla(valor){
        return (valor || '').toString().trim().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'');
    }

    function categoriaResultadoValidacionApostilla(tipo,mensaje,codigo){
        const codigoNorm=normalizarTextoApostilla(codigo).toUpperCase();
        if(tipo==='loading') return 'loading';
        if(tipo==='ok') return 'ok';
        if(codigoNorm==='RATE_LIMIT') return 'rate_limit';
        if(codigoNorm==='SISTEMA_NO_CONFIGURADO') return 'not_configured';
        if(codigoNorm==='API_NO_DISPONIBLE' || codigoNorm==='API_RESPUESTA_INVALIDA') return 'connection';
        if(codigoNorm==='BOLETA_YA_USADA') return 'used';
        if(codigoNorm==='BOLETA_NO_EXISTE' || codigoNorm==='CONTROL_NO_ENCONTRADO') return 'not_found';
        if(codigoNorm==='BOLETA_NO_PERTENECE_PERSONA' || codigoNorm==='BOLETA_NO_CORRESPONDE_TRAMITE' || codigoNorm==='BOLETA_NO_VALIDA') return 'not_match';

        const texto=normalizarTextoApostilla((mensaje || '')+' '+codigoNorm);
        if(texto.indexOf('too many')!==-1 || texto.indexOf('demasiadas solicitudes')!==-1 || texto.indexOf('rate limit')!==-1 || texto.indexOf('429')!==-1) return 'rate_limit';
        if(texto.indexOf('no esta configurado')!==-1 || texto.indexOf('no está configurado')!==-1 || texto.indexOf('sistema_no_configurado')!==-1) return 'not_configured';
        if(texto.indexOf('sin conexion')!==-1 || texto.indexOf('sin conexión')!==-1 || texto.indexOf('no se pudo conectar')!==-1 || texto.indexOf('api_no_disponible')!==-1 || texto.indexOf('timeout')!==-1) return 'connection';
        if(texto.indexOf('ya usado')!==-1 || texto.indexOf('ya fue utilizado')!==-1 || texto.indexOf('ya utilizado')!==-1) return 'used';
        if(texto.indexOf('no se encontro')!==-1 || texto.indexOf('no se encontró')!==-1 || texto.indexOf('boleta no encontrada')!==-1 || texto.indexOf('control no encontrado')!==-1) return 'not_found';
        if(texto.indexOf('no corresponde')!==-1 || texto.indexOf('no pertenece')!==-1 || texto.indexOf('no valida')!==-1 || texto.indexOf('no válida')!==-1) return 'not_match';
        return tipo==='error' ? 'error' : 'warning';
    }

    function iconoCategoriaResultadoValidacionApostilla(categoria){
        if(categoria==='ok') return {clase:'text-success',icon:'fa-check-circle'};
        if(categoria==='loading') return {clase:'text-info',icon:'fa-spinner fa-spin'};
        if(categoria==='rate_limit') return {clase:'text-warning',icon:'fa-clock'};
        if(categoria==='used') return {clase:'text-warning',icon:'fa-ban'};
        if(categoria==='connection') return {clase:'text-warning',icon:'fa-plug'};
        if(categoria==='not_configured') return {clase:'text-muted',icon:'fa-cog'};
        if(categoria==='not_found') return {clase:'text-warning',icon:'fa-search'};
        if(categoria==='not_match') return {clase:'text-warning',icon:'fa-exclamation-circle'};
        if(categoria==='warning') return {clase:'text-warning',icon:'fa-exclamation-circle'};
        return {clase:'text-danger',icon:'fa-times-circle'};
    }

    function setResultadoValidacionApostilla(tipo,mensaje,codigo=''){
        const panel=$('#validacion-resultado');
        if(!panel.length){ return; }

        const categoria=categoriaResultadoValidacionApostilla(tipo,mensaje,codigo);
        const icono=iconoCategoriaResultadoValidacionApostilla(categoria);
        let clase='alert-danger';
        let texto=mensaje || 'No se pudo validar el control del pago.';

        if(categoria==='ok'){
            clase='alert-success';
            texto=mensaje || 'Validado.';
        }else if(categoria==='loading'){
            clase='alert-info';
            texto='Validando número de control...';
        }else if(categoria==='rate_limit' || categoria==='connection' || categoria==='not_configured' || categoria==='not_found' || categoria==='not_match' || categoria==='used' || categoria==='warning'){
            clase='alert-warning';
        }

        panel.html('<div class="alert '+clase+' py-2 mb-0 d-flex align-items-center"><span class="mr-2 '+icono.clase+'"><i class="fas '+icono.icon+'"></i></span><span>'+texto+'</span></div>');
    }

    function extraerAnioDesdeFechaPago(fechaPago){
        const valor=(fechaPago || '').toString().trim();
        if(valor===''){ return ''; }
        const m=valor.match(/(19|20)\d{2}/);
        return m ? m[0] : '';
    }

    function setGestionValoradoDesdeAnio(anio){
        if(!anio){ return; }
        const gestionInput=$('#form_agregar_tramite').find('input[data-campo="gestion-api"]');
        if(!gestionInput.length){ return; }
        gestionInput.val(anio);
    }

    function solicitarValidacionRecaudacion(form,nroControl,codLis,onOk,onFail){
        const codApos=(form.find('input[name="ca"]').val() || '').toString().trim();
        $.ajax({
            url:'{{ url("validar valorado apostilla/$tramite_apostilla->cod_apos") }}',
            type:'POST',
            dataType:'json',
            data:{
                _token:form.find('input[name="_token"]').val(),
                nro_control:parseInt(nroControl,10) || 0,
                cod_lis:parseInt(codLis,10) || 0,
                ca:codApos
            },
            success:function(resp){
                if(resp && resp.ok){
                    onOk(resp);
                    return;
                }
                const msg=(resp && resp.message)
                    ? resp.message
                    : 'No se pudo validar el control del pago.';
                onFail(msg,(resp && resp.code) ? resp.code : '');
            },
            error:function(xhr){
                const msg=(xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : 'No hay conexión. Intente en unos momentos.';
                onFail(msg,(xhr.responseJSON && xhr.responseJSON.code) ? xhr.responseJSON.code : 'API_NO_DISPONIBLE');
            }
        });
    }

    function autocompletarGestionDesdeApi(){
        const nroControl=obtenerNroControlApostilla();
        const form=$('#form_agregar_tramite');
        const codLis=(form.find('input[data-campo="tipo-apostilla-hidden"]').val() || '').toString().trim();

        if(nroControl!=='' && validacionControlOk && controlValidadoValor===nroControl){
            return;
        }

        if(nroControl===''){
            validacionControlOk=false;
            controlValidadoValor='';
            form.find('[data-campo="validacion-recaudacion-ok"]').val('0');
            setResultadoValidacionApostilla('error','Ingrese el N° de control del pago.');
            return;
        }
        if(codLis===''){
            validacionControlOk=false;
            controlValidadoValor='';
            form.find('[data-campo="validacion-recaudacion-ok"]').val('0');
            setResultadoValidacionApostilla('error','Seleccione el tipo de apostilla.');
            return;
        }

        validacionControlOk=false;
        controlValidadoValor='';
        form.find('[data-campo="validacion-recaudacion-ok"]').val('0');
        form.find('input[data-campo="preimpreso-api"]').val('');
        form.find('input[data-campo="gestion-api"]').val('');

        setResultadoValidacionApostilla('loading','');
        solicitarValidacionRecaudacion(form,nroControl,codLis,function(resp){
            const anio=extraerAnioDesdeFechaPago(resp.fecha_pago || '');
            if(anio!==''){
                setGestionValoradoDesdeAnio(anio);
            }

            form.find('input[data-campo="preimpreso-api"]').val(resp.preimpreso || '');
            validacionControlOk=true;
            controlValidadoValor=nroControl;
            form.find('[data-campo="validacion-recaudacion-ok"]').val('1');

            let msg='Validado. Monto Bs. '+(resp.monto || '0');
            if(resp.fecha_pago){
                msg+=' - Fecha '+resp.fecha_pago;
            }
            if(resp.cajero){
                msg+=' - Caja '+resp.cajero;
            }
            setResultadoValidacionApostilla('ok',msg);
        },function(msg,codigo){
            validacionControlOk=false;
            controlValidadoValor='';
            form.find('[data-campo="validacion-recaudacion-ok"]').val('0');
            setResultadoValidacionApostilla('error',msg,codigo);
        });
    }

    function programarValidacionControlApostilla(){
        const nroControl=obtenerNroControlApostilla();
        if(nroControl!=='' && validacionControlOk && controlValidadoValor===nroControl){
            return;
        }

        if(timerValidacionControl!==null){
            clearTimeout(timerValidacionControl);
        }
        timerValidacionControl=setTimeout(function(){
            const nroControl=obtenerNroControlApostilla();
            if(nroControl!==''){
                autocompletarGestionDesdeApi();
            }
        },450);
    }

    function submitAgregarApostilla(){
        const form=$('#form_agregar_tramite');
        const nroControl=obtenerNroControlApostilla();
        const codLis=(form.find('input[data-campo="tipo-apostilla-hidden"]').val() || '').toString().trim();
        const codApos=(form.find('input[name="ca"]').val() || '').toString().trim();
        if(nroControl===''){
            setResultadoValidacionApostilla('error','Ingrese el N° de control del pago.');
            return false;
        }
        if(codLis===''){
            setResultadoValidacionApostilla('error','Seleccione el tipo de apostilla.');
            return false;
        }

        if(validacionControlOk && controlValidadoValor===nroControl && form.find('[data-campo="validacion-recaudacion-ok"]').val()==='1'){
            if((form.find('input[data-campo="gestion-api"]').val() || '').trim()===''){
                setResultadoValidacionApostilla('error','No se pudo obtener la gestión del pago desde la API.');
                return false;
            }

            $.ajax({
                url:'{{url("guardar agregar tramite apostilla")}}',
                type:'POST',
                dataType:'json',
                headers:{
                    'Accept':'application/json'
                },
                data:form.serialize(),
                success:function(resp){
                    if(resp && resp.ok){
                        cargarDatos('{{url("ajax tabla agregar")}}/'+codApos,'panel_lista_tramites_apostilla');
                        cargarDatos('{{url("listar tramite apostilla tabla/".date("Y-m-d",strtotime($tramite_apostilla->apos_fecha_ingreso)))}}','panel_tabla_tramites');
                        $('#tramite_apostilla').modal('hide');
                        return;
                    }

                    const msg=(resp && resp.message)
                        ? resp.message
                        : 'No se pudo registrar el trámite.';
                    setResultadoValidacionApostilla('error',msg,(resp && resp.code) ? resp.code : '');
                },
                error:function(xhr){
                    const msg=(xhr.responseJSON && xhr.responseJSON.message)
                        ? xhr.responseJSON.message
                        : 'No se pudo registrar el trámite. Intente nuevamente.';
                    setResultadoValidacionApostilla('error',msg,(xhr.responseJSON && xhr.responseJSON.code) ? xhr.responseJSON.code : 'API_NO_DISPONIBLE');
                }
            });
            return false;
        }

        setResultadoValidacionApostilla('loading','');
        solicitarValidacionRecaudacion(form,nroControl,codLis,function(respValidacion){
            const anio=extraerAnioDesdeFechaPago(respValidacion.fecha_pago || '');
            if(anio!==''){
                setGestionValoradoDesdeAnio(anio);
            }
            form.find('input[data-campo="preimpreso-api"]').val(respValidacion.preimpreso || '');
            validacionControlOk=true;
            controlValidadoValor=nroControl;
            form.find('[data-campo="validacion-recaudacion-ok"]').val('1');

            if((form.find('input[data-campo="gestion-api"]').val() || '').trim()===''){
                setResultadoValidacionApostilla('error','No se pudo obtener la gestión del pago desde la API.');
                return;
            }

            $.ajax({
                url:'{{url("guardar agregar tramite apostilla")}}',
                type:'POST',
                dataType:'json',
                headers:{
                    'Accept':'application/json'
                },
                data:form.serialize(),
                success:function(resp){
                    if(resp && resp.ok){
                        cargarDatos('{{url("ajax tabla agregar")}}/'+codApos,'panel_lista_tramites_apostilla');
                        cargarDatos('{{url("listar tramite apostilla tabla/".date("Y-m-d",strtotime($tramite_apostilla->apos_fecha_ingreso)))}}','panel_tabla_tramites');
                        $('#tramite_apostilla').modal('hide');
                        return;
                    }

                    const msg=(resp && resp.message)
                        ? resp.message
                        : 'No se pudo registrar el trámite.';
                    setResultadoValidacionApostilla('error',msg);
                },
                error:function(xhr){
                    const msg=(xhr.responseJSON && xhr.responseJSON.message)
                        ? xhr.responseJSON.message
                        : 'No se pudo registrar el trámite. Intente nuevamente.';
                    setResultadoValidacionApostilla('error',msg);
                }
            });
        },function(msg,codigo){
            validacionControlOk=false;
            controlValidadoValor='';
            form.find('[data-campo="validacion-recaudacion-ok"]').val('0');
            setResultadoValidacionApostilla('error',msg,codigo);
        });
        return false;
    }

    $(document)
        .off('input.apostillaControl','#nro_control_sid, #nro_control_other')
        .on('input.apostillaControl','#nro_control_sid, #nro_control_other',function(){
            validacionControlOk=false;
            controlValidadoValor='';
            $('#form_agregar_tramite').find('[data-campo="validacion-recaudacion-ok"]').val('0');
            $('#form_agregar_tramite').find('input[data-campo="preimpreso-api"]').val('');
            $('#form_agregar_tramite').find('input[data-campo="gestion-api"]').val('');
            $('#validacion-resultado').html('');
            programarValidacionControlApostilla();
        });

    // Tipo fijo del trámite en este formulario (comportamiento original).
</script>


