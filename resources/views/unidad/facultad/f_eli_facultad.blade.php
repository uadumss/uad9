<div class="modal-content border-0 shadow-lg overflow-hidden">
    <form action="{{url('eli_facultad')}}" method="post">
        @csrf
        <div class="modal-header text-white" style="background: linear-gradient(135deg, #8a1538 0%, #c1121f 48%, #e5383b 100%);">
            <div>
                <h5 class="modal-title font-weight-bolder mb-0" id="exampleModalLabel">
                    <img src="{{url('img/icon/eliminar.png')}}" class="mr-1"> Eliminar facultad
                </h5>
                <small class="text-white-50">Acción irreversible sobre el registro</small>
            </div>
            <button class="close text-white opacity-100" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body p-0" style="background: linear-gradient(180deg, #fff7f7 0%, #fff1f2 100%);">
            <div class="p-3 p-md-4">
                @if($eliminar==1)
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <div class="text-danger font-weight-bold text-uppercase" style="letter-spacing: .04em; font-size: .78rem;">Confirmación de eliminación</div>
                        <span class="badge px-3 py-2" style="background: #fee2e2; color: #b91c1c;">Requiere confirmación</span>
                    </div>

                    <div class="rounded-lg shadow-sm border border-danger bg-white p-3 p-md-4 mb-3" id="panel_e_titulo">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div class="text-danger font-weight-bolder">¿Está seguro de eliminar esta facultad?</div>
                                <small class="text-muted">Revise los datos antes de continuar.</small>
                            </div>
                            <div class="text-danger font-weight-bolder" style="font-size: 2.4rem; line-height: 1;">?</div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <tr>
                                    <th class="text-right text-secondary border-0" style="width: 35%;">Nombre</th>
                                    <td class="border-bottom border-danger text-dark font-weight-bold">{{$facultad->fac_nombre}}</td>
                                </tr>
                                <tr>
                                    <th class="text-right text-secondary border-0">Nombre corto</th>
                                    <td class="border-bottom border-danger text-dark font-weight-bold">{{$facultad->fac_abreviacion}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="alert alert-danger border-0 shadow-sm mb-0" style="border-left: 4px solid #dc3545;">
                        <strong>Importante:</strong> esta acción quedará registrada en el sistema.
                    </div>
                @else
                    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-start mb-0" style="border-left: 4px solid #dc3545;">
                        <div class="font-weight-bolder mr-3" style="font-size: 1.7rem; line-height: 1;">!</div>
                        <div>
                            <div class="font-weight-bolder">No se puede eliminar la facultad</div>
                            <div>
                                La facultad <span class="font-weight-bold">{{$facultad->fac_nombre}}</span> tiene carreras asignadas.
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="modal-footer border-0" style="background: #fff7f7;">
            <button class="btn btn-light border px-4" type="button" data-dismiss="modal">Cancelar</button>
            @if($eliminar==1)
                <input class="btn btn-danger px-4 shadow-sm" type="submit" value="Aceptar" style="background: linear-gradient(135deg, #c1121f 0%, #e5383b 100%); border: none;" />
            @endif
        </div>
        <input type="hidden" name="cf" value="{{$facultad->cod_fac}}">
    </form>
</div>
