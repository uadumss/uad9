<form action="{{url('g_documento/')}}" method="POST" id="form_importar" enctype="multipart/form-data">
    @csrf

    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary ">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-university"></i> Facultad</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="shadow-sm rounded p-2">
                @if($cod_doc==0)
                    <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                        <h5 class="text-white text-center"> Formulario para nuevo documento</h5>
                    </div>
                    <hr class="sidebar-divider"/>
                    <span class="text-primary font-weight-bold font-italic" style="font-size: 0.8em"> * DATOS DEL DOCUMENTO</span><br/><br/>
                    <div class="row">
                        <div class="col-md-5">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Título :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" required name="titulo"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Tipo de Documento:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="tipo" id="tipo">
                                            <option value="DIPLOMA DE BACHILLER">DIPLOMA DE BACHILLER</option>
                                            <option value="TECNICO MEDIO">TECNICO MEDIO</option>
                                            <option value="TECNICO SUPERIOR">TECNICO SUPERIOR</option>
                                            <option value="DIPLOMA ACADEMICO">DIPLOMA ACADEMICO</option>
                                            <option value="TITULO PROFESIONAL">TITULO PROFESIONAL</option>
                                            <option value="DIPLOMADO">DIPLOMADO</option>
                                            <option value="MAESTRIA">MAESTRIA</option>
                                            <option value="ESPECIALIDAD">ESPECIALIDAD</option>
                                            <option value="DOCTORADO">DOCTORADO</option>
                                            <option value="OTRO">OTRO</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Gestión:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="gestion" id="gestion">
                                            <option value=""></option>
                                            @php
                                                $gestion=date('Y');
                                                for ($gestion1=date('Y');$gestion>1960;$gestion--){
                                                    echo "<option value='".$gestion."'>".$gestion."</option>";
                                                }
                                            @endphp

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Fecha de emisión:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="date" class="form-control form-control-sm border-0" name="fecha"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Universidad:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="universidad" id="universidad" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Nro Reválida:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="revalida" id="revalida" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-7">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Legalizado:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="checkbox" name="legalizado" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Verificado:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="checkbox" name="verificado" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Educación superior:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="checkbox" name="superior" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Documento de la UMSS:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="checkbox" name="umss"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Grado:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="custom-select-sm custom-select border-0" name="grado" id="grado">
                                            <option></option>
                                            <option value="BACHILLER">BACHILLER</option>
                                            <option value="TECNICO MEDIO">TECNICO MEDIO</option>
                                            <option value="TECNICO SUPERIOR">TECNICO SUPERIOR</option>
                                            <option value="PROFESIONAL">PROFESIONAL</option>
                                            <option value="DIPLOMADO">DIPLOMADO</option>
                                            <option value="MAESTRIA">MAESTRIA</option>
                                            <option value="ESPECIALIDAD">ESPECIALIDAD</option>
                                            <option value="DOCTORADO">DOCTORADO</option>
                                            <option value="OTRO">OTRO</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Documento en PDF:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="file" class="form-control form-control-sm border-0" accept=".pdf" name="pdf" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" name="cf" value="{{$cod_fun}}">
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
                                    <th class="text-right font-italic text-dark">Título :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" value="{{$documento->doc_titulo}}" required name="titulo"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Tipo de Documento:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="tipo" id="tipo">

                                            <option value="{{$documento->doc_tipo}}">{{$documento->doc_tipo}}</option>
                                            <option value="DIPLOMA DE BACHILLER">DIPLOMA DE BACHILLER</option>
                                            <option value="TECNICO MEDIO">TECNICO MEDIO</option>
                                            <option value="TECNICO SUPERIOR">TECNICO SUPERIOR</option>
                                            <option value="DIPLOMA ACADEMICO">DIPLOMA ACADEMICO</option>
                                            <option value="TITULO PROFESIONAL">TITULO PROFESIONAL</option>
                                            <option value="DIPLOMADO">DIPLOMADO</option>
                                            <option value="MAESTRIA">MAESTRIA</option>
                                            <option value="ESPECIALIDAD">ESPECIALIDAD</option>
                                            <option value="DOCTORADO">DOCTORADO</option>
                                            <option value="OTRO">OTRO</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Gestión:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="gestion" id="gestion">
                                            <option value="{{$documento->doc_gestion}}">{{$documento->doc_gestion}}</option>
                                            @php
                                                $gestion=date('Y');
                                                for ($gestion1=date('Y');$gestion>1960;$gestion--){
                                                    echo "<option value='".$gestion."'>".$gestion."</option>";
                                                }
                                            @endphp

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Fecha de emisión:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="date" class="form-control form-control-sm border-0" value="{{$documento->doc_fecha_emision}}" name="fecha"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Universidad:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" value="{{$documento->doc_universidad}}" name="universidad" id="universidad" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Nro Reválida:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" value="{{$documento->doc_numero_revalida}}" name="revalida" id="revalida" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-7">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Legalizado:</th>
                                    <td class="border-bottom border-dark">
                                        @if($documento->doc_legalizado=='t')
                                            <input type="checkbox" name="legalizado" checked />
                                        @else
                                            <input type="checkbox" name="legalizado"/>
                                        @endif

                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Verificado:</th>
                                    <td class="border-bottom border-dark">
                                        @if($documento->doc_verificado=='t')
                                            <input type="checkbox" name="verificado" checked/>
                                        @else
                                            <input type="checkbox" name="verificado" />
                                        @endif


                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Educación superior:</th>
                                    <td class="border-bottom border-dark">
                                        @if($documento->doc_edu_superior=='t')
                                            <input type="checkbox" name="superior" checked/>
                                        @else
                                            <input type="checkbox" name="superior" />
                                        @endif

                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Documento de la UMSS:</th>
                                    <td class="border-bottom border-dark">
                                        @if($documento->doc_umss=='t')
                                            <input type="checkbox" name="umss" checked/>
                                        @else
                                            <input type="checkbox" name="umss"/>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Grado:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="custom-select-sm custom-select border-0" name="grado" id="grado">
                                            @if($documento->doc_grado!='')
                                                <option value="{{$documento->doc_grado}}">{{$documento->doc_grado}}</option>
                                            @endif
                                            <option></option>
                                            <option value="BACHILLER">BACHILLER</option>
                                            <option value="TECNICO MEDIO">TECNICO MEDIO</option>
                                            <option value="TECNICO SUPERIOR">TECNICO SUPERIOR</option>
                                            <option value="PROFESIONAL">PROFESIONAL</option>
                                            <option value="DIPLOMADO">DIPLOMADO</option>
                                            <option value="MAESTRIA">MAESTRIA</option>
                                            <option value="ESPECIALIDAD">ESPECIALIDAD</option>
                                            <option value="DOCTORADO">DOCTORADO</option>
                                            <option value="OTRO">OTRO</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Documento en PDF:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="file" class="form-control form-control-sm border-0" accept=".pdf" name="pdf" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" name="cd" value="{{$documento->cod_doc}}">
                    <input type="hidden" name="cf" value="{{$cod_fun}}">
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
            <input class="btn btn-primary" type="submit" value="Guardar"/>
        </div>
    </div>
</form>
