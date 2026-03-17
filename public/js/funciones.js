//==================Funciones en servicios
function cargarDatos(ruta,panel){
    $('#'+panel).html("<br/><br/><div class='d-flex justify-content-center text-warning'><div class='spinner-border' role='status'> <span class='visually-hidden'></span></div><span class='text-white font-weight-bold'>&nbsp;  Cargando ...</span></div>");
    $.ajax({
        url: ruta,
        type: 'GET',
        data:'',
        success: function (resp) {
            $('#'+panel).html(resp);
        },
        error: function () {
            $('#'+panel).html("<span class='text-white font-weight-bold bg-danger rounded p-1'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");
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
            error:function(resp) {
                $('#'+panel).html("<br/><div class='alert-danger p-2 rounded'><span class='font-weight-bold'>Error: </span>Error : Quizá no tenga permisos para esta acción</div>");
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




