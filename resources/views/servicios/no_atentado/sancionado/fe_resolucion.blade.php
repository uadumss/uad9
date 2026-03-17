<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-verde-oscuro">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-file-alt"></i> SANCIONADOS</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>

        <!-- Formulario sancionados -->
        <div class="card shadow">
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <div class="card-body" style="font-size: 14px;">
                        <div class="bg-verde-oscuro centrar_bloque p-1 col-md-5 rounded shadow">
                            <h5 class="text-white text-center">Datos de la resolución </h5>
                        </div>
                        <hr class="sidebar-divider text-bg-dark">
                        <div class="row">
                            <div class="col-md-4">
                                <form id="form_busqueda">
                                    @csrf
                                    <table>
                                        <tr>
                                            <th class="text-right font-italic">N° Resolución : </th>
                                            <td class="border-bottom border-dark">
                                                <input type="text" name="numero" class="form-control-sm form-control border-0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Año :</th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0" name="gestion">
                                                    <?php $año=date('Y');?>
                                                    @for($i=$año;$i>1927;$i--)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Tipo :</th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0"  name="tipo">
                                                    <option value="rcu">RCU</option>
                                                    <option value="rr">RR</option>
                                                    <option value="rvr">RVR</option>
                                                    <option value="rs">RS</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                    <input type="hidden" name="cs" value="{{$sancionado->cod_san}}">
                                </form>
                                <br/>
                                <div class="input-group col-md-12 justify-content-center">
                                    <button class="btn btn-sm btn-primary" type="button" onclick="enviar('form_busqueda','{{url('buscar resolucion sancion')}}','panel_resolucion')">Buscar</button>
                                </div>
                            </div>
                            <div class="col-md-8 shadow border">
                                <div id="panel_resolucion">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End Formulario Convocatoria -->
            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
