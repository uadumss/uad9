<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
@if(Session::has('exito'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <span class="font-weight-bold">{!! session('exito') !!}</span>
    </div>
@endif
@if(Session::has('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <span class="font-weight-bold text-dark">{!! session('error') !!}</span>
    </div>
@endif
@if(count($errors)>0)
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <ul>
            @foreach($errors->all() as $e)
                <li class="font-weight-bold te">{{$e}} - </li>
            @endforeach
        </ul>
    </div>
@endif

<div>
    <form action="{{url('g_glosa')}}" id="form_modelo" enctype="multipart/form-data" method="POST">
        @csrf
        @if($cod_glo=='0')
            <table class="col-md-11">
                <tr>
                    <th class="text-dark ">Nombre :</th>
                </tr>
                <tr>
                    <td class="border-bottom border-dark">
                        <input type="text" name="titulo" class="form-control border-0"/>
                    </td>
                </tr>
                <tr>
                    <th class="text-dark">Glosa :</th>
                </tr>
                <tr>
                    <td class="border-bottom border-dark">
                        <textarea class="form-control border-0" rows="15" name="glosa" id="cuerpo_glosa"></textarea>
                    </td>
                </tr>
            </table>
        @else
            <table class="col-md-11">
                <tr>
                    <th class="text-dark ">Nombre :</th>
                </tr>
                <tr>
                    <td class="border-bottom border-dark">
                        <input type="text" name="titulo" class="form-control border-0" value="{{$glosa->glo_titulo}}"/>
                    </td>
                </tr>
                <tr>
                    <th class="text-dark">Glosa :</th>
                </tr>
                <tr>
                    <td class="border-bottom border-dark">
                        <textarea class="form-control border-0" rows="15" name="glosa" id="cuerpo_glosa">{{$glosa->glo_glosa}}</textarea>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="cg" value="{{$glosa->cod_glo}}">
        @endif
        <input type="hidden" name="ct" value="{{$cod_tre}}">
    </form>
    @if($cod_glo=='0')
        @can('crear glosa - srv')
            <button class="btn btn-sm btn-primary" onclick="enviar('form_modelo','{{url('g_glosa')}}','panel_tramite')">Guardar</button>
        @endcan
    @else
        @can('editar glosa - srv')
            <button class="btn btn-sm btn-primary" onclick="enviar('form_modelo','{{url('g_glosa')}}','panel_tramite')">Guardar</button>
        @endcan
    @endif
</div>
<script type="text/javascript">
    tinymce.init({
        selector: '#cuerpo_glosa',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });
</script>
