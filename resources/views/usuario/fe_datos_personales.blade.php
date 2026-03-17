@extends('marco/pagina')
@section('contenido')
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
    <div class="card shadow rounded">
        <div class="card-header py-3 alert-primary">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class=""><i class="fas fa-file"></i>&nbsp;&nbsp;FORMULARIO DE DATOS PERSONALES</h5>
            </div>
        </div>
        <div class="card-body">

            <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                <h5 class="text-white text-center">Editar datos del usuario</h5>
            </div>

            <hr class="sidebar-divider"/>
            <form action="{{url('g_cuenta_usuario')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="card shadow" id="d_personales">
                            <div class="card-header bg-secondary">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h5 class="text-white">Datos personales</h5>

                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table-hover col-md-11 table table-sm">
                                    <tr>
                                        <th class="text-right font-italic">Apellidos y Nombres :</th>
                                        <td class="border-bottom border-dark">{{$usuario->name}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Cédula de Identidad :</th>
                                        <td class="border-bottom border-dark">{{$usuario->ci}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Sexo : </th>
                                        <td class="border-bottom border-dark">
                                            @if($usuario->sexo=='M')
                                                MASCULINO
                                            @else
                                                FEMENINO
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Login :</th>
                                        <td class="border-bottom border-dark">{{$usuario->email}} </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Cargo : </th>
                                        <td class="border-bottom border-dark">{{$usuario->cargo}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Rol : </th>
                                        <td class="border-bottom border-dark">{{$usuario->rol}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Contacto : </th>
                                        <td class="border-bottom border-dark">{{$usuario->contacto}} </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Dirección : </th>
                                        <td class="border-bottom border-dark">{{$usuario->direccion}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <div class="card shadow" id="d_personales">
                            <div class="card-header bg-primary">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h5 class="text-white">Formulario de edición de datos</h5>

                                </div>
                            </div>
                            <div class="card-body">
                                <table class="col-md-8">
                                    <tr>
                                        <th class="font-italic text-danger text-left border-bottom">PASSWORD :</th>
                                        <td class="border-bottom border-dark">
                                            <input type="password" class="form-control form-control-sm border-0 col-md-12" name="pas"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="font-italic text-danger text-left border-bottom">RE-PASSWORD :</th>
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
                                    <input type="hidden" name="login" value="{{$usuario->email}}">
                                    <input type="hidden" name="id" value="{{$usuario->id}}">

                                </div>
                                <input type="submit" name="Guardar" value="Guardar" class="btn btn-primary btn-sm float-right">
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
            </form>
        </div>
    </div>


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

@endsection
