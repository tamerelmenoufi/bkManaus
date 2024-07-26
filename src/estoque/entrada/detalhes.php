<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    $query = "select * from notas where codigo = '{$_POST['detalhes']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);


    var_dump($d);