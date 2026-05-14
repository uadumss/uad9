<form action="{{url('g_facultad/')}}" method="POST" id="form_importar" enctype="multipart/form-data">
    @csrf
    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-university"></i> Facultad</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">

            <div class="shadow-sm rounded p-2">
                @if($cod_fac==0)
                    <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                        <h5 class="text-white text-center"> Formulario para nueva facultad</h5>
                    </div>
                    <hr class="sidebar-divider"/>
                    <span class="text-primary float-right font-weight-bold font-italic" style="font-size: 0.8em"> * DATOS DE LA FACULTAD</span><br/><br/>
                    <table class="col-md-12">
                        <tr>
                            <th class="text-right font-italic">Nombre de la Facultad:</th>
                            <td class="border-bottom border-dark">
                                <input type="text" class="form-control form-control-sm border-0" required name="nombre" />
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Nombre corto:</th>
                            <td class="border-bottom border-dark">
                                <input type="text" class="form-control form-control-sm border-0" name="corto" />
                            </td>
                        </tr>
                    </table>
                @else
                    <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                        <h5 class="text-white text-center"> Formulario para editar facultad</h5>
                    </div>
                    <hr class="sidebar-divider"/>
                    <span class="text-primary font-weight-bold font-italic float-right" style="font-size: 0.8em"> * DATOS DE LA FACULTAD</span><br/><br/>

                    <table class="col-md-12">
                        <tr>
                            <th class="text-right font-italic ">Nombre de la Facultad:</th>
                            <td class="border-bottom border-dark ">
                                <input type="text" class="form-control form-control-sm border-0" required name="nombre" value="{{$facultad->fac_nombre}}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Nombre corto:</th>
                            <td class="border-bottom border-dark">
                                <input type="text" class="form-control form-control-sm border-0" name="corto" value="{{$facultad->fac_abreviacion}}"/>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="cf" value="{{$facultad->cod_fac}}">
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
            <input class="btn btn-primary" type="submit" value="Guardar"/>
        </div>
    </div>
</form>
