<form action="{{url('g_autoridad')}}" method="POST">
    @csrf
    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-user"></i> Editar autoridad</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                <h6 class="text-white text-center">Formulario edición de autoridad</h6>
            </div>
            <br/>
            <table class="table table-hover table-sm">
                <tr>
                    <th class="text-right font-italic">Nombre :</th>
                    <td class="border-bottom border-dark">
                        <input type="text" class="form-control form-control-sm border-0" name="nombre" value="{{$autoridad['aut_nombre']}}" />
                    </td>
                </tr>
                <tr>
                    <th class="text-right font-italic">Cargo :</th>
                    <td class="border-bottom border-dark">
                        <input type="text" class="form-control form-control-sm border-0" name="cargo" value="{{$autoridad['aut_cargo']}}"/>
                    </td>
                </tr>
                <tr>
                    <th class="text-right font-italic">Año de inicio :</th>
                    <td class="border-bottom border-dark">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm border-0" required pattern="[0-9]{1,4}" name="inicio" value="{{$autoridad['aut_inicio']}}" />
                            <span style="font-size: 0.8em" class="text-primary font-weight-bold font-italic pt-2">Ejm: 2000</span>
                        </div>

                    </td>
                </tr>
                <tr>
                    <th class="text-right font-italic">Año de conclusión :</th>
                    <td class="border-bottom border-dark">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm border-0" pattern="[0-9]{1,4}" required name="fin" value="{{$autoridad['aut_fin']}}"/>
                            <span style="font-size: 0.8em" class="text-primary font-weight-bold font-italic pt-2">Ejm: 2004</span>
                        </div>

                    </td>
                </tr>
            </table>
            <input type="hidden" name="ca" value="{{$autoridad['cod_aut']}}">
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <input class="btn btn-primary" type="submit" value="Aceptar"/>
        </div>
    </div>
</form>
