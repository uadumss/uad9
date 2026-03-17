@extends('marco/pagina')
@section('contenido')

    <div class="row col-md-12" id="panel_completo">
        <div class="col-md-3">
            <div class="card shadow" id="d_personales">
                <div class="card-header bg-primary">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h5 class="text-white">Datos personales</h5>
                        <button onclick="//$('#d_personales').hide(200);$('#btn_personal').show(200);"
                                class="d-none d-sm-inline-block btn btn-sm btn-light shadow-sm">
                            <i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body" id="d_personales">
                    <div>
                        <div class="centrar_bloque col-md-7">
                            @if($usu['foto']!='')
                                <img src="{{url('img/foto/'.$usu['foto'])}}" class="imgRedonda centrar_bloque" width="150" height="150">
                            @else
                                <img src="{{url('img/icon/sin foto'.$usu['sexo'].'.png')}}" class="imgRedonda centrar_bloque" width="150" height="150">
                            @endif
                        </div>
                        <br/>
                        <div class="p-1">
                            <table class="">
                                <tr class="border-bottom">
                                    <th>Nombre:</th>
                                    <td>{{$usu['name']}}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th>Ci:</th>
                                    <td>{{$usu['ci']}}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th>Contacto:</th>
                                    <td>{{$usu['contacto']}}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th>Sexo:</th>
                                    @if($usu['sexo']=='M')
                                        <td>MASCULINO</td>
                                    @else
                                        <td>FEMENINO</td>
                                    @endif
                                </tr>
                                <tr class="border-bottom">
                                    <th>fecha ingreso:</th>
                                    <td>{{$usu['created_at']}}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th>Rol :</th>
                                    <td>{{$usu['rol']}}</td>

                                </tr>
                                <tr class="border-bottom">
                                    <th>Cargo :</th>
                                    <td>{{$usu['cargo']}}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th>Dirección:</th>
                                    <td>{{$usu['direccion']}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9" id="p_trabajo">
            <div class="card shadow">
                <div class="card-header bg-primary">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h5 class="text-white">Rendimiento</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div id="panel">
                        @if(Session::has('exito'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {!! session('exito') !!}
                            </div>
                        @endif
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

                    </div>
                    <div id="panel_graf">
                        <a data-toggle="collapse" href="#rendimiento" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <i class="fas fa-chart-line"> </i> Establecer rango</a>
                        <div class="collapse" id="rendimiento">
                            <div class="card card-body">
                                <form id="form_ren">
                                    <div class="input-group">
                                        @csrf
                                        <span class="pt-2 text-dark font-weight-bolder">Año :</span>&nbsp; <select class="custom-select col-md-2" placeholder="seleccione un año" name="a" id="a">
                                            <option value="<?php echo date('Y')?>"><?php echo date('Y')?></option>
                                            <?php $año=date('Y')?>
                                            @for($i=2021;$i<=$año;$i++)
                                                <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                        </select>&nbsp;&nbsp;&nbsp;
                                        <span class="pt-2 text-dark font-weight-bolder">Mes Inicial : </span>&nbsp;<select class="custom-select" name="mi" id="mi">
                                            <option value="1">Enero</option><option value="2">Febrero</option>
                                            <option value="3">Marzo</option><option value="4">Abril</option>
                                            <option value="5">Mayo</option><option value="6">Junio</option>
                                            <option value="7">Julio</option><option value="8">Agosto</option>
                                            <option value="9">Septiembre</option><option value="10">Octubre</option>
                                            <option value="11">Noviembre</option><option value="12">Diciembre</option>
                                        </select>&nbsp;&nbsp;&nbsp;
                                        <span class="pt-2 text-dark font-weight-bolder">Mes Final :</span>&nbsp; <select class="custom-select" name="mf" id="mf">
                                            <option value="1">Enero</option><option value="2">Febrero</option>
                                            <option value="3">Marzo</option><option value="4">Abril</option>
                                            <option value="5">Mayo</option><option value="6">Junio</option>
                                            <option value="7">Julio</option><option value="8">Agosto</option>
                                            <option value="9">Septiembre</option><option value="10">Octubre</option>
                                            <option value="11">Noviembre</option><option value="12">Diciembre</option>
                                        </select>&nbsp;&nbsp;&nbsp;

                                        <input type="hidden" name="id_per" value="{{$usu['id']}}"/>
                                            <a type="button" class="btn btn-sm btn-primary text-white" onclick="rend()">Ver rendimiento</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="personal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content border-bottom-primary">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-edit"></i> RENDIMIENTO</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="panelRen">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border text-danger" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" type="button" data-dismiss="modal" onclick="enviarForm()" >Enviar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function rend(){
            //alert("entro");
            var url = "{{url('rendimiento_per')}}"; // El script a dónde se realizará la petición.
            var mi=parseInt($('#mi').val());
            var mf=parseInt($('#mf').val());
            if(mi>mf) {
                $('#errorMesFinal').modal('show');
            }else{
                $('#personal').modal('show');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#form_ren").serialize(), // Adjuntar los campos del formulario enviado.
                    success: function(data)
                    {
                        $('#panelRen').html(data);
                    }
                });
            }
        }
    </script>

@endsection
