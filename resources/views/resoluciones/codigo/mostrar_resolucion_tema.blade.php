<div class="modal-dialog modal-xl" role="document" id="panel_editar">
    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-user"></i> Editar código de archivado</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="bg-primary centrar_bloque p-2 col-md-5 rounded shadow">
                <h6 class="text-white text-center font-weight-bold">Formulario de detalle de código</h6>
            </div>
            <hr/>
            <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr class="bg-gradient-secondary text-white text-center" style="font-size: 0.9em">
                    <th>Nº</th>
                    <th>Resolución</th>
                    <th>Descripción</th>
                    <th>Objeto</th>
                    <th>Tema</th>
                    <th>Códigos</th>

                    <th>Tipo</th>
                    <th>Observaciones</th>

                    <th>Opciones</th>
                </tr>
                </thead>
                <tbody>
                <?php $i=1;?>
                @foreach($resultado as $r)
                    <tr id="fila{{$i}}" style="font-size: 0.9em">
                        <td class="text-primary border-right">{{$i}}</td>
                        <td id="num{{$i}}" class="text-right">{{$r->res_numero}}<br/></td>
                        <td id="desc{{$i}}">
                            <div class="text-dark border-bottom ">{{$r->res_desc}}</div>
                            <span style="font-size: 0.8em">
                                                <span class="font-weight-bold text-dark font-italic">Fecha: </span> <span><?php if($r->res_fecha!=''){?>
                                    {{date('d/m/Y',strtotime($r->res_fecha))}}
                                    <?php }?>
                                                                                                </span> |

                                                    @if($r->res_pdf!='')
                                    <span class="font-weight-bold text-dark font-italic">Resolución: </span><img src="{{url('img/icon/tit.gif')}}" width="15" height="15">
                                @endif

                                @if($r->res_ant!='')
                                    <span class="font-weight-bold text-dark font-italic">Antecedentes: </span><img src="{{url('img/icon/antecedente.gif')}}" width="15" height="15">
                                @endif
                                            </span>
                        </td>

                        <td id="obj{{$i}}">{{$r->res_objeto}}</td>
                        <td id="tem{{$i}}">{{$r->res_tema}}</td>

                        <td id="cod{{$i}}">
                                <?php $archivados=\App\Http\Controllers\ResolucionController::l_codigo($r->cod_res);
                                echo $archivados;
                                ?>
                        </td>

                        <td id="tip">{{$r->res_tipo}}</td>
                        <td>
                            @if($r->res_obs!='')
                                <i class="fas fa-eye text-danger"></i>
                            @endif
                        </td>

                        <td class="text-right">
                            <a href="" class="btn btn-circle btn-light btn-sm text-primary" data-toggle="modal" data-target="#verDatos"
                               onclick="verDatos('{{url('ver datos resolucion/'.$r->cod_res)}}','p_detalle')" title="Ver detalle de la resolución"> <i class="fas fa-arrow-right"></i></a>
                        </td>
                    </tr>
                        <?php $i++;?>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>
