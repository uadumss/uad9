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
    @if(count($errors)>0)
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                @foreach($errors->all() as $e)
                    <li class="font-weight-bold te">{{$e}} - </li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class=""><i class="fas fa-user-friends"></i>&nbsp;&nbsp; Lista de usuarios dependientes</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="card  mb-4">
                <div class="card-body">
                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Lista de usuarios dependientes</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr class="bg-gray-600 text-white">
                                <th>Nº</th>
                                <th class="text-right">CI</th>
                                <th class="text-left">Foto</th>
                                <th class="text-left">Nombre</th>
                                <th class="text-left">Cargo</th>
                                <th class="text-left">Login</th>
                                <th class="text-right">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=1;?>
                            @foreach($dependientes as $u)
                                <tr style="font-size: 0.9em">
                                    <th class="border-right font-weight-bolder text-primary">{{$i}}</th>
                                    <td class="text-right">{{$u->ci}}</td>
                                    <td>@if($u->foto!='')
                                            <img src="{{url('img/foto/'.$u->foto)}}" width="40" height="40" class="imgRedonda centrar_bloque float-right"/>
                                        @else
                                            <img src="{{url('img/icon/sin foto'.$u->sexo.'.png')}}" class="imgRedonda centrar_bloque float-right" width="40" height="40">
                                        @endif
                                    </td>
                                    <td class="font-weight-bold">
                                        {{$u->name}}
                                    </td>
                                    <td>{{$u->cargo}}</td>
                                    <td>{{$u->email}}</td>

                                    <td class="text-right">
                                            <a class="btn btn-light btn-circle btn-sm text-primary"  data-toggle="modal" data-target="#dependiente" title="Tareas"
                                               onclick="cargarDatos('{{url('lista tareas dependiente/'.$u->id)}}','panel_dependiente')">
                                                <i class="fas fa-pen-alt"></i>
                                            </a>
                                        <a href="{{url('l_reporte_periodico_dependiente/'.$u->id)}}" class="btn btn-light btn-circle btn-sm text-primary" title="Reportes periódicos">
                                            <i class="fas fa-calendar"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php $i++;?>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===========================MODAL NUEVO USUARIO===================-->
    <div class="modal fade" id="dependiente" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" id="panel_dependiente">

        </div>
    </div>
    <!--===========================END ==============================-->

    <script>
            function cargarDatos(ruta,panel){
                $('#'+panel).html("<br/><br/><div class='d-flex justify-content-center'><div class='spinner-border text-danger' role='status'> <span class='visually-hidden'></span></div></div>");
                $.ajax({
                    url: ruta,
                    type: 'GET',
                    data:'',
                    success: function (resp) {
                        $('#'+panel).html(resp);
                    },
                    error: function () {
                        alert('No se puede ejecutar la petición');
                    }
                });
            }
        function MsgEliminarTomo(tomo,ct){
            $('#ct').val(ct);
            $('#eliTomo').html("");
        }
    </script>

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
@endsection
