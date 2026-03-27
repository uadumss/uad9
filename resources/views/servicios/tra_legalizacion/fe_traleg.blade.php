    <?php $fecha=date('Y-m-d',strtotime($tramite->tra_fecha_solicitud))?>
    <div class="modal-content border-bottom-primary" xmlns="http://www.w3.org/1999/html">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Legalización </h5>
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
                <h6 class="text-white text-center">Formulario para editar legalización</h6>
            </div>
            {{$tipos_array}}
            <hr class="sidebar-divider"/>
            <div class="row">
                <div class="col-md-4">
                    <span class="text-primary font-italic font-weight-bold" style="font-size: 0.8em">* Datos personales</span>
                        <div class="shadow-sm p-2 col-md-5 float-md-right">
                            <h1 class="text-danger pr-3 text-center">{{$tramite->tra_numero}}</h1>
                            <span class="font-italic text-dark text-center"><?php if($tramite->tra_fecha_solicitud!=''){echo date('d/m/Y',strtotime($tramite->tra_fecha_solicitud));} ?></span>
                        </div>
                    @if($tramite->per_ci=='')
                    <form id="form_traleg">
                        @csrf
                            <table class="table-hover col-md-12 text-dark">
                                <tr>
                                    <th class="text-right font-italic">CI : </th>
                                    <td class="border-bottom border-dark">

                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               name="ci" value="{{$tramite->per_ci}}" onchange="cargarDatosPersonales(this.value)" /></td>

                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Passaporte : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               name="pasaporte" value="{{$tramite->per_pasaporte}}" /></td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Apellidos : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               required name="apellido" id="apellido" value="{{$tramite->per_apellido}}" /></td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Nombres : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               required name="nombre" id="nombre" value="{{$tramite->per_nombre}}" /></td>
                                </tr>
                            </table>
                            <br/>
                            <input type="hidden" name="ctra" value="{{$tramite->cod_tra}}">
                            <input type="hidden" name="ip" value="{{$tramite->id_per}}">

                        </form>
                            @can('editar datos traleg - srv')
                                <button type="submit" class="btn btn-primary btn-sm float-md-right" onclick="guardarDatos('{{url("g_traleg")}}','panel_traleg','form_traleg')"> Guardar </button>
                            @endcan
                        @else
                        <table class="col-md-12 text-dark table table-sm">
                            <tr>
                                <th class="text-right font-italic">CI : </th>
                                <td class="border-bottom border-dark">{{$tramite->per_ci}}</td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Passaporte : </th>
                                <td class="border-bottom border-dark">{{$tramite->per_pasaporte}}</td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Nombre : </th>
                                <td class="border-bottom border-dark">{{$tramite->per_nombre." ".$tramite->per_apellido}}</td>
                            </tr>

                        </table>
                        @endif
                    <div>
                        <ul class="list-group-item-danger rounded">
                            @if(sizeof($ptaang)>0)
                                @foreach($ptaang as $p)
                                    <li class="text-darkr">Ya tiene
                                        @php echo \App\Models\Funciones::tipo_ptaang($p->dtra_ptaang)." Nº " @endphp
                                        <span class="font-weight-bold">{{$p->dtra_numero."/".$p->dtra_gestion}} </span> por PTAANG</li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <br/>
                    <div>
                        <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Datos del apoderado</span>
                        <div class="" id="apoderado">
                            <table class=" table table-sm">
                                <tr>
                                    <th class="text-right font-italic text-dark">CI : </th>
                                    <td class="border-bottom border-dark">
                                        @if($apoderado)
                                            {{$apoderado['apo_ci']}}
                                        @else
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark font-italic">Nombre apoderado : </th>
                                    <td class="border-bottom border-dark">
                                        @if($apoderado)
                                            {{$apoderado['apo_apellido']." ".$apoderado['apo_nombre']}}
                                        @else
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Tipo de apoderado : </th>
                                    <td class="border-bottom border-dark">
                                        @if($tramite->tra_tipo_apoderado=='d')
                                            Declaración jurada
                                        @else
                                            @if($tramite->tra_tipo_apoderado=='p')
                                                Poder notariado
                                            @else
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            @endif
                                        @endif
                                    </td>
                                </tr>

                            </table>
                            @can('editar apoderado traleg - srv')
                                <button id="otros" class="btn btn-sm btn-primary float-right" onclick="$('#editarApoderado').show(500); $('#apoderado').hide(500);"> Editar datos</button>
                            @endcan
                        </div>
                        @can('editar apoderado traleg - srv')
                        <div id="editarApoderado" class="border rounded shadow" style="display: none;">
                            <div class="p-3">
                                <a onclick="$('#editarApoderado').hide(500);$('#apoderado').show(500); " id="ocultar" style="float:right">
                                    <i class="fas fa-minus-circle text-danger"></i></a>
                                <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Editar datos del apoderado</span>
                                <form id="form_apoderado_edi">
                                <br/><br/>
                                    @php
                                        $nombre='';    $apellido='';  $ci="";
                                        if($apoderado){   $ci=$apoderado->apo_ci;       $apellido=$apoderado->apo_apellido;     $nombre=$apoderado->apo_nombre;  }
                                    @endphp

                                    <table class="table-hover col-md-12">
                                        <tr>
                                            <th class="text-right font-italic">CI : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       name="ci" value="{{$ci}}" onchange="cargarDatosApoderado(this.value)"/></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Apellidos : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       required name="apellido" id="apellido_apoderado" value="{{$apellido}}" /></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Nombres : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       required name="nombre" id="nombre_apoderado" value="{{$nombre}}" /></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic" valign="top">Tipo de apoderado : </th>
                                            <td class="border-bottom border-dark">
                                                @if($tramite->tra_tipo_apoderado=='d')
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" checked> Declaración jurada<br/>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p"> Poder notariado
                                                @else
                                                    @if($tramite->tra_tipo_apoderado=='p')
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d"> Declaración jurada<br/>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" checked> Poder notariado
                                                    @else
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d"> Declaración jurada<br/>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p"> Poder notariado
                                                    @endif
                                            @endif

                                        </tr>
                                    </table>
                                    <br/>
                                    <input type="hidden" name="ctra" value="{{$tramite->cod_tra}}">
                                    @csrf
                                </form>

                                    <a class="btn btn-primary btn-sm text-white float-right" onclick="enviar('form_apoderado_edi','{{url("guardar apoderado")}}','panel_traleg');" >Guardar</a><br/>
                            </div>
                        </div>
                        @endcan
                    </div>
                </div>
                <!-- ================================LISTA DE DOCUMENTOS====================================-->
                <div class="col-md-8 pl-3">
                    <span class="text-primary font-italic font-weight-bold" style="font-size: 0.8em">* Documentos del trámite</span>
                    <div>
                        @if(Session::has('error'))
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <span class="font-weight-bold">{!! session('error') !!}</span>
                            </div>
                        @endif
                        <table class="col-md-12 table table-sm table-hover table-dark">
                            <tr class="bg-gradient-secondary text-white p-2">
                                <th>Nº</th>
                                @if(!in_array($tramite->tra_tipo_tramite,['E','F']))
                                    <th>Sitra</th>
                                @endif
                                <!--<th>Estado</th>-->
                                <th>Nombre</th>

                                <th>Número trámite</th>
                                @if($tramite->tra_tipo_tramite=='B')
                                    <th>Documentos</th>
                                @endif
                                @if($tramite->tra_tipo_tramite=='F')
                                    <th>Documentos</th>
                                @else
                                    <th>Nº Título</th>
                                    <th colspan="4">Opciones</th>
                                @endif
                            </tr>
                            <?php $i=1;?>
                            @foreach($documentos as $d)
                                @if($d->dtra_falso=='t')
                                        <tr style="font-size: 10px" class="alert-danger border">
                                    @else
                                        @if($d->dtra_generado=='t')
                                        <tr style="font-size: 10px" class="alert-success border">
                                        @else
                                        <tr style="font-size: 10px" class="alert-light">
                                        @endif
                                    @endif
                                    <td>{{$i}}</td>
                                    @if(!in_array($tramite->tra_tipo_tramite,['E','F']))
                                        <td>@if($d->dtra_verificacion_sitra=='0')
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-success" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("verificacion sitra/".$d->cod_dtra)}}','panel_docleg')"
                                                   title="Verificado en el sitra"><i class="fas fa-check-circle"></i>
                                                </a>
                                            @elseif($d->dtra_verificacion_sitra=='1' || $d->dtra_verificacion_sitra=='2')
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("verificacion sitra/".$d->cod_dtra)}}','panel_docleg')"
                                                   title="Verificación no válida en SITRA/SID"><i class="fas fa-minus-circle"></i>
                                                </a>
                                            @else
                                                <span class="btn btn-light btn-circle btn-sm text-secondary" title="SITRA/SID pendiente">
                                                    <i class="fas fa-minus-circle"></i>
                                                </span>
                                            @endif
                                        </td>
                                    @endif
                                    <!--<td>if($d->dtra_estado_doc==0 || $d->dtra_estado_doc==4 )
                                            <div class="border border-success font-weight-bold text-success rounded pl-2" ><?php echo \App\Http\Controllers\TramiteLegalizacionController::estado($d->dtra_estado_doc)?></div>
                                        else
                                            <div class="border border-danger font-weight-bold text-danger rounded pl-2" ><?php echo \App\Http\Controllers\TramiteLegalizacionController::estado($d->dtra_estado_doc)?></div>
                                        endif
                                    </td>-->
                                    <td class="text-left">{{$d->tre_nombre}} @if($d->dtra_interno=='t') <span class="text-danger font-weight-bold">(Int.)</span> @endif</td>
                                    <td>

                                            {{$d->dtra_numero_tramite." / ".$d->dtra_gestion_tramite}}

                                    </td>

                                    @if($tramite->tra_tipo_tramite=='B')
                                                <td>
                                                    @foreach($confrontacion as $c)
                                                        @if($c->cod_dtra==$d->cod_dtra)
                                                            <span class="font-weight-bold font-italic"><?php echo  $c->dcon_doc; ?> </span><br/>
                                                        @endif
                                                    @endforeach
                                                </td>
                                    @endif
                                    @if($tramite->tra_tipo_tramite=='F')
                                        <td>
                                            @foreach($confrontacion as $c)
                                                <span class="font-weight-bold font-italic"><?php echo  \App\Http\Controllers\ConfrontacionController::nombreDocumento($c->dcon_doc); ?> </span><br/>
                                            @endforeach
                                        </td>
                                    @else
                                                <td class="text-left">
                                                    @if($d->dtra_numero==0)
                                                        {{"-/".substr($d->dtra_gestion,-2)}}</td>
                                                    @else
                                                        {{$d->dtra_numero."/".substr($d->dtra_gestion,-2)}}</td>
                                                    @endif

                                                <td class="text-right">
                                                    @if($d->dtra_generado=='t')
                                                        @can('deshacer generado glosa - srv')
                                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("fe_corregir_docleg/".$d->cod_dtra)}}','panel_docleg')"
                                                               title="Corregir tramite"><i class="fas fa-arrow-circle-left"></i> </a>
                                                        @endcan
                                                            @if($tramite->tra_tipo_tramite!='B')
                                                                @can('imprimir legalizacion docleg - srv')
                                                                    <a class="btn btn-light btn-sm btn-circle" data-target='#docleg' data-toggle="modal" onclick="cargarDatos('{{url("configurar impresion pdf leg/".$d->cod_dtra)}}','panel_docleg')"
                                                                       title="Ver Glosa"><i class="text-dark fas fa-file-pdf" ></i></a>
                                                                @endcan
                                                            @endif
                                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("ver documento pdf legalizado/".$d->cod_dtra)}}','panel_docleg')"
                                                               title="Ver documento PDF"><i class="fas fa-file-code"></i> </a>

                                                    @else
                                                        @can('deshacer generado glosa - srv')
                                                            @if($tramite->tra_tipo_tramite=='L' ||$tramite->tra_tipo_tramite=='C')
                                                                <a href="#traleg" class="btn btn-light btn-circle btn-sm font-weight-bold"  onclick="cargarDatos('{{url("cambiar interno docleg/".$d->cod_dtra)}}','panel_traleg')"
                                                                   title="Cambiar destino de trámite">
                                                                    @if($d->dtra_interno=='t')
                                                                        <span class="text-danger">Int</span>
                                                                    @else
                                                                        <span class="text-primary">Ext</span>
                                                                    @endif
                                                                </a>
                                                            @endif
                                                        @endcan

                                                        @if($d->dtra_obs!='' || $d->dtra_falso=='t')
                                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("obs_docleg/".$d->cod_dtra)}}','panel_docleg')"
                                                               title="Observado"> <i class="fas fa-eye text-danger"></i></a>
                                                        @else
                                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("obs_docleg/".$d->cod_dtra)}}','panel_docleg')"
                                                               title="Observado"> <i class="fas fa-eye"></i></a>
                                                        @endif
                                                            </a>
                                                            @if($d->dtra_falso!='t')
                                                                @can('generar glosa docleg - srv')
                                                                    @if($tramite->tra_tipo_tramite=='B' || $d->dtra_solo_sello=='t')

                                                                        <a href="#" class="btn btn-light btn-circle btn-sm text-dark" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("busqueda doc encontrado/".$d->cod_dtra)}}','panel_docleg')"
                                                                           title="Registrar verificación"><i class="fas fa-file-signature"></i>
                                                                        </a>
                                                                    @else

                                                                            <a href="#" class="btn btn-light btn-circle btn-sm text-dark" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("generar glosa_leg/".$d->cod_dtra)}}','panel_docleg')"
                                                                                title="Generar glosa"><i class="fas fa-file-signature"></i>
                                                                            </a>

                                                                   @endif
                                                                @endcan
                                                                @if($d->dtra_tipo!='E')
                                                                    <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("ver documento pdf legalizado/".$d->cod_dtra)}}','panel_docleg')"
                                                                       title="Ver documento PDF"><i class="fas fa-file-code"></i> </a>
                                                                @endif
                                                                @can('eliminar docleg - srv')
                                                                    <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("f_eli_docleg/".$d->cod_dtra)}}','panel_docleg')"
                                                                       title="Eliminar"> <i class="fas fa-trash-alt"></i>
                                                                    </a>
                                                               @endcan
                                                            @endif
                                                    @endif
                                                </td>
                                            @endif
                                </tr>
                                <?php $i++;?>
                            @endforeach
                        </table>
                    </div>
                    @can('crear docleg - srv')
                    <!--Solo cuando es BUSQUEDA SE MUESTRA EL FORMULARIO-->
                    @if($tramite->id_per!='' && $tramite->tra_tipo_tramite=='B')
                        <button id="btnNuevoTra" class="btn btn-sm btn-primary float-right" onclick="$('#divNueTram').show(500); $('#btnNuevoTra').hide(500);"> + Trámite</button><br/>
                        <div class="shadow-sm border col-md-10 float-right" id="divNueTram" style="display: none">
                            <a onclick="$('#divNueTram').hide(500);$('#btnNuevoTra').show(500); " id="ocultar" style="float:right" class="mr-2">
                                <i class="fas fa-minus-circle text-danger"></i></a>
                            <br/>
                            <div id="error_datos" style="display: none" class="alert alert-danger alert-dismissible">
                                    <span id="error_datos_span"></span>
                            </div>
                                <div class="alert-primary centrar_bloque p-1 col-md-7 rounded shadow">
                                    <h6 class="text-dark text-center font-weight-bold">Añadir documento para Búsqueda</h6>
                                </div>
                            <br/>
                            <div class="col-md-11 float-right">
                                <form id="form_docleg">
                                    @csrf
                                    <table>
                                        <tr>
                                            <th class="text-right font-italic">Trámite : </th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0" data-campo="tipo-legalizacion" disabled>
                                                    <option value="" selected></option>
                                                    @foreach($lista_tramites as $l)
                                                        <option value="{{$l->cod_tre}}">{{$l->tre_nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic"> Nº control valorado: </th>
                                            <td class="border-bottom border-dark">
                                                <div class="input-group">
                                                    <input type="text" class=" form-control form-control-sm" name="control" required oninput="programarValidacionControl(this)">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="font-italic text-dark font-weight-bold"> CUADIS :
                                                            <input type="checkbox" name="cuadis" />
                                                        </span>&nbsp;&nbsp;
                                                </div>
                                            </td>
                                        </tr>
                                        <tr><th class="text-right font-italic">Nro. Título:</th>
                                            <td class="border-bottom border-dark">
                                                <div class="input-group ">
                                                    <input name="numero" required class="form-control col-md-2 form-control-sm border " pattern="[0-9]{1,6}"> &nbsp;&nbsp;
                                                    / &nbsp;&nbsp;<input name="gestion" required class="form-control col-md-2 form-control-sm border" pattern="[0-9]{1,4}"> &nbsp;&nbsp;(e.j. 1999)
                                                    &nbsp;&nbsp;<a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-campo="estado-sitra-icon" title="No existe en el sitra" onclick="abrirModalSitraFormulario(this); return false;"><i class="fas fa-minus-circle"></i></a>
                                                    <span class="ml-1 text-info font-italic" data-campo="sitra-fuente"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Buscar en :</th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0" required name="buscar_en">
                                                    <option value="db">DB</option>
                                                    <option value="ca">CA</option>
                                                    <option value="da">DA</option>
                                                    <option value="tp">TP</option>
                                                    <option value="di">DI</option>
                                                    <option value="tpos">TPOS</option>
                                                    <option value="re">RE</option>
                                                    <option value="su">SU</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr><th class="text-right font-italic">
                                                <span class="text-dark font-weight-bold font-italic" style="font-size: 0.9em"> Documentos : </span>
                                            </th>
                                            <td>
                                                <textarea name="documentos" class="form-control form-control-sm" required></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                    <div data-campo="estado-validacion" class="mt-2"></div>
                                    <div data-campo="estado-sitra" class="mt-2"></div>
                                    <input type="hidden" name="ctra" value="{{$tramite->cod_tra}}">
                                    <input type="hidden" name="tipo_tramite" value="t">
                                    <input type="hidden" name="tipo" data-campo="tipo-legalizacion-hidden" value="">
                                    <input type="hidden" name="reimpresion" data-campo="preimpreso-api" value="">
                                    <input type="hidden" data-campo="validacion-recaudacion-ok" value="0">
                                </form>
                                <a href="#" class="btn btn-sm btn-primary float-right mr-4" onclick="crearDoclegConValidacion('form_docleg','{{url('g_docleg')}}','panel_traleg')"
                                   title="Editar legalización">+ Crear </a>
                                <br/><br/>
                            </div>
                        </div>
                    @endif

                    @if($tramite->id_per!='' && ($tramite->tra_tipo_tramite=='L' || $tramite->tra_tipo_tramite=='C' || $tramite->tra_tipo_tramite=='E' ))
                        <br/>
                    <hr class="sidebar-divider"/>
                        <!--==============================Añadir Documentos=================-->
                    <button id="btnNuevoTra" class="btn btn-sm btn-primary float-right" onclick="$('#divNueTram').show(500); $('#btnNuevoTra').hide(500);"> + Trámite</button><br/>
                    <div class="shadow-sm" id="divNueTram" style="display: none">

                        <a onclick="$('#divNueTram').hide(500);$('#btnNuevoTra').show(500); " id="ocultar" style="float:right" class="mr-2">
                            <i class="fas fa-minus-circle text-danger"></i></a>
                        <br/>
                        <div id="error_datos" style="display: none" class="alert alert-danger alert-dismissible">
                            <span id="error_datos_span"></span>
                        </div>
                            <div>
                                <div class="alert-primary centrar_bloque p-1 col-md-7 rounded shadow">
                                    <h6 class="text-dark text-center font-weight-bold">Añadir documento</h6>
                                </div>

                                <form id="form_docleg">
                                    @csrf
                                    <table>
                                        <tr>
                                            <th class="text-right font-italic ">Tipo de legalización :</th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0 " data-campo="tipo-legalizacion" disabled>
                                                    <option value="" selected></option>
                                                    @foreach($lista_tramites as $l)
                                                        <option value="{{$l->cod_tre}}">{{$l->tre_nombre}}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="tipo" data-campo="tipo-legalizacion-hidden" value="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic ">Tipo de trámite :</th>
                                            <td class="border-bottom border-dark">
                                                <input type="radio" name="tipo_tramite" checked value="f"> EXTERNO  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="radio" name="tipo_tramite" value="t"> INTERNO
                                                &nbsp;&nbsp;
                                                <span class="font-weight-bold text-danger" style="font-size: 20px">|</span>
                                                &nbsp;&nbsp;
                                                @if($tramite->tra_tipo_tramite=='L')
                                                    <span class="font-weight-bold text-dark font-italic">&nbsp;&nbsp;PTAG : &nbsp;&nbsp;
                                                            <input type="checkbox" name="ptaang">
                                                        </span>
                                                @endif
                                                &nbsp;&nbsp;<span class="font-italic text-dark font-weight-bold"> CUADIS :
                                                            <input type="checkbox" name="cuadis" />
                                                        </span>&nbsp;&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic ">Nro. Título o Resolución:</th>
                                            <td class="border-bottom border-dark">
                                                <div class="input-group ">
                                                    &nbsp;&nbsp;  &nbsp;&nbsp;<input name="numero" class="form-control col-md-2 form-control-sm border "> &nbsp;&nbsp;
                                                    / &nbsp;&nbsp;<input name="gestion" required class="form-control col-md-2 form-control-sm border" pattern="[0-9]{1,4}"> &nbsp;&nbsp;(e.j. 1999)
                                                    @if(!in_array($tramite->tra_tipo_tramite,['E','F']))
                                                        &nbsp;&nbsp;<a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-campo="estado-sitra-icon" title="No existe en el sitra" onclick="abrirModalSitraFormulario(this); return false;"><i class="fas fa-minus-circle"></i></a>
                                                        <span class="ml-1 text-info font-italic" data-campo="sitra-fuente"></span>
                                                    @endif
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <span class="font-weight-bold text-dark float-right">
                                                        Supletorio : <input type="checkbox" name="supletorio">
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic ">Nro. Control:</th>
                                            <td class="border-bottom border-dark input-group">
                                                <div class="input-group">
                                                    <input class="form-control form-control-sm border-0" required name="control" oninput="programarValidacionControl(this)" />
                                                    <span class="text-primary font-weight-bold font-italic"> Reintegro : &nbsp;</span>
                                                    <input class="form-control form-control-sm border" required name="reintegro" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic ">N° control Búsqueda:</th>
                                            <td class="border-bottom border-dark">
                                                <div class="input-group">
                                                    <input class="form-control form-control-sm" name="valorado_bus" />
                                                    &nbsp;&nbsp;<span class="font-italic font-weight-bold"> Nro. control Reimpresión : </span>&nbsp;&nbsp;
                                                    <input class="form-control form-control-sm" name="reimpresion" data-campo="preimpreso-api" readonly />
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <div data-campo="estado-validacion" class="mt-2"></div>
                                    @if(!in_array($tramite->tra_tipo_tramite,['E','F']))
                                        <div data-campo="estado-sitra" class="mt-2"></div>
                                    @endif
                                    <input type="hidden" name="ctra" value="{{$tramite->cod_tra}}">
                                    <input type="hidden" data-campo="validacion-recaudacion-ok" value="0">
                                </form>
                                <br/>
                                <a href="#" class="btn btn-sm btn-primary float-right mr-4" onclick="crearDoclegConValidacion('form_docleg','{{url('g_docleg')}}','panel_traleg')"
                                   title="Editar legalización">+ Crear </a>
                                <br/><br/>
                            </div>
                    </div>
                    @endif
                    @endcan
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>

    <style>
        /* Estilos para estados de validación */
        [data-campo="estado-validacion"] {
            display: block;
            font-size: 0.9rem;
            line-height: 1.4;
            width: 100%;
            max-width: 100%;
        }

        [data-campo="estado-sitra"] {
            display: none;
            font-size: 0.86rem;
            line-height: 1.3;
            width: 100%;
            max-width: 100%;
        }

        [data-campo="estado-sitra-icon"] {
            display: inline-block;
            margin-left: .45rem;
            vertical-align: middle;
            line-height: 1;
        }

        [data-campo="estado-validacion"] .alert,
        [data-campo="estado-sitra"] .alert {
            font-size: 0.86rem;
            border-radius: .25rem;
            width: 100%;
            max-width: 100%;
            white-space: normal;
            overflow-wrap: anywhere;
            word-break: break-word;
            max-height: 5.5rem;
            overflow-y: auto;
        }
    </style>

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

        function validarControlRecaudaciones(inputControl){
            var formulario=$(inputControl).closest('form');
            sincronizarCamposObligatorios(formulario);
            var control=($.trim(formulario.find('input[name="control"]').val()) || '');
            var okInput=formulario.find('[data-campo="validacion-recaudacion-ok"]');
            okInput.val('0');
            
            if(!control){
                limpiarTipoLegalizacion(formulario);
                formulario.find('input[data-campo="preimpreso-api"]').val('');
                formulario.find('input[data-campo="gestion-api"]').val('');
                limpiarSitraFormulario(formulario);
                actualizarEstadoSitra(formulario,'text-muted','SITRA: pendiente');
                limpiarEstadoValidacion(formulario);
                formulario.removeData('control-validado-ok');
                formulario.removeData('control-validado-valor');
                return;
            }

            actualizarEstadoValidacion(formulario,'loading','Verificando...');
            
            $.ajax({
                url: "{{url('validar valorado recaudaciones/'.$tramite->cod_tra)}}",
                type: 'POST',
                data: {
                    _token: formulario.find('input[name="_token"]').val(),
                    control: control,
                    reimpresion: formulario.find('input[name="reimpresion"]').val() || ''
                },
                success: function(resp){
                    if(!resp.ok){
                        okInput.val('0');
                        limpiarTipoLegalizacion(formulario);
                        formulario.find('input[data-campo="preimpreso-api"]').val('');
                        formulario.find('input[data-campo="gestion-api"]').val('');
                        limpiarSitraFormulario(formulario);
                        actualizarEstadoSitra(formulario,'text-muted','SITRA: pendiente');
                        var msg=armarMensajeValidacionRecaudacion(resp,'No se pudo validar el comprobante');
                        actualizarEstadoValidacion(formulario,'error',msg);
                        formulario.removeData('control-validado-ok');
                        formulario.removeData('control-validado-valor');
                        return;
                    }

                    autoseleccionarTipoLegalizacion(formulario,resp);
                    sincronizarTipoLegalizacion(formulario);
                    formulario.find('input[data-campo="preimpreso-api"]').val(resp.preimpreso || '');
                    okInput.val('1');

                    var msg='Validado. Monto Bs. '+(resp.monto || '0');
                    if(resp.fecha_pago){
                        msg+=' - Fecha '+resp.fecha_pago;
                    }
                    if(resp.cajero){
                        msg+=' - Caja '+resp.cajero;
                    }
                    actualizarEstadoValidacion(formulario,'ok',msg);
                    formulario.data('control-validado-ok',1);
                    formulario.data('control-validado-valor',control);

                    programarValidacionSitra(formulario);
                },
                error: function(xhr){
                    var msg='No hay conexión. Intente en unos momentos.';
                    if(xhr.responseJSON && xhr.responseJSON.message){
                        msg=xhr.responseJSON.message;
                    }
                    okInput.val('0');
                    limpiarTipoLegalizacion(formulario);
                    formulario.find('input[data-campo="preimpreso-api"]').val('');
                    formulario.find('input[data-campo="gestion-api"]').val('');
                    limpiarSitraFormulario(formulario);
                    actualizarEstadoSitra(formulario,'text-muted','SITRA: pendiente');
                    actualizarEstadoValidacion(formulario,'error',msg);
                    formulario.removeData('control-validado-ok');
                    formulario.removeData('control-validado-valor');

                    programarValidacionSitra(formulario);
                }
            });
        }

        function armarMensajeValidacionRecaudacion(resp,mensajePorDefecto){
            if(!resp){
                return mensajePorDefecto;
            }
            var mensajeBase=(resp.message || mensajePorDefecto || '').toString().trim();
            var detalle=(resp.detalle || '').toString().trim();
            if(detalle===''){
                return mensajeBase;
            }
            return mensajeBase+' '+detalle;
        }

        function crearDoclegConValidacion(formulario,ruta,panel){
            var form=$('#'+formulario);
            sincronizarCamposObligatorios(form);
            var cuadis=form.find('input[name="cuadis"]').is(':checked');
            var validado=form.find('[data-campo="validacion-recaudacion-ok"]').val()==='1';

            if(!cuadis && !validado){
                $('#error_datos_span').html('Valide el número de control primero.');
                $('#error_datos').show();
                setTimeout(function () {
                    $('#error_datos').hide(500);
                }, 4000);
                return;
            }

            enviar1(formulario,ruta,panel);
        }

        function actualizarEstadoSitra(formulario,clase,mensaje){
            var estado=formulario.find('[data-campo="estado-sitra"]');
            var icono=formulario.find('[data-campo="estado-sitra-icon"]');
            if(!estado.length){
                return;
            }
            var tipo='loading';
            if(clase==='text-success'){
                tipo='ok';
            }else if(clase==='text-danger'){
                tipo='error';
            }
            renderEstadoEnAlerta(estado,tipo,mensaje);

            if(icono.length){
                icono.removeClass('text-danger text-success text-secondary text-muted');
                if(clase==='text-success'){
                    icono.attr('title','Verificado en el sitra');
                    icono.addClass('text-success').html('<i class="fas fa-check-circle"></i>');
                }else if(clase==='text-danger'){
                    icono.attr('title','No existe en el sitra');
                    icono.addClass('text-danger').html('<i class="fas fa-minus-circle"></i>');
                }else{
                    icono.attr('title','Pendiente');
                    icono.addClass('text-secondary').html('<i class="fas fa-minus-circle"></i>');
                }
            }
        }

        function renderEstadoEnAlerta(contenedor,tipo,mensaje){
            if(!contenedor || !contenedor.length){
                return;
            }
            var clase='alert-info';
            var texto=String(mensaje || '')
                .replace(/&/g,'&amp;')
                .replace(/</g,'&lt;')
                .replace(/>/g,'&gt;')
                .replace(/"/g,'&quot;')
                .replace(/'/g,'&#39;');
            if(tipo==='ok'){
                clase='alert-success';
            }else if(tipo==='error'){
                clase='alert-danger';
            }
            contenedor.html('<div class="alert '+clase+' py-2 mb-0">'+texto+'</div>');
        }

        function actualizarEstadoValidacion(formulario,tipo,mensaje){
            var estado=formulario.find('[data-campo="estado-validacion"]');
            renderEstadoEnAlerta(estado,tipo,mensaje);
        }

        function limpiarEstadoValidacion(formulario){
            var estado=formulario.find('[data-campo="estado-validacion"]');
            if(estado.length){
                estado.html('');
            }
        }

        function limpiarSitraFormulario(form){
            form.removeData('sitra-response');
            form.removeData('sitra-estado');
            form.removeData('sitra-fuente');
            form.find('[data-campo="sitra-fuente"]').text('');
        }

        function actualizarFuenteSitra(form,fuente){
            var etiqueta=form.find('[data-campo="sitra-fuente"]');
            if(!etiqueta.length){
                return;
            }
            var fuenteNormalizada=String(fuente || '').toLowerCase();
            if(fuenteNormalizada==='sid'){
                etiqueta.text('SID');
            }else if(fuenteNormalizada==='sitra_sid'){
                etiqueta.text('SITRA y SID');
            }else{
                etiqueta.text('');
            }
        }

        function abrirModalSitraFormulario(trigger){
            var form=$(trigger).closest('form');
            var resp=form.data('sitra-response') || null;
            var estado=form.data('sitra-estado') || '';
            var fuente=(form.data('sitra-fuente') || '').toString().toLowerCase();
            var nombreSistemaBase=@json(trim((string)(($tramite->per_apellido ?? '').' '.($tramite->per_nombre ?? ''))));

            var nombreSistema='';
            var numeroSistema=(form.find('input[name="numero"]').val() || '').trim();
            var tipoSistema='';

            if($('#apellido').length || $('#nombre').length){
                nombreSistema=(($('#apellido').val() || '').trim()+' '+($('#nombre').val() || '').trim()).trim();
            }

            if(nombreSistema===''){
                nombreSistema=(nombreSistemaBase || '').toString().trim();
            }

            if(nombreSistema==='' && resp && resp.nombre){
                nombreSistema=(resp.nombre || '').toString().trim();
            }

            var buscarEn=(form.find('select[name="buscar_en"]').val() || '').trim();
            if(buscarEn===''){
                var tipoTexto=form.find('select[data-campo="tipo-legalizacion"] option:selected').text() || '';
                tipoSistema=tipoTexto.trim();
            }else{
                tipoSistema=buscarEn.toUpperCase();
            }

            function esc(v){
                return String(v || '-')
                    .replace(/&/g,'&amp;')
                    .replace(/</g,'&lt;')
                    .replace(/>/g,'&gt;')
                    .replace(/"/g,'&quot;')
                    .replace(/'/g,'&#39;');
            }

            var detalle='';
            if(resp && estado==='0'){
                detalle='<table class="col-md-12">'
                    +'<tr><th class="text-right">Nombre:</th><th class="text-dark text-left border-bottom border-danger pl-3">'+esc(resp.nombre || '')+'</th></tr>'
                    +'<tr><th class="text-right">Título:</th><th class="text-dark text-left border-bottom border-danger pl-3">'+esc(resp.titulo || '')+'</th></tr>'
                    +'<tr><th class="text-right">Número:</th><th class="text-dark text-left border-bottom border-danger pl-3">'+esc(resp.numero || '')+'</th></tr>'
                    +'<tr><th class="text-right">Gestión:</th><th class="text-dark text-left border-bottom border-danger pl-3">'+esc(resp.gestion || '-')+'</th></tr>'
                    +'<tr><th class="text-right">Tipo documento:</th><th class="text-dark text-left border-bottom border-danger pl-3">'+esc(resp.tipo || '')+'</th></tr>'
                    +'</table>';
            }else if(estado==='1'){
                detalle='<p>El documento existe en SITRA, pero los datos no coinciden.</p>';
            }else if(estado==='2'){
                detalle='<p>No se encuentra el documento registrado en SITRA ni en SID.</p>';
            }else{
                detalle='<p>No hay datos de verificación SITRA para mostrar todavía.</p>';
            }

            var esOk=(estado==='0');
            var claseCaja=esOk ? 'alert-success' : 'alert-danger';
            var icono=esOk ? '<h1><i class="fas fa-check-circle"></i></h1>' : '<h1><i class="fas fa-minus-circle"></i></h1>';
            var claseIcono=esOk ? 'text-success' : 'text-danger';
            var mensajeFinal='INCORRECTO';
            if(estado==='0'){
                mensajeFinal='Verificacion Correcta';
            }else if(estado==='1'){
                mensajeFinal='Existe en SITRA, pero no coincide';
            }else if(estado==='2'){
                mensajeFinal='No existe en SITRA ni SID';
            }else{
                mensajeFinal='Pendiente de verificación';
            }

            var html=''
                +'<div class="modal-dialog modal-lg" role="document" id="panel_docleg">'
                +'  <div class="modal-content '+(esOk?'border-bottom-primary':'border-bottom-danger')+' shadow-lg">'
                +'    <div class="modal-header '+(esOk?'bg-verde-oscuro':'bg-danger')+'">'
                +'      <h5 class="modal-title text-white"><img src="{{url('img/icon/eliminar.png')}}">&nbsp;&nbsp;Verificación en el sitra</h5>'
                +'      <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'
                +'    </div>'
                +'    <div class="modal-body">'
                +'      <span class="font-italic">Verificación de trámite: </span><br/><br/>'
                +'      <span class="text-dark font-weight-bold">Datos:</span><br/>'
                +'      <span class="text-dark font-italic" style="font-size: 0.8em">'
                +'        <span class="font-weight-bold">Nombre :</span> <span>'+esc(nombreSistema)+'</span> | '
                +'        <span class="font-weight-bold">Nro. Título :</span> <span>'+esc(numeroSistema)+'</span> | '
                +'        <span class="font-weight-bold">Tipo Documento :</span> <span>'+esc(tipoSistema)+'</span>'
                +'      </span><br/>'
                + (fuente==='sid' ? '<span class="text-info font-italic" style="font-size:0.85em">Fuente: SID</span><br/>' : '')
                + (fuente==='sitra_sid' ? '<span class="text-info font-italic" style="font-size:0.85em">Fuente: SITRA y SID</span><br/>' : '')
                +'      <br/>'
                +'      <div class="row">'
                +'        <div class="font-weight-bold '+claseCaja+' shadow text-center centrar_bloque col-md-9 p-2">'+detalle+'</div>'
                +'        <div class="pt-2 col-md-2 '+claseIcono+' font-weight-bolder text-left">'+icono+'</div>'
                +'      </div><br/>'
                +'      <div class="'+(esOk?'text-success border border-success':'text-danger border border-danger')+' font-italic font-weight-bold rounded col-md-5" style="font-size: 1.1em">'+esc(mensajeFinal)+'</div>'
                +'    </div>'
                +'    <div class="modal-footer"><button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button></div>'
                +'  </div>'
                +'</div>';

            $('#panel_docleg').html(html);
            $('#docleg').modal('show');
        }

        function validarSitraEnFormulario(formulario){
            var form=$(formulario);
            if(!form.length){
                return;
            }

            if(!form.find('[data-campo="estado-sitra"]').length){
                return;
            }

            var numero=(form.find('input[name="numero"]').val() || '').trim();
            var codTipo=(form.find('input[data-campo="tipo-legalizacion-hidden"]').val() || '').trim();
            var buscarEn=(form.find('select[name="buscar_en"]').val() || '').trim();

            if(numero==='' || numero==='-'){
                limpiarSitraFormulario(form);
                actualizarEstadoSitra(form,'text-muted','SITRA: pendiente');
                return;
            }

            if(codTipo==='' && buscarEn===''){
                limpiarSitraFormulario(form);
                actualizarEstadoSitra(form,'text-muted','SITRA: seleccione/valide tipo para consultar');
                return;
            }

            actualizarEstadoSitra(form,'text-muted','SITRA: verificando...');

            $.ajax({
                url: "{{url('validar sitra legalizacion/'.$tramite->cod_tra)}}",
                type: 'POST',
                data: {
                    _token: form.find('input[name="_token"]').val(),
                    numero: numero,
                    tipo: codTipo,
                    buscar_en: buscarEn
                },
                success: function(resp){
                    if(!resp || resp.aplica===false){
                        limpiarSitraFormulario(form);
                        actualizarEstadoSitra(form,'text-muted',resp && resp.message ? resp.message : 'SITRA: no aplica para este tipo');
                        return;
                    }

                    form.data('sitra-response',resp);
                    form.data('sitra-estado',resp.estado || '');
                    form.data('sitra-fuente',resp.fuente || 'sitra');
                    actualizarFuenteSitra(form,resp.fuente || 'sitra');

                    if(resp.estado==='0'){
                        actualizarEstadoSitra(form,'text-success','SITRA: coincide');
                        return;
                    }
                    if(resp.estado==='2'){
                        actualizarEstadoSitra(form,'text-danger','SITRA/SID: no existe');
                        return;
                    }
                    if(resp.estado==='1'){
                        actualizarEstadoSitra(form,'text-danger','SITRA: existe, pero no coincide');
                        return;
                    }

                    actualizarEstadoSitra(form,'text-muted',resp.message || 'SITRA: sin datos para validar');
                },
                error: function(xhr){
                    limpiarSitraFormulario(form);
                    var msg='SITRA: no disponible en este momento';
                    if(xhr.responseJSON && xhr.responseJSON.message){
                        msg='SITRA: '+xhr.responseJSON.message;
                    }
                    actualizarEstadoSitra(form,'text-danger',msg);
                }
            });
        }

        function programarValidacionSitra(formulario){
            var form=$(formulario);
            if(!form.length){
                return;
            }
            var timer=form.data('timer-sitra');
            if(timer){
                clearTimeout(timer);
            }
            timer=setTimeout(function(){
                validarSitraEnFormulario(form);
            },400);
            form.data('timer-sitra',timer);
        }

        function normalizarTexto(texto){
            if(!texto){
                return '';
            }
            return texto.toString()
                .toUpperCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/\s+/g, ' ')
                .trim();
        }

        function autoseleccionarTipoLegalizacion(formulario,resp){
            var select=formulario.find('select[data-campo="tipo-legalizacion"]');
            if(!select.length){
                return;
            }

            var codigo=(resp && resp.tipo_legalizacion_sugerido) ? String(resp.tipo_legalizacion_sugerido) : '';
            if(codigo!=='' && select.find('option[value="'+codigo+'"]').length){
                select.val(codigo);
                return;
            }

            var nombreSugerido=normalizarTexto(resp && resp.nombre_tipo_legalizacion_sugerido ? resp.nombre_tipo_legalizacion_sugerido : '');
            var cuentaApi=normalizarTexto(resp && resp.cuenta ? resp.cuenta : '');

            if(nombreSugerido==='' && cuentaApi===''){
                return;
            }

            var encontrado=false;
            select.find('option').each(function(){
                var texto=normalizarTexto($(this).text());
                if(
                    (nombreSugerido!=='' && (texto===nombreSugerido || texto.indexOf(nombreSugerido)!==-1 || nombreSugerido.indexOf(texto)!==-1)) ||
                    (cuentaApi!=='' && (texto===cuentaApi || texto.indexOf(cuentaApi)!==-1 || cuentaApi.indexOf(texto)!==-1))
                ){
                    select.val($(this).val());
                    encontrado=true;
                    return false;
                }
            });

            if(!encontrado){
                select.val('');
            }
        }

        function sincronizarCamposObligatorios(formulario){
            var form=$(formulario);
            if(!form.length){
                return;
            }

            // Reglas del formulario original:
            // Búsqueda (tipo B): numero, gestion, buscar_en, documentos y control obligatorios.
            // Legalización (L/C/E): gestion, control y reintegro obligatorios.
            var esBusqueda=form.find('select[name="buscar_en"]').length>0 || form.find('textarea[name="documentos"]').length>0;

            form.find('input[name="control"]').prop('required',true);
            form.find('input[name="gestion"]').prop('required',true);
            form.find('input[name="numero"]').prop('required',esBusqueda);
            form.find('input[name="reintegro"]').prop('required',!esBusqueda);
            form.find('select[name="buscar_en"]').prop('required',esBusqueda);
            form.find('textarea[name="documentos"]').prop('required',esBusqueda);
        }

        function sincronizarTipoLegalizacion(formulario){
            var select=formulario.find('select[data-campo="tipo-legalizacion"]');
            if(select.length){
                var valorSeleccionado=select.find('option:selected').val() || '';
                select.val(valorSeleccionado);
                formulario.find('input[data-campo="tipo-legalizacion-hidden"]').val(valorSeleccionado);
            }
        }

        function limpiarTipoLegalizacion(formulario){
            var select=formulario.find('select[data-campo="tipo-legalizacion"]');
            if(select.length){
                select.val('');
            }
            formulario.find('input[data-campo="tipo-legalizacion-hidden"]').val('');
        }

        function programarValidacionControl(inputControl){
            var form=$(inputControl).closest('form');
            if(!form.length){
                return;
            }
            var control=($.trim(form.find('input[name="control"]').val()) || '');
            var controlOk=form.data('control-validado-ok')===1;
            var controlPrevio=(form.data('control-validado-valor') || '').toString();

            if(control!=='' && controlOk && controlPrevio===control){
                return;
            }

            var timer=form.data('timer-control');
            if(timer){
                clearTimeout(timer);
            }
            timer=setTimeout(function(){
                validarControlRecaudaciones(inputControl);
            },350);
            form.data('timer-control',timer);
        }

        $(function(){
            $('form').each(function(){
                if($(this).find('select[data-campo="tipo-legalizacion"]').length){
                    sincronizarTipoLegalizacion($(this));
                }
                if($(this).find('input[name="control"]').length){
                    sincronizarCamposObligatorios($(this));
                }
                if($(this).find('[data-campo="estado-sitra"]').length){
                    programarValidacionSitra($(this));
                }
            });

            $(document).on('change','form input[name="cuadis"]',function(){
                sincronizarCamposObligatorios($(this).closest('form'));
            });

            $(document).on('input change','form input[name="numero"], form input[name="gestion"], form select[name="buscar_en"], form input[data-campo="tipo-legalizacion-hidden"]',function(){
                var form=$(this).closest('form');
                if(form.find('[data-campo="estado-sitra"]').length){
                    programarValidacionSitra(form);
                }
            });
        });

    </script>

