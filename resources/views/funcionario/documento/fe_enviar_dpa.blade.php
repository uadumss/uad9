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

                <!-- Sección para cargar PDF manual (opcional) -->
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark">
                        <i class="fas fa-check"></i> O cargar PDF manualmente (opcional)
                    </label>
                    <input type="file" class="form-control-file" id="pdf_control" name="pdf_control" accept="application/pdf,.pdf">
                    <small class="form-text text-muted">Si no genera el PDF automáticamente, puede cargar uno aquí. Solo archivos PDF. Máximo 5 MB.</small>
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
                    documentos_envio: documentosSeleccionados
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
            
            if (!pdfGenerado && !pdfControlInput.value) {
                e.preventDefault();
                alert('Genere un PDF o cargue uno manualmente');
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
    }

    // Ejecutar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGenerarPDF);
    } else {
        initGenerarPDF();
    }
})();
</script>


