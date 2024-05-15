<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
    // exit();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);

    $q = "select *, produtos->>'$[*].produto' as cod_prod, produtos->>'$[*].quantidade' as qtd_prod from produtos where categoria = '8' and deletado != '1' and situacao = '1' order by promocao desc";

    // $q = "select * from produtos where situacao = '1' and deletado != '1' and categoria = '8'";
    $r = mysqli_query($con, $q);
    
    $p = [];
    while($d = mysqli_fetch_object($r)){


        $lista_produtos = json_decode($d->cod_prod);
        if($lista_produtos){
            $cods = implode(", ",$lista_produtos);
            $q1 = "select * from produtos where codigo in ($cods) limit 3";
            $r1 = mysqli_query($con, $q1);
            $prd = [];
            while($d1 = mysqli_fetch_object($r1)){
                $prd[] = $d1->produto;
            }
    
            $d->descricao = "- ".implode("\n- ", $prd);
            $d->valor = (($d->promocao == '1')?$d->valor_promocao:CalculaValorCombo($d->codigo));
        }

        $p[] = $d;
    }
    
    echo json_encode($p);