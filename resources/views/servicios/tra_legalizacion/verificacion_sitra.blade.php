<div class="modal-dialog modal-lg" role="document" id="panel_docleg">

        @if($docleg->dtra_verificacion_sitra==0)
        <div class="modal-content border-bottom-primary shadow-lg">
            <div class="modal-header bg-verde-oscuro">
                <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="{{url('img/icon/eliminar.png')}}">&nbsp;&nbsp;Verificación en el sitra</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        @else
                <div class="modal-content border-bottom-danger shadow-lg">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="{{url('img/icon/eliminar.png')}}">&nbsp;&nbsp;Verificación en el sitra</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
        @endif
        <div class="modal-body">
                <span class="font-italic">Verificación de trámite: </span><br/><br/>

            <span class="text-dark font-weight-bold" >Datos:</span><br/>
            <span class="text-dark font-italic" style="font-size: 0.8em">
                <span class="font-weight-bold">Nombre :</span> <span>{{$persona->per_apellido." ".$persona->per_nombre}}</span> |
                <span class="font-weight-bold">Nro. Título :</span> <span>{{$docleg->dtra_numero}}</span> |
                <span class="font-weight-bold">Tipo Documento :</span> <span>{{\App\Models\Funciones::nombre_titulo($docleg->dtra_buscar_en)}}</span>
            </span>
            <br/>
            <br/>
                <div class="row">
                    @if($docleg->dtra_verificacion_sitra==0)
                    <div class="font-weight-bold alert-success shadow text-center centrar_bloque col-md-9 p-2" >
                        <div>
                           <table class="col-md-12">
                                    <tr>
                                        <th class="text-right ">Nombre:</th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3">{{$respuesta->nombre}}</th>
                                    </tr>
                                    <tr>
                                        <th class="text-right ">Título:</th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3">{{$respuesta->titulo}}</th>
                                    </tr>
                                    <tr>
                                        <th class="text-right ">Número:</th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3">{{$respuesta->numero}}</th>
                                    </tr>

                                    <tr>
                                        <th class="text-right ">Gestión:</th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3">{{$respuesta->gestion}}</th>
                                    </tr>

                                    <tr>
                                        <th class="text-right ">Tipo documento:</th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3">{{$respuesta->tipo}}</th>
                                    </tr>
                                </table>
                        </div>

                    </div>
                    <div class="pt-2 col-md-2 text-success font-weight-bolder text-left"><h1><i class="fas fa-check-circle"></i></h1></div>
                    @else
                        <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-2" >
                            <div>
                               <p>No se encuentra el documento registrado en el Sistema SITRA</p>
                            </div>
                        </div>
                        <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h1><i class="fas fa-minus-circle"></i></h1></div>
                    @endif
                </div>
                <br/>
            @if($docleg->dtra_verificacion_sitra==0)
                <div class="text-success font-italic font-weight-bold border border-success rounded col-md-3" style="font-size: 1.2em">Verificacion Correcta</div>
            @else
                <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-3" style="font-size: 1.2em">INCORRECTO</div>
            @endif
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>

        </div>
    </div>
</div>
