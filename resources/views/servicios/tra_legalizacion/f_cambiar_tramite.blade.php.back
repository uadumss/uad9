<div class="modal-dialog modal-xl" role="document" id="panel_docleg">
    <div class="modal-content border-bottom-danger shadow-lg">
        <form action="{{url('e_tipo tramite')}}" method="post" id="form_eli_docleg">
        <div class="modal-header bg-danger">
            <h5 class="modal-title text-white" id="exampleModalLabel"> <i class="fas fa-question-circle text-danger"></i>&nbsp;&nbsp;Cambiar tipo de trámite</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body" style="font-size: 15px">
                @csrf
                <span class="font-italic">Esta seguro de modificar el tipo de trámite de
                    <span class="font-weight-bold text-dark">{{App\Models\Funciones::tipo_tramite($tramita->tra_tipo_tramite)}}
                    </span> a otro <i class="fas fa-question text-danger"></i>
                </span><br/><br/>
                <div class="row">

                            @if($docleg)
                                <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-6 p-2" >
                                    <p> No puede modificar este trámite debido a que tiene documentos asociados</p>
                                </div>
                            @else
                            <div class="col-md-5">
                                <table class="col-md-11">
                                    <tr class="text-right">
                                        <th colspan="2"><span class="text-primary font-weight-bold text-right font-italic">Datos del trámite</span><br/><br/></th>
                                    </tr>
                                    <tr class="border-bottom border-dark">
                                        <th class="text-dark font-italic text-right">Tipo de trámite : </th>
                                        <td>
                                            <span class="font-weight-bold rounded pl-2 pr-2 bg-danger text-white font-italic">
                                            {{App\Models\Funciones::tipo_tramite($tramita->tra_tipo_tramite)}}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom border-dark">
                                        <th class="text-dark font-italic text-right">Número de tramite : </th>
                                        <td>{{$tramita->tra_numero}}</td>
                                    </tr>
                                    <tr class="border-bottom border-dark">
                                        <th class="text-dark font-italic text-right">Pertenece a : </th>
                                        @if($persona)
                                            <td>{{$persona->per_apellido." ".$persona->per_nombre}}</td>
                                        @endif
                                    </tr>

                                </table>
                                <input type="hidden" name="ctra" value="{{$cod_tra}}">
                                <br/>
                                <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-6" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
                            </div>
                                <div class="col-md-5 shadow-lg">
                                    <table class="col-md-10 pl-lg-5">
                                        <tr class="text-right">
                                            <th colspan="2"><span class="text-primary font-weight-bold text-right font-italic">Formulario para el cambio de trámite</span><br/><br/></th>
                                        </tr>
                                        <tr class="border-bottom border-0">
                                            <th class="font-weight-bold font-italic text-dark text-right">Tipo de trámite : </th>
                                            <td>
                                                    <div id="tramite">
                                                        <select class="custom-select custom-select-sm border border-info" name="tramite">
                                                            <option value="{{$tramita->tra_tipo_tramite}}">{{mb_strtoupper(App\Models\Funciones::tipo_tramite($tramita->tra_tipo_tramite))}}</option>
                                                            <option value="L">LEGALIZACIÓN</option>
                                                            <option value="C">CERTIFICACIÓN</option>
                                                            <option value="B">BÚSQUEDA</option>
                                                            <option value="F">CONFROTACIÓN</option>
                                                        </select>
                                                    </div>
                                            </td>
                                        </tr>
                                        <tr class="border-bottom border-0">
                                            <th class="text-right font-italic text-dark">Borrar datos personales :</th>
                                            <td class="text-dark font-italic text-left">
                                               <input type="checkbox" name="borrar_datos">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            @endif

                    </div>
                <br/>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <input type="submit" class="btn btn-danger" value="Cambiar"/>
        </div>
        </form>
    </div>
</div>

