<?php //$fecha=date('Y-m-d',strtotime($apostilla->apos_fecha_ingreso))?>
<div class="modal-dialog modal-lg" role="document" id="panel_tramite_apostilla">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-verde-oscuro">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Candidatos </h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body" style="font-size: smaller">
            <div class="bg-verde-oscuro centrar_bloque p-1 col-md-7 rounded shadow">
                @if($candidato)
                    <h6 class="text-white text-center">Formulario para editar datos personales del candidato</h6>
                @else
                    <h6 class="text-white text-center">Formulario para registrar candidato</h6>
                @endif
            </div>
            <hr class="sidebar-divider"/>
            <div>
                <form id="form_candidato">
                    @csrf
                    <input type="hidden" name="cd" value="{{$tramite->cod_dtra}}">
                    @if($candidato)
                        <input type="hidden" name="cn" value="{{$candidato->cod_noa}}">
                    @endif
                    <table class="table-hover col-md-12 text-dark">
                        <tr>
                            <th colspan="2" class="text-right text-primary"><br/>* DATOS PERSONALES</th>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">CI : </th>
                            <td class="border-bottom border-dark">
                                <input class="form-control form-control-sm border-0" placeholder=""
                                       name="ci" value="{{$candidato ? $candidato->per_ci : ''}}" onchange="cargarDatosPersonales(this.value)" /></td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Nombres : </th>
                            <td class="border-bottom border-dark">
                                <input class="form-control form-control-sm border-0" placeholder=""
                                       required name="nombre" id="nombre" value="{{$candidato ? $candidato->per_nombre : ''}}" /></td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Apellidos : </th>
                            <td class="border-bottom border-dark">
                                <input class="form-control form-control-sm border-0" placeholder=""
                                       required name="apellido" id="apellido" value="{{$candidato ? $candidato->per_apellido : ''}}" /></td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Código SIS: </th>
                            <td class="border-bottom border-dark">
                                <input class="form-control form-control-sm border-0" placeholder=""
                                       required name="cod_sis" id="cod_sis" value="{{$candidato ? $candidato->per_cod_sis : ''}}" /></td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Cargo: </th>
                            <td class="border-bottom border-dark">
                                <select class="form-control form-control-sm border-0" required name="cod_carg" id="cod_carg">
                                    <option value="">Seleccione un cargo...</option>
                                    @foreach($cargos as $cargo)
                                        <option value="{{$cargo->cod_carg}}" {{$candidato && $candidato->cod_carg == $cargo->cod_carg ? 'selected' : ''}}>{{$cargo->carg_nombre}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary" type="button"
                    data-save-url="{{url('guardar candidato convocatoria')}}"
                    data-refresh-url="{{url('actualizar lista tramite convocatoria/'.$tramite->cod_con)}}"
                    onclick="guardarEdicionCandidatoNoatentado(this)">Guardar</button>
        </div>
    </div>
</div>
<script>
    function guardarEdicionCandidatoNoatentado(boton){
        var rutaGuardar=boton.dataset.saveUrl;
        var rutaRefresco=boton.dataset.refreshUrl;
        enviar('form_candidato',rutaGuardar,'panel_noatentado');
        $('#Noatentado_agregar').modal('hide');
        cargarDatos(rutaRefresco,'panel_lista_tramites');
    }

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
                    $('#cod_sis').val(res['per_cod_sis']);
                }
            },
            error: function () {
                $('#'+panel).html("<span class='text-danger'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");
            }
        });
    }
</script>



