<div class="modal-dialog modal-xl" role="document">
        <div class="modal-content border-bottom-primary">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"> Datos personales</h5>
                <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow-lg">
                            <div class="card-header alert-primary">
                                <h5 class="text-dark">Datos del funcionario</h5>
                            </div>
                            <div class="card-body" id="d_personales">
                                @foreach($usu as $u)
                                    <div>
                                        <div class="centrar_bloque col-md-7">
                                            @if($u->foto!='')
                                                <img src="{{url('img/foto/'.$u->foto)}}" class="imgRedonda centrar_bloque" width="150" height="150">
                                            @else
                                                <img src="{{url('img/icon/sin foto'.$u->sexo.'.png')}}" class="imgRedonda centrar_bloque" width="150" height="150">
                                            @endif
                                        </div>
                                        <br/>
                                        <div class="p-1">
                                            <table class="">
                                                <tr class="border-bottom">
                                                    <th>Nombre:</th>
                                                    <td>{{$u->name}}</td>
                                                </tr>
                                                <tr class="border-bottom">
                                                    <th>Ci:</th>
                                                    <td>{{$u->ci}}</td>
                                                </tr>
                                                <tr class="border-bottom">
                                                    <th>Contacto:</th>
                                                    <td>{{$u->contacto}}</td>
                                                </tr>
                                                <tr class="border-bottom">
                                                    <th>Sexo:</th>
                                                    <td>{{$u->sexo}}</td>
                                                </tr>
                                                <tr class="border-bottom">
                                                    <th>Fecha designación:</th>
                                                    <td>{{$u->des_fech_asig}}</td>
                                                </tr>
                                                <tr class="border-bottom">
                                                    <th>Fecha colcusión:</th>
                                                    <td>{{$u->des_fech_ret}}</td>
                                                </tr>
                                                <tr class="border-bottom">
                                                    <th>Dirección:</th>
                                                    <td>{{$u->direccion}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-lg">
                            <div class="card-header alert-primary">
                                <h5 class="text-dark"> Datos de la tarea</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <td><span class="font-weight-bold text-dark">Tarea :</span></td>
                                        <td>{{$tarea['tar_nombre']}}</td>

                                    </tr>
                                    <tr>
                                        <td><span class="font-weight-bold text-dark">Inicio :</span></td>
                                        <td>{{date('d/m/Y',strtotime($tarea['tar_fi']))}}</td>
                                    </tr>
                                    <tr>
                                        <td><span class="font-weight-bold text-dark">Conclusión :</span></td>
                                        <td>@if($tarea['tar_ff']!='')
                                                {{date('d/m/Y',strtotime($tarea['tar_ff']))}}
                                            @endif
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
</div>
