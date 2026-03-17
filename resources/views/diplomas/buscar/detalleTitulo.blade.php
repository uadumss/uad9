

<style type="text/css">
                      #outerContainer #mainContainer div.toolbar {
                          display: none !important; /* hide PDF viewer toolbar */
                      }
#outerContainer #mainContainer #viewerContainer {
    top: 0 !important; /* move doc up into empty bar space */
}
</style>


<hr class="sidebar-divider"/>
<div class="row">
    <div class="col-md-4 border">
        <div>
            <table style="font-size:0.9em;" class="col-md-12">
                <tr>
                    <th class="text-primary text-right text-primary font-italic" colspan="2">DATOS PERSONALES</th>
                </tr><tr>
                    <th class="text-dark text-right font-italic">Nombre : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->per_apellido." ".$titulo[0]->per_nombre}}</td>
                </tr><tr>
                    <th class="text-dark text-right font-italic">CI : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->per_ci}} &nbsp;&nbsp;
                        <span class="text-danger font-weight-bold" style="font-size: 0.9em">Exp. </span> {{$titulo[0]->per_ci_exp}}</td>
                </tr><tr>
                    <th class="text-dark text-right font-italic">Pasaporte : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->per_pasaporte}}</td>
                </tr><tr>
                    <th class="text-dark text-right font-italic">Sexo : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->per_sexo}}</td>
                </tr><tr>
                    <th class="text-dark text-right font-italic">Nacionalidad : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->nac_nombre}}</td>
                </tr><tr>
                    <th class="text-primary text-right text-primary font-italic" colspan="2">DATOS DEL DIPLOMA O TITULO</th>
                </tr><tr>
                    <th class="text-dark text-right font-italic">Tipo de documento : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{\App\Models\Funciones::nombre_titulo($titulo[0]->tit_tipo)}}</td>
                </tr><tr>
                    <th class="text-dark text-right font-italic">Nº Título : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->tit_nro_titulo}}</td>
                </tr><tr>
                    <th class="text-dark text-right font-italic">Fecha de emisión : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{date('d/m/Y',strtotime($titulo[0]->tit_fecha_emision))}}</td>
                </tr><tr>
                    <th class="text-dark text-right font-italic">Nº Tomo : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->tom_numero}}</td>
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
                    <th class="text-dark text-right font-italic">Gestión : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->tom_gestion}}</td>
                </tr><tr>
                    <th class="text-dark text-right font-italic">Grado : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->tit_grado}}</td>
                </tr>
                @if(sizeof($diploma_academico)>0)
                <tr>
                    <th class="text-dark text-right font-italic">Carrera : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$diploma_academico[0]->car_nombre}}</td>
                </tr><tr>
                    <th class="text-dark text-right font-italic">Facultad : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$diploma_academico[0]->fac_nombre}}</td>
                </tr>
                @endif
                @if($titulo[0]->tit_ref!='')
                    <tr>
                        <th class="text-dark text-right font-italic">Referencia: </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->tit_ref}}</td>
                    </tr>
                @endif
                @if($titulo[0]->tit_titulo!='')
                    <tr>
                        <th class="text-dark text-right font-italic">Título: </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->tit_titulo}}</td>
                    </tr>
                @endif
                @if($titulo[0]->mod_nombre!='')
                    @if($titulo[0]->mod_nombre=='Otro...')
                    <tr>
                        <th class="text-dark text-right font-italic">Modalidad : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->tit_otra_modalidad}}</td>
                    </tr>
                    @else
                        <tr>
                            <th class="text-dark text-right font-italic">Modalidad : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$titulo[0]->mod_nombre}}</td>
                        </tr>
                    @endif
                @endif
                @if(sizeof($revalida)>0)
                    <tr>
                        <th class="text-dark text-right text-primary font-italic" colspan="2">DATOS DEL REVÁLIDA</th>
                    </tr>
                    <tr>
                        <th class="text-dark text-right font-italic">Universidad origen: </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$revalida[0]->re_universidad}}</td>
                    </tr><tr>
                        <th class="text-dark text-right font-italic">País : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{$revalida[0]->nac_nombre}}</td>
                    </tr>
                    <tr>
                        <th class="text-dark text-right font-italic">Fecha emisión docmuento : </th> <td class="border-bottom border-dark"> &nbsp;&nbsp;{{date('d/m/Y',strtotime($revalida[0]->re_fecha))}}</td>
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
</div>
