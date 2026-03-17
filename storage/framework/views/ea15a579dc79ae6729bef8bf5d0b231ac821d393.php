<div>
    <form action="<?php echo e(url('personal')); ?>" enctype="multipart/form-data" method="POST">
        <?php echo csrf_field(); ?>
        Archivo 2022 : <input type="file" name="2022"><br/>
        Archivo 2023 : <input type="file" name="2023"><br/>
        <input type="submit" name="enviar" value="ENVIAR"><br/>
    </form>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/prueba/prueba.blade.php ENDPATH**/ ?>