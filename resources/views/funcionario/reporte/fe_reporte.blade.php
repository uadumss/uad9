@extends('marco.pagina')
@section('contenido')
    <style>
        .table-reporte {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #dee2e6;
        }
        .table-reporte tr {
            border: 1px solid #dee2e6;
        }
        .table-reporte th,
        .table-reporte td {
            border: 1px solid #dee2e6 !important;
            padding: 0.5rem !important;
            text-align: left;
        }
        .table-reporte th {
            font-weight: 600;
            background-color: #f8f9fa;
        }
        .table-reporte .border-right {
            border-right: 1px solid #dee2e6 !important;
        }
        .table-responsive .table-reporte {
            margin-bottom: 0;
        }
    </style>
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
    @if(Session::has('errores'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('errores') !!}
        </div>
    @endif

    @if(isset($fallas) && count($fallas)>0)
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
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
    <div class="card">
        <div class="card shadow mb-4">
            <div class="card-header py-3 alert-primary">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h5 class=""><i class="fas fa-university"></i>&nbsp;Reportes</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="">
                    <div class="">

                        <a href="{{url('reporte dya')}}" class="btn btn-outline-info btn-sm text-dark mt-1 shadow-sm"><i class="fas fa-recycle"></i> Limpiar formulario</a> &nbsp;&nbsp;
                        <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                            <h5 class="text-white text-center">Reportes Docente - Administrativo</h5>
                        </div>
                                <hr class="sidebar-divider">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="form_reporte" action="{{url('procesar reporte dya')}}" method="POST">
                                    @csrf
                                    <div>
                                        <div class="bg-info centrar_bloque p-1 col-md-12 rounded shadow">
                                            <h5 class="text-white text-center">Formulario de reporte</h5>
                                        </div>
                                        <hr class="sidebar-divider"/>
                                        <div class="table-responsive">
                                            <table class="table-reporte">
                                            <tr>
                                                <td class="text-dark font-italic text-right">Tipo funcionario : </td>
                                                <td colspan="3">
                                                    <select name="funcionario" class="custom-select custom-select-sm">
                                                        <option></option>
                                                        <option value="D">Docente</option>
                                                        <option value="A">Adminitrativo</option>
                                                        <option value="E">Ambos</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr class="text-dark font-italic">
                                                <td></td>
                                                <th class="border-right">Con</th>
                                                <th class="border-right">Sin</th>
                                                <th class="border-right">Legalizado</th>
                                                <th class="border-right">Verificado</th>
                                                <th class="border-right">Doc. Umss</th>
                                            </tr>
                                            <tr>
                                                <td class="text-dark font-italic text-right">Folder presentado <span class="text-danger">*</span> : </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="folder" checked>
                                                </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="nofolder">
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-dark font-italic text-right">Bachiller : </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="bachiller">
                                                </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="nobachiller">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="lbachiller">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nlbachiller">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="vbachiller">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nvbachiller">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="ubachiller">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nubachiller">
                                                </td>

                                            </tr>
                                            <tr>
                                                <td class="text-dark font-italic text-right">Técnico medio : </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="tmedio">
                                                </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="notmedio">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="ltmedio">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nltmedio">

                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="vtmedio">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nvtmedio">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="utmedio">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nutmedio">
                                                </td>

                                            </tr>
                                            <tr>
                                                <td class="text-dark font-italic text-right">Técnico superior : </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="tsuperior">
                                                </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="notsuperior">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="ltsuperior">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nltsuperior">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="vtsuperior">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nvtsuperior">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="utsuperior">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nutsuperior">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-dark font-italic text-right">Diploma Académico : </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="academico">
                                                </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="noacademico">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="lacademico">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nlacademico">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="vacademico">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nvacademico">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="uacademico">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nuacademico">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-dark font-italic text-right">Titulo Profesional: </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="profesional">
                                                </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="noprofesional">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="lprofesional">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nlprofesional">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="vprofesional">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nvprofesional">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="uprofesional">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nuprofesional">
                                                </td>

                                            </tr>
                                            <tr>
                                                <td class="text-dark font-italic text-right">Educación superior : </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="ddu">
                                                </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="noddu">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="lddu">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nlddu">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="vddu">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nvddu">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="uddu">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nuddu">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-dark font-italic text-right">Diplomado : </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="diplomado">
                                                </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="nodiplomado">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="ldiplomado">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nldiplomado">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="vdiplomado">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nvdiplomado">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="udiplomado">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nudiplomado">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-dark font-italic text-right">Especialidad : </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="especialidad">
                                                </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="noespecialidad">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="lespecialidad">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nlespecialidad">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="vespecialidad">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nvespecialidad">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="uespecialidad">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nuespecialidad">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-dark font-italic text-right">Maestria : </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="maestria">
                                                </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="nomaestria">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="lmaestria">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nlmaestria">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="vmaestria">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nvmaestria">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="umaestria">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="numaestria">
                                                </td>

                                            </tr>
                                            <tr>
                                                <td class="text-dark font-italic text-right">Doctorado : </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="doctorado">
                                                </td>
                                                <td class="border-right">
                                                    <input type="checkbox" name="nodoctorado">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="ldoctorado">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nldoctorado">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="vdoctorado">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nvdoctorado">
                                                </td>
                                                <td class="border-right">
                                                    <span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Si </span><input type="checkbox" name="udoctorado">
                                                    <span class="text-danger font-italic font-weight-bold" style="font-size: 14px;">No </span><input type="checkbox" name="nudoctorado">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right"><span class="text-success font-italic font-weight-bold" style="font-size: 14px;">Reporte excel <i class="fas fa-file-excel"></i> :</span></th>
                                                <td><input type="checkbox" name="excel" id="excel"></td>
                                                <td colspan="4" class="text-right pr-3">
                                                    <button class="btn btn-primary btn-sm" type="button" onclick="enviar('form_reporte','{{url('procesar reporte dya')}}','panel_reporte')">Generar reporte</button>
                                                </td>
                                            </tr>
                                        </table>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 shadow-lg rounded border p-3 mt-4" id="panel_reporte" style="overflow-x: auto;">

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
