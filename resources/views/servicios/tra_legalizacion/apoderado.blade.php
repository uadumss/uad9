<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"><i class="fas fa-user"></i> Apoderado </h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body" >

        @if(Session::has('exito'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="font-weight-bold">{!! session('exito') !!}</span>
            </div>
        @endif
        <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow-sm">
            <h6 class="text-white font-weight-bold text-center">Datos del apoderado</h6>
        </div>

            <hr class="sidebar-divider"/>
            <div class="row">

                <div class="col-md-6">
                    <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Datos del trámite</span>
                    <br/>
                    <br/>

                    <table class="table table-sm">
                        @php
                            $nombre='';    $apellido='';  $ci="";
                            if($persona){   $apellido=$persona->per_apellido;     $nombre=$persona->per_nombre;  }
                        @endphp
                        <tr>
                            <th class="text-right font-italic text-dark font-italic">Nro Trámite : </th>
                            <td class="border-bottom border-dark">
                                    {{$tramita->tra_numero}}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark font-italic">Fecha de solicitud : </th>
                            <td class="border-bottom border-dark">
                                    {{date('y/m/Y', strtotime($tramita->tra_fecha_solicitud))}}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark font-italic">Titular : </th>
                            <td class="border-bottom border-dark">
                                {{$apellido." ".$nombre}}
                            </td>

                        </tr>
                    </table>

                <hr class="sidebar-divider"/>
                <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Datos del apoderado</span>
                <br/>
                <br/>

                <table class=" table table-sm">
                    <tr>
                        <th class="text-right font-italic text-dark">CI : </th>
                        <td class="border-bottom border-dark">
                            @if($apoderado)
                                {{$apoderado['apo_ci']}}
                            @else
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic text-dark font-italic">Nombre apoderado : </th>
                        <td class="border-bottom border-dark">
                            @if($apoderado)
                                {{$apoderado['apo_apellido']." ".$apoderado['apo_nombre']}}
                            @else
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic text-dark">Tipo de apoderado : </th>
                        <td class="border-bottom border-dark">
                            @if($tramita->tra_tipo_apoderado=='d')
                                Declaración jurada
                            @else
                                @if($tramita->tra_tipo_apoderado=='p')
                                    Poder notariado
                                @else
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                @endif
                            @endif
                        </td>
                    </tr>

                </table>

                <button id="otros" class="btn btn-sm btn-primary" onclick="$('#otrosDiv').show(500); $('#otros').hide(500);"> Editar datos</button>
                </div>
                <div class="col-md-6">
                    <div>
                        <div id="otrosDiv" class="border rounded shadow" style="display: none;">
                            <div class="p-3">
                                <a onclick="$('#otrosDiv').hide(500);$('#otros').show(500); " id="ocultar" style="float:right">
                                    <i class="fas fa-minus-circle text-danger"></i></a>
                                <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Editar datos del apoderado</span>
                                <br><br>

                                <form id="form_apoderado">

                                    @php
                                        $nombre='';    $apellido='';  $ci="";
                                        if($apoderado){   $ci=$apoderado->apo_ci;       $apellido=$apoderado->apo_apellido;     $nombre=$apoderado->apo_nombre;  }
                                    @endphp

                                    <table class="table-hover col-md-12">
                                        <tr>
                                            <th class="text-right font-italic">CI : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       name="ci" value="{{$ci}}"/></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Apellidos : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       required name="apellido" id="apellido" value="{{$apellido}}" /></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Nombres : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       required name="nombre" id="nombre" value="{{$nombre}}" /></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic" valign="top">Tipo de apoderado : </th>
                                            <td class="border-bottom border-dark">
                                                @if($tramita->tra_tipo_apoderado=='d')
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" checked> Declaración jurada<br/>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p"> Poder notariado
                                                @else
                                                    @if($tramita->tra_tipo_apoderado=='p')
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d"> Declaración jurada<br/>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" checked> Poder notariado
                                                    @else
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d"> Declaración jurada<br/>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p"> Poder notariado
                                                    @endif
                                                @endif

                                        </tr>
                                    </table>
                                    <br>
                                    <input type="hidden" name="ctra" value="{{$tramita->cod_tra}}">
                                </form>
                                <a class="btn btn-primary btn-sm text-white" onclick="guardarDatos('guardar apoderado','panel_apoderado','form_apoderado');" >Guardar</a>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ================================GLOSA====================================-->

            </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
    </div>
</div>


