
<div class="modal-content border-bottom-primaryr">
    <div class="modal-header bg-verde-oscuro">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-eye"></i>&nbsp;&nbsp;Verificar faltantes</h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="bg-verde-oscuro centrar_bloque p-1 col-md-8 rounded shadow-sm">
            <h6 class="text-white text-center">Formulario de verificación de titulos faltantes</h6>
        </div>
        <hr class="sidebar-divider"/>

        @if(Session::has('exito'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {!! session('exito') !!}
            </div>
        @endif
        @if(Session::has('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {!! session('error') !!}
            </div>
        @endif
        <span class="font-italic text-dark" style="font-size: 15px">
            <span class="font-weight-bold">Gestión : </span><span>{{$gestion}}</span> |
            <span class="font-weight-bold">Tipo : </span><span>{{App\Models\Funciones::nombre_titulo($tipo)}}</span> |
            <span class="font-weight-bold">Rango : </span><span> 1 - {{$rango}}</span> |
            <span class="font-weight-bold">Ultimo título de sistema : </span><span> {{$max[0]->max}}</span> |
        </span>
        <br/>
        <br/>
        <div class="row">
            <div class="col-md-6">

                    @if($faltantes!='')
                    <div class="alert-danger p-3 rounded">
                        <span class="font-weight-bold">Diplomas o títulos faltantes</span><br/><br/>
                        {{$faltantes}}
                    </div>
                    @else
                    <div class="alert-success p-3 rounded text-center">
                        <i class="fas fa-check-circle text-success" style="font-size: 20px"></i> &nbsp;&nbsp;<span class="font-weight-bold">NO</span> existen titulos faltantes
                    </div>
                    @endif

            </div>
            <div class="col-md-5 shadow-lg ml-5">
                <br/>
                <span class="font-italic text-primary font-weight-bold"> Formulario para verificación de titulos faltantes</span><br/><br/>
                <form id="verificar_titulos1">
                    @csrf
                    <span class="font-italic text-danger float-right font-weight-bold" style="font-size: 14px">* Todos los datos deben ser llenados</span>
                    <table class="col-md-10 pl-lg-5">
                        <tr class="border-bottom">
                            <th class="font-weight-bold text-right">Gestión : </th>
                            <td>
                                <select class="custom-select custom-select-sm border border-info"  name="gestion">
                                    <option value="{{$gestion}}">{{$gestion}}</option>
                                    <?php $año=date('Y');?>
                                    @for($i=$año;$i>1927;$i--)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </td>
                        </tr>
                        <tr class="border-bottom">
                            <th class="font-weight-bold text-right">Tipo de documento : </th>
                            <td>
                                <select class="custom-select custom-select-sm border border-info" name="tipo">
                                    <option value="db"> Diplomas de bachiller</option>
                                    <option value="ca">Certificado académico</option>
                                    <option value="da">Diploma académico</option>
                                    <option value="tp">Título profesional</option>
                                    <option value="di">Diplomado</option>
                                    <option value="tpos">Títulos de posgrado</option>
                                    <option value="re">Reválida</option>
                                    <option value="su">Certificado supletorio</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="border-bottom">
                            <th class="font-weight-bold text-right">Rango : </th>
                            <td>
                                <input type="text" name="rango" class="form-control form-control-sm border border-info">
                                <span class="font-italic text-danger" required style="font-size: 14px">Poner 0 para el rango del sistema</span>
                            </td>
                        </tr>
                    </table>
                </form>
                <br/>
                <button class="btn btn-primary btm-sm float-right mr-5 mb-2" onclick="enviar('verificar_titulos1','{{url('verificar titulos/')}}','panel_editar')" type="button">Consultar</button>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    </div>
</div>
