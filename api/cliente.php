<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
    // exit();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);

    if($_POST['acao'] == 'ativar'){
        $query = "select * from clientes where telefone = '{$_POST['telefone']}'";
        $result = mysqli_query($con, $query);
        $c = mysqli_fetch_object($result);

        mysqli_query($con, "update vendas_tmp set cliente = '{$c->codigo}' where id_unico = '{$_POST['id_unico']}'");

    }

    if($_POST['acao'] == 'enviar_codigo'){

        $telefone = str_replace(['-',' ','(',')'],false,trim($_POST['telefone']));
    
        $d1 = rand(1,9);
        $d2 = rand(0,9);
        $d3 = rand(0,9);
        $d4 = rand(0,9);
    
        $cod = $d1.$d2.$d3.$d4;
    
        $result = EnviarWapp($_POST['telefone'],"BK Manaus informe: Seu código de acesso é *{$cod}*");
    
        echo "{\"status\":\"success\", \"codigo\":\"{$cod}\"}";

        exit();
    }



    $c = [];
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
        $c['vendas_tmp'] = [
                            'cliente'=> $d->cliente,
                            'id_unico'=> $d->id_unico, 
                            'venda' => json_decode($d->detalhes)];
    }

    $query = "select * from enderecos where cliente = '{$d->cliente}' and cliente > 0";
    $result = mysqli_query($con, $query);
    $c['padrao'] = [];
    $c['enderecos'] = [];
    $c['cliente'] = [];
    while($d1 = mysqli_fetch_object($result)){
        if($d1->padrao){
            $c['padrao'] = $d1;
        }
            $c['enderecos'][] = $d1;
    }

    $query = "select * from clientes where codigo = '{$d->cliente}'";
    $result = mysqli_query($con, $query);
    $d2 = mysqli_fetch_object($result);
    if($d2->codigo){
        $c['cliente'] = $d2;
    }

    echo json_encode($c);