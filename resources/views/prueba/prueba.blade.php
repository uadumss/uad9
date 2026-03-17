<div>
    <form action="{{url('personal')}}" enctype="multipart/form-data" method="POST">
        @csrf
        Archivo 2022 : <input type="file" name="2022"><br/>
        Archivo 2023 : <input type="file" name="2023"><br/>
        <input type="submit" name="enviar" value="ENVIAR"><br/>
    </form>
</div>
