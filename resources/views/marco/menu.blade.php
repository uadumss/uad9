<ul class="navbar-nav bg-gradient-light sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <div class="d-flex align-items-center justify-content-between">
        <a href="/" class="logo d-flex align-items-center">
            <img src="{{url('img/icon/logo archivos.png')}}" alt=""/>
            <span class="d-none d-lg-block">S I D</span>
        </a>
        <!--<i class="bi bi-list toggle-sidebar-btn"></i>-->
    </div>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <!-- Divider -->
    <div class="sidebar-heading text-dark">
        SISTEMAS
    </div>

    <!--<div class="sidebar-heading nav-link">
        <span style="font-size: 14px" class="text-dark">MENÚ</span>
    </div>-->
    <!-- Nav Item - Pages Collapse Menu -->
    @can('acceso al sistema - dyt')
    <li class="nav-item">
        <a class="nav-link collapsed text-dark" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-book text-dark"></i>
            <span>DIPLOMAS Y TÍTULOS</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar" overflow>
            <div class="bg-white py-2 collapse-inner rounded">
                <h5 class="collapse-header">MENU</h5>
                <div class="dropright">
                    <a class="collapse-item" data-toggle="dropdown" id="1" style="cursor: pointer" >
                        <i class="fas fa-book"></i> Listar Tomos
                    </a>
                    <div class="dropdown-menu" aria-labelledby="1" >
                        <a href="{{url('/tomo/db').'/'.date('Y')}}" class="collapse-item"><i class="fas fa-atlas"></i> Diplomas de bachiller</a>
                        <a href="{{url('/tomo/ca').'/'.date('Y')}}" class="collapse-item"><i class="fas fa-atlas"></i> Certificado académico</a>
                        <a href="{{url('/tomo/da').'/'.date('Y')}}" class="collapse-item"><i class="fas fa-atlas"></i> Diploma académico</a>
                        <a href="{{url('/tomo/tp').'/'.date('Y')}}" class="collapse-item"><i class="fas fa-atlas"></i> Título profesional</a>
                        <a href="{{url('/tomo/di').'/'.date('Y')}}" class="collapse-item"><i class="fas fa-atlas"></i> Diplomado</a>
                        <a href="{{url('/tomo/tpos').'/'.date('Y')}}" class="collapse-item"><i class="fas fa-atlas"></i> Títulos de posgrado</a>
                        <a href="{{url('/tomo/re').'/'.date('Y')}}" class="collapse-item"><i class="fas fa-atlas"></i> Reválida</a>
                        <a href="{{url('/tomo/su').'/'.date('Y')}}" class="collapse-item"><i class="fas fa-atlas"></i> Certificado supletorio</a>
                    </div>
                    @can('busqueda - dyt')
                        <a class="collapse-item" href="{{url('buscar_t')}}"><i class="fas fa-search"></i> Buscar título</a>
                    @endcan
                    <a class="collapse-item" href="{{url('lista importaciones/'.Auth::user()->id)}}"><i class="fas fa-upload"></i> Importar títulos</a>
                    <a class="collapse-item" href="{{url('reportes/')}}"><i class="fas fa-chart-area"></i> Reportes</a>
                    <a class="collapse-item" href="{{url('corregir duplicados/')}}"><i class="fas fa-chart-area"></i> Duplicados</a>

                </div>
            </div>
        </div>
    </li>
    @endcan
    <!-- MENU PARA LAS RESOLUCIONES-->
    @can('acceder al sistema - rr')
    <li class="nav-item">
        <a class="nav-link collapsed text-dark" href="#" data-toggle="collapse" data-target="#collapsethree" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-file-pdf text-dark"></i>
            <span>RESOLUCIONES</span>
        </a>
        <div id="collapsethree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar" overflow>
            <div class="bg-white py-2 collapse-inner rounded">
                <h5 class="collapse-header">MENU</h5>
                <div class="dropright">
                    @can('ver tomos - rr')
                    <a class="collapse-item" href="{{url('listar tomos resoluciones/'.date('Y'))}}"><i class="fas fa-book"></i> Listar tomos </a>
                    @endcan
                    @can('listar resoluciones - rr')
                    <a class="collapse-item" href="{{url('listar resoluciones gestion/'.date('Y').'/rcu')}}"><i class="fas fa-file-pdf"></i> Listar resoluciones</a>
                    @endcan
                    @can('ver autoridad - rr')
                    <a class="collapse-item" href="{{url('listar autoridades')}}"><i class="fas fa-user-alt"></i> Autoridad</a>
                    @endcan
                    @can('acceder al codigo archivado - rr')
                    <a class="collapse-item" href="{{url('lista codigos/0')}}"><i class="fas fa-sort-amount-up"></i> Codigo de archivado</a>
                    @endcan
                    @can('buscar - rr')
                        <a class="collapse-item" href="{{url('buscar resolucion')}}"><i class="fas fa-search"></i> Búsquedas</a>
                    @endcan
                    @can('importar - rr')
                    <a class="collapse-item" href="{{url('lista importaciones resolucion/'.Auth::user()->id)}}"><i class="fas fa-upload"></i> Importar Resoluciones</a>
                    @endcan
                    @can('acceder a temas - rr')
                    <a class="collapse-item" href="{{url('temas interes/')}}"><i class="fas fa-bookmark"></i> Temas de interés</a>
                    @endcan
                    @can('ver reportes - rr')
                    <a class="collapse-item" href="{{url('reportes/')}}"><i class="fas fa-chart-area"></i> Reportes</a>
                    @endcan
                </div>
            </div>
        </div>
    </li>
    @endcan
    @can('acceder al sistema - rfc')
        <li class="nav-item">
            <a class="nav-link collapsed text-dark" href="#" data-toggle="collapse" data-target="#collapsethree2" aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-file-pdf text-dark"></i>
                <span>RESOLUCIONES RFC - RCC</span>
            </a>
            <div id="collapsethree2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar" overflow>
                <div class="bg-white py-2 collapse-inner rounded">
                    <h5 class="collapse-header">MENU</h5>
                    <div class="dropright">
                        @can('acceder al sistema - rcc')
                            <a class="collapse-item" href="{{url('lista resoluciones - rcc/'.date('Y'))}}"><i class="fas fa-file-pdf"></i> Listar resoluciones cc</a>
                            <a class="collapse-item" href="{{url('listar resoluciones gestion/'.date('Y').'/rcu')}}"><i class="fas fa-file-pdf"></i> Listar resoluciones</a>
                        @endcan
                    </div>
                </div>
            </div>
        </li>
    @endcan
    <!-- Nav Item - Utilities Collapse Menu -->
    @can('acceso al sistema - srv')
    <li class="nav-item">
        <a class="nav-link collapsed text-dark" href="#" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-file-pdf text-dark"></i>
            <span>SERVICIOS</span>
        </a>
        <div id="collapseFour" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar" overflow>
            <div class="bg-white py-2 collapse-inner rounded">
                <h5 class="collapse-header">MENU</h5>
                <div class="dropright">
                    <a class="collapse-item" href="{{url('listar tramites')}}"> <i class="fas fa-fw fa-wrench"></i> Configurar trámites</a>
                    <a class="collapse-item" href="{{url('listar tramite legalizacion/'.date('Y-m-d'))}}"><i class="fas fa-file-signature"></i> Trámites</a>
                    @can('listar entregas - srv')
                        <a class="collapse-item" href="{{url('lista tramite entrega')}}"><i class="fas fa-file-signature"></i> Entrega de trámites</a>
                    @endcan
                    <a class="collapse-item" href="{{url('lista reportes servicios')}}"><i class="fas fa-chart-area"></i> Reportes</a>
                </div>
            </div>
        </div>
    </li>
    @endcan
    @can('acceso al sistema - apo')
        <li class="nav-item">
            <a class="nav-link collapsed text-dark" href="#" data-toggle="collapse" data-target="#collapseApostilla" aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-file-pdf text-dark"></i>
                <span>APOSTILLA</span>
            </a>
            <div id="collapseApostilla" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar" overflow>
                <div class="bg-white py-2 collapse-inner rounded">
                    <h5 class="collapse-header">MENU</h5>
                    <div class="dropright">
                        <a class="collapse-item" href="{{url('listar tramite apostilla/'.date('Y-m-d'))}}"><i class="fas fa-file-signature"></i> Trámites apostilla</a>
                        <a class="collapse-item" href="{{url('listar documentos apostilla')}}"> <i class="fas fa-fw fa-wrench"></i> Configurar apostilla</a>
                        @can('ver reportes - apo')
                            <a class="collapse-item" href="{{url('lista reportes apostilla')}}"><i class="fas fa-chart-area"></i> Reportes</a>
                        @endcan
                    </div>
                </div>
            </div>
        </li>
    @endcan
    @can('acceder al sistema - noa')
    <li class="nav-item">
        <a class="nav-link text-dark" href="#" data-toggle="collapse" data-target="#CollapseNoAtentado" aria-expanded="true" aria-controls="menu_no_atentado">
            <i class="fas fa-book text-dark"></i>
            <span>NO ATENTADO</span>
        </a>
        <div id="CollapseNoAtentado" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar" overflow>
            <div class="bg-white py-2 collapse-inner rounded">
                <h5 class="collapse-header">MENU</h5>
                <div class="dropright show">
                    <a class="collapse-item" href="{{url("lista convocatoria noatentado/".date('Y'))}}"><i class="fas fa-clipboard-list"></i> Convocatoria</a>
                    <a class="collapse-item" href="{{url("lista sancionados noatentado")}}"><i class="fas fa-user-lock"></i> Lista de sancionados</a>
                    <a class="collapse-item" href="#"><i class="fas fa-chart-area"></i> Reportes</a>
                </div>
            </div>
        </div>
    </li>
    @endcan
     @can('acceso al sistema - f')
        <li class="nav-item">
            <a class="nav-link collapsed text-dark" href="#" data-toggle="collapse" data-target="#CollapseUnidad" aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-file-pdf text-dark"></i>
                <span>UNIDADES</span>
            </a>
            <div id="CollapseUnidad" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar" overflow>
                <div class="bg-white py-2 collapse-inner rounded">
                    <h5 class="collapse-header">MENU</h5>
                    <div class="dropright">
                        <a class="collapse-item" href="{{url('listar facultad/')}}"><i class="fas fa-university text-dark"></i> Facultad</a>
                        <a class="collapse-item" href="{{url('listar unidad/')}}"><i class="fas fa-university text-dark"></i> Unidad</a>
                    </div>
                </div>
            </div>
        </li>
    @endcan
    @can('acceso al sistema - srv')
    <li class="nav-item">
        <a class="nav-link collapsed text-dark" href="{{url('l_firma/')}}">
            <i class="fas fa-university text-dark"></i> <span>Firma</span></a>
    </li>
    @endcan

    @can('acceder al sistema - cla')
        <li class="nav-item">
            <a class="nav-link collapsed text-dark" href="#" data-toggle="collapse" data-target="#collapseClaustro" aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-vote-yea text-dark"></i>
                <span>CLAUSTROS</span>
            </a>
            <div id="collapseClaustro" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar" overflow>
                <div class="bg-white py-2 collapse-inner rounded">
                    <h5 class="collapse-header">MENU</h5>
                    <div class="dropright">
                        <a class="collapse-item" href="{{url('lista consejo')}}"> <i class="fas fa-user-friends"></i> Consejos</a>
                    </div>
                </div>
            </div>
        </li>
    @endcan

    @can('acceder al sistema - dya')
        <li class="nav-item">
            <a class="nav-link collapsed text-dark" href="#" data-toggle="collapse" data-target="#collapsedya" aria-expanded="true" aria-controls="collapseUtilities">
                <i class="fas fa-user-circle text-dark"></i>
                <span>FUNCIONARIOS</span>
            </a>
            <div id="collapsedya" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Opciones:</h6>
                    <a class="collapse-item" href="{{url('listar funcionario/docente')}}"><i class="fas fa-chalkboard-teacher"></i> Docentes</a>
                    <a class="collapse-item" href="{{url('listar funcionario/administrativo')}}"><i class="fas fa-user-alt"></i> Administrativos</a>
                    <a class="collapse-item" href="{{url('reporte dya')}}"><i class="fas fa-chart-pie"></i> Reporte</a>
                </div>
            </div>
        </li>

    @endcan
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    @can('acceso al sistema - adm')
    <div class="sidebar-heading text-dark">
        ADMINISTRACIÓN
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed text-dark" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-user-friends text-dark"></i>
            <span>Usuarios</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Opciones de usuario:</h6>
                <a class="collapse-item" href="{{url('l_usuario/f')}}">Lista de usuarios</a>
                <a class="collapse-item" href="{{url('listar reportes fecha adm/')}}">Reportes</a>
            </div>
        </div>

        <a class="nav-link collapsed text-dark" href="{{url('corregir datos persona')}}" aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-user-check text-dark"></i>
            <span>Corregir datos personales</span>
        </a>
    </li>
    @endcan
    @if(Auth::user()->responsable=='t')
        <li class="nav-item">
            <a class="nav-link collapsed text-dark" href="#" data-toggle="collapse" data-target="#collapseProgramacion" aria-expanded="true" aria-controls="collapseUtilities">
                <i class="fas fa-fw fa-briefcase text-dark"></i>
                <span>Programación</span>
            </a>
            <div id="collapseProgramacion" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Opciones de actividades:</h6>
                    <a class="collapse-item" href="{{url('listar actividades/')}}">Actividades</a>
                    <a class="collapse-item" href="{{url('listar dependientes/')}}">Dependientes</a>
                </div>
            </div>
        </li>
    @endif
    @can('acceder a reportes - rep')
    <li class="nav-item">
        <a class="nav-link collapsed text-dark" href="#" data-toggle="collapse" data-target="#collapseTareas" aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-pen-alt text-dark"></i>
            <span>Reporte</span>
        </a>
        <div id="collapseTareas" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Opciones de Reporte:</h6>
                <a class="nav-link collapsed text-dark" href="{{url('listar mis tareas/')}}"><i class="fas fa-pen-alt text-dark"></i> Reporte de Tareas</a>
                <a href="{{url('listar informePeriodo')}}" class="nav-link collapsed text-dark"><i class="fas fa-pen-fancy"></i> Registro periódico</a>
            </div>
        </div>
    </li>
    @endcan

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline text-dark">
        <button class="rounded-circle border-0 text-dark bg-dark" id="sidebarToggle"></button>
    </div>

</ul>
