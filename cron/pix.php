<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    function SituacaoPIX($e){
        $opc = [
            'approved' => 'pago',
            'pending' => 'pendente',
            'cancelled' => 'cancelado'
        ];
        return $opc[$e];
    }

    $query = "select *, pix_detalhes->>'$.id' as operadora_id from vendas where situacao = 'pendente'";
    $result = mysqli_query($con, $query);
    while($v = mysqli_fetch_object($result)){


        $pedido = str_pad($v->codigo, 6, "0", STR_PAD_LEFT);
        $PIX = new MercadoPago;
        $retorno = $PIX->ObterPagamento($v->operadora_id);
        $operadora_retorno = $retorno;
        $dados = json_decode($retorno);

        // echo "<pre>".print_r($dados)."</pre>";

        echo $q = "update vendas set
            pagamento = 'pix',
            pix_detalhes = '".(($retornoX)?:'{}')."',
            situacao = '".SituacaoPIX($dados->ststus)."'
            where codigo = '{$v->codigo}'
        ";

        //mysqli_query($con, $q);

    }