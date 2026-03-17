<div class="modal-dialog modal-xl" role="document" id="panel_reporte">
    <div class="modal-content border-bottom-primary shadow-lg">
            <input  type="hidden" name="ct" id="ct" value=""/>
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel"> <i class="fas fa-chart-line"></i>&nbsp;&nbsp;Reporte general</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            @csrf
            <div class="modal-body" style="font-size: 13px">
                <div class="bg-primary centrar_bloque p-1 col-md-8 rounded shadow">
                    <h5 class="text-white text-center">Reporte general</h5>
                </div>
                <hr class="sidebar-divider"/>
                <div class="row">
                    <div class="col-md-4 border rounded shadow-lg">
                        <br/>
                        <span class="text-primary font-weight-bold col-md-12 centrar_bloque"> * DATOS PARA EL REPORTE</span>
                        <br/><br/>
                            <form id="form_general">
                                @csrf
                                <table>
                                    <tr>
                                        <th class="text-right font-italic">Por trámite : </th>
                                        <td class="border-bottom border-dark">
                                            <select class="custom-select custom-select-sm" name="tramite" id="tramite" onchange="$('#tipo').val('')">
                                                <option></option>
                                                @foreach($tramites as $t)
                                                    <option value="{{$t->cod_tre}}">{{$t->tre_nombre}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Por tipo : </th>
                                        <td class="border-bottom border-dark">
                                            <select class="custom-select-sm custom-select" name="tipo" id="tipo" onchange="$('#tramite').val('')">
                                                <option></option>
                                                <option value="L">Legalización</option>
                                                <option value="C">Certificación</option>
                                                <option value="F">Confrontación</option>
                                                <option value="B">Búsqueda</option>
                                            </select>
                                        </td>
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
                                            <input type="date" name="fecha_final"  class="form-control form-control-sm border-0">
                                        </td>
                                    </tr>
                                </table>
                            </form>
                            <br/>
                            <button class="btn btn-primary btn-sm float-right" onclick="enviar('form_general','{{url('reporte servicios general')}}','panel_resultado')" > Generar</button>
                    </div>
                    <div class="col-md-8">
                        <div id="panel_resultado">

                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            </div>
    </div>
</div>
