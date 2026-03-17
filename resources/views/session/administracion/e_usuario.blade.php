@if(count($errors)>0)
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <ul>
            @foreach($errors->all() as $e)
                <li>{{$e}} - </li>
            @endforeach
        </ul>
    </div>
@endif
<div>
    <div class="col-md-6 alert-info p-2 centrar_bloque rounded shadow">
        <h5 class="text-dark text-center">Formulario de edición de datos personales</h5>
    </div>
    <br/>
    <form method="POST" action="{{ url('g_e_persona') }}" enctype="multipart/form-data">
        @csrf
        <table valign="top" class="table table-sm col-md-8 centrar_bloque">

            <tr>
                <td>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nombres : ') }}</label>
                        <div class="col-md-8">
                            <input id="name" type="text" class="form-control-sm form-control @error('name') is-invalid @enderror" name="name" value="{{ $usu['per_nom'] }}" required autocomplete="name" autofocus>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                            @enderror
                        </div>
                    </div>
                </td>
                <td>
                    <div class="form-group row">
                        <label for="apellido" class="col-md-4 col-form-label text-md-right">{{ __('Apellidos : ') }}</label>

                        <div class="col-md-8">
                            <input id="apellido" type="text" class="form-control-sm form-control @error('apellido') is-invalid @enderror" name="apellido" value="{{ $usu['per_ape'] }}" required autocomplete="apellido" autofocus>

                            @error('apellido')
                            <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                            @enderror
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('CI : ') }}</label>
                        <div class="col-md-8">
                            <input id="ci" type="text" class="form-control-sm form-control @error('ci') is-invalid @enderror" name="ci" value="{{ $usu['per_ci']  }}" required autocomplete="ci" autofocus>

                            @error('ci')
                            <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                            @enderror
                        </div>
                    </div>
                </td>
                <td>Sexo:
                    <div class="form-group row">
                        <label for="sexo" class="col-md-4 col-form-label text-md-right">{{ __('Masculino ') }}</label>

                        <div class="col-md-6">
                            <input id="sexo" type="radio" class="form-control form-control-sm" name="sexo" value="M" <?php if($usu['per_sexo']=='M'){echo "checked";}?>>
                        </div>
                        <label for="sexo" class="col-md-4 col-form-label text-md-right">{{ __('Femenino ') }}</label>
                        <div class="col-md-6">
                            <input id="sexo" type="radio" class="form-control form-control-sm" name="sexo" value="F" <?php if($usu['per_sexo']=='F'){echo "checked";}?>>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group row">
                        <label for="cel" class="col-md-4 col-form-label text-md-right">{{ __('Celular: ') }}</label>
                        <div class="col-md-8">
                            <input id="cel" type="text" class="form-control-sm form-control @error('cel') is-invalid @enderror" name="cel" value="{{ $usu['per_telf'] }}"  autocomplete="cel" autofocus>

                            @error('cel')
                            <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                            @enderror
                        </div>
                    </div>
                </td>
                <td>
                    <div class="form-group row">
                        <label for="celr" class="col-md-4 col-form-label text-md-right">{{ __('Nº Referencia: ') }}</label>
                        <div class="col-md-8">
                            <input id="celr" type="text" class="form-control-sm form-control @error('celr') is-invalid @enderror" name="celr" value="{{ $usu['per_telf_ref'] }}" autocomplete="celr" autofocus>
                            @error('celr')
                            <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                            @enderror
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="form-group row">
                        <label for="dir" class="col-md-2 col-form-label text-md-right">{{ __('Dirección: ') }}</label>
                        <div class="col-md-10">
                            <input id="dir" type="text" class="form-control-sm form-control" size="100" name="dir" value="{{ $usu['per_dir'] }}" autocomplete="dir" autofocus>
                            @error('dir')
                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                            @enderror
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div id="prevista" class="centrar_bloque col-md-6">

                        <img id="imgSalida" width="150" height="150" src="{{url('img/foto/'.$usu['per_fot'])}}" />

                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <div class="custom-file">
                            <input accept="image/.jpg,.png,.jpeg" type="file" class="custom-file-input" id="foto" name="foto">
                            <label class="custom-file-label" for="foto">Seleccionar foto</label>
                        </div>
                    </div>
                    <br/>
                    <br/>
                    <div class="form-group row mb-0">
                        <div class="col-md-12 text-sm-center">
                            <button type="submit" class="btn btn-info">
                                {{ __('Guardar') }}
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="ip" value="{{$usu['id_per']}}">
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
    document.getElementById("foto").onchange = function(e) {
        // Creamos el objeto de la clase FileReader
        let reader = new FileReader();

        // Leemos el archivo subido y se lo pasamos a nuestro fileReader
        reader.readAsDataURL(e.target.files[0]);

        // Le decimos que cuando este listo ejecute el código interno
        reader.onload = function(){
            let preview = document.getElementById('prevista'),
                image = document.createElement('img');

            image.src = reader.result;
            image.setAttribute('width', '150px');
            image.setAttribute('height', '150px');
            preview.innerHTML = '';
            preview.append(image);
        };
    }
</script>
