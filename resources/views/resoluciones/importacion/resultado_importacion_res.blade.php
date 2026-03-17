@extends('marco/pagina')
@section('contenido')

        <a href="{{url('lista importaciones resolucion/'.Auth::user()->id)}}" class="btn btn-outline-danger text-dark"><i class="fas fa-arrow-alt-circle-left"></i> Atrás</a>
        <br/>
        <br/>

@if(isset($fallas) && count($fallas)>0)
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <span class="font-weight-bold">Ocurrió los siguientes errores :</span>
        <br/>
        <ul>
            @foreach($fallas as $f)
                <li>
                    <?php echo "Fila: ".$f->row()." - ";?>
                    <?php $errores=(array) $f->errors();
                        foreach ($errores as $e):
                            echo $e;
                        endforeach;
                        ?>
                </li>
            @endforeach
        </ul>
    </div>
@endif

@endsection

