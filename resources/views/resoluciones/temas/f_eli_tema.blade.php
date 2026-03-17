<div class="modal-dialog modal-lg" role="document" id="panel_docleg">
    <div class="modal-content border-bottom-danger shadow-lg">
        <div class="modal-header bg-danger">
            <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="{{url('img/icon/eliminar.png')}}">&nbsp;&nbsp;Eliminar Tema</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>

        <div class="modal-body">
            @if($eliminar=='1')
                <div class="row">
                    <div class="col-md-9" >

                            <form action="{{url('eli_tema')}}" method="post" id="form_eli_tem">
                                @csrf
                                <span class="font-italic">Esta seguro de eliminar el tema de interés : </span><br/><br/>
                                    <div class="font-weight-bold alert-danger shadow text-center centrar_bloque  rounded p-2">
                                                <table class="col-md-12">
                                                    <tr>
                                                        <th class="text-right font-italic text-dark">Nombre :</th>
                                                        <td class="border-bottom border-dark text-left">
                                                        {{$tema->tem_titulo}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-right font-italic text-dark">CI:</th>
                                                        <td class="border-bottom border-dark text-left">{{$tema->tem_des}}</td>
                                                    </tr>
                                                </table>
                                    </div>
                                <input type="hidden" name="ct" value="{{$tema->cod_tem}}"/>
                            </form>
                        </div>
                        <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
                    </div>
                    <br/>
                    <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
                </div>
                @else
                    <div class="alert-danger rounded p-2 col-md-8 centrar_bloque">
                        <span class="font-weight-bold">
                                 <span><i class="text-danger">X</i></span>   No se puede eliminar el tema devido a que tiene enlace a resoluciones
                        </span>
                    </div>
                    <br/>
                    <br/>
              @endif




        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
            @if($eliminar==1)
                <button class="btn btn-danger btn-sm" type="button" data-dismiss="modal" onclick="$('#form_eli_tem').submit()">Eliminar</button>
            @endif
        </div>

    </div>
</div>
