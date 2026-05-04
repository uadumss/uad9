<style>
    .apo-inline-hint {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 4px;
        padding: 2px 6px;
        font-size: 11px;
        border-radius: 3px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        line-height: 1.2;
    }
    .apo-inline-hint:empty { display: none; }
    .apo-inline-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-left: 8px;
        padding: 2px 6px;
        font-size: 11px;
        border-radius: 3px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        line-height: 1.2;
    }
    .apo-inline-status.text-info { background: #dbeafe; border-color: #bfdbfe; color: #1d4ed8; }
    .apo-inline-status.text-success { background: #ecfdf5; border-color: #a7f3d0; color: #047857; }
    .apo-inline-status.text-danger { background: #fef2f2; border-color: #fca5a5; color: #b91c1c; }
</style>

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
                                            <input type="text" class="form-control form-control-sm col-md-3" name="numero" inputmode="numeric" pattern="[0-9]*" maxlength="20"> &nbsp;&nbsp;/&nbsp;&nbsp; Gestión : &nbsp;&nbsp;
                                            <input type="text" class="form-control form-control-sm col-md-3" pattern="[0-9]{4}" name="gestion" inputmode="numeric" maxlength="4">
                                        </div>
                                        <div class="apo-inline-hint" data-campo="error-numero"></div>
                                        <div class="apo-inline-hint" data-campo="error-gestion"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-dark font-italic">N° Control del pago : </th>
                                    <td class="border-bottom border-dark">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm col-sm-6" name="nro_control" id="nro_control_sid" inputmode="numeric" pattern="[0-9]*" maxlength="20">
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <th class="text-dark font-italic">Numero del título : </th>
                                    <td class="border-bottom border-dark">
                                        <div class="input-group pt-1">
                                            <input type="text" class="form-control form-control-sm col-md-3" name="numero" data-campo="numero-sitra" inputmode="numeric" pattern="[0-9]*" maxlength="20"> &nbsp;&nbsp;/&nbsp;&nbsp; Gestión : &nbsp;&nbsp;
                                            <input type="text" class="form-control form-control-sm col-md-3" pattern="[0-9]{4}" name="gestion" data-campo="gestion-sitra" inputmode="numeric" maxlength="4">
                                            &nbsp;&nbsp;<span class="btn btn-light btn-circle btn-sm text-muted" data-campo="icono-sitra-estado" title="Verificación SITRA"><i class="fas fa-minus-circle"></i></span>
                                        </div>
                                        <div class="apo-inline-hint" data-campo="error-numero"></div>
                                        <div class="apo-inline-hint" data-campo="error-gestion"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-dark font-italic">N° Control del pago : </th>
                                    <td class="border-bottom border-dark">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm col-sm-6" name="nro_control" id="nro_control_other" inputmode="numeric" pattern="[0-9]*" maxlength="20">
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
            <button type="button" class="btn btn-primary btn-sm" data-campo="btn-agregar-apostilla" onclick="return submitAgregarApostilla();">+ Agregar </button>
            <span class="apo-inline-status" data-campo="estado-accion-apostilla"></span>
        </div>
    </div>

<script>
    let validacionControlOk=false;
    let controlValidadoValor='';
    let timerValidacionControl=null;
    let envioAgregarApostillaEnCurso=false;
    let apostillaSitraSeq=0;
    let timerValidacionSitra=null;

    function obtenerBotonAgregarApostilla(){
        return document.querySelector('[data-campo="btn-agregar-apostilla"]');
    }
    function setErrorAgregarApostilla(campo,mensaje){
        const el=$('[data-campo="error-'+campo+'"]');
        if(!el.length){return;}
        el.text((mensaje||'').toString());
    }
    function limpiarErroresAgregarApostilla(){
        setErrorAgregarApostilla('numero','');
        setErrorAgregarApostilla('gestion','');
    }
    function setEstadoAccionAgregarApostilla(mensaje,estado){
        const el=$('[data-campo="estado-accion-apostilla"]');
        if(!el.length){return;}
        el.removeClass('text-success text-danger text-info');
        if(estado==='loading'){el.addClass('text-info');}
        else if(estado==='ok'){el.addClass('text-success');}
        else if(estado==='error'){el.addClass('text-danger');}
        el.html((mensaje||'').toString());
    }
    function setBotonCargandoApostillaForm(btn,texto){
        if(!btn){return;}
        if(btn.dataset.loading==='1'){return;}
        btn.dataset.loading='1';
        btn.dataset.originalHtml=btn.innerHTML;
        btn.classList.add('disabled');
        btn.setAttribute('aria-busy','true');
        btn.setAttribute('disabled','disabled');
        btn.innerHTML='<i class="fas fa-spinner fa-spin"></i>'+(texto ? ' '+texto : ' Procesando...');
    }
    function limpiarBotonCargandoApostillaForm(btn){
        if(!btn || btn.dataset.loading!=='1'){return;}
        if(btn.dataset.originalHtml){btn.innerHTML=btn.dataset.originalHtml;}
        btn.classList.remove('disabled');
        btn.removeAttribute('aria-busy');
        btn.removeAttribute('disabled');
        btn.dataset.loading='0';
    }
    function resetValidacionPagoApostilla(){
        validacionControlOk=false;
        controlValidadoValor='';
        if(timerValidacionControl!==null){clearTimeout(timerValidacionControl);timerValidacionControl=null;}
        const form=$('#form_agregar_tramite');
        form.find('[data-campo="validacion-recaudacion-ok"]').val('0');
        form.find('input[data-campo="preimpreso-api"]').val('');
        form.find('input[data-campo="gestion-api"]').val('');
        $('#validacion-resultado').html('');
        limpiarErroresAgregarApostilla();
        setEstadoAccionAgregarApostilla('','');
    }

    function obtenerNroControlApostilla(){
        const sid=document.getElementById('nro_control_sid');
        const other=document.getElementById('nro_control_other');
        return ((sid ? sid.value : '') || (other ? other.value : '') || '').trim();
    }

    function normalizarTextoApostilla(valor){
        return (valor || '').toString().trim().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'');
    }

    function limpiarTextoSitraApostilla(texto){return (texto||'').toString().replace(/\s+/g,' ').trim();}
    function limitarTextoSitraApostilla(texto,maximo){var txt=limpiarTextoSitraApostilla(texto),max=(typeof maximo==='number'&&maximo>10)?maximo:260;return txt.length<=max?txt:txt.substring(0,max-3)+'...';}
    function normalizarClaveSitraApostilla(texto){return limpiarTextoSitraApostilla(texto).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'');}
    function compactarMensajeSitraApostilla(mensaje,respaldo){
        var texto=(mensaje||'').toString().trim(),fallback=(respaldo||'').toString().trim();
        if(texto==='')return fallback;
        var normal=normalizarClaveSitraApostilla(texto);
        if(normal.indexOf('verificando')!==-1||normal.indexOf('validando')!==-1)return 'Validando en SITRA/SID...';
        if(normal==='sitra pendiente.'||normal==='sitra pendiente')return 'SITRA pendiente.';
        if((normal.indexOf('complete')!==-1||normal.indexOf('completar')!==-1)&&normal.indexOf('gestion')!==-1)return 'Complete gestion para validar SITRA.';
        if(normal.indexOf('seleccione')!==-1&&normal.indexOf('tipo')!==-1)return 'Seleccione tipo para validar SITRA.';
        if(normal.indexOf('no aplica')!==-1)return 'No aplica para este tipo.';
        if(normal.indexOf('no disponible')!==-1||normal.indexOf('no se pudo conectar')!==-1)return 'SITRA/SID no disponible.';
        if(normal.indexOf('no existe')!==-1||normal.indexOf('no se encontro')!==-1||normal.indexOf('no se encontró')!==-1)return 'No existe en SITRA/SID.';
        if(normal.indexOf('no coincide')!==-1)return 'Existe, pero no coincide.';
        if(normal.indexOf('coincide')!==-1)return 'Coincide en SITRA/SID.';
        if(texto.length>140)return fallback!==''?fallback:texto.substring(0,137)+'...';
        return texto;
    }
    function construirDetalleSitraApostilla(resumenCorto,mensajeOriginal){
        var resumen=limpiarTextoSitraApostilla(resumenCorto||'SITRA pendiente.'),original=limpiarTextoSitraApostilla(mensajeOriginal||'');
        if(original===''||original.toLowerCase()===resumen.toLowerCase())return resumen;
        return limitarTextoSitraApostilla(resumen+' Detalle: '+original,280);
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
        limpiarErroresAgregarApostilla();
        if(nroControl===''){
            setResultadoValidacionApostilla('error','Ingrese el N° de control del pago.');
            return false;
        }
        if(codLis===''){
            setResultadoValidacionApostilla('error','Seleccione el tipo de apostilla.');
            return false;
        }
        const numeroDocumento=(form.find('input[name="numero"]').val() || '').toString().trim();
        const gestionDocumento=(form.find('input[name="gestion"]').val() || '').toString().trim();
        if(numeroDocumento!=='' && !/^\d+$/.test(numeroDocumento)){
            setErrorAgregarApostilla('numero','El numero debe ser numerico.');
            return false;
        }
        if(gestionDocumento!=='' && !/^\d{4}$/.test(gestionDocumento)){
            setErrorAgregarApostilla('gestion','La gestion debe tener 4 digitos.');
            return false;
        }
        if(envioAgregarApostillaEnCurso){
            return false;
        }
        const btn=obtenerBotonAgregarApostilla();
        const iniciarEnvio=function(){
            if(envioAgregarApostillaEnCurso){return false;}
            envioAgregarApostillaEnCurso=true;
            setBotonCargandoApostillaForm(btn,'Agregando...');
            setEstadoAccionAgregarApostilla('<i class="fas fa-spinner fa-spin"></i> Procesando...','loading');
            return true;
        };
        const finalizarEnvio=function(){
            envioAgregarApostillaEnCurso=false;
            limpiarBotonCargandoApostillaForm(btn);
        };

        if(validacionControlOk && controlValidadoValor===nroControl && form.find('[data-campo="validacion-recaudacion-ok"]').val()==='1'){
            if((form.find('input[data-campo="gestion-api"]').val() || '').trim()===''){
                setResultadoValidacionApostilla('error','No se pudo obtener la gestión del pago desde la API.');
                return false;
            }
            if(!iniciarEnvio()){
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
                        resetValidacionPagoApostilla();
                        finalizarEnvio();
                        setEstadoAccionAgregarApostilla('Listo.','ok');
                        $('#tramite_apostilla').modal('hide');
                        return;
                    }

                    const msg=(resp && resp.message)
                        ? resp.message
                        : 'No se pudo registrar el trámite.';
                    setResultadoValidacionApostilla('error',msg,(resp && resp.code) ? resp.code : '');
                    finalizarEnvio();
                    setEstadoAccionAgregarApostilla('Error.','error');
                },
                error:function(xhr){
                    const msg=(xhr.responseJSON && xhr.responseJSON.message)
                        ? xhr.responseJSON.message
                        : 'No se pudo registrar el trámite. Intente nuevamente.';
                    setResultadoValidacionApostilla('error',msg,(xhr.responseJSON && xhr.responseJSON.code) ? xhr.responseJSON.code : 'API_NO_DISPONIBLE');
                    finalizarEnvio();
                    setEstadoAccionAgregarApostilla('Error.','error');
                }
            });
            return false;
        }
        if(!iniciarEnvio()){
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
                finalizarEnvio();
                setEstadoAccionAgregarApostilla('Error.','error');
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
                        resetValidacionPagoApostilla();
                        finalizarEnvio();
                        setEstadoAccionAgregarApostilla('Listo.','ok');
                        $('#tramite_apostilla').modal('hide');
                        return;
                    }

                    const msg=(resp && resp.message)
                        ? resp.message
                        : 'No se pudo registrar el trámite.';
                    setResultadoValidacionApostilla('error',msg);
                    finalizarEnvio();
                    setEstadoAccionAgregarApostilla('Error.','error');
                },
                error:function(xhr){
                    const msg=(xhr.responseJSON && xhr.responseJSON.message)
                        ? xhr.responseJSON.message
                        : 'No se pudo registrar el trámite. Intente nuevamente.';
                    setResultadoValidacionApostilla('error',msg);
                    finalizarEnvio();
                    setEstadoAccionAgregarApostilla('Error.','error');
                }
            });
        },function(msg,codigo){
            validacionControlOk=false;
            controlValidadoValor='';
            form.find('[data-campo="validacion-recaudacion-ok"]').val('0');
            setResultadoValidacionApostilla('error',msg,codigo);
            finalizarEnvio();
            setEstadoAccionAgregarApostilla('Error.','error');
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

    function actualizarEstadoSitraApostilla(clase,icono,mensaje,detalleExtra){
        const iconoElement=$('span[data-campo="icono-sitra-estado"]');
        if(!iconoElement.length){return;}
        var resumen=compactarMensajeSitraApostilla(mensaje,'SITRA pendiente.');
        var detalle=construirDetalleSitraApostilla(resumen,mensaje);
        if(detalleExtra){
            detalle=(detalle+' '+detalleExtra).trim();
        }
        iconoElement.removeClass('text-success text-warning text-danger text-info text-muted').addClass(clase);
        iconoElement.find('i').attr('class','fas '+icono);
        iconoElement.attr('title','Ver detalle SITRA').attr('aria-label',resumen).attr('data-detalle-sitra',detalle);
        if(typeof iconoElement.popover==='function'){
            iconoElement.removeAttr('data-popover-visible').popover('hide');
        }else{
            iconoElement.removeAttr('data-popover-visible');
        }
    }

    function togglePopoverSitraApostilla(trigger,detalle){
        var icono=$(trigger);if(!icono.length)return false;
        if(typeof icono.popover!=='function'){return false;}
        var visible=icono.attr('data-popover-visible')==='1';
        if(visible){icono.popover('hide').removeAttr('data-popover-visible');return false;}
        $('[data-campo="icono-sitra-estado"]').not(icono).popover('hide').removeAttr('data-popover-visible');
        icono.popover('dispose').popover({container:'body',trigger:'manual',placement:'top',content:(detalle||'Sin detalle disponible').toString(),html:false}).popover('show');
        icono.attr('data-popover-visible','1');
        return false;
    }

    function validarSitraApostilla(){
        const form=$('#form_agregar_tramite');
        const numeroInput=form.find('input[data-campo="numero-sitra"]');
        const gestionInput=form.find('input[data-campo="gestion-sitra"]');
        if(!numeroInput.length){return;}

        const numero=(numeroInput.val()||'').toString().trim();
        const gestion=(gestionInput.val()||'').toString().trim();
        const codLis=(form.find('input[data-campo="tipo-apostilla-hidden"]').val()||'').toString().trim();

        if(numero===''||numero==='-'){actualizarEstadoSitraApostilla('text-muted','fa-minus-circle','SITRA pendiente.');return;}
        if(gestion===''){actualizarEstadoSitraApostilla('text-muted','fa-minus-circle','Complete gestion para validar SITRA.');return;}
        if(codLis===''){actualizarEstadoSitraApostilla('text-muted','fa-minus-circle','Seleccione tipo para validar SITRA.');return;}

        const requestSeq=++apostillaSitraSeq;
        actualizarEstadoSitraApostilla('text-info','fa-spinner fa-spin','Validando SITRA/SID...');

        $.ajax({
            url:'{{url("validar sitra apostilla/".$cod_apos)}}',
            type:'POST',
            dataType:'json',
            data:{
                _token:form.find('input[name="_token"]').val(),
                numero:numero,
                gestion:gestion,
                cl:parseInt(codLis,10)||0
            },
            success:function(resp){
                if(requestSeq!==apostillaSitraSeq){return;}
                if(!resp||resp.aplica===false){
                    actualizarEstadoSitraApostilla('text-muted','fa-minus-circle',(resp&&resp.message)?resp.message:'No aplica para este tipo.');
                    return;
                }

                let estado=(resp&&resp.estado!==undefined&&resp.estado!==null)?String(resp.estado).trim():'';
                const fuente=(resp&&resp.fuente)?String(resp.fuente).toLowerCase():'sitra';
                let mensaje=(resp&&resp.message)?String(resp.message):'';
                let fuenteDetalle='';
                if(fuente==='sid')fuenteDetalle='Fuente: SID.';
                else if(fuente==='sitra_sid')fuenteDetalle='Fuente: SITRA y SID.';
                else if(fuente==='ninguno')fuenteDetalle='Fuente: Ninguna.';

                let extraDetalle='';
                if(estado==='0'){
                    const extra=[];
                    if(resp && resp.numero) extra.push('Nro: '+resp.numero);
                    if(resp && resp.gestion) extra.push('Gestión: '+resp.gestion);
                    if(resp && resp.tipo) extra.push('Tipo: '+resp.tipo);
                    if(resp && resp.titulo) extra.push('Título: '+resp.titulo);
                    if(extra.length){extraDetalle=extra.join(' | ');} 
                }
                if(fuenteDetalle!==''){
                    extraDetalle = extraDetalle!=='' ? (extraDetalle+' '+fuenteDetalle) : fuenteDetalle;
                }

                if((estado===''||estado==='null'||estado==='undefined')&&fuente==='sitra_sid')estado='2';
                if((estado===''||estado==='null'||estado==='undefined')&&mensaje.toLowerCase().indexOf('no existe')!==-1)estado='2';
                if((estado===''||estado==='null'||estado==='undefined')&&mensaje.toLowerCase().indexOf('no coincide')!==-1)estado='1';

                if(mensaje===''){
                    if(estado==='0')mensaje='Coincide en SITRA/SID.';
                    else if(estado==='1')mensaje='Existe, pero no coincide.';
                    else if(estado==='2')mensaje='No existe en SITRA/SID.';
                    else mensaje='SITRA pendiente.';
                }
                if(estado==='0')actualizarEstadoSitraApostilla('text-success','fa-check-circle',mensaje,extraDetalle);
                else if(estado==='1')actualizarEstadoSitraApostilla('text-danger','fa-times-circle',mensaje,extraDetalle);
                else if(estado==='2')actualizarEstadoSitraApostilla('text-danger','fa-times-circle',mensaje,extraDetalle);
                else actualizarEstadoSitraApostilla('text-muted','fa-minus-circle',mensaje,extraDetalle);
            },
            error:function(xhr){
                if(requestSeq!==apostillaSitraSeq){return;}
                const msg=(xhr.responseJSON&&xhr.responseJSON.message)?xhr.responseJSON.message:'SITRA/SID no disponible.';
                actualizarEstadoSitraApostilla('text-danger','fa-times-circle',msg);
            }
        });
    }

    function programarValidacionSitraApostilla(){
        if(timerValidacionSitra!==null){clearTimeout(timerValidacionSitra);}
        timerValidacionSitra=setTimeout(function(){validarSitraApostilla();},350);
    }

    $(document)
        .off('input.apostillaSitra','input[data-campo="numero-sitra"],input[data-campo="gestion-sitra"]')
        .on('input.apostillaSitra','input[data-campo="numero-sitra"],input[data-campo="gestion-sitra"]',function(){
            programarValidacionSitraApostilla();
        });

    $(document)
        .off('click.apostillaSitraDetalle','[data-campo="icono-sitra-estado"]')
        .on('click.apostillaSitraDetalle','[data-campo="icono-sitra-estado"]',function(e){
            e.preventDefault();
            var detalle=($(this).attr('data-detalle-sitra')||'').toString();
            return togglePopoverSitraApostilla(this,detalle);
        });

    $(document)
        .off('click.apostillaSitraCerrar')
        .on('click.apostillaSitraCerrar',function(e){
            if($(e.target).closest('[data-campo="icono-sitra-estado"],.popover').length===0){
                if(typeof $.fn.popover==='function'){
                    $('[data-campo="icono-sitra-estado"]').popover('hide').removeAttr('data-popover-visible');
                }else{
                    $('[data-campo="icono-sitra-estado"]').removeAttr('data-popover-visible');
                }
            }
        });

    $(document)
        .off('input.apostillaErrores','input[name="numero"],input[name="gestion"]')
        .on('input.apostillaErrores','input[name="numero"],input[name="gestion"]',function(){
            if($(this).attr('name')==='numero'){setErrorAgregarApostilla('numero','');}
            if($(this).attr('name')==='gestion'){setErrorAgregarApostilla('gestion','');}
        });

    // Tipo fijo del trámite en este formulario (comportamiento original).

</script>


