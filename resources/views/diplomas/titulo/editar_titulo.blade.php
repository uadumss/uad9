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
        <div class="justify-content-center">
            <div class="card shadow">
                <div class="card-body">
                    <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                        <h6 class="text-white text-center">Formulario para nuevo título</h6>
                    </div>
                    <hr class="sidebar-divider"/>
                    <div class="row">
                        <div class="col-md-6">
                            <span class="text-primary font-weight-bold float-right">DATOS DEL TITULO</span>
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic">Nº título:</th>
                                    <td class="border-bottom border-dark">

                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm border-0" pattern="[0-9]{1,5}"
                                                   required name="nro" value="{{$titulo[0]->tit_nro_titulo}}" id="e_nro"/>
                                            @if($tipo=='re')
                                                <span class="text-danger font-weight-bold pt-1" style="font-size: 0.8em">Reconocimiento</span>&nbsp;&nbsp;

                                                @if($titulo[0]->tit_reconocimiento=='t')
                                                    <input type="checkbox" name="reconocimiento" id="reconocimiento" class="" checked/>
                                                @else
                                                    <input type="checkbox" name="reconocimiento" id="reconocimiento"/>
                                                @endif
                                            @endif
                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Fecha:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="date" class="form-control form-control-sm border-0" required
                                               name="fecha" value="{{$titulo[0]->tit_fecha_emision}}" id="e_fec"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Grado :</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="grado" id="e_grado" >
                                            <option value="{{$titulo[0]->tit_grado}}">{{$titulo[0]->tit_grado}}</option>
                                            @foreach($grado as $g)
                                                <option value="{{$g}}">{{$g}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){?>
                                <tr>
                                    <th class="text-right font-italic">Carrera: </th>
                                    <td class="border-bottom border-dark">
                                        <div class="row">
                                            <div id="fila_e_car" class="col-md-11">
                                                <select class="custom-select custom-select-sm" name="car" id="e_car">
                                                    <option value="{{$titulo[0]->cod_car}}">{{$titulo[0]->car_nombre}}</option>
                                                    @foreach($carrera as $c)
                                                        <option value="{{$c->cod_car}}">{{$c->fac_abreviacion." - ".$c->car_nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){?>
                                                <a href='#' class="btn btn-sm btn-info btn-circle btn-info ml-1" data-toggle="modal" data-target="#verObs"
                                                   onclick="verDatos('{{url('añadir carrera tomo/'.$tomo["cod_tom"].'/fila_e_car')}}','p_observacion','')" title="Añadir Carrera">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php }?>
                                <tr>
                                    <th class="text-right font-italic">Nº folio:</th>
                                    <td class="border-bottom border-dark">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm border-0" pattern="[0-9]{1,5}"
                                                   name="folio" value="{{$titulo[0]->tit_nro_folio}}" id="e_fol"/>
                                            <span class="text-primary font-weight-bold" style="font-size: 0.9em">Fecha Folio. </span>&nbsp;&nbsp;
                                            <input type="date" class="form-control form-control-sm border-0" name="fecha_folio"
                                                   value="{{$titulo[0]->tit_fecha_folio}}" id="e_fecha_folio"/>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Título en PDF:</th>
                                    <td class="border-bottom border-dark input-group">
                                        <input type="file" class="form-control form-control-sm border-0" accept=".pdf" name="pdf" id="pdf" />
                                        @if($titulo[0]->tit_pdf!='')
                                            <img src="{{url('img/icon/tit.gif')}}" width="30" height="30">
                                            <input type="hidden" name="pdf_val" id="pdf_val" value="1">
                                        @else
                                            <input type="hidden" name="pdf_val" id="pdf_val" value="0">
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Antecedentes en PDF:</th>
                                    <td class="border-bottom border-dark input-group">
                                        <input type="file" class="form-control form-control-sm border-0" accept=".pdf" name="pdf_ant" id="pdf_ant"/>
                                        @if($titulo[0]->tit_antecedentes!='')
                                            <img src="{{url('img/icon/antecedente.gif')}}" width="30" height="30">
                                            <input type="hidden" name="pdf_val_ant" id="pdf_val_ant" value="1">
                                        @else
                                            <input type="hidden" name="pdf_val_ant" id="pdf_val_ant" value="0">
                                        @endif
                                    </td>
                                </tr>
                                <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa' || $tipo=='tpos' || $tipo=='di' || $tipo=='re' || $tipo=='db'){?>
                                <tr>
                                    <th class="text-right font-italic">Título:</th>
                                    <td class="border-bottom border-dark">
                                        <textarea rows="2" class="form-control-sm form-control border-0" name="titulo" id="e_tit">{{$titulo[0]->tit_titulo}}</textarea>
                                        <input type="hidden" id="e_titulo_manual" value="0"/>
                                        <div class="d-flex justify-content-end align-items-center mt-1">
                                            <button type="button" class="btn btn-sm btn-primary" id="e_auto_titulo" aria-pressed="true">Autocompletado: ACTIVO</button>
                                        </div>
                                    </td>
                                </tr>
                                <?php }?>
                                <?php if($tipo=='su'){?>
                                <tr>
                                    <th class="text-right font-italic">Referencia A:</th>
                                    <td class="border-bottom border-dark">
                                        <textarea rows="2" class="form-control-sm form-control border-0" name="ref">{{$titulo[0]->tit_ref}}</textarea>
                                    </td>
                                </tr>
                                <?php }?>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <span class="text-primary font-weight-bold float-right">DATOS PERSONALES</span>
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic">Nº CI:</th>
                                    <td class="border-bottom border-dark">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm border-0" name="ci" id="e_ci"
                                                   value="{{$titulo[0]->per_ci}}"/>
                                            <span class="text-danger font-weight-bold" style="font-size: 0.9em">Exp. </span>&nbsp;&nbsp;
                                            <select name="expedido" class="custom-select-sm custom-select col-md-4" id="expedido">
                                                <option value="{{$titulo[0]->per_ci_exp}}">{{$titulo[0]->per_ci_exp}}</option>
                                                <option value=""></option>
                                                <option value="CB">CB</option>
                                                <option value="LP">LP</option>
                                                <option value="SC">SC</option>
                                                <option value="PT">PT</option>
                                                <option value="OR">OR</option>
                                                <option value="TA">TA</option>
                                                <option value="BE">BE</option>
                                                <option value="PA">PA</option>
                                                <option value="CH">CH</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Nº passaporte:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="pass" id="e_pas"
                                            value="{{$titulo[0]->per_pasaporte}}"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Apellidos:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="apellido" id="e_ape"
                                        value="{{$titulo[0]->per_apellido}}"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Nombres:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="nombre" id="e_nom"
                                            value="{{$titulo[0]->per_nombre}}"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Sexo:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="sexo" id="e_sex">
                                            <?php if($titulo[0]->per_sexo=='M'){?>
                                                <option value="M">MASCULINO</option>
                                                <option value="F">FEMENINO</option>
                                            <?php }else{?>
                                                <option value="F">FEMENINO</option>
                                                <option value="M">MASCULINO</option>
                                            <?php }?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Nacionalidad:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="nac" id="e_nac">
                                            <option value="{{$titulo[0]->cod_nac}}">{{$titulo[0]->nac_nombre}}</option>
                                            @foreach($nacionalidad as $n)
                                                <option value="{{$n['cod_nac']}}">{{$n['nac_nombre']}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa' || $tipo=='tpos' || $tipo=='di'){?>
                                <tr>
                                    <th class="text-right font-italic">Modalidad:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="mod" id="e_mod" onchange="e_habilitarMod($('#e_mod option:selected').text())">
                                            <option value="{{$titulo[0]->cod_mod}}">{{$titulo[0]->mod_nombre}}</option>
                                            @foreach($modalidad as $m)
                                                <option value="{{$m['cod_mod']}}">{{$m['mod_nombre']}}</option>
                                            @endforeach
                                        </select>
                                        @if($titulo[0]->tit_otra_modalidad=='')
                                            <div id="e_otraMod" style="display: none">
                                        @else
                                            <div id="e_otraMod">
                                        @endif
                                                <input type="text" class="form-control-sm form-control border border-primary text-danger" name="otra_modalidad"
                                                       placeholder="Ingrese la modalidad" value="{{$titulo[0]->tit_otra_modalidad}}">
                                            </div>
                                            <script>
                                                function e_habilitarMod(valor){
                                                    if(valor=='Otro...'){
                                                        $('#e_otraMod input').prop('disabled', false);
                                                        $('#e_otraMod').show(250);
                                                    }else{
                                                        $('#e_otraMod input').prop('disabled', true);
                                                        $('#e_otraMod').hide(250);
                                                    }
                                                }
                                            </script>

                                    </td>
                                </tr>
                                <?php }?>
                            </table>
                        </div>
                    </div>

                    <?php if($tipo=='re' || ($tipo=='tp' && $titulo[0]->tit_revalida=='t')){?>
                    <hr class="sidebar-divider"/>
                        <input type="hidden" name="revalida" id="revalida" value="t">
                        <div class="col-md-5" id="div_revalida">
                            <span class="text-primary font-weight-bold float-right">DATOS DE REVÁLIDA</span>
                            <br/>
                            <table>
                                <tr>
                                    <th class="text-right font-italic">País de origen:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="pais_origen" id="e_pao">
                                            <option value="{{$revalida[0]->cod_nac}}">{{$revalida[0]->nac_nombre}}</option>
                                            @foreach($nacionalidad as $n)
                                                <option value="{{$n['cod_nac']}}">{{$n['nac_nombre']}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Universidad:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0 col-md-12" value="{{$revalida[0]->re_universidad}}" name="universidad" id="e_uni"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Otorgado el:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="date" class="form-control form-control-sm border-0 col-md-12" value="{{$revalida[0]->re_fecha}}" name="fecha_revalida" id="e_fre"/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

        <input type="hidden" name="tipo" id="tipo" value="{{$tipo}}">
        <input type="hidden" name="ctit" value="{{$titulo[0]->cod_tit}}">
        <input type="hidden" name="ct" value="{{$tomo['cod_tom']}}"/>
        <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){?>
            <input type="hidden" name="fac" id="e_fac" value="{{$titulo[0]->fac_nombre}}"/>
        <?php }?>

        <script>
            (function(){
                function normalizarTituloTexto(texto){
                    if(!texto){
                        return '';
                    }
                    var txt = texto.toString().toUpperCase();
                    if(typeof txt.normalize === 'function'){
                        txt = txt.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                    }
                    return txt.replace(/\s+/g, ' ').trim();
                }

                function basePorGradoYSexo(grado, sexo){
                    var gradoNorm = normalizarTituloTexto(grado);
                    var esFemenino = sexo === 'F';
                    switch (gradoNorm){
                        case 'LICENCIATURA': return esFemenino ? 'LICENCIADA' : 'LICENCIADO';
                        case 'TECNICO SUPERIOR': return esFemenino ? 'TECNICA SUPERIOR' : 'TECNICO SUPERIOR';
                        case 'TECNICO MEDIO': return esFemenino ? 'TECNICA MEDIA' : 'TECNICO MEDIO';
                        case 'AUXILIAR': return 'AUXILIAR';
                        case 'BACHILLER': return 'BACHILLER';
                        case 'DIPLOMADO': return esFemenino ? 'DIPLOMADA' : 'DIPLOMADO';
                        case 'ESPECIALIDAD': return 'ESPECIALISTA';
                        case 'MAESTRIA': return 'MAGISTER';
                        case 'DOCTORADO': return esFemenino ? 'DOCTORA' : 'DOCTOR';
                        default: return '';
                    }
                }

                function extraerCarrera(optionText){
                    var txt = (optionText || '').trim();
                    var i = txt.indexOf(' - ');
                    if(i >= 0){
                        return txt.substring(i + 3).trim();
                    }
                    return txt;
                }

                function limpiarPrefijosAcademicos(carrera){
                    var carreraNorm = normalizarTituloTexto(carrera);
                    var prefijos = [
                        'LICENCIATURA EN ',
                        'LICENCIATURA ',
                        'TECNICO SUPERIOR EN ',
                        'TECNICO MEDIO EN ',
                        'DIPLOMADO EN ',
                        'MAESTRIA EN ',
                        'DOCTORADO EN ',
                        'BACHILLER EN ',
                        'AUXILIAR EN '
                    ];

                    for(var i = 0; i < prefijos.length; i++){
                        if(carreraNorm.indexOf(prefijos[i]) === 0){
                            return carreraNorm.substring(prefijos[i].length).trim();
                        }
                    }

                    return carreraNorm;
                }

                function tienePrefijoAcademico(carrera){
                    var carreraNorm = normalizarTituloTexto(carrera);
                    var prefijos = [
                        'LICENCIATURA EN ',
                        'LICENCIATURA ',
                        'TECNICO SUPERIOR EN ',
                        'TECNICO MEDIO EN ',
                        'DIPLOMADO EN ',
                        'MAESTRIA EN ',
                        'DOCTORADO EN ',
                        'BACHILLER EN ',
                        'AUXILIAR EN '
                    ];

                    for(var i = 0; i < prefijos.length; i++){
                        if(carreraNorm.indexOf(prefijos[i]) === 0){
                            return true;
                        }
                    }

                    return false;
                }

                function sugerirTituloProfesional(carrera, sexo, grado){
                    var sexoNorm = (sexo || '').toUpperCase() === 'F' ? 'F' : 'M';
                    var conPrefijoAcademico = tienePrefijoAcademico(carrera);
                    var carreraNorm = limpiarPrefijosAcademicos(carrera);
                    var carreraClave = carreraNorm.replace(/[^A-Z0-9 ]+/g, ' ').replace(/\s+/g, ' ').trim();

                    if(carreraClave === 'PROG ING REC HIDRICOS AGROPECUARIA'){
                        return sexoNorm === 'F'
                            ? 'LICENCIADA EN INGENIERIA EN GESTION DE RECURSOS HIDRICOS AGROPECUARIOS'
                            : 'LICENCIADO EN INGENIERIA EN GESTION DE RECURSOS HIDRICOS AGROPECUARIOS';
                    }

                    if(!conPrefijoAcademico && carreraNorm.indexOf('INGENIERIA') === 0){
                        var sufijoIng = carreraNorm.replace('INGENIERIA', '').trim();
                        var baseIng = sexoNorm === 'F' ? 'INGENIERA' : 'INGENIERO';
                        return (baseIng + ' ' + sufijoIng).trim();
                    }

                    if(!conPrefijoAcademico && carreraNorm.indexOf('ARQUITECTURA') === 0){
                        var sufijoArq = carreraNorm.replace('ARQUITECTURA', '').trim();
                        var baseArq = sexoNorm === 'F' ? 'ARQUITECTA' : 'ARQUITECTO';
                        return (baseArq + ' ' + sufijoArq).trim();
                    }

                    var baseGrado = basePorGradoYSexo(grado, sexoNorm);
                    if(baseGrado === ''){
                        return carreraNorm !== '' ? ('PROFESIONAL EN ' + carreraNorm) : '';
                    }

                    if(carreraNorm === ''){
                        return baseGrado;
                    }

                    return baseGrado + ' EN ' + carreraNorm;
                }

                function actualizarTituloEdicion(forzar){
                    var esManual = $('#e_titulo_manual').val() === '1';
                    if(esManual && !forzar){
                        return;
                    }

                    var sexo = $('#e_sex').val() || 'M';
                    var grado = $('#e_grado').val() || '';
                    var carrera = extraerCarrera($('#e_car option:selected').text());
                    var sugerido = sugerirTituloProfesional(carrera, sexo, grado);

                    if(sugerido !== ''){
                        $('#e_tit').val(sugerido);
                    }
                }

                function actualizarEstadoBotonAutoEdicion(){
                    var autoActivo = $('#e_titulo_manual').val() === '0';
                    var $btn = $('#e_auto_titulo');

                    if(autoActivo){
                        $btn.removeClass('btn-outline-primary').addClass('btn-primary');
                        $btn.text('Autocompletado: ACTIVO');
                        $btn.attr('aria-pressed', 'true');
                    }else{
                        $btn.removeClass('btn-primary').addClass('btn-outline-primary');
                        $btn.text('Autocompletado: INACTIVO');
                        $btn.attr('aria-pressed', 'false');
                    }
                }

                $('#e_tit').off('input.autoTitulo').on('input.autoTitulo', function(){
                    $('#e_titulo_manual').val('1');
                    actualizarEstadoBotonAutoEdicion();
                });

                $(document).off('change.autoTituloEdicion input.autoTituloEdicion', '#e_sex, #e_grado, #e_car');
                $(document).on('change.autoTituloEdicion input.autoTituloEdicion', '#e_sex, #e_grado, #e_car', function(){
                    actualizarTituloEdicion(false);
                });

                $('#editarTitulo').off('shown.bs.modal.autoTituloEdicion').on('shown.bs.modal.autoTituloEdicion', function(){
                    actualizarTituloEdicion(false);
                });

                $('#e_auto_titulo').off('click.autoTitulo').on('click.autoTitulo', function(){
                    var autoActivo = $('#e_titulo_manual').val() === '0';
                    $('#e_titulo_manual').val(autoActivo ? '1' : '0');
                    actualizarEstadoBotonAutoEdicion();
                    if(!autoActivo){
                        actualizarTituloEdicion(true);
                    }
                });

                actualizarEstadoBotonAutoEdicion();
                if($.trim($('#e_tit').val()) === ''){
                    actualizarTituloEdicion(true);
                }
            })();
        </script>

