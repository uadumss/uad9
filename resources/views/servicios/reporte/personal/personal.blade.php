<div class="modal-dialog modal-xl" role="document" id="panel_reporte">
    <div class="modal-content border-bottom-primary shadow-lg">
            <input  type="hidden" name="ct" id="ct" value=""/>
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel"> <i class="fas fa-chart-line"></i>&nbsp;&nbsp;Reporte</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form_reporte">
            @csrf
            <div class="modal-body" style="font-size: 13px">
                <div class="bg-primary centrar_bloque p-1 col-md-8 rounded shadow">
                    <h5 class="text-white text-center">Reporte mediante datos personales</h5>
                </div>
                <hr class="sidebar-divider"/>
                <div class="row">
                    <div class="col-md-4">
                        <table class="col-md-12">
                            <tr>
                                <th class="text-right font-italic">CI : </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder=""
                                           name="ci" onchange="cargarDatosPersonales(this.value)" id="ci"/></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Apellidos : </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder="" name="apellido" id="apellido"/></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Nombres : </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder="" name="nombre" id="nombre"/></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Periodo inicial : </th>
                                <td class="border-bottom border-dark">
                                    <input type="date" name="fecha_inicial"  class="form-control form-control-sm border-0">
                                </td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Periodo final : </th>
                                <td class="border-bottom border-dark">
                                    <input type="date" name="fecha_final" class="form-control form-control-sm border-0">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-8">
                        <div id="panel_resultado" style="height: 500px;" class="overflow-auto">

                        </div>
                    </div>
                </div>

            </div>
            </form>
            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
                <button class="btn btn-primary btn-sm" onclick="enviar('form_reporte','{{url('reporte servicios personal')}}','panel_resultado')" > Generar</button>
                <!--<button class="btn btn-primary btn-sm" type="submit" value="Aceptar">Generar PDF</button> -->
            </div>
    </div>
</div>
