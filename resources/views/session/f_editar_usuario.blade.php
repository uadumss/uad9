@if(Session::has('exito'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <span class="font-weight-bold">{!! session('exito') !!}</span>
    </div>
@endif
@if(Session::has('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <span class="font-weight-bold text-dark">{!! session('error') !!}</span>
    </div>
@endif

<div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
    <h5 class="text-white text-center">Editar datos del usuario</h5>
</div>

<hr class="sidebar-divider"/>
<form action="{{url('g_usuario')}}" method="POST" enctype="multipart/form-data">
    @csrf
<div class="row">
    <div class="col-md-7">

        <table class="table-hover col-md-11">
            <tr>
                <th class="text-right font-italic">Apellidos y Nombres :</th>
                <td class="border-bottom border-dark">
                    <input type="text" class="form-control form-control-sm border-0 col-md-12" name="nombre" required value="{{$usuario->name}}"/>
                </td>
            </tr>
            <tr>
                <th class="text-right font-italic">Cédula de Identidad :</th>
                <td class="border-bottom border-dark">
                    <input type="text" class="form-control form-control-sm border-0 col-md-12" name="ci" required value="{{$usuario->ci}}"/>
                </td>
            </tr>
            <tr>
                <th class="text-right font-italic">Sexo : </th>
                <td class="border-bottom border-dark">
                    <select class="custom-select custom-select-sm border-0" name="sexo">
                        @if($usuario->sexo=='M')
                            <option value="M">MASCULINO</option>
                            <option value="F">FEMENINO</option>
                        @else
                            <option value="F">FEMENINO</option>
                            <option value="M">MASCULINO</option>
                        @endif
                    </select>
                </td>
            </tr>
            <tr>
                <th class="text-right font-italic">Login :</th>
                <td class="border-bottom border-dark">
                    <input type="text" class="form-control form-control-sm border-0 col-md-12" name="login-i" disabled value="{{$usuario->email}}"/>
                </td>
            </tr>
            <tr>
                <th class="text-right font-italic">Cargo : </th>
                <td class="border-bottom border-dark">
                    <select class="custom-select custom-select-sm" name="cargo">
                        <option value="{{$usuario->cargo}}">{{$usuario->cargo}}</option>
                        <option value="JEFA">JEFA</option>
                        <option value="ASISTENTE DE JEFATURA">ASISTENTE DE JEFATURA</option>
                        <option value="RESPONSABLE INFORMATICO UAD">RESPONSABLE INFORMATICO UAD</option>
                        <option value="RESPONSABLE DE LEGALIZACION">RESPONSABLE DE LEGALIZACION</option>
                        <option value="RESPONSABLE DE APOSTILLA">RESPONSABLE DE APOSTILLA</option>
                        <option value="RESPONSABLE DE TRAMITES">RESPONSABLE DE TRAMITES</option>
                        <option value="RESPONSABLE DE ATENCION AL CLIENTE">RESPONSABLE DE ATENCION AL CLIENTE</option>
                        <option value="ASISTENTE INFORMATICO UAD">ASISTENTE INFORMATICO UAD</option>
                        <option value="ENCARGADO DE SISTEMATIZACION">ENCARGADO DE SISTEMATIZACION</option>
                        <option value="ENCARGADO DE DIGITALIZACION">ENCARGADO DE DIGITALIZACION</option>
                        <option value="ENCARGADO DE ARCHIVO HISTORICO">ENCARGADO DE ARCHIVO HISTORICO</option>
                        <option value="ENCARGADO DE ARCHIVO ACADEMICO">ENCARGADO DE ARCHIVO ACADEMICO</option>
                        <option value="BECARIO IDH">BECARIO IDH</option>
                        <option value="AUXILIAR">AUXILIAR</option>
                        <option value="ADSCRITO">ADSCRITO</option>
                        <option value="PRACTICANTE">PRACTICANTE</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th class="text-right font-italic">Rol : </th>
                <td class="border-bottom border-dark">
                    <select class="custom-select custom-select-sm" name="rol">
                        <option value="{{$usuario->rol}}">{{$usuario->rol}}</option>
                        <option value="FUNCIONARIO">FUNCIONARIO</option>
                        <option value="ADMINISTRADOR">ADMINISTRADOR</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th class="text-right font-italic">Contacto : </th>
                <td class="border-bottom border-dark"><textarea class="form-control" rows="2" required name="contacto" id="contacto">{{$usuario->contacto}}</textarea>
                </td>
            </tr>
            <tr>
                <th class="text-right font-italic">Dirección : </th>
                <td class="border-bottom border-dark"><textarea class="form-control" rows="3" required name="direccion" id="direccion">{{$usuario->direccion}}</textarea>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-5">
        <table class="table-hover col-md-11">
            <tr>
                <th class="text-right font-italic text-danger">PASSWORD :</th>
                <td class="border-bottom border-dark">
                    <input type="password" class="form-control form-control-sm border-0 col-md-12" name="pas"/>
                </td>
            </tr>
            <tr>
                <th class="text-right font-italic text-danger">RE-PASSWORD :</th>
                <td class="border-bottom border-dark">
                    <input type="password" class="form-control form-control-sm border-0 col-md-12" name="repas"/>
                </td>
            </tr>
        </table>
        <br/>
        <br/>
        <div class="col-md-8">
            <span class="text-dark font-weight-bold"> Foto:</span>
            <div class="input-group">
                <div class="custom-file">
                    <input accept="image/,.png,.jpeg,.jpg" type="file" class="custom-file-input" id="foto1" name="foto1">
                    <label class="custom-file-label" for="foto">Seleccionar foto</label>
                </div>
            </div>
            <br/>
            <br/>
            <div id="prevista1" class="centrar_bloque col-md-6">
                @if($usuario->foto!='')
                    <img id="imgSalida" class="imgRedonda" width="150" height="150" src="{{url('img/foto/'.$usuario->foto)}}" />
                @else
                    <img id="imgSalida" class="imgRedonda" width="150" height="150" src="{{url('img/icon/fotoM.png')}}" />
                @endif
            </div>
        </div>
    </div>

</div>
<input type="hidden" name="login" value="{{$usuario->email}}">
<input type="hidden" name="id" value="{{$usuario->id}}">
    <br/>
    <input type="submit" name="Guardar" value="Guardar" class="btn btn-primary btn-sm float-right">
</form>
<script>
    document.getElementById("foto1").onchange = function(e) {
        // Creamos el objeto de la clase FileReader
        let reader = new FileReader();

        // Leemos el archivo subido y se lo pasamos a nuestro fileReader
        reader.readAsDataURL(e.target.files[0]);

        // Le decimos que cuando este listo ejecute el código interno
        reader.onload = function(){
            let preview = document.getElementById('prevista1'),
                image = document.createElement('img');

            image.src = reader.result;
            image.setAttribute('width', '150px');
            image.setAttribute('height', '150px');
            preview.innerHTML = '';
            preview.append(image);
        };
    }
</script>
