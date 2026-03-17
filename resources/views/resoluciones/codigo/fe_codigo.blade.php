<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-user"></i> Editar código de archivado</h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <form action="{{url('g_codigo')}}" method="POST">
        @csrf
            <div class="modal-body">
                <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                    <h6 class="text-white text-center">Formulario de edicion de código</h6>
                </div>
                <br/>
                <table class="table-hover col-md-12" >
                    <tr>
                        <th class="text-right font-italic" width="200">Número de código:</th>
                        <td class="border-bottom border-dark">
                            <input type="text" class="form-control form-control-sm border-0" name="codigo" required value="{{$codigo->carch_numero}}" />
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic"> Título :</th>
                        <td class="border-bottom border-dark">
                            <input type="text" class="form-control form-control-sm border-0" name="titulo" required value="{{$codigo->carch_titulo}}"/>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic"> Plan :</th>
                        <td class="border-bottom border-dark">
                            <span class="text-danger font-weight-bold">&nbsp;&nbsp;{{$plan->plan_numero}}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic"> Temas :</th>
                        <td class="border-bottom border-dark">
                            <textarea class="form-control" name="desc" id="desc1" cols="10" rows="5">{{$codigo->carch_desc}}</textarea>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="cc" value="{{$codigo->cod_carch}}">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <input class="btn btn-primary" type="submit" value="Aceptar"/>
            </div>
    </form>
    <script type="text/javascript">
        tinymce.init({
            selector: '#desc1',
        });
    </script>
</div>

