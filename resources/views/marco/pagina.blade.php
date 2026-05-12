<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SID</title>
    <link href="{{url('img/icon/sid.png')}}" rel="icon">
    <link href="{{url('img/icon/sid.png')}}" rel="apple-touch-icon">
    <!-- Custom fonts for this template-->
    <link href="{{url('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <!--<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">-->
    <!-- Custom styles for this template-->
    <link href="{{url('css/sb-admin-2.css')}}" rel="stylesheet">
    <link href="{{url('css/sistema.css')}}" rel="stylesheet">
    <link href="{{url('css/servicios-ui.css')}}" rel="stylesheet">
    <link href="{{url('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">


</head>
<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
   @include ('marco.menu')
    <!-- End of Sidebar -->
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
          @include('marco/cuenta')
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            @php
                $rutaActual = \Illuminate\Support\Facades\Route::current();
                $middlewares = $rutaActual ? $rutaActual->gatherMiddleware() : [];
                $action = (string)($rutaActual ? $rutaActual->getActionName() : '');

                $esModuloServicios = collect($middlewares)->contains(function ($mw) {
                    $mw = (string)$mw;
                    return str_contains($mw, 'acceso al sistema - srv')
                        || str_contains($mw, 'acceder al sistema - noa');
                })
                || preg_match('/(TramiteLegalizacionController|ConfrontacionController|ReporteServiciosController|TramiteNoAtentadoController|ConvocatoriaController|SancionadosController)/', $action);

                $esModuloApostilla = collect($middlewares)->contains(function ($mw) {
                    return str_contains((string)$mw, 'acceso al sistema - apo');
                }) || preg_match('/ApostillaController/', $action);

                $claseModulo = '';
                if ($esModuloServicios) {
                    $claseModulo = 'modulo-ui servicios-ui';
                } elseif ($esModuloApostilla) {
                    $claseModulo = 'modulo-ui apostilla-ui';
                }
            @endphp

            <div class="container-fluid {{ $claseModulo }}">
                @include('marco.panel')
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->
        <!-- Footer -->
       @include('marco/pie')
        <!-- End of Footer -->
    </div>

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->


<!-- Bootstrap core JavaScript-->

<script>
    // Feature flags globales para apoderado
    window.GLOB_REQUIERE_BOLETA_DJ = @json(config('apoderado.requiere_boleta_dj', false));
</script>

<script src="{{url('js/jquery-3-4-1.min.js')}}"></script>
<script src="{{url('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{url('js/funciones.js')}}"></script>
<!-- Core plugin JavaScript-->
<script src="{{url('vendor/jquery-easing/jquery.easing.min.js')}}"></script>
<!-- Custom scripts for all pages-->
<script src="{{url('js/sb-admin-2.min.js')}}"></script>

<script src="{{url('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<!-- Page level custom scripts -->
<script src="{{url('js/demo/datatables-demo.js')}}"></script>
<script>
    $('#dataTable').dataTable( {
        "pageLength": 500
    });
    $('#dataTable2').dataTable( {
        "pageLength": 500
    });

    function serviciosSincronizarScrollModales() {
        var modalesVisibles = $('.modulo-ui .modal.show:visible').length;
        if (modalesVisibles > 0) {
            $('body').addClass('modal-open').css('overflow', 'hidden');
        } else {
            $('body').removeClass('modal-open').css('overflow', '');
            $('.modal-backdrop').removeClass('modulo-ui-backdrop-stack');
        }
    }

    $(document).on('show.bs.modal', '.modulo-ui .modal', function () {
        var zIndex = 1050 + (10 * $('.modulo-ui .modal.show:visible').length);
        $(this).css('z-index', zIndex);

        setTimeout(function () {
            $('.modal-backdrop').not('.modulo-ui-backdrop-stack').last()
                .css('z-index', zIndex - 1)
                .addClass('modulo-ui-backdrop-stack');
            serviciosSincronizarScrollModales();
        }, 0);
    });

    $(document).on('shown.bs.modal', '.modulo-ui .modal', function () {
        var objetivo = $(this).find('form :input:visible:not([readonly]):not([disabled])').first();
        if (objetivo.length) {
            objetivo.trigger('focus');
        }
        serviciosSincronizarScrollModales();
    });

    $(document).on('hidden.bs.modal', '.modulo-ui .modal', function () {
        setTimeout(serviciosSincronizarScrollModales, 0);
    });

    function serviciosMarcarObligatorios(scope) {
        var raiz = scope ? $(scope) : $('.modulo-ui');
        raiz.find('form :input[required]').each(function () {
            var campo = $(this);
            if (campo.attr('type') === 'hidden') {
                return;
            }

            var etiqueta = $();
            var id = campo.attr('id');
            if (id) {
                etiqueta = raiz.find('label[for="' + id + '"]').first();
            }
            if (!etiqueta.length) {
                etiqueta = campo.closest('tr').find('th').first();
            }
            if (!etiqueta.length) {
                etiqueta = campo.closest('.form-group').find('label').first();
            }
            if (!etiqueta.length) {
                return;
            }

            etiqueta.addClass('servicios-required-label');
            if (!etiqueta.find('.servicios-required-star').length) {
                etiqueta.append('<span class="servicios-required-star" aria-hidden="true">*</span>');
            }
        });
    }

    $(function () {
        serviciosMarcarObligatorios(document);
    });

    $(document).ajaxComplete(function () {
        serviciosMarcarObligatorios(document);
    });
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!-- google chart-->
</body>
</html>
