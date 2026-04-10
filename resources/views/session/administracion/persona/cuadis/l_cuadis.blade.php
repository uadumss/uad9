<div class="card-body">
    @php
        $resultadoLista=$resultadoLista ?? null;
        $resolucionNumero=(string)($resolucionNumero ?? '');
        $resolucionAnio=(string)($resolucionAnio ?? '');
        $personasTabla=is_array($personasTabla ?? null) ? $personasTabla : [];
        if(sizeof($personasTabla)===0){
            $personasTabla=[
                ['apellido'=>'','nombre'=>'','ci'=>''],
                ['apellido'=>'','nombre'=>'','ci'=>''],
                ['apellido'=>'','nombre'=>'','ci'=>''],
            ];
        }
        $habilitadoSeleccion=(string)($habilitado ?? '1');
    @endphp

    @if(Session::has('exito cuadis'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="font-weight-bold">{!! session('exito cuadis') !!}</span>
        </div>
    @endif

    <style>
        .cuadis-card{border:1px solid #d8dce6;border-radius:.5rem;background:#fff;box-shadow:0 .125rem .5rem rgba(58,59,69,.08)}
        .cuadis-toolbar .btn{margin-left:.25rem}
        #tabla_cuadis_personas thead th,#tabla_resultado_cuadis thead th{position:sticky;top:0;z-index:2;background:#eef2f8}
        #tabla_cuadis_personas .fila-indice{width:46px;text-align:center;font-weight:700;color:#6b7280}
        #tabla_cuadis_personas .fila-cuadis-persona.fila-incompleta td{background:#fff7e6}
        .cuadis-ayuda{font-size:.82rem;color:#6c757d}
    </style>

    @if(Session::has('error cuadis'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="font-weight-bold">{!! session('error cuadis') !!}</span>
        </div>
    @endif

    <div class="bg-primary centrar_bloque p-1 col-md-4 rounded shadow">
        <h5 class="text-white text-center mb-0">Registro CUADIS Por Lista</h5>
    </div>
    <hr class="sidebar-divider">

    <div class="row">
        <div class="col-md-5">
            <div class="cuadis-card p-3">
                <h6 class="text-primary font-weight-bold">Cargar Personas CUADIS</h6>
                <form id="form_registro_cuadis_lista">
                    @csrf
                    <table class="table table-sm mb-2">
                        <tr>
                            <th class="text-right font-italic" style="width: 38%;">Estado CUADIS:</th>
                            <td>
                                <select class="form-control form-control-sm" name="habilitado">
                                    <option value="1" @if($habilitadoSeleccion==='1') selected @endif>ACTIVO</option>
                                    <option value="0" @if($habilitadoSeleccion==='0') selected @endif>INACTIVO</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Resolución:</th>
                            <td>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">N°</span>
                                    </div>
                                    <input type="text"
                                           class="form-control"
                                           name="resolucion_numero"
                                           maxlength="40"
                                           required
                                           value="{{$resolucionNumero}}"
                                           placeholder="Ej: 123" />
                                    <div class="input-group-prepend input-group-append">
                                        <span class="input-group-text">/</span>
                                    </div>
                                    <input type="text"
                                           class="form-control"
                                           name="resolucion_anio"
                                           maxlength="4"
                                           pattern="[0-9]{4}"
                                           required
                                           value="{{$resolucionAnio}}"
                                           placeholder="AAAA" />
                                </div>
                            </td>
                        </tr>
                    </table>

                    <div class="form-group mb-2">
                        <div class="d-flex align-items-center justify-content-between mb-2 cuadis-toolbar">
                            <label class="font-weight-bold text-primary mb-0">Tabla de personas</label>
                            <div class="text-right">
                                <a href="#" class="btn btn-outline-primary btn-sm" onclick="return agregarFilaCuadis();">
                                    <i class="fas fa-plus"></i> Agregar fila
                                </a>
                                <a href="#" class="btn btn-outline-primary btn-sm" onclick="return agregarFilaCuadis(5);">
                                    +5
                                </a>
                                <a href="#" class="btn btn-outline-secondary btn-sm" onclick="return limpiarFilasVaciasCuadis();">
                                    Limpiar vacías
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm" onclick="return limpiarTodoCuadis();">
                                    Limpiar todo
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive border rounded" style="max-height: 430px; overflow-y: auto;">
                            <table class="table table-sm table-bordered mb-0" id="tabla_cuadis_personas">
                                <thead class="thead-light">
                                <tr>
                                    <th class="fila-indice">#</th>
                                    <th style="width: 33%;">Apellidos</th>
                                    <th style="width: 33%;">Nombres</th>
                                    <th style="width: 22%;">CI</th>
                                    <th style="width: 8%;"></th>
                                </tr>
                                </thead>
                                <tbody id="tbody_cuadis_personas">
                                @foreach($personasTabla as $i=>$fila)
                                    <tr class="fila-cuadis-persona">
                                        <td class="fila-indice">{{$i+1}}</td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" data-campo="apellido" value="{{trim((string)($fila['apellido'] ?? ''))}}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" data-campo="nombre" value="{{trim((string)($fila['nombre'] ?? ''))}}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" data-campo="ci" maxlength="20" value="{{trim((string)($fila['ci'] ?? ''))}}">
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="#" class="btn btn-outline-danger btn-sm" onclick="return eliminarFilaCuadis(this);" title="Eliminar fila">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="cuadis-ayuda mt-2">Atajo: presione Ctrl + Enter para procesar la tabla. Si la persona no existe, se crea automáticamente en personas.</div>
                    </div>
                </form>

                <div id="alerta_lista_cuadis" class="mb-2"></div>
                <div class="text-right">
                    <a href="#" id="btn_procesar_cuadis" class="btn btn-primary btn-sm" onclick="return guardarListaCuadis();">
                        <i class="fas fa-save"></i> Procesar lista CUADIS
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="cuadis-card p-3" id="panel_resultado_cuadis_lista">
                <h6 class="text-danger font-weight-bold">Resultado de la carga</h6>
                <hr class="sidebar-divider mt-1">

                @php
                    $procesados=$resultadoLista['procesados'] ?? [];
                @endphp
                <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                    <table class="table table-sm table-striped table-bordered mb-0" id="tabla_resultado_cuadis">
                        <thead class="thead-light">
                        <tr>
                            <th style="width: 20%;">CI</th>
                            <th>Nombre</th>
                            <th style="width: 22%;">Resultado</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($procesados as $item)
                            <tr>
                                <td>{{$item['ci'] ?? ''}}</td>
                                <td>{{$item['nombre'] ?? ''}}</td>
                                <td>{{$item['accion'] ?? ''}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted font-italic py-3">Sin registros procesados.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function guardarListaCuadis(){
        var form=$('#form_registro_cuadis_lista');
        if(!form.length){
            return false;
        }

        var numero=($.trim(form.find('input[name="resolucion_numero"]').val()) || '');
        var anio=($.trim(form.find('input[name="resolucion_anio"]').val()) || '');
        var panelError=$('#alerta_lista_cuadis');
        var filas=$('#tbody_cuadis_personas').find('tr.fila-cuadis-persona');
        var totalFilasValidas=0;
        var filaIncompleta=0;

        limpiarMarcasErrorCuadis();

        if(numero===''){
            panelError.html('<div class="alert alert-danger py-2 mb-0">Debe ingresar el número de resolución CUADIS.</div>');
            return false;
        }
        if(!/^\d{4}$/.test(anio)){
            panelError.html('<div class="alert alert-danger py-2 mb-0">Debe ingresar un año válido de 4 dígitos.</div>');
            return false;
        }

        filas.each(function(indice){
            var fila=$(this);
            var apellido=($.trim(fila.find('input[data-campo="apellido"]').val()) || '');
            var nombre=($.trim(fila.find('input[data-campo="nombre"]').val()) || '');
            var ci=($.trim(fila.find('input[data-campo="ci"]').val()) || '');

            if(apellido==='' && nombre==='' && ci===''){
                return;
            }

            if(apellido==='' || nombre==='' || ci===''){
                if(filaIncompleta===0){
                    filaIncompleta=indice+1;
                }
                fila.addClass('fila-incompleta');
                if(apellido===''){
                    fila.find('input[data-campo="apellido"]').addClass('is-invalid');
                }
                if(nombre===''){
                    fila.find('input[data-campo="nombre"]').addClass('is-invalid');
                }
                if(ci===''){
                    fila.find('input[data-campo="ci"]').addClass('is-invalid');
                }
                return;
            }

            totalFilasValidas++;
        });

        if(filaIncompleta>0){
            panelError.html('<div class="alert alert-danger py-2 mb-0">La fila '+filaIncompleta+' está incompleta. Debe llenar Apellidos, Nombres y CI.</div>');
            return false;
        }

        if(totalFilasValidas===0){
            panelError.html('<div class="alert alert-danger py-2 mb-0">Debe completar al menos una fila de la tabla de personas.</div>');
            return false;
        }

        reindexarFilasCuadis();
        panelError.html('<div class="alert alert-info py-2 mb-0">Procesando lista CUADIS...</div>');
        enviarFormularioCuadis(form,panelError);
        return false;
    }

    function obtenerMensajeErrorCuadis(xhr){
        if(xhr && xhr.status===422 && xhr.responseJSON && xhr.responseJSON.errors){
            var errores=[];
            Object.keys(xhr.responseJSON.errors).forEach(function(campo){
                var lista=xhr.responseJSON.errors[campo] || [];
                if(Array.isArray(lista)){
                    errores=errores.concat(lista);
                }
            });
            if(errores.length){
                return errores.join(' ');
            }
        }

        if(xhr && xhr.responseJSON && xhr.responseJSON.message){
            return xhr.responseJSON.message;
        }

        if(xhr && xhr.status===419){
            return 'La sesión expiró. Recargue la página e intente nuevamente.';
        }
        if(xhr && xhr.status===403){
            return 'No tiene permisos para esta acción.';
        }
        if(xhr && xhr.status===404){
            return 'No se encontró la ruta solicitada.';
        }

        return 'No se pudo procesar la lista CUADIS. Corrija los datos e intente nuevamente.';
    }

    function enviarFormularioCuadis(form,panelError){
        var btnProcesar=$('#btn_procesar_cuadis');
        btnProcesar.addClass('disabled').attr('aria-disabled','true');

        $.ajax({
            type:'POST',
            url:'{{url("g_cuadis")}}',
            data:form.serialize(),
            success:function(resp){
                $('#panel_persona').html(resp);
            },
            error:function(xhr){
                var mensaje=obtenerMensajeErrorCuadis(xhr);
                panelError.html('<div class="alert alert-danger py-2 mb-0">'+mensaje+'</div>');
            },
            complete:function(){
                btnProcesar.removeClass('disabled').removeAttr('aria-disabled');
            }
        });
    }

    function limpiarMarcasErrorCuadis(){
        $('#tbody_cuadis_personas').find('tr.fila-cuadis-persona').removeClass('fila-incompleta');
        $('#tbody_cuadis_personas').find('input').removeClass('is-invalid');
    }

    function reindexarFilasCuadis(){
        $('#tbody_cuadis_personas').find('tr.fila-cuadis-persona').each(function(index){
            var fila=$(this);
            fila.find('.fila-indice').text(index+1);
            fila.find('input[data-campo="apellido"]').attr('name','personas['+index+'][apellido]');
            fila.find('input[data-campo="nombre"]').attr('name','personas['+index+'][nombre]');
            fila.find('input[data-campo="ci"]').attr('name','personas['+index+'][ci]');
        });
    }

    function crearFilaCuadisHtml(){
        return $('<tr class="fila-cuadis-persona">'+
            '<td class="fila-indice"></td>'+
            '<td><input type="text" class="form-control form-control-sm" data-campo="apellido"></td>'+
            '<td><input type="text" class="form-control form-control-sm" data-campo="nombre"></td>'+
            '<td><input type="text" class="form-control form-control-sm" data-campo="ci" maxlength="20"></td>'+
            '<td class="text-center align-middle">'+
                '<a href="#" class="btn btn-outline-danger btn-sm" onclick="return eliminarFilaCuadis(this);" title="Eliminar fila">'+
                    '<i class="fas fa-trash"></i>'+
                '</a>'+
            '</td>'+
        '</tr>');
    }

    function agregarFilaCuadis(cantidad){
        cantidad=parseInt(cantidad,10) || 1;
        var ultimaFilaCreada=null;
        for(var i=0;i<cantidad;i++){
            ultimaFilaCreada=crearFilaCuadisHtml();
            $('#tbody_cuadis_personas').append(ultimaFilaCreada);
        }

        if(ultimaFilaCreada){
            ultimaFilaCreada.find('input[data-campo="apellido"]').trigger('focus');
        }

        reindexarFilasCuadis();
        return false;
    }

    function limpiarFilasVaciasCuadis(){
        $('#tbody_cuadis_personas').find('tr.fila-cuadis-persona').each(function(){
            var fila=$(this);
            var apellido=($.trim(fila.find('input[data-campo="apellido"]').val()) || '');
            var nombre=($.trim(fila.find('input[data-campo="nombre"]').val()) || '');
            var ci=($.trim(fila.find('input[data-campo="ci"]').val()) || '');
            if(apellido==='' && nombre==='' && ci===''){
                if($('#tbody_cuadis_personas').find('tr.fila-cuadis-persona').length>1){
                    fila.remove();
                }
            }
        });

        reindexarFilasCuadis();
        return false;
    }

    function limpiarTodoCuadis(){
        if(!confirm('¿Desea limpiar toda la tabla de personas?')){
            return false;
        }

        var tbody=$('#tbody_cuadis_personas');
        tbody.html('');
        agregarFilaCuadis(3);
        $('#alerta_lista_cuadis').html('');
        limpiarMarcasErrorCuadis();
        return false;
    }

    function normalizarTextoCuadis(valor){
        return valor.toString().trim().replace(/\s+/g,' ').toUpperCase();
    }

    function normalizarCiCuadis(valor){
        return valor.toString().trim().replace(/\s+/g,' ').toUpperCase();
    }

    function eliminarFilaCuadis(btn){
        var filas=$('#tbody_cuadis_personas').find('tr.fila-cuadis-persona');
        if(filas.length<=1){
            var fila=$(btn).closest('tr');
            fila.find('input[data-campo="apellido"]').val('');
            fila.find('input[data-campo="nombre"]').val('');
            fila.find('input[data-campo="ci"]').val('');
            reindexarFilasCuadis();
            return false;
        }

        $(btn).closest('tr').remove();
        reindexarFilasCuadis();
        return false;
    }

    $(function(){
        reindexarFilasCuadis();
        $('#tbody_cuadis_personas').on('keydown','input',function(evento){
            if(evento.ctrlKey===true && evento.key==='Enter'){
                evento.preventDefault();
                guardarListaCuadis();
                return false;
            }

            if(evento.key==='Enter' && $(evento.target).attr('data-campo')==='ci'){
                evento.preventDefault();
                var filaActual=$(evento.target).closest('tr');
                var filaSiguiente=filaActual.next('.fila-cuadis-persona');
                if(!filaSiguiente.length){
                    agregarFilaCuadis();
                    return false;
                }
                filaSiguiente.find('input[data-campo="apellido"]').trigger('focus');
                return false;
            }
        });

        $('#tbody_cuadis_personas').on('blur','input[data-campo="apellido"], input[data-campo="nombre"]',function(){
            $(this).val(normalizarTextoCuadis($(this).val()));
        });

        $('#tbody_cuadis_personas').on('blur','input[data-campo="ci"]',function(){
            $(this).val(normalizarCiCuadis($(this).val()));
        });
    });
</script>
