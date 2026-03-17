    <?php //$fecha=date('Y-m-d',strtotime($apostilla->apos_fecha_ingreso))?>
    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary">
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
            <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                <h6 class="text-white text-center">Formulario para editar tramite de apostilla</h6>
            </div>
            <hr class="sidebar-divider"/>
            <div class="row">
                <div class="col-md-4">

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
                                    </td>
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
                                    </tr>
                                @else
                                    @can('editar apoderado - apo')
                                    <tr>
                                        <th class="text-right font-italic">CI apoderado: </th>
                                        <td class="border-bottom border-dark">
                                            <input class="form-control form-control-sm border-0" placeholder=""
                                                   name="ci_apoderado" onchange="cargarDatosApoderado(this.value)" /></td>
                                        </td>
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
                <div class="col-md-5 pl-3 border shadow pt-2">
                    <span class="text-danger font-italic font-weight-bold" style="font-size: 16px">* Trámites seleccionados</span>

                    <div id="panel_lista_tramites_apostilla" class="overflow-auto" style="height: 400px;">
                        <table class="col-md-12 table table-sm table-hover table-info rounded" style="font-size: 12px">
                            <tr class="bg-gradient-info text-white p-2">
                                <th>Nº</th>
                                <th>Sitra</th>
                                <th>Nombre</th>
                                <th>N° trámite</th>
                                <th>N° Documento</th>
                                <th>Opciones</th>
                            </tr>
                            <?php $i=1?>
                            @foreach($detalle_apostilla as $d)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td></td>
                                    <td>{{$d->lis_nombre}}</td>
                                    <td>{{$d->dapo_numero}}</td>
                                    <td>{{$d->dapo_numero_documento."/".$d->dapo_gestion_documento}}</td>
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
                       title="Agregar trámite">Observar
                    </a>
                    @can('generar pdf - apo')
                        <a href="{{url('generar pdf tramites apostilla/'.$cod_apos)}}" class="btn btn-sm btn-danger float-right" onclick="$('#apostilla').modal('hide');" target="otro">
                            <i class="fas fa-file-pdf"></i> Generar
                        </a>
                    @endcan

                </div>
                <div class="col-md-3 pt-2">
                    <span class="text-danger font-italic font-weight-bold" style="font-size: 16px">* Lista de trámites de apostilla</span>
                    <div class="border rounded overflow-auto" style="height: 530px;">
                        <div class="table-responsive col-md-12">
                            @if($tramite_apostilla->apos_estado<2)
                            <table class="col-md-12 table-hover" style="font-size: 12px;" id="dataListaTramite">
                                <thead>
                                <tr class="bg-gradient-primary text-white pl-2" style="height: 30px">
                                    <th class="text-center">N°</th>
                                    <th class="text-center">TRÁMITE</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1;?>
                                @can('agregar documento - apo')
                                    @foreach($apostilla as $a)
                                        <tr>
                                            <td class="border-bottom">{{$i}}</td>
                                            <td class="border-bottom">
                                                @if($a->lis_tipo!='')
                                                    <a href="#tramite_apostilla" class="text-primary" data-toggle="modal"
                                                       onclick="cargarDatos('{{url("agregar tramite lista apostilla/$a->cod_lis/$cod_apos")}}','panel_tramite_apostilla');"
                                                       title="Agregar trámite">{{$a->lis_alias}}
                                                    </a>
                                                @else
                                                    <form id="form_agregar_tramite{{$i}}">
                                                        @csrf
                                                        <input type="hidden" name="cl" value="{{$a->cod_lis}}">
                                                        <input type="hidden" name="ca" value="{{$tramite_apostilla->cod_apos}}">
                                                    </form>
                                                    <a href="#" class="text-dark"
                                                       onclick="enviar('form_agregar_tramite{{$i}}','{{url("guardar agregar tramite apostilla/")}}','panel_lista_tramites_apostilla');cargarDatos('{{url("listar tramite apostilla tabla/".date('Y-m-d',strtotime($tramite_apostilla->apos_fecha_ingreso)))}}','panel_tabla_tramites')"
                                                       title="Agregar trámite">{{$a->lis_alias}}
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                            <?php $i++;?>
                                    @endforeach
                                @endcan
                                </tbody>
                            </table>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
<script>
        $('#dataListaTramite').dataTable( {
            "paging": false,
    });
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
</script>
