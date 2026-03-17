<div>
    @if($resolucion->res_pdf!='')
        <span class="font-weight-bold text-danger">* RESOLUCION</span>
        <embed src="{{url('pdf resolucion/'.$resolucion['cod_res'])}} #toolbar=0" class="col-md-12" height="600"/>
    @else
        <div class="alert alert-danger alert-dismissible">
            <span class="">No existe el archivo digital</span>
        </div>
    @endif
</div>
