@extends('marco/pagina')
@section('contenido')
    <div class="card shadow ">
    <div class="card-header bg-primary">
        <div class="d-sm-flex align-items-center">
            <h5 class=" text-white" style="font-family: 'Nunito', sans-serif;">Nuevo diploma académico</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="alert-primary centrar_bloque col-md-6 p-2 mb-2 rounded shadow">
            <h5 class="text-dark text-center">Formulario de registro de Diplomas Académicos</h5>
        </div>
        <form>
            <div class="row border col-md-10 justify-content-center">
                <div class="col-md-5 mr-2">
                    <div class="text-center">
                        <span class="text-primary">DATOS PERSONALES </span>
                    </div>
                    <div>
                        <table>
                            <tr>
                                <td>CI:</td>
                                <td><input type="text"  class="form-control form-control-sm" placeholder=""/></td>
                            </tr>
                            <tr>
                                <td>Passaporte:</td>
                                <td><input type="text"  class="form-control form-control-sm" placeholder=""/></td>
                            </tr>
                            <tr>
                                <td>Apellidos:</td>
                                <td><input type="text"  class="form-control form-control-sm" placeholder=""/></td>
                            </tr>
                            <tr>
                                <td>Nombres:</td>
                                <td><input type="text"  class="form-control form-control-sm" placeholder=""/></td>
                            </tr>
                            <tr>
                                <td>sexo:</td>
                                <td><select class="form-control custom-select-sm">
                                        <option>Masculino</option>
                                        <option>femenino</option>
                                    </select></td>
                            </tr>
                            <tr>
                                <td>Nacionalidad:</td>
                                <td>
                                    <select class="form-control custom-select-sm">
                                        <option value="120">Bolivia</option>
                                        @foreach($nacion as $n)
                                            <option value="{{$n['cod_nac']}}">{{$n['nac_nombre']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div  class="col-md-5">
                    <div class="text-center">
                        <span class="text-primary">DATOS DEL DOCUMENTO </span>
                        <table>
                            <tr>
                                <td>Número de titulo:</td>
                                <td><input class="form-control"/></td>
                            </tr>
                            <tr>
                                <td>Número de folio:</td>
                                <td><input class="form-control"/></td>
                            </tr>
                            <tr>
                                <td>Número de tomo XXXX:</td>
                                <td><select class="form-control overflow-auto">
                                        <option></option>
                                        @foreach($tomo as $t)
                                            <option value="{{$t['cod_tom']}}"> {{$t['tom_numero']}}</option>
                                        @endforeach
                                    </select>
                            </tr>
                            <tr>
                                <td>grado :</td>
                                <td>
                                    <select class="form-control">
                                        @foreach($grado as $g)
                                            <option value="{{$g['cod_gra']}}">{{$g['gra_nombre']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
    </div>
@endsection
