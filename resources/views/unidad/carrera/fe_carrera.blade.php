
<form id="form_carrera">
    @csrf
    <div class="modal-content border-0 shadow-lg overflow-hidden">
        <div class="modal-header text-white" style="background: linear-gradient(135deg, #184e77 0%, #1e6091 45%, #2a6f97 100%);">
            <div>
                <h5 class="modal-title font-weight-bolder mb-0" id="exampleModalLabel"><i class="fas fa-university mr-1"></i> Carrera</h5>
                <small class="text-white-50">Registro y acreditación académica</small>
            </div>
            <button class="close text-white opacity-100" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body p-0" style="background: linear-gradient(180deg, #f8fbff 0%, #eef5fb 100%);">
            <div class="p-3 p-md-4">
                <div id="form_carrera_error" class="alert alert-danger d-none mb-3">
                    Revise los campos obligatorios y corrija las fechas antes de guardar.
                </div>
                <div class="centrar_bloque col-md-8 px-0 mb-3">
                    <div class="rounded-lg shadow-sm text-white text-center py-2" style="background: linear-gradient(135deg, #2a6f97 0%, #457b9d 100%);">
                        <h5 class="mb-0 font-weight-bolder">{{$cod_car==0 ? 'Formulario para nueva Carrera' : 'Formulario para editar Carrera'}}</h5>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <div class="font-weight-bold text-dark">Facultad: <span class="text-primary">{{$facultad->fac_nombre}}</span></div>
                    <span class="badge px-3 py-2" style="background: #dbeafe; color: #1d4ed8;">{{$cod_car==0 ? 'Nuevo registro' : 'Edición activa'}}</span>
                </div>

                <div class="border rounded-lg shadow-sm bg-white p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <div class="text-primary font-weight-bold text-uppercase" style="letter-spacing: .04em; font-size: .78rem">* Datos de la carrera</div>
                        <span class="badge badge-light border text-primary px-3 py-2">Información general</span>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-8 mb-2">
                            <label class="font-italic mb-1 text-secondary">Nombre de la carrera</label>
                            <input type="text" class="form-control form-control-sm shadow-sm" required name="nombre" value="{{$cod_car==0 ? '' : $carrera->car_nombre}}" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                        </div>
                        <div class="form-group col-md-4 mb-2">
                            <label class="font-italic mb-1 text-secondary">Nombre corto</label>
                            <input type="text" class="form-control form-control-sm shadow-sm" required name="corto_c" value="{{$cod_car==0 ? '' : $carrera->car_abreviacion}}" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                        </div>
                    </div>
                </div>

                <input type="hidden" name="cf" value="{{$facultad->cod_fac}}">
                @if($cod_car!=0)
                    <input type="hidden" name="cc" value="{{$carrera->cod_car}}">
                @endif
            </div>
        </div>
        <div class="modal-footer border-0" style="background: #f8fbff;">
            <button class="btn btn-light border px-4" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary px-4 shadow-sm" type="button" onclick="enviar('form_carrera','g_carrera')" style="background: linear-gradient(135deg, #1d4e89 0%, #2c7da0 100%); border: none;">Guardar</button>
        </div>
    </div>
</form>

