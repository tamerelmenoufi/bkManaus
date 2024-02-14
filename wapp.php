<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
    $con2 = AppConnect("bk");
    $query = "select * from clientes where telefone != '' and telefone_confirmado = '1'";
    $result = mysqli_query($con2, $query);
    $i = 1;
    while($d = mysqli_fetch_object($result)){

        echo "{$i}. {$d->telefone} >> {$d->nome}<br>";
        $i++;
    }

?>