//==================Funciones en servicios
function obtenerDetalleErrorHttp(xhr){
    if(!xhr){
        return 'Error desconocido.';
    }

    if(xhr.responseJSON && xhr.responseJSON.message){
        return xhr.responseJSON.message;
    }

    if(typeof xhr.responseText === 'string' && xhr.responseText.trim() !== ''){
        return xhr.responseText.trim();
    }

    return 'Sin detalle disponible.';
}

function cargarDatos(ruta,panel){
    $('#'+panel).html("<br/><br/><div class='d-flex justify-content-center text-warning'><div class='spinner-border' role='status'> <span class='visually-hidden'></span></div><span class='text-white font-weight-bold'>&nbsp;  Cargando ...</span></div>");
    $.ajax({
        url: ruta,
        type: 'GET',
        data:'',
        success: function (resp) {
            $('#'+panel).html(resp);
        },
        error: function (xhr) {
            console.error('Error al cargar la ventana AJAX:', {
                ruta: ruta,
                status: xhr ? xhr.status : null,
                statusText: xhr ? xhr.statusText : null,
                response: obtenerDetalleErrorHttp(xhr)
            });

            var mensaje='Ocurrio un error interno al cargar la ventana.';
            if(xhr && xhr.status===403){
                mensaje='No tiene permisos para esta acción.';
            }else if(xhr && xhr.status===404){
                mensaje='No se encontro la ruta solicitada.';
            }else if(xhr && xhr.status===419){
                mensaje='La sesion expiro. Recargue la pagina e intente nuevamente.';
            }else if(xhr && xhr.status===500){
                mensaje='Error interno del servidor.';
            }
            $('#'+panel).html("<span class='text-white font-weight-bold bg-danger rounded p-1'>"+mensaje+"</span>");
        }
    });
}
function cargarDatosTitulo(ruta,panel,fila){
    $('#'+panel).html("<br/><br/><div class='d-flex justify-content-center text-danger'><div class='spinner-border' role='status'> <span class='visually-hidden'></span></div></div>");
    $('#panel_error_archivo').hide();
    $.ajax({
        url: ruta,
        type: 'GET',
        data:'',
        success: function (resp) {
            $('#'+panel).html(resp);
            $('#fila').val(fila);
        },
        error: function () {
            alert('No se puede ejecutar la petición');
        }
    });
}
function enviar(formulario,ruta,panel){
    if (document.getElementById("excel") !== null && document.getElementById("excel").checked) {
        $("#"+formulario).submit();
    }
    else {
        $.ajax({
            type: "POST",
            url: ruta,
            data: $("#"+formulario).serialize(), // Adjuntar los campos del formulario enviado.
            beforeSend: function () {
                $('#'+panel).html("<br/><br/><div class='d-flex justify-content-center text-danger'><div class='spinner-border' role='status'> <span class='visually-hidden'></span></div><span class='text-white font-weight-bold'>&nbsp;  Cargando ...</span></div>");
            },
            success: function(resp)
            {
                $('#'+panel).html(resp);
            },
            error:function(xhr) {
                console.error('Error en envio AJAX:', {
                    ruta: ruta,
                    formulario: formulario,
                    status: xhr ? xhr.status : null,
                    statusText: xhr ? xhr.statusText : null,
                    response: obtenerDetalleErrorHttp(xhr)
                });

                var mensaje='Error interno al procesar la solicitud.';

                if(xhr && xhr.status===422){
                    if(xhr.responseJSON && xhr.responseJSON.errors){
                        var errores=[];
                        Object.keys(xhr.responseJSON.errors).forEach(function(campo){
                            var lista=xhr.responseJSON.errors[campo] || [];
                            if(Array.isArray(lista)){
                                errores=errores.concat(lista);
                            }
                        });
                        if(errores.length){
                            mensaje=errores.join(' ');
                        }
                    }else if(xhr.responseJSON && xhr.responseJSON.message){
                        mensaje=xhr.responseJSON.message;
                    }else{
                        mensaje='Los datos enviados no son validos.';
                    }
                }else if(xhr && xhr.status===419){
                    mensaje='La sesion expiro. Recargue la pagina e intente nuevamente.';
                }else if(xhr && xhr.status===403){
                    mensaje='No tiene permisos para esta accion.';
                }else if(xhr && xhr.status===404){
                    mensaje='No se encontro la ruta solicitada.';
                }else if(xhr && xhr.status===500){
                    mensaje='Error interno del servidor.';
                }else if(xhr && xhr.responseJSON && xhr.responseJSON.message){
                    mensaje=xhr.responseJSON.message;
                }

                $('#'+panel).html("<br/><div class='alert-danger p-2 rounded'><span class='font-weight-bold'>Error: </span>"+mensaje+"</div>");
            }
        });
    }
}
function enviarExcel(formulario,ruta,panel){

    $.ajax({
        type: "POST",
        url: ruta,
        data: $("#"+formulario).serialize(), // Adjuntar los campos del formulario enviado.
        beforeSend: function () {
            $('#'+panel).html("<br/><br/><div class='d-flex justify-content-center text-danger'><div class='spinner-border' role='status'> <span class='visually-hidden'></span></div><span class='text-white font-weight-bold'>&nbsp;  Cargando ...</span></div>");
        },
        success: function(resp)
        {
            var window = window.open("https://www.google.com", "_blank");
            window.focus();
        },
        error:function(resp) {
            $('#'+panel).html("<br/><div class='alert-danger p-2 rounded'><span class='font-weight-bold'>Error: </span>Error : Quizá no tenga permisos para esta acción</div>");
        }
    });
}




