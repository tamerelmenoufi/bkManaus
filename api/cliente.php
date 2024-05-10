<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);

    $_POST['cliente'] = 1;

    $query = "select * from enderecos where cliente = '{$_POST['cliente']}'";
    $result = mysqli_query($con, $query);
    $c = [];
    while($d = mysqli_fetch_object($result)){
        if($d->padrao){
            $c['padrao'] = $d;
        }
            $c['enderecos'][] = $d;
    }

    $query = "select * from clientes where codigo = '{$_POST['cliente']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);
    if($d->codigo){
        $c['cliente'] = $d;
    }

    echo json_encode($c);