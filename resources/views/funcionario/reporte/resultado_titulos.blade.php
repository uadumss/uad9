<br/>
<div class="alert-success centrar_bloque p-1 col-md-3 rounded shadow">
    <h5 class="text-dark text-center">Resultado de la consulta</h5>
</div>
<span class="text-dark">
    <span>Cantidad encontrada : </span><span class="font-weight-bold text-primary">{{sizeof($resultado)}}</span> | 

    <?php
        if($tipo_funcionario!=''){
            if($tipo_funcionario=='A'){
                $tipo_funcionario='ADMINISTRATIVO';
            }else{
                if($tipo_funcionario=='D'){
                    $tipo_funcionario='DOCENTE';
            }else{
                    $tipo_funcionario='DOCENTE Y ADMINISTRATIVO';
                }
            }
    }?>
    @if($tipo_funcionario!='')
        <span> Tipo de funcionario : </span><span class="font-weight-bold text-primary">{{$tipo_funcionario}}</span>
    @endif
</span>

<hr class="sidebar-divider">
<div style="overflow-x: auto; border-top: 3px solid #ddd; border-bottom: 3px solid #ddd;">
    <table class="table table-sm table-hover" cellspacing="0" style="font-size: 0.85em; min-width: 1600px;">
            <thead>
            <tr class="bg-gray-600 text-white">
                <th style="width: 3%;">Nº</th>
                <th style="width: 10%;">Nombre</th>
                <th style="width: 7%;">CI</th>
                <th style="width: 8%;">Facultad</th>
                <th style="width: 8%;">Carrera</th>
                <th style="width: 9%;">Tipo de Funcionario</th>
                <th style="width: 10%;">Tipo de Documento</th>
                <th style="width: 8%;">Fecha de Emisión</th>
                <th style="width: 12%;">Título/Diploma</th>
                <th style="width: 6%;">Es Tesis</th>
                <th style="width: 12%;">Título de Tesis</th>
                <th style="width: 12%;">Universidad</th>
                <th style="width: 9%;">Tipo de Universidad</th>
                <th style="width: 6%;">Edu Superior</th>
                <th style="width: 6%;">Revalidación</th>
                <th style="width: 8%;">Verificado</th>
                <th style="width: 6%;">Legalizado</th>
                <th style="width: 6%;">UMSS</th>
            </tr>
            </thead>
            <tbody>
            <?php $j=1; $funcionario_num=0;?>
            @foreach($resultado as $f)
                @if(isset($f->documentos) && count($f->documentos) > 0)
                    <?php $primer_doc = true; $funcionario_num++; ?>
                    {{-- Mostrar estado del folder como fila separada de resumen al inicio --}}
                    @if(isset($f->estado_carpeta))
                        <tr style="background-color: #e8f4f8; border-top: 3px solid #0c5460; border-bottom: 3px solid #0c5460;">
                            <td style="font-weight: bold; color: #0c5460; padding: 10px; vertical-align: middle;">{{ $funcionario_num }}</td>
                            <td colspan="5" style="font-weight: bold; color: #0c5460; padding: 10px; vertical-align: middle;">{{ $f->fun_nombre }} ({{ $f->fun_ci }})</td>
                            <td colspan="12" style="padding: 10px; vertical-align: top; text-align: right;">
                                <div style="text-align: right;">
                                    <div style="margin-bottom: 5px;">
                                        <strong style="color: #0c5460; margin-right: 10px;">Estado del Folder:</strong>
                                        @if($f->estado_carpeta['completo'])
                                            <span style="background-color: #d4edda; color: #155724; padding: 5px 10px; border-radius: 4px; font-weight: bold;">COMPLETO</span>
                                        @else
                                            <span style="background-color: #f8d7da; color: #721c24; padding: 5px 10px; border-radius: 4px; font-weight: bold;">INCOMPLETO</span>
                                        @endif
                                    </div>
                                    @if(!$f->estado_carpeta['completo'])
                                        <div style="color: #721c24; font-size: 0.85em; margin-top: 3px;">
                                            Faltan: {{ implode(', ', $f->estado_carpeta['faltantes']) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endif
                    @foreach($f->documentos as $index => $doc)
                        <tr class="">
                            @if($primer_doc)
                                <td rowspan="{{ count($f->documentos) }}">{{ $funcionario_num }}</td>
                                <td rowspan="{{ count($f->documentos) }}">{{ $f->fun_nombre }}</td>
                                <td rowspan="{{ count($f->documentos) }}">{{ $f->fun_ci }}</td>
                                <td rowspan="{{ count($f->documentos) }}">{{ $f->fun_facultad }}</td>
                                <td rowspan="{{ count($f->documentos) }}">{{ $f->fun_carrera }}</td>
                                <td rowspan="{{ count($f->documentos) }}">
                                    @switch($f->fun_doc_adm)
                                        @case('D')
                                            DOCENTE
                                        @break
                                        @case('A')
                                            ADMINISTRATIVO
                                        @break
                                        @case('E')
                                            DOCENTE Y ADMINISTRATIVO
                                        @break
                                        @default
                                            {{ $f->fun_doc_adm }}
                                    @endswitch
                                </td>
                                <?php $primer_doc = false; ?>
                            @endif
                            <td>{{ $doc['tipo'] }}</td>
                            <td>{{ $doc['fecha_emision'] ? date('d/m/Y', strtotime($doc['fecha_emision'])) : '' }}</td>
                            <td>{{ $doc['titulo'] }}</td>
                            <td>{{ $doc['es_tesis'] }}</td>
                            <td>{{ $doc['titulo_tesis'] }}</td>
                            <td>{{ $doc['universidad'] }}</td>
                            <td>{{ $doc['tipo_universidad'] }}</td>
                            <td>{{ $doc['edu_superior'] }}</td>
                            <td @if($doc['revalida'] === 'FALTA REVALIDACION') style="background-color: #ffe6e6; color: #c41e3a; font-weight: bold;" @endif>
                                {{ $doc['revalida'] === 'FALTA REVALIDACION' ? 'FALTA REVALIDACION' : $doc['revalida'] }}
                            </td>
                            <td @if($doc['verificado'] === 'Pendiente') style="background-color: #fff3cd; color: #856404; font-weight: bold;" @else style="background-color: #d4edda; color: #155724; font-weight: bold;" @endif>
                                {{ $doc['verificado'] }}
                            </td>
                            <td @if($doc['legalizado'] === 'Si') style="background-color: #d4edda; color: #155724; font-weight: bold; text-align: center;" @else style="background-color: #fff3cd; color: #856404; text-align: center;" @endif>
                                {{ $doc['legalizado'] }}
                            </td>
                            <td style="text-align: center;">
                                {{ $doc['umss'] }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <?php $funcionario_num++; ?>
                    <tr class="">
                        <td>{{ $funcionario_num }}</td>
                        <td>{{ $f->fun_nombre }}</td>
                        <td>{{ $f->fun_ci }}</td>
                        <td>{{ $f->fun_facultad }}</td>
                        <td>{{ $f->fun_carrera }}</td>
                        <td>
                            @switch($f->fun_doc_adm)
                                @case('D')
                                    DOCENTE
                                @break
                                @case('A')
                                    ADMINISTRATIVO
                                @break
                                @case('E')
                                    DOCENTE Y ADMINISTRATIVO
                                @break
                                @default
                                    {{ $f->fun_doc_adm }}
                            @endswitch
                        </td>
                        <td colspan="10" class="text-muted">Sin documentos registrados</td>
                        <td style="background-color: #f8d7da; color: #721c24; font-weight: bold;">
                            <span style="background-color: #f8d7da; color: #721c24; padding: 5px 10px; border-radius: 4px; font-weight: bold; display: block;">INCOMPLETO</span>
                            <span style="color: #721c24; font-size: 0.9em; margin-top: 5px; display: block;">Sin documentos</span>
                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
    </table>
</div>

