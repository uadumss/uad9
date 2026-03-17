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
            <div class="container-fluid">
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
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!-- google chart-->
</body>
</html>
