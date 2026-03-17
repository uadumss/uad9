@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
                <table>
                    <tr>
                        <th>N</th>
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Corregido</th>
                    </tr>
                    <?php $i=1;?>
                    @foreach($persona as $p)

                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$p->id_per}}</td>
                            <td>{{$p->per_nombre}}</td>
                            <td>{{$p->per_apellido}}</td>
                            <?php $letra="Ñ";
                                $corregido= str_replace("Ã", $letra, $p->per_apellido);
                            ?>

                            <td>{{$corregido}}</td>
                        </tr>
                        <?php $i++;?>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
