<form action="{{url('enviar dpa')}}" method="POST" enctype="multipart/form-data" id="form_enviar_dpa">
    @csrf
    <input type="hidden" name="cod_fun" value="{{$cod_fun}}"/>

    <div class="modal-content border-bottom-success">
        <div class="modal-header bg-success">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-paper-plane"></i> Enviar a la DPA</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>

        <div class="modal-body">
            <div class="shadow-sm rounded p-2">
                <div class="alert alert-warning mb-3" role="alert">
                    Esta accion marcara al funcionario como <strong>enviado a la DPA</strong>. Si registra un nuevo diploma o titulo, el estado volvera a no enviado.
                </div>

                <p class="mb-2">
                    <span class="text-primary font-italic">Funcionario:</span>
                    <span class="font-weight-bold text-dark">{{$funcionario->fun_nombre}}</span>
                </p>

                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark">Titulos/Diplomas a enviar</label>
                    <div class="border rounded p-2" style="max-height: 220px; overflow-y: auto;">
                        @foreach($documentos as $d)
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="checkbox" class="custom-control-input documento-checkbox" id="doc_envio_{{$d->cod_doc}}" name="documentos_envio[]" value="{{$d->cod_doc}}" checked>
                                <label class="custom-control-label" for="doc_envio_{{$d->cod_doc}}">
                                    <span class="font-weight-bold">{{$d->doc_tipo}}</span> - {{$d->doc_titulo}}
                                    @if($d->doc_enviado_dpa === true || $d->doc_enviado_dpa === 1 || $d->doc_enviado_dpa === 't')
                                        <span class="badge badge-success ml-1">Ya enviado</span>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <small class="form-text text-muted">Puede desmarcar los titulos que no seran enviados en esta remision.</small>
                </div>

                @php
                    $esDocente = strtoupper(trim((string)($funcionario->fun_doc_adm ?? ''))) === 'D';
                    $fechaHoy = date('Y-m-d');
                    $asuntoDefault = $esDocente
                        ? 'REF.: ENTREGA DE DOCUMENTACIÓN ACADÉMICA DOCENTE VERIFICADA Y LEGALIZADA'
                        : 'REF.: ENTREGA DE DOCUMENTACIÓN ACADÉMICA DE FUNCIONARIOS ADMINISTRATIVOS VERIFICADA Y LEGALIZADA';
                    $textoDefault = $esDocente
                        ? 'En cumplimiento a la Resolución Rectoral 489/21 de fecha 25 de junio de 2021 referente a la actualización de documentación académica docente, adjunto a la presente, le envió un ejemplar de documentación de Diplomas/Título verificados y legalizados que corresponden a Docente(s), según el siguiente detalle:'
                        : 'En cumplimiento a la Circular N° 06/2023 de fecha 21 de agosto de 2023 referente a la actualización de documentación académica de funcionarios administrativos de la UMSS que acrediten su formación académica, adjunto a la presente, le envió un ejemplar de documentación de Diplomas-Títulos verificados y legalizados, que corresponden a Administrativo(s), según el siguiente detalle:';
                @endphp

                <div class="form-group mb-3 border rounded p-3">
                    <label class="font-weight-bold text-dark d-block mb-2">
                        <i class="fas fa-edit text-primary"></i> Datos editables de la carta
                    </label>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="carta_fecha">Fecha</label>
                            <input type="date" class="form-control" id="carta_fecha" value="{{$fechaHoy}}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="carta_ref">Referencia</label>
                            <input type="text" class="form-control" id="carta_ref" value="" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="carta_sidoc">Sidoc</label>
                            <input type="text" class="form-control" id="carta_sidoc" value="" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="carta_trato">Trato</label>
                            <input type="text" class="form-control" id="carta_trato" value="Señor" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="carta_nombre_destinatario">Nombre destinatario</label>
                            <input type="text" class="form-control" id="carta_nombre_destinatario" value="" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="carta_cargo_destinatario">Cargo destinatario</label>
                            <input type="text" class="form-control" id="carta_cargo_destinatario" value="" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="carta_estado_destinatario">Estado destinatario</label>
                        <input type="text" class="form-control" id="carta_estado_destinatario" value="Presente" required>
                    </div>

                    <div class="form-group">
                        <label for="carta_asunto">Asunto</label>
                        <input type="text" class="form-control" id="carta_asunto" value="{{$asuntoDefault}}" required>
                    </div>

                    <div class="form-group">
                        <label for="carta_saludo">Saludo</label>
                        <input type="text" class="form-control" id="carta_saludo" value="De mi consideración:" required>
                    </div>

                    <div class="form-group">
                        <label for="carta_texto_principal">Texto principal</label>
                        <textarea class="form-control" id="carta_texto_principal" rows="4" required>{{$textoDefault}}</textarea>
                    </div>

                    <div class="form-group mb-0">
                        <label for="carta_despedida">Despedida</label>
                        <input type="text" class="form-control" id="carta_despedida" value="Sin otro particular, saludo a usted atentamente," required>
                    </div>
                </div>

                <!-- Sección para generar PDF automáticamente -->
                <div class="form-group mb-3 border rounded p-3 bg-light">
                    <label class="font-weight-bold text-dark d-block mb-2">
                        <i class="fas fa-file-pdf text-danger"></i> Generar PDF de control
                    </label>
                    <p class="text-muted small mb-2">
                        Haga clic en "Generar PDF" para crear automáticamente un documento con la tabla de títulos. El PDF se abrirá en una nueva pestaña donde podrá visualizar e imprimir sobre papel membretado.
                    </p>
                    <button type="button" class="btn btn-primary btn-sm" id="btn_generar_pdf">
                        <i class="fas fa-cog"></i> Generar PDF
                    </button>
                    <span id="estado_pdf" class="ml-2"></span>
                </div>

                <!-- Sección para cargar PDF manual (obligatorio) -->
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark">
                        <i class="fas fa-check"></i> Cargar PDF manualmente (obligatorio)
                    </label>
                    <input type="file" class="form-control-file" id="pdf_control" name="pdf_control" accept="application/pdf,.pdf" required>
                    <small class="form-text text-muted">Debe cargar el PDF firmado o final para registrar el envio. Solo archivos PDF. Máximo 5 MB.</small>
                </div>

                <div class="form-group mb-0">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="confirmar_envio" name="confirmar_envio" value="1" required>
                        <label class="custom-control-label" for="confirmar_envio">Confirmo que se realizo el envio a la DPA.</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-success" type="submit" id="btn_confirmar_envio">
                <i class="fas fa-check-circle"></i> Confirmar envio
            </button>
        </div>
    </div>
</form>

<script>
// Generar PDF - Manejo robusto con debugging
(function() {
    function initGenerarPDF() {
        const btnGenerarPDF = document.getElementById('btn_generar_pdf');
        const estadoPDF = document.getElementById('estado_pdf');
        const formEnviarDPA = document.getElementById('form_enviar_dpa');
        const pdfControlInput = document.getElementById('pdf_control');
        
        console.log('Inicializando generador de PDF...');
        console.log('Botón encontrado:', btnGenerarPDF ? 'Sí' : 'No');
        console.log('Estado div encontrado:', estadoPDF ? 'Sí' : 'No');
        
        if (!btnGenerarPDF) {
            console.error('No se encontró el botón con ID: btn_generar_pdf');
            return;
        }
        
        let pdfGenerado = false;

        function obtenerDatosCarta() {
            return {
                fecha: document.getElementById('carta_fecha') ? document.getElementById('carta_fecha').value : '',
                ref: document.getElementById('carta_ref') ? document.getElementById('carta_ref').value : '',
                sidoc: document.getElementById('carta_sidoc') ? document.getElementById('carta_sidoc').value : '',
                trato: document.getElementById('carta_trato') ? document.getElementById('carta_trato').value : '',
                nombre_destinatario: document.getElementById('carta_nombre_destinatario') ? document.getElementById('carta_nombre_destinatario').value : '',
                cargo_destinatario: document.getElementById('carta_cargo_destinatario') ? document.getElementById('carta_cargo_destinatario').value : '',
                estado_destinatario: document.getElementById('carta_estado_destinatario') ? document.getElementById('carta_estado_destinatario').value : '',
                asunto: document.getElementById('carta_asunto') ? document.getElementById('carta_asunto').value : '',
                saludo: document.getElementById('carta_saludo') ? document.getElementById('carta_saludo').value : '',
                texto_principal: document.getElementById('carta_texto_principal') ? document.getElementById('carta_texto_principal').value : '',
                despedida: document.getElementById('carta_despedida') ? document.getElementById('carta_despedida').value : ''
            };
        }

        function limpiarErroresCarta() {
            const campos = document.querySelectorAll('[id^="carta_"]');
            campos.forEach(campo => {
                campo.classList.remove('is-invalid');
                campo.removeAttribute('aria-invalid');

                const contenedor = campo.closest('.form-group');
                if (!contenedor) return;
                const error = contenedor.querySelector('.carta-error-msg');
                if (error) {
                    error.remove();
                }
            });
        }

        function mostrarErrorCampo(inputId, mensaje) {
            const campo = document.getElementById(inputId);
            if (!campo) return;

            campo.classList.add('is-invalid');
            campo.setAttribute('aria-invalid', 'true');

            const contenedor = campo.closest('.form-group');
            if (!contenedor) return;

            const existente = contenedor.querySelector('.carta-error-msg');
            if (existente) {
                existente.textContent = mensaje;
                return;
            }

            const msg = document.createElement('div');
            msg.className = 'invalid-feedback d-block carta-error-msg';
            msg.textContent = mensaje;
            contenedor.appendChild(msg);
        }

        function validarCamposCarta(datos) {
            const etiquetas = {
                fecha: 'Fecha',
                ref: 'Referencia',
                sidoc: 'Sidoc',
                trato: 'Trato',
                nombre_destinatario: 'Nombre destinatario',
                cargo_destinatario: 'Cargo destinatario',
                estado_destinatario: 'Estado destinatario',
                asunto: 'Asunto',
                saludo: 'Saludo',
                texto_principal: 'Texto principal',
                despedida: 'Despedida'
            };

            const ids = {
                fecha: 'carta_fecha',
                ref: 'carta_ref',
                sidoc: 'carta_sidoc',
                trato: 'carta_trato',
                nombre_destinatario: 'carta_nombre_destinatario',
                cargo_destinatario: 'carta_cargo_destinatario',
                estado_destinatario: 'carta_estado_destinatario',
                asunto: 'carta_asunto',
                saludo: 'carta_saludo',
                texto_principal: 'carta_texto_principal',
                despedida: 'carta_despedida'
            };

            limpiarErroresCarta();
            let esValido = true;

            for (const key in etiquetas) {
                const valor = (datos[key] || '').toString().trim();
                if (!valor) {
                    mostrarErrorCampo(ids[key], 'Este campo es obligatorio.');
                    esValido = false;
                }
            }

            if (!esValido) {
                const primerError = document.querySelector('.is-invalid');
                if (primerError) {
                    primerError.focus();
                }
            }

            return esValido;
        }

        // Click en botón Generar PDF
        btnGenerarPDF.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Click en Generar PDF');
            
            // Documentos seleccionados
            const documentosSeleccionados = [];
            document.querySelectorAll('.documento-checkbox:checked').forEach(checkbox => {
                documentosSeleccionados.push(parseInt(checkbox.value));
            });
            
            console.log('Documentos seleccionados:', documentosSeleccionados);

            if (documentosSeleccionados.length === 0) {
                alert('Debe seleccionar al menos un documento');
                return;
            }

            const datosCarta = obtenerDatosCarta();
            if (!validarCamposCarta(datosCarta)) {
                return;
            }

            // Estado: Cargando
            btnGenerarPDF.disabled = true;
            estadoPDF.innerHTML = '<span class="text-info"><i class="fas fa-spinner fa-spin"></i> Generando...</span>';
            
            console.log('Enviando fetch a:', '{{ url("generar pdf dpa") }}');

            // AJAX
            fetch('{{ url("generar pdf dpa") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    cod_fun: {{ $cod_fun }},
                    documentos_envio: documentosSeleccionados,
                    ...datosCarta
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Error HTTP: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Data recibida:', data);
                
                if (data.success) {
                    console.log('PDF generado exitosamente');
                    pdfGenerado = true;
                    
                    // Abrir PDF
                    window.open(data.url, '_blank');
                    
                    // Actualizar estado
                    estadoPDF.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> PDF listo</span>';
                } else {
                    console.error('Error en data:', data.error);
                    estadoPDF.innerHTML = '<span class="text-danger">Error: ' + (data.error || 'Desconocido') + '</span>';
                }
                
                btnGenerarPDF.disabled = false;
            })
            .catch(error => {
                console.error('Error en fetch:', error);
                estadoPDF.innerHTML = '<span class="text-danger">Error al generar PDF: ' + error.message + '</span>';
                btnGenerarPDF.disabled = false;
            });
        });

        // Validar envioDPA
        formEnviarDPA.addEventListener('submit', function(e) {
            const docsSeleccionados = document.querySelectorAll('.documento-checkbox:checked').length;
            
            if (docsSeleccionados === 0) {
                e.preventDefault();
                alert('Seleccione al menos un documento');
                return false;
            }
            
            if (!pdfControlInput.value) {
                e.preventDefault();
                alert('Debe cargar el PDF manualmente para confirmar el envio');
                return false;
            }
        });

        // PDF cargado manualmente
        pdfControlInput.addEventListener('change', function() {
            if (this.value) {
                pdfGenerado = true;
                estadoPDF.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> PDF cargado</span>';
            }
        });

        // Limpia el error visual de cada campo cuando el usuario escribe.
        const camposCarta = document.querySelectorAll('[id^="carta_"]');
        camposCarta.forEach(campo => {
            campo.addEventListener('input', function() {
                if ((this.value || '').toString().trim() !== '') {
                    this.classList.remove('is-invalid');
                    this.removeAttribute('aria-invalid');
                    const contenedor = this.closest('.form-group');
                    if (!contenedor) return;
                    const error = contenedor.querySelector('.carta-error-msg');
                    if (error) {
                        error.remove();
                    }
                }
            });
        });
    }

    // Ejecutar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGenerarPDF);
    } else {
        initGenerarPDF();
    }
})();
</script>


