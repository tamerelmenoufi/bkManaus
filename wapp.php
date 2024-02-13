<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    $query = "select * from clientes where telefone != '' and telefone_confirmado = '1'";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){

        echo "{$d->telefone} >> {$d->nome}<br>";

    }

?>