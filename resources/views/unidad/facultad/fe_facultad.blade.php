<form action="{{url('g_facultad/')}}" method="POST" id="form_importar" enctype="multipart/form-data">
    @csrf
    <div class="modal-content border-0 shadow-lg overflow-hidden">
        <div class="modal-header text-white" style="background: linear-gradient(135deg, #184e77 0%, #1e6091 45%, #168aad 100%);">
            <div>
                <h5 class="modal-title font-weight-bolder mb-0" id="exampleModalLabel"><i class="fas fa-university mr-1"></i> Facultad</h5>
                <small class="text-white-50">Registro y edición de datos académicos</small>
            </div>
            <button class="close text-white opacity-100" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body p-0" style="background: linear-gradient(180deg, #f8fbff 0%, #eef5fb 100%);">

            <div class="p-3 p-md-4">
                @if($cod_fac==0)
                    <div class="centrar_bloque col-md-7 px-0 mb-3">
                        <div class="rounded-lg shadow-sm text-white text-center py-2" style="background: linear-gradient(135deg, #2a6f97 0%, #457b9d 100%);">
                            <h5 class="mb-0 font-weight-bolder">Formulario para nueva facultad</h5>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <div class="text-primary font-weight-bold text-uppercase" style="letter-spacing: .04em; font-size: .78rem;">* Datos de la facultad</div>
                        <span class="badge px-3 py-2" style="background: #dbeafe; color: #1d4ed8;">Registro nuevo</span>
                    </div>
                    <div class="form-group row align-items-center mb-3">
                        <label class="col-md-4 col-form-label text-right font-italic text-secondary mb-0">Nombre de la Facultad:</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm shadow-sm" required name="nombre" placeholder="Ingrese el nombre de la facultad" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                        </div>
                    </div>
                    <div class="form-group row align-items-center mb-0">
                        <label class="col-md-4 col-form-label text-right font-italic text-secondary mb-0">Nombre corto:</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm shadow-sm" name="corto" placeholder="Sigla o abreviación" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                        </div>
                    </div>
                @else
                    <div class="centrar_bloque col-md-7 px-0 mb-3">
                        <div class="rounded-lg shadow-sm text-white text-center py-2" style="background: linear-gradient(135deg, #1d4e89 0%, #2c7da0 100%);">
                            <h5 class="mb-0 font-weight-bolder">Formulario para editar facultad</h5>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <div class="text-primary font-weight-bold text-uppercase" style="letter-spacing: .04em; font-size: .78rem;">* Datos de la facultad</div>
                        <span class="badge px-3 py-2" style="background: #e0f2fe; color: #0369a1;">Edición</span>
                    </div>

                    <div class="form-group row align-items-center mb-3">
                        <label class="col-md-4 col-form-label text-right font-italic text-secondary mb-0">Nombre de la Facultad:</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm shadow-sm" required name="nombre" value="{{$facultad->fac_nombre}}" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                        </div>
                    </div>
                    <div class="form-group row align-items-center mb-0">
                        <label class="col-md-4 col-form-label text-right font-italic text-secondary mb-0">Nombre corto:</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm shadow-sm" name="corto" value="{{$facultad->fac_abreviacion}}" style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;" />
                        </div>
                    </div>
                    <input type="hidden" name="cf" value="{{$facultad->cod_fac}}">
                @endif
            </div>
        </div>
        <div class="modal-footer border-0" style="background: #f8fbff;">
            <button class="btn btn-light border px-4" type="button" data-dismiss="modal">Cerrar</button>
            <input class="btn btn-primary px-4 shadow-sm" type="submit" value="Guardar" style="background: linear-gradient(135deg, #1d4e89 0%, #2c7da0 100%); border: none;"/>
        </div>
    </div>
</form>
