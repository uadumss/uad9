
        <div class="table-responsive">
            <table class="table table-sm table-hover" width="100%" cellspacing="0" style="font-size: 12px;">
                <thead>
                <tr class="bg-gray-600 text-white">
                    <th>Nº</th>
                    <th>Descripción</th>
                    <th>Opciones</th>
                </tr>
                </thead>
                <tbody>
                <?php $i=1;?>
                @foreach($resultado as $t)
                    <tr>
                        <td>{{$i}}</td>
                        <td>
                            <span class="font-weight-bold">{{strtoupper($t->res_tipo)." ".$t->res_numero}} </span>
                            <span class="font-weight-bold font-italic  text-danger"> | </span>
                            <span>
                                <?php if($t->res_fecha!=''){echo date('d/m/Y',strtotime($t->res_fecha));} ?>
                            </span>
                            <span class="font-weight-bold font-italic text-danger"> | </span>
                            <span> {{$t->res_objeto}}</span>
                            <br/>
                            <span class="font-weight-bold">Tema : </span>
                            <span>
                            <?php
                                if($clave!=''){
                                    $text =preg_replace('#'. preg_quote($clave) .'#i', '<span style="background-color:#f1fa8c">\0</span>',$t->res_tema);
                                    echo $text;
                                }else{
                                    echo $t->res_tema;
                                }
                                ?>
                            </span>
                            <br/>
                            <span class="font-weight-bold">Descripción : </span>
                            <span>
                            <?php
                                if($clave!=''){
                                    $text =preg_replace('#'. preg_quote($clave) .'#i', '<span style="background-color:#f1fa8c">\0</span>',$t->res_desc);
                                    echo $text;
                                }else{
                                    echo $t->res_desc;
                                }
                                ?>
                            </span>
                        </td>
                        <td class="text-right">
                            <a class="btn btn-circle btn-light btn-sm text-primary" data-toggle="modal" data-target="#Asignar"
                               onclick="cargarDatos('{{url('ver datos resolucion/'.$t->cod_res)}}','panel_asignar')" title="Mostrar resolución"> <i class="fas fa-eye"></i></a>

                            <a class="btn btn-circle btn-light btn-sm text-danger" data-toggle="modal" data-target="#Asignar"
                               onclick="cargarDatos('{{url('asignar resolucion/'.$cod_tem.'/'.$t->cod_res)}}','panel_asignar')" title="Incluir al tema"> <i class="fas fa-pen-alt"></i></a>
                        </td>
                    </tr>
                    <?php $i++;?>
                @endforeach
                </tbody>
            </table>
        </div>

