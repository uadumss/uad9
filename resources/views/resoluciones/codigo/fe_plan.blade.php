
    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-edit"></i> Editar plan de archivado</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <form action="{{url('g_plan')}}" method="POST">
            @csrf
            <div class="modal-body">

                <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                    <h6 class="text-white text-center">Formulario de edición de plan</h6>
                </div>
                <br/>
                <table class="table-hover col-md-12" >
                    <tr>
                        <th class="text-right font-italic" width="200">Numero de código:</th>
                        <td class="border-bottom border-dark">
                            <input type="text" class="form-control form-control-sm border-0" name="numero"  value="{{$plan['plan_numero']}}"/>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic"> Título :</th>
                        <td class="border-bottom border-dark">
                            <input type="text" class="form-control form-control-sm border-0" name="titulo" value="{{$plan['plan_titulo']}}"/>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="cp" value="{{$plan['cod_plan']}}">

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <input type="submit" value="Aceptar" class="btn btn-primary" />
            </div>
        </form>
    </div>

