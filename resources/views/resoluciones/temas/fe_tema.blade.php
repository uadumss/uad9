<form action="{{url('g_tema/')}}" method="POST">
    @csrf
    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary ">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-institution"></i> Tema de interés</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="shadow-sm rounded p-2">
                @if($cod_tem==0)
                    <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                        <h5 class="text-white text-center"> Formulario para nuevo tema de interes</h5>
                    </div>
                    <hr class="sidebar-divider"/>
                    <span class="text-primary font-weight-bold font-italic" style="font-size: 0.8em"> * DATOS DEL TEMA</span><br/><br/>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="col-md-11">
                                <tr>
                                    <th class="text-right font-italic text-dark">Título del tema :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" required name="titulo" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Descripción:</th>
                                    <td class="border-bottom border-dark">
                                        <textarea name="des" class="form-control form-control-sm"></textarea>
                                    </td>
                                </tr>


                            </table>
                        </div>
                    </div>

                @else
                    <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                        <h5 class="text-white text-center"> Formulario para editar Tema de interés</h5>
                    </div>
                    <hr class="sidebar-divider"/>
                    <span class="text-primary font-weight-bold font-italic float-right" style="font-size: 0.8em"> * DATOS TEMA</span><br/><br/>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="col-md-11">
                                <tr>
                                    <th class="text-right font-italic text-dark">Título del tema :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" required name="titulo"  value="{{$tema->tem_titulo}}"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Descripción:</th>
                                    <td class="border-bottom border-dark">
                                        <textarea name="des" class="form-control form-control-sm">{{$tema->tem_des}}</textarea>
                                    </td>
                                </tr>
                            </table>
                            <input type="hidden" name="ct" value="{{$tema->cod_tem}}">
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
            <input class="btn btn-primary" type="submit" value="Guardar"/>
        </div>
    </div>
</form>
