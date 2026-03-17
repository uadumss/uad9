<div>
    <br/>
    <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
        <thead>
        <tr class="bg-gradient-secondary text-white text-center">
            <th>Nº</th>
            <th>Resolución</th>
            <th>Descripción</th>
            <th>Objeto</th>
            <th>Tema</th>
            <th>Opciones</th>
        </tr>
        </thead>
        <tbody>
        <?php $i=1;?>
        @foreach($resoluciones as $r)
            <tr id="fila{{$i}}" style="font-size: 0.9em">
                <td class="text-primary border-right">{{$i}}</td>
                <td id="num{{$i}}" class="text-right">{{$r->res_numero."/".$r->res_gestion}}<br/></td>
                <td id="desc{{$i}}">
                    <div class="text-dark border-bottom ">{{$r->res_desc}}</div>
                    <span style="font-size: 0.9em">
                            <span class="font-weight-bold text-dark font-italic">Fecha: </span> <span><?php if($r->res_fecha!=''){?>
                            {{date('d/m/Y',strtotime($r->res_fecha))}}
                            <?php }?></span>
                            <span class="text-danger font-weight-bold"> | </span>
                            <span class="font-weight-bold text-dark font-italic">Tomo: </span> <span>{{mb_strtoupper($r->res_tipo)}}</span>
                            <span class="text-danger font-weight-bold"> | </span>
                            <span class="font-weight-bold text-dark font-italic">Tomo: </span> <span>{{$r->tom_numero}}</span>
                            <span class="text-danger font-weight-bold"> | </span>
                            @if($r->res_pdf!='')
                                <span class="font-weight-bold text-dark font-italic">Resolución: </span><img src="{{url('img/icon/tit.gif')}}" width="15" height="15">
                                <span class="text-danger font-weight-bold"> | </span>
                            @endif
                            @if($r->res_ant!='')
                                <span class="font-weight-bold text-dark font-italic">Antecedentes: </span><img src="{{url('img/icon/antecedente.gif')}}" width="15" height="15">
                            @endif
                        </span>
                </td>

                <td id="obj{{$i}}">{{$r->res_objeto}}</td>
                <td id="tem{{$i}}">{{$r->res_tema}}</td>
                <td class="text-right">
                    <form id="resolucion{{$i}}">
                        @csrf
                        <input type="hidden" name="cr" value="{{$r->cod_res}}">
                        <input type="hidden" name="cs" value="{{$cod_san}}">
                    </form>
                    <button href="" class="btn btn-danger btn-sm btn-circle" title="Asignar resolucion" title="Ver detalle de la resolución"
                       onclick="enviar('resolucion{{$i}}','{{url('asignar resolucion sancionado')}}','panel_documento');$('#Modal2').modal('hide')"> <i class="fas fa-arrow-circle-right"></i>
                    </button>
                </td>
            </tr>
                <?php $i++;?>
        @endforeach
        </tbody>
    </table>
</div>
