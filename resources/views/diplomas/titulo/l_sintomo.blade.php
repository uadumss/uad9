<div class="modal-content border-bottom-primary shadow-lg">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-file-signature"></i> Nueva búsqueda </h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        @if($cod_tom==0)
            <span class="border border-danger font-italic font-weight-bold" style="font-size: 0.9em"> * No existen títulos sin tomos en esta gestión</span>
        @else
            <table class="table table-sm table-hover" width="100%" cellspacing="0" style="font-size: 0.9em">
                <thead>
                <tr class="bg-gray-600 text-white">
                    <th>Nº</th>
                    <th class="text-left">Número</th>
                    <th class="text-left">CI</th>
                    <th class="text-left">Nombre</th>
                    <th>Fecha</th>
                    <th>Opciones</th>

                </tr>
                </thead>
                <tbody>
                <?php $i=1;?>
                @foreach($titulo as $t)
                    <tr>
                        <th>{{$i}}</th>
                        <td>{{$t->tit_nro_titulo}}</td>
                        <td>{{$t->per_ci}}</td>
                        <td>{{$t->per_apellido.", ".$t->per_nombre}}</td>
                        <td>@if($t->tit_fecha_emision!='')
                                {{date('d/m/Y', strtotime($t->tit_fecha_emision))}}
                            @endif
                        </td>
                        <td id="asignar{{$i}}">
                            <button class="btn btn-primary btn-sm" onclick="asignarTomo('asignarTomo',{{$t->cod_tit}},{{$i}})"><i class="fas fa-angle-right"></i> Asignar</button>
                        </td>
                    </tr>
                    <?php $i++;?>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
    </div>
</div>
<script>
    function asignarTomo(url,cod_tit,fila){
        var token = "{{csrf_token()}}";
        var datos='cod_tomo_antiguo={{$cod_tom}}&cod_tomo_actual={{$cod_tomo_actual}}&cod_tit='+cod_tit;
        $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
        $.ajax({
            url:'{{url('/')}}/'+url,
            type:'POST',
            data:datos,
            //data:$('#form_editar').serialize(),
            success: function (resp) {
                if(resp=='') {
                    $('#asignar' + fila).html("<span class='text-success font-italic font-weight-bold border border-success rounded p-1' style='font-size: 0.9em'>Asignado</span>");
                }
                else{
                    $('#asignar' + fila).html("<span class='text-danger font-italic font-weight-bold border border-danger rounded p-1' style='font-size: 0.9em'>"+resp+"</span>");
                }
            },
            error: function (data) {
                $('#panel_error_archivo').show();
            }
        });
    }
</script>

