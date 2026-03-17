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
                <h6 class="text-white text-center">Formulario para editar candidato</h6>
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
                                <input class="form-control form-control-sm border-0" placeholder=""
                                       required name="cod_sis" id="cod_sis" /></td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Cargo: </th>
                            <td class="border-bottom border-dark">
                                <input class="form-control form-control-sm border-0" placeholder=""
                                       required name="cargo" id="cargo" />
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic"></th>
                            <td class="border-bottom border-dark">
                                <span class="text-primary font-italic font-weight-bold"> * Cargo de convocatoria</span>
                                <select class="custom-select custom-select-sm" name="cargo_convocatoria">
                                    <option></option>
                                    @foreach($cargos as $c)
                                        <option value="{{$c->cod_carg}}">{{$c->carg_nombre}}</option>
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
                    onclick="enviar('form_candidato','{{url('guardar candidato convocatoria')}}','panel_noatentado');$('#Noatentado_agregar').modal('hide');cargarDatos('{{url('actualizar lista tramite convocatoria/'.$tramite->cod_con)}}','panel_lista_tramites')">Guardar</button>
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



