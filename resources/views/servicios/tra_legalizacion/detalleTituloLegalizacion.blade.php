<style type="text/css">
    #outerContainer #mainContainer div.toolbar {
        display: none !important; /* hide PDF viewer toolbar */
    }
    #outerContainer #mainContainer #viewerContainer {
        top: 0 !important; /* move doc up into empty bar space */
    }
</style>
@php
    $normalizarCarrera = function ($texto) {
        $txt = mb_strtoupper(trim((string) $texto), 'UTF-8');
        $txt = strtr($txt, [
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ñ' => 'N'
        ]);
        $txt = preg_replace('/[^A-Z0-9 ]+/', ' ', $txt);
        $txt = preg_replace('/\s+/', ' ', $txt);
        return trim($txt);
    };

    $ajustarPorSexo = function ($texto, $sexo) {
        $valor = (string) $texto;
        if (trim($valor) === '') {
            return '';
        }

        $sexoNorm = mb_strtoupper(trim((string) $sexo), 'UTF-8') === 'F' ? 'F' : 'M';
        $pares = [
            'LICENCIADO' => 'LICENCIADA',
            'INGENIERO' => 'INGENIERA',
            'ARQUITECTO' => 'ARQUITECTA',
            'TECNICO' => 'TECNICA',
            'MEDICO' => 'MEDICA',
            'ABOGADO' => 'ABOGADA',
            'DOCTOR' => 'DOCTORA',
            'AUDITOR' => 'AUDITORA',
            'QUIMICO' => 'QUIMICA',
            'BIOLOGO' => 'BIOLOGA',
            'FISICO' => 'FISICA',
            'MATEMATICO' => 'MATEMATICA',
            'INFORMATICO' => 'INFORMATICA',
            'PSICOLOGO' => 'PSICOLOGA',
            'CONTADOR' => 'CONTADORA',
            'ADMINISTRADOR' => 'ADMINISTRADORA',
            'COMUNICADOR' => 'COMUNICADORA',
            'TRABAJADOR' => 'TRABAJADORA',
        ];

        if ($sexoNorm === 'F') {
            foreach ($pares as $masc => $fem) {
                $valor = preg_replace('/\\b' . preg_quote($masc, '/') . '\\b/u', $fem, $valor);
            }
            return $valor;
        }

        foreach ($pares as $masc => $fem) {
            $valor = preg_replace('/\\b' . preg_quote($fem, '/') . '\\b/u', $masc, $valor);
        }

        return $valor;
    };

    $mapaBaseTitulos = [
        'PROG. ING. REC. HIDRICOS AGROPECUARIA' => 'INGENIERO EN GESTION DE RECURSOS HIDRICOS AGROPECUARIOS',
        'LICENCIATURA EN INGENIERIA AGRONOMICA ZOOTECNISTA' => 'INGENIERO AGRONOMICO ZOOTECNISTA',
        'LICENCIATURA EN INGENIERIA AGRICOLA' => 'INGENIERO AGRICOLA',
        'LICENCIATURA EN INGENIERIA AGRONOMICA FITOTECNISTA' => 'INGENIERO AGRONOMA FITOTECNISTA',
        'PROGRAMA LIC. EN INGENIERIA FORESTAL' => 'INGENIERO FORESTAL',
        'LICENCIATURA EN INGENIERIA AGRONOMICA' => 'INGENIERO AGRONOMO',
        'PROGRAMA COMPLEMENTACION ING. FORESTAL' => 'INGENIERO FORESTAL',
        'LICENCIATURA EN INGENIERIA FORESTAL(NUE)' => 'INGENIERO FORESTAL',
        'LIC. EN ING. AGR TROPICAL MANEJO DE RECURSOS RENOVABLES' => 'INGENIERA EN AGRICULTURA TROPICAL Y MANEJO DE RECURSOS RENOVABLES',
        'LICENCIATURA EN INGENIERIA AGROINDUSTRIAL' => 'INGENIERO AGROINDUSTRIAL',
        'TECNICO UNIVERSITARIO SUPERIOR AGRONOMO' => 'TECNICO UNIVERSITARIO SUPERIOR AGRONOMO',
        'TECNICO UNIVERSITARIO SUPERIOR FORESTAL' => 'TECNICO UNIVERSITARIO SUPERIOR FORESTAL',
        'LICENCIATURA EN INGENIERIA AGROFORESTAL' => 'INGENIERO AGROFORESTAL',
        'LICENCIATURA EN INGENIERIA AMBIENTAL' => 'INGENIERO AMBIENTAL',
        'LICENCIATURA EN INGENIERIA EN AGRICULTURA TROPICAL Y MANEJO DE RECURSOS RENOVABLES' => 'INGENIERIA EN AGRICULTURA TROPICAL Y MANEJO DE RECURSOS RENOVABLES',
        'PROGRAMA DE INGENIERIA AGRONOMICA ZOOTECNISTA' => 'INGENIERA AGRONOMA ZOOCTENISTA',
        'LICENCIATURA EN GESTION DEL DESARROLLO ENDOGENO Y AGROECOLOGIA' => 'LICENCIADO EN GESTION DE DESARROLLO ENDOGENO Y AGROECOLOGIA',
        'LICENCIATURA EN BIOQUIMICA Y FARMACIA' => 'BIOQUIMICA FAMACEUTICA',
        'LICENCIATURA EN ECONOMIA' => 'ECONOMISTA',
        'LICENCIATURA EN ADMINISTRACION DE EMPRESAS' => 'ADMINISTRADOR DE EMPRESAS',
        'LICENCIATURA EN INGENIERIA COMERCIAL' => 'INGENIERO COMERCIAL',
        'LICENCIATURA EN INGENIERIA FINANCIERA' => 'INGENIERA FINANCIERA',
        'LICENCIATURA EN CONTADURIA PUBLICA' => 'CONTADORA PUBLICA AUTORIZADA',
        'TECNICO UNIVERSITARIO SUPERIOR CONTADOR GENERAL' => 'CONTADOR GENERAL',
        'TECNICO UNIVERSITARIO SUPERIOR EN PROYECTOS DE INVERSION' => 'TECNICO UNIVERSITARIO SUPERIOR EN PROYECTOS DE INVERSION',
        'TECNICO UNIVERSITARIO SUPERIOR EN PROYECTOS SOCIALES' => 'TECNICO UNIVERSITARIO SUPERIOR EN PROYECTOS SOCIALES',
        'TECNICO UNIVERSITARIO SUPERIOR EN ESTADÍSTICA' => 'TECNICO UNIVERSITARIO SUPERIOR EN ESTADÍSTICA',
        'LICENCIATURA EN AUDITORIA' => 'AUDITOR',
        'LICENCIATURA EN ODONTOLOGIA (PLAN NUEVO)' => 'CIRUJANA DENTISTA',
        'LICENCIATURA EN FISIOTERAPIA Y KINESIOLOGIA' => 'LICENCIADA EN FISIOTERAPIA Y KINESIOLOGIA',
        'LICENCIATURA EN MEDICINA' => 'MEDICO CIRUJANO',
        'TEC.UNIV.SUP. FISIOTERAPIA Y KINESIOLOGIA' => 'TECNICO UNIVERSITARIO SUPERIOR EN FISITERAPIA Y KINESIOLOGIA',
        'LIC. ENFERMERIA' => 'LICECNIADA EN ENFERMERIA',
        'LICENCIATURA EN ARQUITECTURA' => 'ARQUITECTO',
        'LICENCIATURA EN DISEÑO DE INTERIORES Y DEL MOBILIARIO' => 'LICENCIADO EN DISEÑO DE INTERIORES Y DEL MOBILIARIO',
        'LICENCIATURA EN PLANIFICACION DEL TERRITORIO Y MEDIO AMBIENTE' => 'LICENCIADO EN PLANIFICACION DEL TERRITORIO Y MEDIO AMBIENTE',
        'LICENCIATURA EN DISEÑO GRAFICO Y COMUNICACION VISUAL' => 'LICENCIADO EN DISEÑO GRAFICO Y COMUNICACION VISUAL',
        'LICENCIATURA EN TURISMO' => 'LICENCIADA EN TURISMO',
        'TEC.UNIV. SUP EN CONSTRUCCIONES' => 'TECNICO UNIVERSITARIO SUPERIOR EN CONSTRUCCIONES',
        'TECNICO UNIVERSITARIO SUPERIOR EN DISEÑO DE INTERIORES' => 'TECNICO UNIVERSITARIO SUPERIOR EN DISEÑO DE INTERIORES',
        'LICENCIATURA EN CONSTRUCCIONES' => 'LICENCIADO EN CONSTRUCCIONES',
        'TEC. UNIVERSITARIO SUP. EN CARTOGRAFÍA, CATASTRO Y SIS. DE INFORMACIÓN GEOGRÁFICA' => 'TECNICO UNIVERSITARIO SUPERIOR EN CARTOGRAFÍA, CATASTRO Y SIS. DE INFORMACIÓN GEOGRÁFICA Y CATASTRO',
        'TEC.UNIV.SUP. DISEÑO GRÁFICO' => 'TECNICO UNIVERSITARIO SUPERIOR EN DISEÑO GRAFICO',
        'LICENCIATURA EN CIENCIAS DE LA EDUCACION' => 'LICENCIADO EN CIENCIAS DE LA EDUCACION',
        'PROGRAMA LIC. EN CS. ACT. FISICA Y DEPORTE' => 'LICENCIADO EN CIENCIAS DE LA ACTIVIDAD FISICA Y DEL DEPORTE',
        'PROGRAMA DE LICENCIATURA EN MUSICA' => 'LICENCIADO EN MUSICA',
        'PROGRAMA LIC. ESP. ED. INTERCUL.BILINGUE' => 'LICENCIADA EN EDUCACION INTERCULTURAL BILINGUE',
        'LICENCIATURA EN TRABAJO SOCIAL' => 'TRABAJADORA SOCIAL',
        'LICENCIATURA EN COMUNICACION SOCIAL' => 'COMUNICADORA SOCIAL',
        'LICENCIATURA EN PSICOLOGIA (NUE)' => 'PSICOLOGO',
        'LICENCIATURA EN LINGUISTICA APLICADA EN LA ENSEÑANZA DE LENGUAS' => 'LICENCIADO EN LINGÜÍSTICA APLICADA A LA ENSEÑANZA DE LENGUAS',
        'LICENCIATURA EN CIENCIAS JURIDICAS' => 'ABOGADO',
        'LICENCIATURA EN CIENCIAS POLITICAS (NUE)' => 'POLITOLOGA',
        'LICENCIATURA EN INFORMATICA' => 'INFORMATICO',
        'LICENCIATURA EN INGENIERIA DE SISTEMAS' => 'INGENIERO DE SISTEMAS',
        'LICENCIATURA EN DIDACTICA  MATEMATICA' => 'INGENIERO EN DIDCATICA MATEMATICA',
        'LICENCIATURA EN INGENIERIA INFORMATICA' => 'INGENIERO INFORMATICO',
        'LICENCIATURA EN INGENIERIA QUIMICA' => 'INGENIERO QUIMICO',
        'LICENCIATURA EN QUIMICA' => 'QUIMICO',
        'LICENCIATURA EN ING. ELECTROMECANICA' => 'INGENIERO ELECTROMECANICO',
        'LICENCIATURA EN MATEMATICAS' => 'MATEMATICO',
        'LICENCIATURA EN INGENIERIA ELECTRICA' => 'INGENIERO ELECTRICO',
        'LICENCIATURA EN INGENIERIA MECANICA' => 'INGENIERO MECANICO',
        'LICENCIATURA EN INGENIERIA INDUSTRIAL' => 'INGENIERO INDUSTRIAL',
        'LICENCIATURA EN DIDACTICA DE LA FISICA' => 'LICENCIADO EN DICACTICA DE LA FISICA',
        'LICENCIATURA EN INGENIERIA ELECTRONICA' => 'INGENIERO ELECTRONICO',
        'LICENCIATURA EN INGENIERIA DE ALIMENTOS' => 'INGENIERA DE ALIMENTOS',
        'LICENCIATURA EN BIOLOGIA' => 'BIOLOGO',
        'LICENCIATURA EN FISICA' => 'FISICO',
        'LICENCIATURA EN INGENIERIA MATEMATICA' => 'INGENIERO MATEMATICO',
        'LICENCIATURA EN INGENIERIA PETROQUIMICA' => 'INGENIERO EN PETROQUIMICA',
        'LICENCIATURA EN INGENIERIA CIVIL' => 'INGENIERO CIVIL',
        'TECNICO SUPERIOR EN FISICA' => 'TECNICO SUPERIOR EN FISICA',
        'TECNICO SUPERIOR EN BIOLOGIA' => 'TECNICO SUPERIOR EN BIOLOGIA',
        'TECNICO SUPERIOR EN QUIMICA' => 'TECNICO SUPERIOR EN QUIMICA',
        'LICENCIATURA ESPECIAL EN DIDACTICA MATEMATICA' => 'LICENCIATURA ESPECIAL EN DIDACTICA MATEMATICA',
        'AUXILIAR TECNICO EN ENFERMERIA' => 'AUXILIAR TECNICO EN ENFERMERIA',
        'TEC.UNIV.SUP. EN  MECANICA AUTOMOTRIZ' => 'TECNICO SUPERIOR UNIVERSITARIO EN MECANICA AUTOMOTRIZ',
        'TEC.UNIV.SUP. EN CONSTRUCCION CIVIL' => 'TECNICO SUPERIOR UNIVERSITARIO EN CONSTRUCCION CIVIL',
        'TEC.UNIV.MED EN ENFERMERIA' => 'TECNICO UNIVERSITARIO MEDIO EN ENFERMERIA',
        'LICENCIATURA EN SOCIOLOGIA' => 'SOCIOLOGA',
        'TECNICO SUPERIOR UNIVERSITARIO EN GESTION CULTURAL' => 'TECNICA SUPERIOR UNIVERSITARIA EN GESTION CULTURAL',
        'LICENCIATURA EN MEDICINA VETERINARIA Y ZOOTECNIA' => 'MEDICA VETERINARIA Y ZOOCTENISTA',
        'TEC. UNIVERSITARIO MEDIO EN ENFERMERIA' => 'TECNICO UNIVERSITARIO MEDIO EN ENFERMERIA',
        'LICENCIATURA EN ENFERMERIA' => 'LICENCIADA EN ENFERMERIA',
    ];

    $mapaBaseDiplomas = [
        'PROG. ING. REC. HIDRICOS AGROPECUARIA' => 'LICENCIADO EN INGENIERIA EN GESTION DE RECURSOS HIDRICOS AGROPECUARIOS',
    ];

    $titulosPorCarrera = [];
    foreach ($mapaBaseTitulos as $carreraMapa => $tituloMapa) {
        $titulosPorCarrera[$normalizarCarrera($carreraMapa)] = $tituloMapa;
    }

    $diplomasPorCarrera = [];
    foreach ($mapaBaseDiplomas as $carreraMapa => $diplomaMapa) {
        $diplomasPorCarrera[$normalizarCarrera($carreraMapa)] = $diplomaMapa;
    }

    $tituloSegunCarrera = '';
    $diplomaSegunCarrera = '';
    if (sizeof($diploma_academico) > 0) {
        $carreraDetalle = $normalizarCarrera($diploma_academico[0]->car_nombre ?? '');
        $tituloSegunCarrera = $titulosPorCarrera[$carreraDetalle] ?? '';
        $diplomaSegunCarrera = $diplomasPorCarrera[$carreraDetalle] ?? '';
    }

    $sexoDetalle = $titulo[0]->per_sexo ?? '';
    $diplomaBase = $diplomaSegunCarrera !== '' ? $diplomaSegunCarrera : ($titulo[0]->tit_titulo ?? '');
    $diplomaAcademicoMostrado = $ajustarPorSexo($diplomaBase, $sexoDetalle);

    $tituloBase = $tituloSegunCarrera !== '' ? $tituloSegunCarrera : ($titulo[0]->tit_titulo ?? '');
    $tituloMostrado = $ajustarPorSexo($tituloBase, $sexoDetalle);
@endphp
<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-verde-oscuro">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-eye"></i>&nbsp;&nbsp;Título</h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="bg-primary centrar_bloque p-1 col-md-8 rounded shadow-sm">
            <h6 class="text-white text-center">Detalle del título</h6>
        </div>
        <div>
            <hr class="sidebar-divider"/>
            <div class="row">
                @if($docleg->dtra_cod_tit!='')
                    <div class="col-md-4 border">
                        <div>
                            <table style="font-size:0.9em;" class="col-md-12">
                                <tr>
                                    <th class="text-right text-primary font-italic" colspan="2">DATOS PERSONALES</th>
                                </tr><tr>
                                    <th class="text-right font-italic">Nombre : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->per_apellido." ".$titulo[0]->per_nombre}}</td>
                                </tr><tr>
                                    <th class="text-right font-italic">CI : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->per_ci}}</td>
                                </tr><tr>
                                    <th class="text-right font-italic">Pasaporte : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->per_pasaporte}}</td>
                                </tr><tr>
                                    <th class="text-right font-italic">Sexo : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->per_sexo}}</td>
                                </tr><tr>
                                    <th class="text-right font-italic">Nacionalidad : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->nac_nombre}}</td>
                                </tr><tr>
                                    <th class="text-right text-primary font-italic" colspan="2">DATOS DEL TITULO</th>
                                </tr><tr>
                                    <th class="text-dark text-right font-italic">Tipo de documento : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{\App\Models\Funciones::nombre_titulo($titulo[0]->tit_tipo)}}</td>
                                </tr><tr>
                                    <th class="text-right font-italic">Nº Título : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->tit_nro_titulo}}</td>
                                </tr><tr>
                                    <th class="text-right font-italic">fecha de emisión : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{date('d/m/Y',strtotime($titulo[0]->tit_fecha_emision))}}</td>
                                </tr><tr>
                                    <th class="text-right font-italic">Nº Tomo : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->tom_numero}}</td>
                                </tr><tr>
                                    <th class="text-dark text-right font-italic">Nº Folio : </th>
                                    <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->tit_nro_folio}} &nbsp;&nbsp;&nbsp; <span class="text-primary font-weight-bold">Fecha folio</span>
                                        <span><?php if($titulo[0]->tit_fecha_folio!=''){
                                                echo date('d/m/Y',strtotime($titulo[0]->tit_fecha_folio));
                                            }?>
                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Gestión : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->tom_gestion}}</td>
                                </tr><tr>
                                    <th class="text-right font-italic">Grado : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->tit_grado}}</td>
                                </tr>
                                @if(sizeof($diploma_academico)>0)
                                    <tr>
                                        <th class="text-right font-italic">Carrera : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$diploma_academico[0]->car_nombre}}</td>
                                    </tr><tr>
                                        <th class="text-right font-italic">Facultad : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$diploma_academico[0]->fac_nombre}}</td>
                                    </tr>
                                @endif
                                @if($titulo[0]->tit_ref!='')
                                    <tr>
                                        <th class="text-right font-italic">Referencia: </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->tit_ref}}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th class="text-right font-italic">Diploma académico : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$diplomaAcademicoMostrado}}</td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Título : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$tituloMostrado}}</td>
                                </tr>
                                @if($titulo[0]->mod_nombre!='')
                                    <tr>
                                        <th class="text-right font-italic">Modalidad : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->mod_nombre}}</td>
                                    </tr>
                                @endif
                                @if(sizeof($revalida)>0)
                                    <tr>
                                        <th class="text-right text-primary font-italic" colspan="2">DATOS DEL REVÁLIDA</th>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Universidad origen: </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$revalida[0]->re_universidad}}</td>
                                    </tr><tr>
                                        <th class="text-right font-italic">País : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$revalida[0]->nac_nombre}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Fecha emisión docmuento : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{date('d/m/Y',strtotime($revalida[0]->re_fecha))}}</td>
                                    </tr>
                                @endif

                            </table>
                        </div>
                    </div>

                    <div class="col-md-8" >
                        <h5 class="text-center text-primary">Título</h5>
                        @if($titulo[0]->tit_pdf!='')
                            <embed src="{{url('pdf/'.$titulo[0]->cod_tit)}}#toolbar=0" class="col-md-12" height="600"/>
                        @else
                            <div class="alert alert-danger alert-dismissible">
                                <span class="">No existe el archivo digital</span>
                            </div>
                        @endif
                        <hr class="sidebar-divider bg-primary"/>
                        <h5 class="text-center text-primary">Antecedentes</h5>

                        @if($titulo[0]->tit_antecedentes!='')
                            @can('mostrar antecedente - dyt')
                                <a class="btn btn-light" data-toggle="collapse" href="#collapseExample" role="button"
                                   aria-expanded="false" aria-controls="collapseExample">
                                    Antecedentes <i class="fas fa-arrow-alt-circle-down"></i>
                                </a>
                                <br/>
                                <br/>
                                <div class="collapse" id="collapseExample">
                                    <div>
                                        <embed src="{{url('pdf_a/'.$titulo[0]->cod_tit)}}#toolbar=0" class="col-md-12" height="600"/>
                                    </div>
                                </div>
                            @endcan
                        @else
                            <div class="alert alert-danger alert-dismissible">
                                <span class="">No existe el archivo digital</span>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="alert-danger border border-danger rounded col-md-6 centrar_bloque p-3">
                        No existe el Título registrado en la base de datos
                    </div>
                @endif
            </div>

        </div>
    </div>
    <input type="hidden" name="fila_obs" id="fila_obs" value="0">
    <div class="modal-footer">
        <button class="btn btn-secondary" onclick="" data-dismiss="modal">Cerrar</button>
    </div>
</div>




