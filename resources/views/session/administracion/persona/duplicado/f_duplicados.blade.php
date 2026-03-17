<div>

    <div class="row">
        <div class="col-md-3">
            <div class="bg-info centrar_bloque p-1 col-md-10 rounded shadow">
                <h5 class="text-white text-center">Formulario de Duplicados</h5>
            </div>
            <hr class="sidebar-divider">
            <div class="">
                <div class="list-group" id="list-tab" role="tablist">
                    <a class="list-group-item list-group-item-action" id="list-home-list" data-toggle="list" href="#panel_duplicados" role="tab"
                       aria-controls="home" onclick="cargarDatos('{{url('lista duplicados/total')}}','panel_duplicados')">Duplicados completos</a>
                    <a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list" href="#panel_duplicados" role="tab"
                       aria-controls="profile" onclick="cargarDatos('{{url('lista duplicados/apellido')}}','panel_duplicados')">Duplicado por apellido</a>
                    <a class="list-group-item list-group-item-action" id="list-messages-list" data-toggle="list" href="#panel_duplicados" role="tab"
                       aria-controls="messages" onclick="cargarDatos('{{url('lista duplicados/nombre')}}','panel_duplicados')">Duplicado por nombre</a>
                    <a class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#panel_duplicados" role="tab"
                       aria-controls="settings" onclick="cargarDatos('{{url('lista duplicados/ci')}}','panel_duplicados')">Duplicado Por CI</a>
                </div>
            </div>
        </div>

        <div class="col-md-9 shadow border rounded p-3">
            <span class="text-danger font-weight-bold font-italic">* Resultado de la búsqueda</span>
            <hr class="sidebar-divider"/>
            <div id="panel_duplicados">

            </div>

        </div>
    </div>


</div>
