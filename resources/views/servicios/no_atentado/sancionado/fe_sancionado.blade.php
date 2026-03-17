<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-file-alt"></i> SANCIONADOS</h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>

    <!-- Formulario sancionados -->
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
                <div class="card-body" style="font-size: 14px;">
                    <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                        <h5 class="text-white text-center">Datos de sancionado </h5>
                    </div>
                    <hr class="sidebar-divider text-bg-dark">
                    @if(!$sancionado)
                        <form id="form_sancionado">
                        <div class="row">
                            <div class="col-md-6">
                                    @csrf
                                    <span class="text-primary font-weight-bold text-primary font-italic" style="font-size: 14px">* Datos del sancionado</span>
                                    <table class="col-md-11">
                                        <tbody>
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
                                            <th class="text-right font-italic">Código SIS: </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" required name="cod_sis" id="cod_sis"/>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                            </div>
                            <div class="col-md-6 shadow border">
                                <span class="text-primary font-weight-bold text-primary font-italic" style="font-size: 14px">* Datos de la sanción</span>
                                <br/><br/>
                                <table class="col-md-12">
                                    <tr>
                                        <th class="font-italic text-dark" colspan="2">Referencia :
                                            <textarea class="form-control form-control-sm" name="referencia"></textarea>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="font-italic text-dark" colspan="2">Sentencia :
                                            <textarea class="form-control form-control-sm" name="sentencia"></textarea>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="font-italic text-dark" colspan="2">Resolución :
                                            <textarea class="form-control form-control-sm" name="resolucion"></textarea>
                                        </th>
                                    </tr>
                                </table>
                                <br/>
                            </div>
                        </div>

                        </form>
                    @else

                            <div class="row">
                                <div class="col-md-6">
                                    <span class="text-primary font-weight-bold text-primary font-italic" style="font-size: 14px">* Datos del sancionado</span>
                                    <table class="col-md-11 table">
                                        <tbody>
                                        <tr>
                                            <th class="text-right font-italic">CI : </th>
                                            <td class="border-bottom border-dark">{{$sancionado->per_ci}}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Nombres : </th>
                                            <td class="border-bottom border-dark">{{$sancionado->per_apellido." ".$sancionado->per_nombre}}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Código SIS: </th>
                                            <td class="border-bottom border-dark">{{$sancionado->per_cod_sis}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br/><br/>
                                    <div class="input-group p-2 rounded shadow float-right border">
                                        <a class="btn btn-sm btn-primary text-white" data-toggle="modal" data-target="#Modal2" onclick="cargarDatos('{{url('obtener resolucion sancionado/'.$sancionado->cod_san)}}','panel_modal2')">+ Resolucion</a>
                                        <div id="panel_documento" class="col-md-6">
                                            @if($resolucion)
                                                <span class="font-weight-bold text-danger m-2">Resolución N° {{$resolucion->res_numero."/".$resolucion->res_gestion}}</span>
                                                <a href="" class="btn btn-circle btn-light btn-sm text-danger float-right border" data-toggle="modal" data-target="#Modal2"
                                                   onclick="cargarDatos('{{url('ver datos resolucion/'.$resolucion->cod_res)}}','panel_modal2')" title="Ver detalle de la resolución"> <i class="fas fa-file-pdf"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 shadow border">
                                    <span class="text-primary font-weight-bold text-primary font-italic" style="font-size: 14px">* Datos de la sanción</span>
                                    <br/><br/>
                                    <form id="form_sancionado">
                                        @csrf
                                    <table class="col-md-12">
                                        <tr>
                                            <th class="font-italic text-dark" colspan="2">Referencia :
                                                <textarea class="form-control form-control-sm" name="referencia">{{$sancionado->san_referencia}}</textarea>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="font-italic text-dark" colspan="2">Sentencia :
                                                <textarea class="form-control form-control-sm" name="sentencia">{{$sancionado->san_sentencia}}</textarea>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="font-italic text-dark" colspan="2">Resolución :
                                                <textarea class="form-control form-control-sm" name="resolucion">{{$sancionado->san_resolucion}}</textarea>
                                            </th>
                                        </tr>
                                    </table>
                                        <input type="hidden" name="cs" value="{{$sancionado->cod_san}}">
                                    </form>
                                    <br/>
                                </div>
                            </div>
                    @endif
                </div>

            </div>
        </div><!-- End Formulario Convocatoria -->
        <div class="modal-footer">

            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary btn-sm" type="button" onclick="enviar('form_sancionado','{{url('guardar sancionado noatentado')}}','panel_modal');cargarDatos('{{url('actualizar lista sancionado noatentado')}}','panel_lista_sancionado')">Guardar</button>
        </div>
    </div>
</div>
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
                    $('#cod_sis').val('');
                }else{
                    var res=JSON.parse(resp);
                    $('#apellido').val(res['per_apellido']);
                    $('#nombre').val(res['per_nombre']);
                    $('#cod_sis').val(res['per_cod_sis']);
                }
            },
            error: function () {
                $('#'+panel).html("<span class='text-danger'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");
            }
        });
    }
</script>
