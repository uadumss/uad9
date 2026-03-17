<div class="modal-dialog modal-lg" role="document">
<div class="modal-content border-bottom-danger">
    <div class="modal-header bg-danger">
        <h5 class="modal-title text-white" id="exampleModalLabel"><img src="{{url('img/icon/eliminar.png')}}"> Eliminar Tarea</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <span class="font-italic text-dark">Esta seguro de eliminar el reporte: </span><br/>
        <br/>
        <div class="row shadow ml-3 mr-3 rounded p-3">

                <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-3" id="panel_e_titulo">
                    <table class="col-md-12">
                        <tr>
                            <th class="text-right"> Nùmero de reporte periodico: </th>
                            <th class="text-dark text-left border-bottom border-danger pl-3"> {{$reporte_periodico['rt_num']}}</th>
                        </tr>
                        <tr>
                            <th class="text-right"> Fecha inicio : </th>
                            <th class="text-dark text-left border-bottom border-danger pl-3">{{date('d/m/Y',strtotime($reporte_periodico['rt_fech_ini']))}}</th>
                        </tr>
                        <tr>
                            <th class="text-right"> Fecha final : </th>
                            <th class="text-dark text-left border-bottom border-danger pl-3">{{date('d/m/Y',strtotime($reporte_periodico['rt_fech_fin']))}}</th>
                        </tr>

                    </table>
                </div>
                <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2><i class="fas fa-question-circle"></i></h2></div>

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>

            <form action="{{url('eliminar reporte periodo')}}" method="POST">
                @csrf
                <input class="btn btn-danger" type="submit" value="Aceptar"/>
                <input type="hidden" name="cr" value="{{$reporte_periodico['cod_rt']}}">
            </form>
    </div>
</div>
</div>
