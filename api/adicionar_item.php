<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
    // exit();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);


    if($_POST['acao'] == 'anotacoes'){

        $data = $_POST;
        unset($data['acao']);
        unset($data['codigo']);
        unset($data['idUnico']);
        unset($data['quantidade']);
        unset($data['valor']);
        unset($data['anotacoes']);
        unset($data['status']);
    
        $valor_adicional = 0;
        if($data['inclusao']){
            foreach($data['inclusao'] as $i => $v){
                $valor_adicional = $valor_adicional + ($data['inclusao_valor'][$i]*$data['inclusao_quantidade'][$i]);
            }
        }
    
        if($data['substituicao']){
            foreach($data['substituicao'] as $i => $v){
                $valor_adicional = $valor_adicional + ($data['substituicao_valor'][$i]*1);
            }
        }
    
        $update = [
            'tipo' => 'produto',
            'regras' => $data,
            'anotacoes' => $_POST['anotacoes'],
            'adicional' => ($valor_adicional*1),
            'valor' => ($_POST['valor']*1),
            'total' => ($valor_adicional + $_POST['valor']),
            'quantidade' => ($_POST['quantidade']*1),
            'codigo' => ($_POST['codigo']*1),
            'status' => $_POST['status'],
        ];
    
        $update = json_encode($update, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
        mysqli_query($con, "UPDATE vendas_tmp set detalhes = JSON_SET(detalhes, '$.item{$_POST['codigo']}', JSON_EXTRACT('{$update}', '$')) where id_unico = '{$_POST['idUnico']}'");
        
    }




    $c = [];
    $query = "select * from vendas_tmp where id_unico = '{$_POST['idUnico']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    $c['vendas_tmp'] = [
                        'cliente'=> $d->cliente,
                        'id_unico'=> $d->id_unico, 
                        'venda' => json_decode($d->detalhes)];


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






