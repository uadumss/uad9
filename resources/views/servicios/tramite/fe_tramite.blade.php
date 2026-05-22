<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>

<form action="{{url('g_legalizacion')}}" method="POST">
    @csrf
    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Trámite </h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                @php                                $tipo_tramite['L']='LEGALIZACIÓN'; $tipo_tramite['LC']='bg-info text-white';
                                                    $tipo_tramite['F']='CONFRONTACIÓN';$tipo_tramite['FC']='bg-danger text-white';
                                                    $tipo_tramite['C']='CERTIFICACIÓN';$tipo_tramite['CC']='bg-warning text-dark';
                                                    $tipo_tramite['B']='BUSQUEDA';$tipo_tramite['BC']='bg-success text-white';
                                                    $tipo_tramite['A']='NO-ATENTADO';$tipo_tramite['AC']='bg-primary text-white';
                                                    $tipo_tramite['E']='CONSEJO';$tipo_tramite['EC']='bg-secondary text-white';
                @endphp

                <h6 class="text-white text-center">Formulario para editar trámite</h6>
            </div>
            <hr class="sidebar-divider"/>
            @if($cod_tre==0)
                <div class="row">
                    <div class="col-sm-5">
                        <span class="text-primary font-italic font-weight-bold">* Datos del trámite</span>
                        <table class="table-hover col-md-12">
                            <tr>
                                <th class="text-right font-italic">Nombre : </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder=""
                                           required name="nombre" /></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">N° Cuenta : </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder=""
                                           required name="cuenta" /></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Costo (Bs.): </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder=""
                                           required name="costo" pattern="[0-9]{1,4}"/></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Duración (Hrs): </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder=""
                                           required name="duracion"/></td>
                            </tr>
                            @if($tipo=='L' || $tipo=='C' || $tipo=='A')
                            <tr>
                                <th class="text-right font-italic border-bottom">Buscar en :</th>
                                <td class="border-bottom border-dark">
                                    <div style="max-height: 150px; overflow-y: auto; padding: 5px; border: 1px solid #ddd; border-radius: 4px; background: #fff;">
                                        @php
                                            $opciones = [
                                                'db' => 'DB', 'ca' => 'CA', 'da' => 'DA', 'tp' => 'TP',
                                                'di' => 'DI', 'tpos' => 'TPOS', 're' => 'RE', 'su' => 'SU',
                                                'res' => 'RESOLUCION', 'db-ant' => 'DB-ANTECEDENTE',
                                                'ca-ant' => 'CA-ANTECEDENTE', 'da-ant' => 'DA-ANTECEDENTE',
                                                'tp-ant' => 'TP-ANTECEDENTE', 'di-ant' => 'DI-ANTECEDENTE',
                                                'tpos-ant' => 'TPOS-ANTECEDENTE', 're-ant' => 'RE-ANTECEDENTE',
                                                'su-ant' => 'SU-ANTECEDENTE'
                                            ];
                                        @endphp
                                        @foreach($opciones as $val => $label)
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="chk_crear_{{$val}}" name="buscar_en[]" value="{{$val}}">
                                                <label class="custom-control-label" for="chk_crear_{{$val}}" style="font-size: 0.85rem; cursor: pointer;">{{$label}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th class="text-right font-italic">Descripción : </th>
                                <td class="border-bottom border-dark">
                                    <textarea class="form-control border-0" rows="5" name="desc" id="desc"></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>
                    @if($tipo=='L' || $tipo=='C' || $tipo=='A' || $tipo=='E')
                        <div class="col-sm-7">
                            <span class="text-primary font-italic font-weight-bold">* Datos de glosa</span>
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic"> Título de glosa: </th>
                                    <td class="border-bottom border-dark">
                                        <textarea class="form-control border-0" rows="2" name="titulo" id="titulo"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic"> Título de glosa (Interno): </th>
                                    <td class="border-bottom border-dark">
                                        <textarea class="form-control border-0" rows="2" name="titulo_interno" id="titulo_interno"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Solo sello: </th>
                                    <td class="border-bottom border-dark">
                                        &nbsp;&nbsp; <input type="checkbox" name="sello">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @endif
                    <input type="hidden" name="tipo" value="{{$tipo}}">
                </div>
            @else
                <div class="row">

                    @if($tramite->tre_tipo=='L' || $tramite['tre_tipo']=='C')
                        <div class="col-sm-5">
                    @else
                                <div class="col-md-12">
                    @endif
                                    <span class="text-primary font-italic font-weight-bold">* Datos del trámite de:
                                        <span class="font-italic font-weight-bold rounded pl-2 pr-2 {{$tipo_tramite[$tramite['tre_tipo'].'C']}}" style="font-size: 0.8em">
                                                {{$tipo_tramite[$tramite['tre_tipo']]}}
                                            </span>
                                        </span>
                                    <table class="table-hover col-md-12">
                                        <tr>
                                            <th class="text-right font-italic">Nombre : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       required name="nombre" value="{{$tramite['tre_nombre']}}" /></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">N° Cuenta : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       required name="cuenta" value="{{$tramite['tre_numero_cuenta']}}" /></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Costo (Bs.): </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       required name="costo" pattern="[0-9]{1,4}" value="{{$tramite['tre_costo']}}"/></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Duración (Hrs): </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       required name="duracion" value="{{$tramite['tre_duracion']}}"/></td>
                                        </tr>
                                        @if($tipo=='L' || $tipo=='C' || $tipo=='A')
                                        <tr>
                                            <th class="text-right font-italic border-bottom">Buscar en :</th>
                                            <td class="border-bottom border-dark">
                                                @php $selecciones = explode(',', $tramite['tre_buscar_en'] ?? ''); @endphp
                                                <div style="max-height: 150px; overflow-y: auto; padding: 5px; border: 1px solid #ddd; border-radius: 4px; background: #fff;">
                                                    @php
                                                        $opciones = [
                                                            'db' => 'DB', 'ca' => 'CA', 'da' => 'DA', 'tp' => 'TP',
                                                            'di' => 'DI', 'tpos' => 'TPOS', 're' => 'RE', 'su' => 'SU',
                                                            'res' => 'RESOLUCION', 'db-ant' => 'DB-ANT',
                                                            'ca-ant' => 'CA-ANT', 'da-ant' => 'DA-ANT',
                                                            'tp-ant' => 'TP-ANT', 'di-ant' => 'DI-ANT',
                                                            'tpos-ant' => 'TPOS-ANT', 're-ant' => 'RE-ANT',
                                                            'su-ant' => 'SU-ANT'
                                                        ];
                                                    @endphp
                                                    @foreach($opciones as $val => $label)
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="chk_editar_{{$val}}" name="buscar_en[]" value="{{$val}}" {{ in_array($val, $selecciones) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="chk_editar_{{$val}}" style="font-size: 0.85rem; cursor: pointer;">{{$label}}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th class="text-right font-italic">Descripción : </th>
                                            <td class="border-bottom border-dark">
                                                <textarea class="form-control border-0" rows="5" name="desc" id="desc">{{$tramite['tre_desc']}}</textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                @if($tramite['tre_tipo']=='L' || $tramite['tre_tipo']=='C' || $tramite['tre_tipo']=='A' || $tramite['tre_tipo']=='E')
                                    <div class="col-sm-7">
                                        <span class="text-primary font-italic font-weight-bold">* Datos de glosa</span>
                                        <table class="col-md-12">
                                            <tr>
                                                <th class="text-right font-italic"> Título de glosa: </th>
                                                <td class="border-bottom border-dark">
                                                    <textarea class="form-control border-0" rows="2" name="titulo" id="titulo">{{$tramite['tre_titulo']}}</textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic"> Título de glosa (Interno): </th>
                                                <td class="border-bottom border-dark">
                                                    <textarea class="form-control border-0" rows="2" name="titulo_interno" id="titulo_interno">{{$tramite['tre_titulo_interno']}}</textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic">Solo sello: </th>
                                                <td class="border-bottom border-dark">
                                                    @if($tramite->tre_solo_sello=='t')
                                                        &nbsp;&nbsp; <input type="checkbox" name="sello" checked>
                                                    @else
                                                        &nbsp;&nbsp; <input type="checkbox" name="sello">
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                @endif
                        </div>
                        <input type="hidden" name="ct" value="{{$tramite['cod_tre']}}">
            @endif
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <input class="btn btn-primary" type="submit" value="Aceptar"/>
        </div>
    </div>

</form>

