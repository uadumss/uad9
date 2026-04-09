    <?php //$fecha=date('Y-m-d',strtotime($apostilla->apos_fecha_ingreso))?>
    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Apostilla </h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body" style="font-size: smaller">
            @if(Session::has('exitoagregar'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span class="font-weight-bold">{!! session('exitoagregar') !!}</span>
                </div>
            @endif
                @if(Session::has('erroragregar'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="font-weight-bold">{!! session('erroragregar') !!}</span>
                    </div>
                @endif

            <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                <h6 class="text-white text-center">Formulario para editar tramite de apostilla</h6>
            </div>
            <hr class="sidebar-divider"/>
            <div class="row apo-edit-layout {{ $cod_apos!=0 ? 'apo-edit-layout--quick-two' : '' }}">
                <div class="col-md-3 apo-col-left">

                        <div class="shadow-sm p-2 col-md-7 centrar_bloque">
                            <span class="text-primary font-weight-bold"> TRÁMITE</span>
                            @if($cod_apos!=0)
                                <h1 class="text-danger pr-3 text-center">UAD{{$tramite_apostilla->apos_numero}}</h1>
                                <span class="font-italic text-dark font-weight-bold centrar_bloque">
                                        <?php if($tramite_apostilla->apos_fecha_ingreso!=''){echo date('d/m/Y',strtotime($tramite_apostilla->apos_fecha_ingreso));} ?>
                                </span>
                            @endif
                        </div>
                    @if($cod_apos==0)
                    <form id="form_tramite_apostilla">
                        @csrf
                            <table class="table-hover col-md-12 text-dark">
                                <tr>
                                    <th colspan="2" class="text-right text-primary"><br/>* DATOS PERSONALES</th>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">CI : </th>
                                    <td class="border-bottom border-dark">

                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               name="ci" onchange="cargarDatosPersonales(this.value)" /></td>

                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Nombres : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               required name="nombre" id="nombre" /></td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Apellidos : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               required name="apellido" id="apellido" /></td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Teléfono celular : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               required name="celular" id="celular" pattern="[0-8]{1-8}" /></td>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right text-primary"><br/>* DATOS DEL APODERADO</th>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">CI apoderado: </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               name="ci_apoderado" onchange="cargarDatosApoderado(this.value)" /></td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Apellidos : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               required name="apellido_apoderado" id="apellido_apoderado" /></td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Nombres : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               required name="nombre_apoderado" id="nombre_apoderado" /></td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic" valign="top">Tipo de apoderado : </th>
                                    <td class="border-bottom border-dark">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" checked> Declaración jurada<br/>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p"> Poder notariado
                                    </td>
                                </tr>
                            </table>

                            <br/>
                            <input type="hidden" name="ca" value="{{$cod_apos}}">
                        </form>
                            @can('crear trámite - apo')
                                <button type="submit" class="btn btn-primary btn-sm float-md-right" onclick="enviar('form_tramite_apostilla','{{url("guardar tramite apostilla")}}','panel_apostilla');cargarDatos('{{url("listar tramite apostilla tabla/".date('Y-m-d'))}}','panel_tabla_tramites')"> Guardar </button>
                            @endcan
                    @else
                        <form id="form_tramite_apostilla">
                            @csrf
                            <table class="col-md-12 text-dark table table-sm">
                                <tr>
                                    <th colspan="2" class="text-right text-primary"><br/>* DATOS PERSONALES</th>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">CI : </th>
                                    <td class="border-bottom border-dark">{{$persona->per_ci}}</td>
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

                                <tr>
                                    <th colspan="2" class="text-right text-primary">* DATOS DEL APODERADO</th>
                                </tr>
                                @if($apoderado)
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
                                            </td>
                                    </tr>
                                @else
                                    @can('editar apoderado - apo')
                                    <tr>
                                        <th class="text-right font-italic">CI apoderado: </th>
                                        <td class="border-bottom border-dark">
                                            <input class="form-control form-control-sm border-0" placeholder=""
                                                   name="ci_apoderado" onchange="cargarDatosApoderado(this.value)" /></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Apellidos : </th>
                                        <td class="border-bottom border-dark">
                                            <input class="form-control form-control-sm border-0" placeholder=""
                                                   required name="apellido_apoderado" id="apellido_apoderado" /></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Nombres : </th>
                                        <td class="border-bottom border-dark">
                                            <input class="form-control form-control-sm border-0" placeholder=""
                                                   required name="nombre_apoderado" id="nombre_apoderado" /></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic" valign="top">Tipo de apoderado : </th>
                                        <td class="border-bottom border-dark">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    <input type="radio" name="tipo" value="d" checked> Declaración jurada<br/>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p"> Poder notariado
                                        </td>
                                    </tr>
                                    @endcan
                            @endif
                            </table>
                            <input type="hidden" name="ca" value="{{$tramite_apostilla->cod_apos}}">
                        </form>
                            @can('editar apoderado - apo')
                                @if(!$apoderado)
                                    <button type="submit" class="btn btn-primary btn-sm float-md-right" onclick="enviar('form_tramite_apostilla','{{url("guardar apoderado tramite apostilla")}}','panel_apostilla')"> Guardar Apoderado </button>
                                @endif
                            @endcan
                    @endif
                    <br/>
                </div>
                <!-- ================================LISTA DE DOCUMENTOS====================================-->
                @if($cod_apos!=0)
                <div class="col-md-9 pl-3 apo-col-main">
                    @can('agregar documento - apo')
                        @if($tramite_apostilla->apos_estado<2)
                            <div class="border shadow rounded p-3 mb-3 bg-white apo-quick-card">
                                <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 16px">* Registro rápido de apostillas</span>
                                </div>
                                <hr class="my-2"/>

                                <form id="form_agregar_tramite_rapido">
                                    @csrf
                                    <div class="form-row align-items-end">
                                        <div class="col-md-4 mb-2">
                                            <label class="font-italic text-dark mb-1">N° control del pago</label>
                                            <div class="d-flex align-items-center">
                                                <input type="text" class="form-control form-control-sm" name="nro_control" id="nro_control_rapido" autocomplete="off">
                                                <span class="btn btn-light btn-circle btn-sm text-secondary ml-2 apo-estado-pago-icon"
                                                      data-campo="estado-pago-icon"
                                                      title="Pago pendiente de validación"
                                                      tabindex="0"
                                                      onclick="mostrarDetallePagoRapido(event,this);">
                                                    <i class="fas fa-minus-circle"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-8 mb-2">
                                            <label class="font-italic text-dark mb-1">Trámite detectado</label>
                                            <input type="text" class="form-control form-control-sm" data-campo="tramite-detectado" readonly placeholder="Pendiente de validación">
                                        </div>
                                    </div>

                                    <div class="form-row align-items-end">
                                        <div class="col-md-6 mb-2">
                                            <label class="font-italic text-dark mb-1" data-campo="label-documento">N° título / resolución</label>
                                            <input type="text" class="form-control form-control-sm" name="numero" autocomplete="off">
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="font-italic text-dark mb-1">Gestión documento</label>
                                            <input type="text" class="form-control form-control-sm" name="gestion" pattern="[0-9]{4}" autocomplete="off">
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <button type="button" class="btn btn-sm btn-primary btn-block" onclick="return submitAgregarApostillaRapida();">+ Agregar trámite</button>
                                        </div>
                                    </div>

                                    <input type="hidden" name="cl" value="" data-campo="tipo-apostilla-hidden">
                                    <input type="hidden" name="ca" value="{{$cod_apos}}">
                                    <input type="hidden" name="gestion_valorado" value="" data-campo="gestion-api">
                                    <input type="hidden" value="0" data-campo="validacion-recaudacion-ok">
                                    <input type="hidden" value="" data-campo="preimpreso-api">
                                </form>

                            </div>
                        @else
                            <div class="alert alert-secondary py-2">
                                Este trámite ya fue firmado/entregado. No se pueden agregar más documentos.
                            </div>
                        @endif
                    @endcan

                    <div class="border shadow rounded p-3 bg-white apo-selected-card">
                        <span class="text-danger font-italic font-weight-bold" style="font-size: 16px">* Trámites seleccionados</span>

                        <div id="panel_lista_tramites_apostilla" class="overflow-auto" style="height: 360px;">
                            <table class="table table-sm table-hover table-info rounded apo-table-responsive-text">
                                <tr class="bg-gradient-info text-white p-2">
                                    <th>Nº</th>
                                    <th>Nombre</th>
                                    <th>N° trámite</th>
                                    <th>N° Documento</th>
                                    <th>Valorado</th>
                                    <th>Opciones</th>
                                </tr>
                                <?php $i=1?>
                                @foreach($detalle_apostilla as $d)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$d->lis_nombre}}</td>
                                        <td>{{$d->dapo_numero}}</td>
                                        <td><span class="font-weight-bolder">{{$d->dapo_numero_documento}}</span>{{" / ".$d->dapo_gestion_documento}}</td>
                                        <td class="bg-gray-200 text-right"><span class="font-weight-bolder">{{$d->dapo_valorado_preimpreso}}</span>{{" / ".$d->dapo_valorado_gestion}}</td>

                                        <td>
                                            @can('quitar doumento - apo')
                                                @if($tramite_apostilla->apos_estado<=1)
                                                    <a href="#" class="btn btn-light btn-circle btn-sm text-dark"
                                                       onclick="cargarDatos('{{url("eliminar tramite agregado apostilla/$d->cod_dapo")}}','panel_lista_tramites_apostilla');cargarDatos('{{url("listar tramite apostilla tabla/".date('Y-m-d',strtotime($tramite_apostilla->apos_fecha_ingreso)))}}','panel_tabla_tramites')"
                                                       title="Eliminar trámite"> <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                @else
                                                    <i class="fas fa"></i>
                                                @endif
                                            @endcan
                                        </td>
                                    </tr>
                                    <?php $i+=1?>
                                @endforeach
                            </table>

                        </div>
                        <div>
                            <span class="text-danger font-italic font-weight-bold" style="font-size: 12px">* Observaciones</span>
                            <div id="panel_observacion" class="overflow-auto rounded border" style="height: 70px" >
                                <div>{{$tramite_apostilla->apos_obs}}</div>
                            </div>
                        </div>
                        <br/>
                        <a href="#tramite_apostilla" class="btn btn-sm btn-primary text-white" data-toggle="modal"
                           onclick="cargarDatos('{{url('mostrar observacion tramite apostilla/'.$tramite_apostilla->cod_apos)}}','panel_tramite_apostilla');"
                           title="Observaciones">Observar
                        </a>
                        @can('generar pdf - apo')
                            <a href="{{url('generar pdf tramites apostilla/'.$cod_apos)}}" class="btn btn-sm btn-danger float-right" onclick="$('#apostilla').modal('hide');" target="otro">
                                <i class="fas fa-file-pdf"></i> Generar
                            </a>
                        @endcan
                    </div>
                </div>
                @endif
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
@php
    $fechaListadoApostilla=data_get($tramite_apostilla ?? null,'apos_fecha_ingreso');
    $fechaListadoApostilla=$fechaListadoApostilla ? date('Y-m-d',strtotime($fechaListadoApostilla)) : date('Y-m-d');
@endphp
<script>
    function cargarDatosPersonales(ci){
        var link="{{url('datos_per/')}}"+"/"+ci;
        $.ajax({
            url: link,
            type: 'GET',
            success: function (resp) {
                if(resp=="No"){
                    $('#apellido').val('');
                    $('#nombre').val('');
                }else{
                    var res=JSON.parse(resp);
                    $('#apellido').val(res['per_apellido']);
                    $('#nombre').val(res['per_nombre']);
                    $('#celular').val(res['per_celular']);
                }
            },
            error: function () {
                $('#'+panel).html("<span class='text-danger'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");
            }
        });
    }
    function cargarDatosApoderado(ci){
        var link="{{url('datos_apo/')}}"+"/"+ci;
        $.ajax({
            url: link,
            type: 'GET',
            success: function (resp) {
                if(resp=="No"){
                    $('#apellido_apoderado').val('');
                    $('#nombre_apoderado').val('');
                }else{
                    var res=JSON.parse(resp);
                    $('#apellido_apoderado').val(res['apo_apellido']);
                    $('#nombre_apoderado').val(res['apo_nombre']);
                }
            },
            error: function () {
                $('#'+panel).html("<span class='text-danger'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");
            }
        });
    }

    let apostillaRapidaValidacionOk=false;
    let apostillaRapidaControlValidado='';
    let apostillaRapidaCodLisDetectado='';
    let apostillaRapidaTimer=null;
    let apostillaRapidaDetallePago='Pago pendiente de validación.';

    function formApostillaRapida(){
        return $('#form_agregar_tramite_rapido');
    }

    function setIconoPagoRapido(tipo,mensaje){
        const form=formApostillaRapida();
        if(!form.length){ return; }

        const icono=form.find('[data-campo="estado-pago-icon"]');
        if(!icono.length){ return; }

        let color='text-secondary';
        let iconClass='fa-minus-circle';
        let title=(mensaje || 'Pago pendiente de validación').toString();

        if(tipo==='loading'){
            color='text-info';
            iconClass='fa-spinner fa-spin';
            title=title || 'Validando pago';
        }else if(tipo==='ok'){
            color='text-success';
            iconClass='fa-check-circle';
            title=title || 'Pago validado';
        }else if(tipo==='error'){
            color='text-danger';
            iconClass='fa-times-circle';
            title=title || 'Pago inválido';
        }else if(tipo==='warn'){
            color='text-warning';
            iconClass='fa-exclamation-circle';
            title=title || 'Atención';
        }

        icono
            .removeClass('text-secondary text-info text-success text-danger text-warning')
            .addClass(color)
            .attr('title','Ver detalle de validación de pago')
            .attr('data-detalle-pago',title)
            .attr('aria-label',title)
            .removeAttr('data-popover-visible');
        icono.popover('hide');
        icono.find('i').attr('class','fas '+iconClass);
    }

    function estadoRegistroRapido(tipo,mensaje){
        let texto=(mensaje || '').toString().trim();
        if(texto===''){
            if(tipo==='loading'){
                texto='Validando pago y detectando trámite...';
            }else if(tipo==='ok'){
                texto='Pago validado.';
            }else if(tipo==='error'){
                texto='Pago inválido.';
            }else if(tipo==='warn'){
                texto='Atención en la validación del pago.';
            }else{
                texto='Pago pendiente de validación.';
            }
        }
        apostillaRapidaDetallePago=texto;
        setIconoPagoRapido(tipo,texto);
    }

    function mostrarDetallePagoRapido(evento,elemento){
        if(evento){
            evento.preventDefault();
            evento.stopPropagation();
        }

        const icono=$(elemento);
        if(!icono.length){ return false; }

        const detalle=(icono.attr('data-detalle-pago') || apostillaRapidaDetallePago || 'Pago pendiente de validación.').toString();
        const visible=icono.attr('data-popover-visible')==='1';

        icono.popover('dispose');
        if(visible){
            icono.removeAttr('data-popover-visible');
            return false;
        }

        icono.popover({
            container:'body',
            trigger:'manual',
            placement:'top',
            content:detalle,
            html:false,
        }).popover('show');
        icono.attr('data-popover-visible','1');
        return false;
    }

    function obtenerControlRapidoApostilla(){
        const form=formApostillaRapida();
        if(!form.length){ return ''; }
        return (form.find('input[name="nro_control"]').val() || '').toString().trim();
    }

    function aplicarEtiquetaDocumentoRapida(label){
        const form=formApostillaRapida();
        if(!form.length){ return; }
        const etiqueta=(label || 'N° título / resolución').toString();
        form.find('[data-campo="label-documento"]').text(etiqueta+':');
    }

    function extraerAnioDesdeFechaPagoApostilla(fechaPago){
        const valor=(fechaPago || '').toString().trim();
        if(valor===''){ return ''; }
        const match=valor.match(/(19|20)\d{2}/);
        return match ? match[0] : '';
    }

    function setGestionValoradoApostilla(anio){
        const form=formApostillaRapida();
        if(!form.length){ return; }
        form.find('input[data-campo="gestion-api"]').val((anio || '').toString());
    }

    function limpiarEstadoValidacionRapida(){
        const form=formApostillaRapida();
        if(!form.length){ return; }

        form.find('[data-campo="validacion-recaudacion-ok"]').val('0');
        form.find('input[data-campo="preimpreso-api"]').val('');
        form.find('input[data-campo="gestion-api"]').val('');

        apostillaRapidaValidacionOk=false;
        apostillaRapidaControlValidado='';
        apostillaRapidaCodLisDetectado='';

        form.find('input[name="cl"]').val('');
        form.find('[data-campo="tramite-detectado"]').val('');
        aplicarEtiquetaDocumentoRapida('N° título / resolución');
        setIconoPagoRapido('pending','Pago pendiente de validación');
    }

    function solicitarValidacionRapidaApostilla(callbackOk,callbackError){
        const form=formApostillaRapida();
        if(!form.length){ return; }

        const nroControl=obtenerControlRapidoApostilla();

        if(nroControl===''){
            limpiarEstadoValidacionRapida();
            estadoRegistroRapido('error','Ingrese el N° de control del pago.');
            if(typeof callbackError==='function'){ callbackError('Ingrese el N° de control del pago.'); }
            return;
        }

        estadoRegistroRapido('loading','');

        $.ajax({
            url:'{{url("validar valorado apostilla/".$cod_apos)}}',
            type:'POST',
            dataType:'json',
            data:{
                _token:form.find('input[name="_token"]').val(),
                nro_control:parseInt(nroControl,10) || 0
            },
            success:function(resp){
                if(!(resp && resp.ok)){
                    const msg=(resp && resp.message) ? resp.message : 'No se pudo validar el pago.';
                    limpiarEstadoValidacionRapida();
                    estadoRegistroRapido('error',msg);
                    if(typeof callbackError==='function'){ callbackError(msg); }
                    return;
                }

                const codSugerido=(resp.cod_lis_sugerido || '').toString().trim();
                if(codSugerido!==''){
                    form.find('input[name="cl"]').val(codSugerido);
                    form.find('[data-campo="tramite-detectado"]').val((resp.lis_alias_sugerido || resp.lis_nombre_sugerido || '').toString());
                    aplicarEtiquetaDocumentoRapida((resp.documento_label_sugerido || 'N° título / resolución').toString());
                }else{
                    limpiarEstadoValidacionRapida();
                    estadoRegistroRapido('error','Boleta inválida: no corresponde a ningún trámite habilitado de apostilla.');
                    if(typeof callbackError==='function'){ callbackError('Boleta inválida: no se detectó trámite.'); }
                    return;
                }

                const anio=extraerAnioDesdeFechaPagoApostilla(resp.fecha_pago || '');
                if(anio!==''){
                    setGestionValoradoApostilla(anio);
                }

                form.find('input[data-campo="preimpreso-api"]').val(resp.preimpreso || '');
                form.find('[data-campo="validacion-recaudacion-ok"]').val('1');

                apostillaRapidaValidacionOk=true;
                apostillaRapidaControlValidado=nroControl;
                apostillaRapidaCodLisDetectado=codSugerido;

                let resumen='Pago validado';
                if(resp.lis_alias_sugerido){
                    resumen+=': '+resp.lis_alias_sugerido;
                }
                resumen+='.';
                estadoRegistroRapido('ok',resumen);

                if(typeof callbackOk==='function'){ callbackOk(resp); }
            },
            error:function(xhr){
                const msg=(xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : 'No hay conexión. Intente en unos momentos.';
                limpiarEstadoValidacionRapida();
                estadoRegistroRapido('error',msg);
                if(typeof callbackError==='function'){ callbackError(msg); }
            }
        });
    }

    function programarValidacionRapidaApostilla(){
        if(apostillaRapidaTimer!==null){
            clearTimeout(apostillaRapidaTimer);
        }
        apostillaRapidaTimer=setTimeout(function(){
            solicitarValidacionRapidaApostilla();
        },400);
    }

    function guardarAgregarApostillaRapida(){
        const form=formApostillaRapida();
        if(!form.length){ return; }

        const codApos=(form.find('input[name="ca"]').val() || '').toString();
        $.ajax({
            url:'{{url("guardar agregar tramite apostilla")}}',
            type:'POST',
            dataType:'json',
            headers:{ 'Accept':'application/json' },
            data:form.serialize(),
            success:function(resp){
                if(resp && resp.ok){
                    cargarDatos('{{url("ajax tabla agregar")}}/'+codApos,'panel_lista_tramites_apostilla');
                    cargarDatos('{{url("listar tramite apostilla tabla/$fechaListadoApostilla")}}','panel_tabla_tramites');

                    form.find('input[name="nro_control"]').val('');
                    form.find('input[name="numero"]').val('');
                    form.find('input[name="gestion"]').val('');

                    limpiarEstadoValidacionRapida();
                    estadoRegistroRapido('ok','Trámite agregado correctamente. Puede registrar otro valorado.');
                    form.find('input[name="nro_control"]').trigger('focus');
                    return;
                }

                const msg=(resp && resp.message)
                    ? resp.message
                    : 'No se pudo registrar el trámite.';
                estadoRegistroRapido('error',msg);
            },
            error:function(xhr){
                const msg=(xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : 'No se pudo registrar el trámite. Intente nuevamente.';
                estadoRegistroRapido('error',msg);
            }
        });
    }

    function submitAgregarApostillaRapida(){
        const form=formApostillaRapida();
        if(!form.length){
            return false;
        }

        const nroControl=obtenerControlRapidoApostilla();

        if(nroControl===''){
            estadoRegistroRapido('error','Ingrese el N° de control del pago.');
            return false;
        }

        const listoParaGuardar=
            apostillaRapidaValidacionOk &&
            apostillaRapidaControlValidado===nroControl &&
            apostillaRapidaCodLisDetectado!=='' &&
            (form.find('input[name="cl"]').val() || '').toString().trim()===apostillaRapidaCodLisDetectado &&
            form.find('[data-campo="validacion-recaudacion-ok"]').val()==='1';

        const intentarGuardar=function(){
            const codLis=(form.find('input[name="cl"]').val() || '').toString().trim();
            if(codLis==='' || codLis!==apostillaRapidaCodLisDetectado){
                estadoRegistroRapido('error','No se detectó automáticamente un trámite válido para esta boleta.');
                return;
            }

            const gestionValorado=(form.find('input[data-campo="gestion-api"]').val() || '').toString().trim();
            if(gestionValorado===''){
                estadoRegistroRapido('error','No se pudo obtener la gestión del pago desde recaudaciones.');
                return;
            }

            guardarAgregarApostillaRapida();
        };

        if(listoParaGuardar){
            intentarGuardar();
            return false;
        }

        solicitarValidacionRapidaApostilla(function(){
            intentarGuardar();
        });
        return false;
    }

    $(document)
        .off('input.apoControlRapido','#nro_control_rapido')
        .on('input.apoControlRapido','#nro_control_rapido',function(){
            limpiarEstadoValidacionRapida();
            if((this.value || '').toString().trim()===''){
                estadoRegistroRapido('warn','Ingrese el N° de control para detectar el trámite automáticamente.');
                return;
            }
            programarValidacionRapidaApostilla();
        });

    $(document)
        .off('click.apoEstadoPagoDetalle')
        .on('click.apoEstadoPagoDetalle',function(e){
            if($(e.target).closest('[data-campo="estado-pago-icon"], .popover').length===0){
                const icono=$('[data-campo="estado-pago-icon"]');
                if(icono.length){
                    icono.popover('hide').removeAttr('data-popover-visible');
                }
            }
        });

    $(function(){
        const form=formApostillaRapida();
        if(form.length){
            estadoRegistroRapido('pending','Pago pendiente de validación.');
        }
    });
</script>
