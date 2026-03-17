<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-file-alt"></i> CONVOCATORIA</h5>
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
                    <div class="card-body" style="font-size: 12px;">
                        @if(!$convocatoria)
                            <form id="form_convocatoria" enctype="multipart/form-data" method="POST" action="{{url('guardar convocatoria noatentado')}}">
                                @csrf
                            <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                                <h5 class="text-white text-center">Nueva convocatoria</h5>
                            </div>
                            <hr class="sidebar-divider text-bg-dark">
                            <div class="row">
                                <div class="col-md-6">
                                    <span class="text-primary font-weight-bold text-primary font-italic" style="font-size: 14px">* Datos de la convocatoria</span>
                                    <table class="col-md-12">
                                        <tbody>
                                            <tr>
                                                <th class="text-right font-italic">Título :</th>
                                                <td class="border-bottom border-dark">
                                                    <input class="form-control-sm form-control border-0" type="text" name="titulo" placeholder="Título de la convocatoria" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic" style=" padding-top: 7px">Publicación Convocatoria :</th>
                                                <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                    <div class="col-md-12">
                                                        <input type="date" name="fi_convocatoria" required="" class="form-control form-control-sm border-0 form-control-user">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic" style=" padding-top: 7px">Entrega documentos :</th>
                                                <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                    <div class="col-md-12">
                                                        <input type="date" name="ff_convocatoria" required="" class="form-control form-control-sm border-0 form-control-user">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic" style=" padding-top: 7px">Fecha conclusión :</th>
                                                <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                    <div class="col-md-12">
                                                        <input type="date" name="fc_convocatoria" class="form-control form-control-sm border-0 form-control-user">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic" style=" padding-top: 7px">Tipo Convocatoria :</th>
                                                <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                    <select class="custom-select custom-select-sm border-0" name="tipo">
                                                        <option value=""></option>
                                                        <option value="ACADEMICO">ACADEMICO</option>
                                                        <option value="GREMIAL">GREMIAL</option>
                                                        <option value="ADMINISTRATIVO">ADMINISTRATIVO</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <!-- POR HIDDEN LA CLASE ES NO ATENTADO-->
                                            <tr>
                                                <th class="text-right font-italic" style=" padding-top: 7px">Dirigido a :</th>
                                                <td class="border-bottom border-dark">
                                                     <select class="custom-select custom-select-sm border-0" name="dirigido">
                                                        <option value=""></option>
                                                        <option value="DOCENTE">DOCENTE</option>
                                                        <option value="ESTUDIANTE">ESTUDIANTE</option>
                                                         <option value="DOCENTE-ESTUDIANTE">DOCENTE-ESTUDIANTE</option>
                                                        <option value="ADMINISTRATIVO">ADMINISTRATIVO</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic ">Periodo:</th>
                                                <td class="border-bottom  input-group">
                                                    <div class="input-group p-2">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="text-primary font-weight-bold font-italic pt-2"> Incial : &nbsp;</span>
                                                        <select class="custom-select-sm custom-select border" name="inicial">
                                                            <option></option>
                                                                <?php $año=date('Y');?>
                                                            @for($i=$año;$i<($año+8);$i++)
                                                                <option value="{{$i}}">{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                        &nbsp;&nbsp;<span class="text-primary font-weight-bold font-italic pt-2"> Final : &nbsp;</span>
                                                        <select class="custom-select-sm custom-select border" name="final">
                                                            <option></option>
                                                                <?php $año=date('Y');?>
                                                            @for($i=$año;$i<($año+8);$i++)
                                                                <option value="{{$i}}">{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">

                                </div>
                            </div>
                            </form>
                        @else
                            <div class="text-center">
                                <h4 class="text-primary font-weight-bold">Editar Convocatoria</h4>
                            </div>
                            <hr class="sidebar-divider text-bg-dark">
                            <div class="row">
                                <div class="col-md-6">
                                    <span
                                        class="text-primary font-weight-bold float-left">DATOS DE LA CONVOCATORIA</span>
                                    <br><br>
                                    <form id="form_convocatoria" enctype="multipart/form-data" method="POST" action="{{url('guardar convocatoria noatentado')}}">
                                        @csrf
                                    <table class="col-md-12">
                                        <tbody>
                                        <tr>
                                            <th class="text-right font-italic">Título :</th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control-sm form-control border-0" type="text" name="titulo" placeholder="Título de la convocatoria" value="{{$convocatoria->con_nombre}}"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic" style=" padding-top: 7px">Publicación Convocatoria :</th>
                                            <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                <div class="col-md-12">
                                                    <input type="date" name="fi_convocatoria" required="" class="form-control form-control-sm border-0 form-control-user" value="{{$convocatoria->con_fecha_publicacion}}">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic" style=" padding-top: 7px">Entrega documentos :</th>
                                            <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                <div class="col-md-12">
                                                    <input type="date" name="ff_convocatoria" required="" class="form-control form-control-sm border-0 form-control-user" value="{{$convocatoria->con_fecha_entrega}}">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic" style=" padding-top: 7px">Fecha conclusión :</th>
                                            <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                <div class="col-md-12">
                                                    <input type="date" name="fc_convocatoria" class="form-control form-control-sm border-0 form-control-user" value="{{$convocatoria->con_fecha_eleccion}}">
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="text-right font-italic" style=" padding-top: 7px">Tipo Convocatoria :</th>
                                            <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                <select class="custom-select custom-select-sm border-0" name="tipo">
                                                    @if($convocatoria->con_tipo!='')
                                                        <option value="{{$convocatoria->con_tipo}}">{{$convocatoria->con_tipo}}</option>
                                                    @endif
                                                    <option value=""></option>
                                                    <option value="ACADEMICO">ACADEMICO</option>
                                                    <option value="GREMIAL">GREMIAL</option>
                                                    <option value="ADMINISTRATIVO">ADMINISTRATIVO</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <!-- POR HIDDEN LA CLASE ES NO ATENTADO-->
                                        <tr>
                                            <th class="text-right font-italic" style=" padding-top: 7px">Dirigido a :</th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0" name="dirigido">
                                                    @if($convocatoria->con_dirigido_a!='')
                                                    <option value="{{$convocatoria->con_dirigido_a}}">{{$convocatoria->con_dirigido_a}}</option>
                                                    @endif
                                                    <option value=""></option>
                                                    <option value="DOCENTE">DOCENTE</option>
                                                    <option value="ESTUDIANTE">ESTUDIANTE</option>
                                                    <option value="DOCENTE-ESTUDIANTE">DOCENTE-ESTUDIANTE</option>
                                                    <option value="ADMINISTRATIVO">ADMINISTRATIVO</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic ">Periodo:</th>
                                            <td class="border-bottom  input-group">
                                                <div class="input-group p-2">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="text-primary font-weight-bold font-italic pt-2"> Incial : &nbsp;</span>
                                                    <select class="custom-select-sm custom-select border" name="inicial">
                                                        <option value="{{$convocatoria->con_periodo_inicial}}">{{$convocatoria->con_periodo_inicial}}</option>
                                                        <option></option>
                                                            <?php $año=date('Y');?>
                                                        @for($i=$año;$i<($año+8);$i++)
                                                            <option value="{{$i}}">{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                    &nbsp;&nbsp;<span class="text-primary font-weight-bold font-italic pt-2"> Final : &nbsp;</span>
                                                    <select class="custom-select-sm custom-select border" name="final">
                                                        <option value="{{$convocatoria->con_periodo_final}}">{{$convocatoria->con_periodo_final}}</option>
                                                        <option></option>
                                                            <?php $año=date('Y');?>
                                                        @for($i=$año;$i<($año+8);$i++)
                                                            <option value="{{$i}}">{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic" style=" padding-top: 7px">Convocatoria PDF :
                                            </th>
                                            <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                <div class="input-group">
                                                    @if($convocatoria->con_pdf!='')
                                                        <input type="file" class="form-control form-control-sm border-danger" name="pdf_conv" accept=".pdf" value="{{$convocatoria->archivo}}">
                                                        <a href="{{url("PDF_convocatoria/".$convocatoria->cod_con)}}" type="button" data-target="#modal_noAtentado" target="_blank">
                                                            &nbsp;&nbsp;<i class="fas fa-file-pdf text-danger" style="font-size: 30px;"></i>
                                                        </a>
                                                        &nbsp;&nbsp;
                                                    @else
                                                            <input type="file" class="form-control form-control-sm border-0" name="pdf_conv" accept=".pdf" value="{{$convocatoria->archivo}}">
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                        <input type="hidden" name="cc" value="{{$convocatoria->cod_con}}">
                                    </form>
                                    <br/><br/>

                                    <div class="shadow rounded p-3 m-2">
                                        <table class="col-md-12">
                                            <tr>
                                                <td class="font-italic text-primary font-weight-bold" >Unidad de la convocatoria:</td>
                                                <td class="border-bottom border-dark">{{$convocatoria->con_convocante}} </td>
                                            </tr>
                                        </table>
                                        <a class="btn btn-sm btn-primary text-white" data-toggle="modal" data-target="#modal_agregar"
                                           onclick="cargarDatos('{{url('editar unidad convocatoria noatentado/'.$convocatoria->cod_con)}}','panel_agregar')">
                                            Unidad</a>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                                <div class="shadow m-2 p-2 rounded">
                                                    <div class="border rounded">
                                                        <span class="font-weight-bold font-italic text-primary"> * Cargos registrados</span>
                                                        <br/>
                                                        <br/>
                                                        <div id="panel_cargos" class="overflow-auto ml-5" style="height: 400px;" >
                                                            <table class="col-md-12">
                                                                <tr class="bg-secondary text-white">
                                                                    <th>No.</th>
                                                                    <th>Nombre cargo</th>
                                                                    <th>Opciones</th>
                                                                </tr>
                                                                    <?php $i=1;?>
                                                                @foreach($cargos as $c)
                                                                    <tr class="border-bottom">
                                                                        <td>{{$i}}</td>
                                                                        <td>{{$c->carg_nombre}}</td>
                                                                        <td>
                                                                            <form id="form_eliminar{{$i}}">
                                                                                @csrf
                                                                                <input type="hidden" name="cc" value="{{$convocatoria->cod_con}}"/>
                                                                                <input type="hidden" name="ca" value="{{$c->cod_carg}}"/>
                                                                            </form>
                                                                            <a class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" data-target="#modal_agregar" onclick="cargarDatos('{{url('cargos convocatoria noatentado/'.$c->cod_carg.'/'.$convocatoria->cod_con)}}','panel_agregar')"><i class="fas fa-edit"></i></a>
                                                                            <a class="btn btn-light btn-circle btn-sm text-danger" onclick="enviar('form_eliminar{{$i}}','{{url('eliminar cargo convocatoria noatentado')}}','panel_cargos')"><i class="fas fa-trash-alt"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                        <?php $i++;?>
                                                                @endforeach
                                                            </table>
                                                        </div>
                                                        <a class="btn btn-sm btn-primary text-white" data-toggle="modal" data-target="#modal_agregar"
                                                           onclick="cargarDatos('{{url('cargos convocatoria noatentado/0/'.$convocatoria->cod_con)}}','panel_agregar')">
                                                            Cargos</a>
                                                    </div>
                                                </div>


                                </div>
                                <br/>

                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div><!-- End Formulario Convocatoria -->
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal"> Cerrar</button>
            @if(!$convocatoria)
                <button class="btn btn-primary btn-sm" type="button" onclick="enviar('form_convocatoria','{{url('guardar convocatoria noatentado')}}','panel_convocatoria');cargarDatos('{{url('actualizar lista convocatoria noatentado/'.date('Y'))}}','panel_lista')"> Guardar</button>
            @else
                <a href="#" class="btn btn-primary btn-sm text-white" onclick="$('#form_convocatoria').submit();"> Guardar</a>
            @endif
        </div>
    </div>
</div>

