<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);

    $query = "select * from vendas_tmp where id_unico = '{$_POST['id_unico']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    if(!$d->codigo){

        $query = "insert into vendas_tmp set id_unico = '{$_POST['id_unico']}', detalhes = '{}'";
        $result = mysqli_query($con, $query);
        $v = mysqli_fetch_object($result);

        $d->cliente = false;

        $c['vendas_tmp'] = [];

    }else{
        $c['vendas_tmp'] = json_decode($d->detalhes);
    }

    $query = "select * from enderecos where cliente = '{$d->cliente}'";
    $result = mysqli_query($con, $query);
    $c['padrao'] = [];
    $c['enderecos'] = [];
    $c['cliente'] = [];
    while($d = mysqli_fetch_object($result)){
        if($d->padrao){
            $c['padrao'] = $d;
        }
            $c['enderecos'][] = $d;
    }

    $query = "select * from clientes where codigo = '{$d->cliente}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);
    if($d->codigo){
        $c['cliente'] = $d;
    }

    echo json_encode($c);