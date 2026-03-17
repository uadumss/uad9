@if(sizeof($diario)>0)
<div class="rounded overflow-auto " style="height: 450px;">
<table class="table table-sm">
    @foreach($diario as $dd)
    <tr>
        <td class="text-justify" style="font-size: 0.8em">
                <span class="text-danger font-italic">{{$funciones->dia($dd->dia_fech)}} {{date('d/m/Y',strtotime($dd->dia_fech))}} </span><br/>
                {{$dd->dia_reporte}}
        </td>
    </tr>
    @endforeach
</table>
</div>
@else
    <br/>
    <div class="alert-danger col-md-8 font-weight-bolder p-1 centrar_bloque text-center">
        No se encontraron reportes
    </div>
@endif
<?php

?>
