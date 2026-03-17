<html>
    <body>
    <?php
        $cliente = curl_init();
        curl_setopt($cliente, CURLOPT_URL, "http://167.157.3.12:8000/verificar_qr/".$_GET['q']);
        curl_setopt($cliente, CURLOPT_HEADER, 0);
        $contenido = curl_exec($cliente);
        curl_close($cliente);

        //echo $contenido;
    ?>
       <h1>Verificación : </h1>

        <table>
            <tr>
                <th>Código de verificacion</th>
                <td><?php echo $_GET['q']?></td>

            </tr>
        </table>
    </body>
</html>
