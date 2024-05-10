<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);

    $_POST['cliente'] = 1;

    $query = "select * from enderecos where cliente = '{$_POST['cliente']}'";
    $result = mysqli_query($con, $query);
    $enderecos = [];
    $padrao = [];
    while($d = mysqli_fetch_object($result)){
        if($d->padrao){
            $padrao = $d;
        }
            $enderecos[] = $d;
    }

    $query = "select * from clientes where codigo = '{$_POST['cliente']}'";
    $result = mysqli_query($con, $query);
    $cliente = [];
    $d = mysqli_fetch_object($result);
    if($d->codigo){
        $cliente = $d;
    }

    $c = array_merge($cliente, $enderecos, $padrao);

    echo json_encode($c);