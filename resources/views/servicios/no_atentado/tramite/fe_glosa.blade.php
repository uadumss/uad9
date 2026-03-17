
<div class="modal-content border-bottom-primary shadow-lg">
    <div class="modal-header bg-verde-oscuro">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Glosa de Legalización </h5>
        <button class="close text-dark" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-dark" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body" >

        @if(Session::has('exito'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="font-weight-bold">{!! session('exito') !!}</span>
            </div>
        @endif
        <div class="alert-primary centrar_bloque p-1 col-md-7 rounded shadow">
            <h6 class="text-dark font-weight-bold text-center">Glosa de Legalización</h6>
        </div>

            <hr class="sidebar-divider"/>
            <div class="row">
                <div class="col-md-4" style="font-size: 0.85em;">
                    <div class="shadow-sm p-2 col-md-5 float-md-right">
                        <h1 class="text-danger pr-3 text-center">{{$tramite_noatentado->dtra_numero_tramite}}</h1>
                        <span class="font-italic text-dark text-center"><?php if($tramite_noatentado->dtra_fecha_registro!=''){echo date('d/m/Y',strtotime($tramite_noatentado->dtra_fecha_registro));} ?></span>
                    </div>
                    <span class="text-primary font-weight-bold text-primary font-italic" style="font-size: 14px">* Datos de la convocatoria</span>
                        <table class="text-dark col-md-12">
                            <tbody>
                            <tr>
                                <th class="text-right font-italic" style=" padding-top: 7px">Convocatoria :</th>
                                <td class="border-bottom border-dark" style=" padding-top: 7px">
                                    <span class="font-italic">{{$convocatoria->con_nombre}}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Trámite :</th>
                                <td class="border-bottom border-dark">
                                    <span class="">{{$tramite->tre_nombre}}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic ">Tipo de trámite :</th>
                                <td class="border-bottom border-dark">
                                    @if($tramite_noatentado->dtra_interno=='t')
                                        <input type="radio" name="tipo_tramite" checked value="t">  INTERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    @else
                                        <input type="radio" name="tipo_tramite" checked value="f">  EXTERNO
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic ">Nro. Control valorado:</th>
                                <td class="border-bottom border-dark">
                                    <div class="input-group">
                                        &nbsp;&nbsp;
                                        {{$tramite_noatentado->dtra_control}}
                                        @if($tramite_noatentado->dtra_valorado_reintegro!='')
                                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="text-dark font-weight-bold font-italic"> Nro. Control Reintegro : &nbsp;</span>
                                        {{$tramite_noatentado->dtra_valorado_reintegro}}
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <br/>
                    <br/>
                    <div class="overflow-auto" style="height: 200px" id="panel_candidato">
                        <span class="font-weight-bold font-italic text-primary" style="font-size: 12px;">* Lista de candidatos</span>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="lista" width="100%" cellspacing="0" style="font-size: smaller">

                                <tr>
                                    <th>N°</th>
                                    <th>Nombre</th>
                                    <th>CI</th></tr>
                                <?php $i=1;?>
                                @foreach($candidatos as $n)
                                        <?php $sancionado=App\Http\Controllers\Noatentado\SancionadosController::verificarSancionado($n->id_per);?>
                                    @if($sancionado)
                                        <tr class="alert-danger">
                                    @else
                                        <tr>
                                            @endif
                                            <td>{{$i++}}</td>
                                            <td>{{$n->per_nombre." ".$n->per_apellido}}</td>
                                            <td>{{$n->per_ci}}</td>
                                        </tr>
                                        @endforeach

                            </table>
                        </div>
                    </div>
                </div>
                <!-- ================================GLOSA====================================-->
                <div class="col-md-8 pl-3 shadow-sm border" style="height: 600px">
                    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
                    <br/>
                    <form id="form_generar_pdf" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-2 pr-1 ">
                                <div class="float-right">
                                    @if($tramite_noatentado->dtra_qr!='')
                                        {{QrCode::size(100)->generate('http://www.archivos.umss.edu.bo/verificar_tramite/index.php?q='.$tramite_noatentado->dtra_qr)}}<br/><br/>
                                    @else
                                        <div class="text-danger font-weight-bold font-italic border-danger rounded p-2 border">* No se puede generar la Glosa, devido a que faltan datos personales</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-10" id="panel_glosa_legalizacion">
                                <span class="font-weight-bold">{{$tramite_noatentado->dtra_titulo}}</span><br/><br/>
                                <span class="font-weight-bold font-italic">{{"ARCH ".$tramite_noatentado->dtra_numero_tramite."/".$tramite_noatentado->dtra_gestion_tramite}}</span><br/>
                                <textarea name="glosa" id="glosa1" class="form-control" style="z-index: 3500;text-align: justify" rows="15">{{$tramite_noatentado->dtra_glosa}}</textarea>
                                <div class="text-right">
                                    <span class="text-right pr-3">{{$tramite_noatentado->dtra_fecha_literal}}</span>
                                </div>
                                <br/>
                                <input type="hidden" name="cd" value="{{$tramite_noatentado->cod_dtra}}">
                            </div>
                        </div>
                    </form>
                    <script type="text/javascript">
                        tinymce.init({
                            selector: '#glosa1',
                            format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
                        });
                    </script>
                    <br/>
                </div>

            </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
        @if($tramite_noatentado->dtra_qr!='')
           <a class="btn btn-primary btn-sm" href="{{url('generar pdf noatentado/'.$tramite_noatentado->cod_dtra)}}" target="No-atentado"
               onclick="guardarGlosa('{{url('generar documento noatentado')}}','panel_lista_tramites','form_generar_pdf');
                $('#Modal').modal('hide')" >Generar PDF</a>
        @endif
    </div>
</div>
<script>
    function guardarGlosa(ruta,panel,form){
        var link = ruta;
        var token = "{{csrf_token()}}";
        $('#glosa1').val(tinyMCE.get('glosa1').getContent());
        var form = new FormData($('#'+form).get(0));
        $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
        $.ajax({
            url: link,
            type: 'POST',
            contentType: false,
            processData: false,
            data:form,
            //data:$('#form_generar_legalizacion').serialize(),
            success: function (resp) {
                $('#'+panel).html(resp);
                $('#Noatentado').modal('hide');
            },
            error: function (data) {
                $('#error_datos').show();
                // setTimeout(function(){ $('#error_datos').hide(500); }, 4000);
            }
        });
    }
</script>
