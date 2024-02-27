<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['acao'] == 'entregador'){

        $d1 = rand(1,9);
        $d2 = rand(0,9);
        $d3 = rand(0,9);
        $d4 = rand(0,9);
    
        $returnCode = $d1.$d2.$d3.$d4;

        $d1 = rand(1,9);
        $d2 = rand(0,9);
        $d3 = rand(0,9);
        $d4 = rand(0,9);
    
        $pickupCode = $d1.$d2.$d3.$d4;
        //$pickupCode = substr($_POST['cliente_telefone'],-4);

        $entrega_id = $_POST['entrega_id'];
        $entrega_nome = $_POST['entrega_nome'];
        $entrega_ddd = $_POST['entrega_ddd'];
        $entrega_telefone = $_POST['entrega_telefone'];
        $cliente_telefone = $_POST['cliente_telefone'];

        $json_modelo = '
        
        {
            "id": '.$_SESSION['pedido'].',
            "code": "",
            "events": [
                {
                    "id": 1,
                    "latitude": null,
                    "createdAt": "'.date("Y-m-d H:i:s").'",
                    "longitude": null,
                    "deliveryMan": {
                        "name": "'.$entrega_nome.'",
                        "phone": "'.$entrega_telefone.'"
                    },
                    "description": "",
                    "requestedBy": {
                        "name": "'.$entrega_nome.'",
                        "phone": "'.$entrega_telefone.'"
                    }
                }
            ],
            "origin": "loja",
            "storeId": '.$_SESSION['pedido'].',
            "fullCode": null,
            "createdAt": "'.date("Y-m-d H:i:s").'",
            "situation": null,
            "deliveries": [
                {
                    "id": null,
                    "code": "",
                    "name": "",
                    "phone": "",
                    "address": {
                        "city": "",
                        "state": "",
                        "number": "",
                        "street": "",
                        "zipCode": "",
                        "latitude": null,
                        "longitude": null,
                        "complement": "",
                        "neighborhood": ""
                    },
                    "distance": null,
                    "fullCode": null,
                    "situation": null,
                    "orderRoute": 1,
                    "dropoffCode": "",
                    "observation": "",
                    "deliveryCode": "",
                    "productValue": 0,
                    "onlinePayment": true
                }
            ],
            "pickupCode": "'.$pickupCode.'",
            "returnCode": "'.$returnCode.'",
            "deliveryFee": 0,
            "deliveryMan": {
                "id": '.$entrega_id.',
                "ddd": "'.$entrega_ddd.'",
                "name": "'.$entrega_nome.'",
                "email": "",
                "phone": "'.$entrega_telefone.'",
                "document": "",
                "latitude": null,
                "longitude": null,
                "profileId": 0
            },
            "totalDistance": null,
            "expectedPickup": null,
            "preparationTime": 0,
            "expectedDelivery": null
        }
        
        ';
        
        $q = "update vendas set producao = 'entrega', delivery_id = '{$_SESSION['pedido']}', delivery_detalhes = '{$json_modelo}' where codigo = '{$_SESSION['pedido']}'";
        mysqli_query($con, $q);

        $v = mysqli_fetch_object(mysqli_query($con, "select a.*,
                                                        a.pix_detalhes->>'$.id' as operadora_id,
                                                        a.delivery_detalhes->>'$.deliveryMan.name' as Dnome,
                                                        concat(a.delivery_detalhes->>'$.deliveryMan.ddd',a.delivery_detalhes->>'$.deliveryMan.phone') as Dtelefone,
                                                        b.nome as Cnome,
                                                        b.cpf as Ccpf,
                                                        b.telefone as Ctelefone,
                                                        b.email as Cemail,
                                                        c.codigo as endereco,
                                                        c.cep as Ecep,
                                                        c.logradouro as Elogradouro,
                                                        c.numero as Enumero,
                                                        c.complemento as Ecomplemento,
                                                        c.ponto_referencia as Eponto_referencia,
                                                        c.bairro as Ebairro,
                                                        c.localidade as Elocalidade,
                                                        c.uf as Euf,
                                                        d.telefone as Ltelefone,
                                                        d.nome as Lnome
                                                    from vendas a 
                                                    left join clientes b on a.cliente = b.codigo
                                                    left join lojas d on a.loja = d.codigo
                                                    left join enderecos c on (a.cliente = c.cliente and c.padrao = '1')
                                                    where a.codigo = '{$_SESSION['pedido']}'"));

        if($v->pagamento == 'ifood'){
            $ifood = json_decode($v->ifood);
            $v->codigo_ifood = $ifood->codigo;
            $v->Cnome = $ifood->cliente->nome;
            $v->Ctelefone = $ifood->cliente->telefone;
           
            $v->Elogradouro = $ifood->endereco->logradouro;
            $v->Enumero = $ifood->endereco->numero;
            $v->Ebairro = $ifood->endereco->bairro;
            $v->Ecomplemento = $ifood->endereco->complemento;
            $v->Eponto_referencia = $ifood->endereco->ponto_referencia;
        }



        $endereco = trim("{$v->Elogradouro} {$v->Enumero} {$v->Ebairro} {$v->complemento} {$v->ponto_referencia}");

        $pedido = str_pad((($v->codigo_ifood)?:$v->codigo), 6, "0", STR_PAD_LEFT);
        $mensagem = "*BK Manaus Informa* - O entregador ({$entrega_nome} - {$entrega_telefone}) está se preparando para levar o pedido *#{$pedido}* ao seu destino.";
        EnviarWapp($v->Ctelefone,$mensagem);

        $mensagem = "Informe ao entregador o seu código de entrega *{$pickupCode}*.";
        EnviarWapp($v->Ctelefone,$mensagem);

        $mensagem = "*BK Manaus Informa* - Olá {$entrega_nome}. Você foi selecionado para a entrega do pedido *#{$pedido}* da loja {$v->Lnome} para {$v->Cnome} no endereço *{$endereco}*. Favor comparecer ao balcão de retirada na loja.";
        EnviarWapp($entrega_ddd.$entrega_telefone,$mensagem);

        $mensagem = "Confirme o recebimento e atualize o andamento da entrega do pedido na plataforma no linque {$urlEntregador}.";
        EnviarWapp($entrega_ddd.$entrega_telefone,$mensagem);

    }

    if($_POST['acao'] == 'trocar_entregador'){

        $q = "update vendas set producao = 'producao', delivery_id = '', delivery_detalhes = '{}' where codigo = '{$_SESSION['pedido']}'";
        mysqli_query($con, $q);

        $v = mysqli_fetch_object(mysqli_query($con, "select a.*,
                                                        a.pix_detalhes->>'$.id' as operadora_id,
                                                        a.delivery_detalhes->>'$.deliveryMan.name' as Dnome,
                                                        concat(a.delivery_detalhes->>'$.deliveryMan.ddd',a.delivery_detalhes->>'$.deliveryMan.phone') as Dtelefone,
                                                        b.nome as Cnome,
                                                        b.cpf as Ccpf,
                                                        b.telefone as Ctelefone,
                                                        b.email as Cemail,
                                                        c.codigo as endereco,
                                                        c.cep as Ecep,
                                                        c.logradouro as Elogradouro,
                                                        c.numero as Enumero,
                                                        c.complemento as Ecomplemento,
                                                        c.ponto_referencia as Eponto_referencia,
                                                        c.bairro as Ebairro,
                                                        c.localidade as Elocalidade,
                                                        c.uf as Euf,
                                                        d.telefone as Ltelefone,
                                                        d.nome as Lnome
                                                    from vendas a 
                                                    left join clientes b on a.cliente = b.codigo
                                                    left join lojas d on a.loja = d.codigo
                                                    left join enderecos c on (a.cliente = c.cliente and c.padrao = '1')
                                                    where a.codigo = '{$_SESSION['pedido']}'"));
        
        if($v->pagamento == 'ifood'){
            $ifood = json_decode($v->ifood);
            $v->codigo_ifood = $ifood->codigo;
            $v->Cnome = $ifood->cliente->nome;
            $v->Ctelefone = $ifood->cliente->telefone;
            
            $v->Elogradouro = $ifood->endereco->logradouro;
            $v->Enumero = $ifood->endereco->numero;
            $v->Ebairro = $ifood->endereco->bairro;
            $v->Ecomplemento = $ifood->endereco->complemento;
            $v->Eponto_referencia = $ifood->endereco->ponto_referencia;
        }

        $pedido = str_pad((($v->codigo_ifood)?:$v->codigo), 6, "0", STR_PAD_LEFT);
        $mensagem = "*BK Manaus Informa* - Estamos buscando um novo entregador para agilizar a entrega do pedido *#{$pedido}*.";
        EnviarWapp($v->Ctelefone,$mensagem);

        $mensagem = "*BK Manaus Informa* - Você foi desvinculado da entrega do pedido *#{$pedido}*.";
        EnviarWapp($v->Dtelefone,$mensagem);

    }


    if($_POST['loja']){
        $_SESSION['bkLoja'] = $_POST['loja'];
    }

    if($_POST['pedido']){
        $_SESSION['pedido'] = $_POST['pedido'];
    }

    if($_POST['acao'] == 'finalizar'){

        mysqli_query($con, "update vendas set producao = 'entregue' where codigo = '{$_POST['pedido']}'");

        $v = mysqli_fetch_object(mysqli_query($con, "select a.*,
                a.pix_detalhes->>'$.id' as operadora_id,
                a.delivery_detalhes->>'$.deliveryMan.name' as Dnome,
                concat(a.delivery_detalhes->>'$.deliveryMan.ddd',a.delivery_detalhes->>'$.deliveryMan.phone') as Dtelefone,
                b.nome as Cnome,
                b.cpf as Ccpf,
                b.telefone as Ctelefone,
                b.email as Cemail,
                c.codigo as endereco,
                c.cep as Ecep,
                c.logradouro as Elogradouro,
                c.numero as Enumero,
                c.complemento as Ecomplemento,
                c.ponto_referencia as Eponto_referencia,
                c.bairro as Ebairro,
                c.localidade as Elocalidade,
                c.uf as Euf,
                d.telefone as Ltelefone,
                d.nome as Lnome
            from vendas a 
            left join clientes b on a.cliente = b.codigo
            left join lojas d on a.loja = d.codigo
            left join enderecos c on (a.cliente = c.cliente and c.padrao = '1')
            where a.codigo = '{$_POST['pedido']}'"));

        if($v->pagamento == 'ifood'){
            $ifood = json_decode($v->ifood);
            $v->codigo_ifood = $ifood->codigo;
            $v->Cnome = $ifood->cliente->nome;
            $v->Ctelefone = $ifood->cliente->telefone;
            
            $v->Elogradouro = $ifood->endereco->logradouro;
            $v->Enumero = $ifood->endereco->numero;
            $v->Ebairro = $ifood->endereco->bairro;
            $v->Ecomplemento = $ifood->endereco->complemento;
            $v->Eponto_referencia = $ifood->endereco->ponto_referencia;
        }

        $pedido = str_pad((($v->codigo_ifood)?:$v->codigo), 6, "0", STR_PAD_LEFT);
        $mensagem = "*BK Manaus Informa* - O pedido *#{$pedido}*, foi confirmado como entregue.";
        EnviarWapp($v->Ctelefone,$mensagem);
        EnviarWapp($v->Dtelefone,$mensagem);

    }


?>
<style>
    .popupPalco{
        overflow:auto;
    }
    li[pedido], .dados{
        cursor:pointer;
        font-size:12px;
    }
    li[entrega_id] > div > i{
        color:#fff;
    }
    li[entrega_id]:hover > div > i{
        color:#000;
    }
</style>

<div class="row g-0 m-3">

    <ul class="list-group">
        <?php

        $query = "select a.*, b.nome, b.telefone, d.telefone as Ltelefone, concat(c.logradouro,', ',c.numero,', ',c.bairro,', ',c.complemento,' (',c.ponto_referencia,')') as endereco, a.delivery_detalhes->>'$.pickupCode' as entrega, a.delivery_detalhes->>'$.returnCode' as retorno from vendas a left join clientes b on a.cliente = b.codigo left join enderecos c on (a.cliente = c.cliente and c.padrao = '1') left join lojas d on a.loja = d.codigo  where a.codigo = '{$_SESSION['pedido']}'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);

        if($d->pagamento == 'ifood'){
            $ifood = json_decode($d->ifood);
            $d->codigo_ifood = $ifood->codigo;
            $d->nome = $ifood->cliente->nome;
            $d->telefone = $ifood->cliente->telefone;
            $d->endereco = "{$ifood->endereco->logradouro}, {$ifood->endereco->numero}, {$ifood->endereco->bairro}, {$ifood->endereco->complemento}, ".(($ifood->endereco->ponto_referencia)?"({$ifood->endereco->ponto_referencia})":false);
            $ifood = 'ifood';
        }


        $Ctelefone = $d->telefone;
        $Ltelefone = $d->Ltelefone;
        $pedido = str_pad((($d->codigo_ifood)?:$d->codigo), 6, "0", STR_PAD_LEFT);

            if($d->producao == 'pendente'){
                mysqli_query($con, "update vendas set producao = 'producao' where codigo = '{$d->codigo}'");
                $d->producao = 'producao';

                $pedido = str_pad((($d->codigo_ifood)?:$d->codigo), 6, "0", STR_PAD_LEFT);
                $mensagem = "*BK Manaus Informa* - O pedido *#{$pedido}* foi recebido pela loja e está sendo preparado.";
                EnviarWapp($d->telefone,$mensagem);

            }
            
            $pedido = json_decode($d->detalhes);
            $delivery = json_decode($d->delivery_detalhes);



        ?>
            <li class="list-group-item" pedido="<?=$d->codigo?>">

                <div class="d-flex justify-content-between dados">
                    <div>
                        <i class="fa-solid fa-receipt"></i> Pedido #<?=str_pad((($d->codigo_ifood)?:$d->codigo), 6, "0", STR_PAD_LEFT).(($ifood)?' (ifood) ':false)?>
                        <br>
                        <i class="fa-solid fa-user"></i> <?=$d->nome?>
                    </div>
                    <div>
                        Entrega: <?=$d->entrega?>
                        <br>
                        Retorno: <?=$d->retorno?>
                    </div>
                </div>
                <div class="d-flex justify-content-between dados">
                    <div>
                        <i class="fa-solid fa-mobile-screen-button"></i> <?=$d->telefone?>
                    </div>
                </div>
                <div class="d-flex justify-content-between dados">
                    <div><i class="fa-solid fa-location-dot me-1"></i></div>
                    <div class="w-100">
                         <?=$d->endereco?>
                    </div>
                </div>
                

                <div class="d-flex justify-content-between dados mt-2">
                    <div>
                        <i class="fa-solid fa-dollar-sign"></i> Valor da Compra
                    </div>
                    <div>
                        R$ <?=number_format($d->valor_compra,2,',',false)?>
                    </div>
                </div>

                <div class="d-flex justify-content-between dados">
                    <div>
                        <i class="fa-solid fa-dollar-sign"></i> Valor da Entrega
                    </div>
                    <div>
                        R$ <?=number_format($d->valor_entrega,2,',',false)?>
                    </div>
                </div>

                <div class="d-flex justify-content-between dados">
                    <div>
                        <i class="fa-solid fa-dollar-sign"></i> Valor de Desconto
                    </div>
                    <div>
                        R$ <?=number_format($d->valor_desconto,2,',',false)?>
                    </div>
                </div>

                <div class="d-flex justify-content-between dados">
                    <div>
                        <i class="fa-solid fa-dollar-sign"></i> Valor Total
                    </div>
                    <div>
                        <b>R$ <?=number_format($d->valor_total,2,',',false)?></b>
                    </div>
                </div>

                <?php
                if($delivery->deliveryMan->name){
                ?>
                <div class="mt-2 mb-2"><b><i class="fa-solid fa-motorcycle"></i> Dados de Entrega</b></div>
                <div class="d-flex justify-content-between dados">
                    <div>
                        <i class="fa-solid fa-person-biking"></i> Nome
                    </div>
                    <div>
                        <?=$delivery->deliveryMan->name?>
                    </div>
                </div>
                <div class="d-flex justify-content-between dados">
                    <div>
                        <i class="fa-solid fa-mobile-screen-button"></i> Telefone
                    </div>
                    <div>
                        <?="({$delivery->deliveryMan->ddd}) {$delivery->deliveryMan->phone}"?>
                    </div>
                </div>
                <?php
                if($d->producao != 'entregue'){
                ?>
                <div class="d-flex justify-content-end mt-1 mb-3">
                    <div class="text-danger trocar_entregador">
                        <i class="fa-solid fa-rotate"></i> Trocar o entregador
                    </div>
                </div>
                <?php
                }
                }else{
                ?>
                <div class="mt-2 mb-2 text-center">
                    <h5>Selecione o Entregador</h5>
                    <ul class="list-group">
                        <?php
                        $q = "select a.* from entregadores a where a.situacao = '1' and a.deletado != '1' and a.loja = '{$_POST['loja']}' and (select count(*) from vendas where delivery_detalhes->>'$.deliveryMan.id' = a.codigo and producao != 'entregue' and situacao = 'pago') = 0 order by a.nome";
                        $r = mysqli_query($con, $q);
                        while($e = mysqli_fetch_object($r)){
                            $fone = trim(str_replace(['-','(',')',' '],false,$e->telefone));
                            $ddd = substr($fone, 0,2);
                            $telefone = substr($fone, 2);
                        ?>
                        <li 
                            class="list-group-item d-flex justify-content-between"
                            entrega_id="<?=$e->codigo?>"
                            entrega_nome="<?=$e->nome?>"
                            entrega_ddd="<?=$ddd?>"
                            entrega_telefone="<?=$telefone?>"
                            cliente_telefone="<?=$d->telefone?>"
                        >
                            <div>
                                <i class="fa-solid fa-check"></i>
                                <?=$e->nome?>
                            </div>
                            <span><?=$e->telefone?></span>
                        </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <?php
                }
                ?>

                <div class="d-flex justify-content-between dados">
                    <div class='col p-1 text-center'>
                        <h5><i class="fa-solid fa-mortar-pestle"></i></h5>
                        Produção
                        <hr style="border:solid 5px; color:<?=(($d->producao == 'producao' or $d->producao == 'entrega' or $d->producao == 'entregue')?'green':'#ccc')?>;">
                    </div>
                    <div class='col p-1 text-center'>
                        <h5><i class="fa-solid fa-person-biking"></i></h5>
                        Entrega
                        <hr style="border:solid 5px; color:<?=(($d->producao == 'entrega' or $d->producao == 'entregue')?'green':'#ccc')?>;">
                    </div>
                    <div class='col p-1 text-center'>
                        <h5><i class="fa-solid fa-people-roof"></i></h5>
                        Entregue
                        <hr style="border:solid 5px; color:<?=(($d->producao == 'entregue')?'green':'#ccc')?>">
                    </div>
                </div>
                
            </li>

            

        <?php

            

            foreach($pedido as $i => $v){

                $q = "select *, produtos->>'$[*].produto' as cod_prod from produtos where codigo ='{$v->codigo}'";
                $r = mysqli_query($con, $q);
                $P = mysqli_fetch_object($r);

                if($P->cod_prod){
                    $lista_produtos = json_decode($P->cod_prod);
                    if($lista_produtos){
                        $cods = implode(", ",$lista_produtos);
                        $q = "select * from produtos where codigo in ($cods) limit 3";
                        $r = mysqli_query($con, $q);
                        $prd = [];
                        while($d1 = mysqli_fetch_object($r)){
                            $prd[] = $d1->produto;
                        }
                
                        $prd = implode("</div><div style='color:".(($d->promocao == '1')?'#ffffff':'#000000')."';>- ", $prd);
                    }
                }

                // print_r($v);
                // echo "<br><br>";
        ?>
            <li class="list-group-item" pedido="<?=$d->codigo?>">

                <div class="d-flex justify-content-between dados">
                    <div>
                        <b><?="{$v->quantidade} X ".(($v->tipo == 'combo')?'Combo ':false)."{$P->produto}"?></b>
                        <?php
                        if($P->cod_prod){
                        ?>
                        <div>- <?=$prd?></div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <!-- <div class="d-flex justify-content-between dados">
                    <div>
                        Valor Unitário
                    </div>
                    <div>
                        <?=number_format($v->valor,2,',',false)?>
                    </div>
                </div>
                <div class="d-flex justify-content-between dados">
                    <div>
                        Valor Adicional
                    </div>
                    <div>
                        <?=number_format($v->adicional,2,',',false)?>
                    </div>
                </div>
                <div class="d-flex justify-content-between dados">
                    <div>
                        Valor por Produto
                    </div>
                    <div>
                        <b><?=number_format($v->total,2,',',false)?></b>
                    </div>
                </div>
                <div class="d-flex justify-content-between dados">
                    <div>
                        Valor Total
                    </div>
                    <div>
                        <b><?=number_format($v->total*$v->quantidade,2,',',false)?></b>
                    </div>
                </div> -->




        <?php
                // echo "{$v->quantidade} X {$P->produto}<br>";
                // echo "Valor unitário {$v->valor}<br>";
                // echo "Valor Adicional {$v->adicional}<br>";
                // echo "Valor Total {$v->total}<br>";


                // echo "Tipo:".$v->tipo."<br>";
                // echo "Total:".$v->total."<br>";
                // echo "Valor:".$v->valor."<br>";
                // echo "Produto:".$s->produto."<br>";
                // echo "Quantidade:".$v->quantidade."<br>";
                // // echo "Categoria:".$v->categoria."<br>";
                // echo "Status:".$v->status."<br>";
                // echo "Adicional:".$v->adicional."<br><hr>";


                if(
                    $v->regras->remocao or 
                    $v->regras->inclusao or 
                    $v->regras->substituicao or
                    $v->regras->combo->remocao or
                    $v->regras->combo->inclusa or 
                    $v->regras->combo->substituicao
                ){
            ?>
                <div class="alert alert-dark dados mt-3" role="alert">
            <?php
                }

                if($v->tipo == 'produto'){


                    
                    if($v->regras->remocao){
                        $q = "select * from itens where codigo in (".implode(",", $v->regras->remocao).")";
                        $r = mysqli_query($con, $q);
                        $lista = [];
                        while($s = mysqli_fetch_object($r)){
                            $lista[] = "{$s->item}";
                        }

            ?>
                        
                            <div class='mt-1'><b><i class="fa-solid fa-circle-minus" style="color:red"></i> Remover de <?=$P->produto?></b></div>
                            <?=implode(", ", $lista)?><br>

            <?php
                        // echo "Remover do :<br>";

                        // foreach($v->regras->remocao as $i1 => $v1){
                        //     echo "Remocao: {$v1}<br>";
                        // }
                    }

                    if($v->regras->inclusao){
                        echo "<div class='mt-1'><b><i class='fa-solid fa-circle-plus' style='color:green'></i> Incluir em {$P->produto}</b></div>";
                        $q = "select * from itens where codigo in (".implode(",", $v->regras->inclusao).")";
                        $r = mysqli_query($con, $q);
                        $qt = $v->regras->inclusao_quantidade;
                        $vl = $v->regras->inclusao_valor;                      
                        while($s = mysqli_fetch_object($r)){
                            $pnt = array_search($s->codigo, $v->regras->inclusao);
                            // echo "{$qt[$pnt]} x $s->item - {$vl[$pnt]} = ".($vl[$pnt] * $qt[$pnt])."<br>";
                ?>
                            <div class="d-flex justify-content-between dados">
                                <div>
                                    <?="{$qt[$pnt]} x $s->item"?>
                                </div>
                                <!-- <div>
                                    <b><?=number_format($vl[$pnt],2,',',false)?></b>
                                </div>
                                <div>
                                    <b><?=number_format($vl[$pnt] * $qt[$pnt],2,',',false)?></b>
                                </div>
                                 -->
                            </div>
                <?php

                        }


                        // echo "-------------------------------------------------------------------------------";
                        // foreach($v->regras->inclusao as $i1 => $v1){
                        //     $qt = $v->regras->inclusao_quantidade;
                        //     $vl = $v->regras->inclusao_valor;
                        //     echo "{$qt[$i1]} x Inclusão {$v1} - {$vl[$i1]} = ".($vl[$i1] * $qt[$i1])."<br>";
                        //     // echo "{$i1} x {$v1}<br>";
                        // }
                    }

                    if($v->regras->substituicao){
                        echo "<div class='mt-1'><b><i class='fa-solid fa-repeat' style='color:blue'></i> Substituir {$P->produto}</b></div>";

                        $q = "select * from produtos where codigo in (".implode(",", $v->regras->substituicao).")";
                        $r = mysqli_query($con, $q);
                        $vl = $v->regras->substituicao_valor;                        
                        while($s = mysqli_fetch_object($r)){
                            $pnt = array_search($s->codigo, $v->regras->substituicao);
                            // echo "$s->item - {$vl[$pnt]}<br>";
                ?>
                            <div class="d-flex justify-content-between dados">
                                <div>
                                    <?="{$s->produto}"?>
                                </div>
                                <!-- <div>
                                    <b><?=number_format($vl[$pnt],2,',',false)?></b>
                                </div>                                 -->
                            </div>
                <?php
                        }
                        // echo "-------------------------------------------------------------------------------<br>";

                        // foreach($v->regras->substituicao as $i1 => $v1){
                        //     $vl = $v->regras->substituicao_valor;
                        //     echo "Substituição {$v1} - {$vl[$i1]} <br>";
                        //     // echo "{$i1} x {$v1}<br>";
                        // }
                    }


                }else if($v->tipo == 'combo'){

                    if($v->regras->combo->remocao){

                        $lista = [];
                        foreach($v->regras->combo->remocao as $i1 => $v1){
                            $lista[$v1->produto][] = $v1->item;
                            // echo "Remocao: {$v1->item} - {$v1->produto}<br>";
                        }     
                        foreach($lista as $i1 => $v1){
                            $q = "select *, (select produto from produtos where codigo = '{$i1}') as produto from itens where codigo in (".implode(",", $v1).")";
                            $r = mysqli_query($con, $q);
                            $lista2 = [];
                            while($s = mysqli_fetch_object($r)){
                                $lista2[] = $s->item;
                                $produto = $s->produto;
                            }
                        ?>
                            <div class='mt-1'><b><i class="fa-solid fa-circle-minus" style="color:red"></i> Remover de <?=$produto?></b></div>
                            <?=implode(", ", $lista2)?><br>
                        <?php
                        }
                   
                    }

                    if($v->regras->combo->inclusao){
                        // echo "<hr>Inclusão:<br>";
                        // foreach($v->regras->combo->inclusao as $i1 => $v1){
                        //     $qt = $v->regras->combo->inclusao_quantidade;
                        //     $vl = $v->regras->combo->inclusao_valor;
                        //     echo "{$qt[$i1]->quantidade} / {$qt[$i1]->produto} x Inclusão {$v1->item} / {$v1->produto} - {$vl[$i1]->valor} / {$vl[$i1]->produto} = ".($vl[$i1]->valor * $qt[$i1]->quantidade)."<br>";
                        // }


                        $lista = [];
                        foreach($v->regras->combo->inclusao as $i1 => $v1){
                            $qt = $v->regras->combo->inclusao_quantidade;
                            $vl = $v->regras->combo->inclusao_valor;
                            $lista[$v1->produto][$v1->item]['quantidade'] = $qt[$i1]->quantidade;
                            $lista[$v1->produto][$v1->item]['valor'] = $vl[$i1]->valor;
                        }     
                        foreach($lista as $i1 => $v1){
                            $arr = array_keys($v1);
                            $q = "select *, (select produto from produtos where codigo = '{$i1}') as produto from itens where codigo in (".implode(",", $arr).")";
                            $r = mysqli_query($con, $q);
                            $produto = false;
                            while($s = mysqli_fetch_object($r)){
                                $lista2[] = $s->item;

                                if($s->produto != $produto){
                                    echo "<div class='mt-1'><b><i class='fa-solid fa-circle-plus' style='color:green'></i> Incluir em {$s->produto}</b></div>";
                                    $produto = $s->produto;
                                }
                        ?>
                            <div class="d-flex justify-content-between dados">
                                <div>
                                    <?="{$lista[$i1][$s->codigo]['quantidade']} x $s->item"?>
                                </div>
                                <!-- <div>
                                    <b><?=number_format($lista[$i1][$s->codigo]['valor'],2,',',false)?></b>
                                </div>
                                <div>
                                    <b><?=number_format($lista[$i1][$s->codigo]['valor'] * $lista[$i1][$s->codigo]['quantidade'],2,',',false)?></b>
                                </div> -->
                                
                            </div>
                        <?php
                            }
                        }



                    }

                    if($v->regras->combo->substituicao){
                        // echo "<hr>Substituição:<br>";
                        // foreach($v->regras->combo->substituicao as $i1 => $v1){
                        //     $vl = $v->regras->combo->substituicao_valor;
                        //     echo "Substituição {$v1->item} / {$v1->produto} - {$vl[$i1]->valor} / {$vl[$i1]->produto}<br>";
                            
                        // }      
                        
                        


                        $lista = [];
                        foreach($v->regras->combo->substituicao as $i1 => $v1){
                            $vl = $v->regras->combo->substituicao_valor;
                            $lista[$v1->produto][$v1->item]['valor'] = $vl[$i1]->valor;
                        }     
                        foreach($lista as $i1 => $v1){
                            $arr = array_keys($v1);
                            $q = "select *, (select produto from produtos where codigo = '{$i1}') as produto from itens where codigo in (".implode(",", $arr).")";
                            $r = mysqli_query($con, $q);
                            $produto = false;
                            while($s = mysqli_fetch_object($r)){
                                if($s->produto != $produto){
                                    echo "<div class='mt-1'><b><i class='fa-solid fa-repeat' style='color:blue'></i> Substituir {$s->produto}</b></div>";
                                    $produto = $s->produto;
                                }
                        ?>
                            <div class="d-flex justify-content-between dados">
                                <div>
                                    <?="{$s->item}"?>
                                </div>
                                <!-- <div>
                                    <b><?=number_format($lista[$i1][$s->codigo]['valor'],2,',',false)?></b>
                                </div> -->
                            </div>
                        <?php
                            }
                        }


                    }
 
                    
                }
                if($v->anotacoes){
                    echo "<p class='mt-1'><b><i class='fa-solid fa-comment fa-flip-horizontal' style='color:orange'></i> ".$v->anotacoes."</b></p>";
                }
                if(
                    $v->regras->remocao or 
                    $v->regras->inclusao or 
                    $v->regras->substituicao or
                    $v->regras->combo->remocao or
                    $v->regras->combo->inclusa or 
                    $v->regras->combo->substituicao
            ){
                ?>
                    </div>
                <?php
            }
            ?>
            </li>
            <?php
            }
        ?>
    </ul>
    <?php
    if($d->producao == 'entrega'){
    ?>
    <button type="button" class="btn btn-outline-danger btn-sm finalizar">Finalizar Pedido</button>
    <?php
    }
    ?>
</div>

<script>
    $(function(){

        $(".trocar_entregador").click(function(){

            $.confirm({
                title:"Trocar Entregador",
                content:`Você confirma a ação de troca de entregador?`,
                columnClass:"col-12",
                type:"red",
                buttons:{
                    'SIM':{
                        text:"Sim",
                        btnClass:"btn btn-danger",
                        action:function(){
                            Carregando();
                            $.ajax({
                                url:"pedido.php",
                                type:"POST",
                                data:{
                                    acao:'trocar_entregador',
                                    loja:'<?=$_POST['loja']?>'
                                },
                                success:function(dados){
                                    $(".popupPalco").html(dados);
                                    Carregando('none');
                                }
                            });

                        }
                    },
                    'NÃO':{
                        text:"Não",
                        btnClass:"btn btn-primary",
                        action:function(){

                        }
                    },
                }
            })

        });

        $("li[entrega_id]").click(function(){
            entrega_id = $(this).attr("entrega_id");
            entrega_nome = $(this).attr("entrega_nome");
            entrega_ddd = $(this).attr("entrega_ddd");
            entrega_telefone = $(this).attr("entrega_telefone");
            cliente_telefone = $(this).attr("cliente_telefone");
            loja = '<?=$_POST['loja']?>';


            $.confirm({
                title:"Definir Entregador",
                content:`Você acaba de selecionar <b>${entrega_nome}</b> para realizar a entrega.<br>Confirma a ação?`,
                columnClass:"col-12",
                type:"blue",
                buttons:{
                    'SIM':{
                        text:"Sim",
                        btnClass:"btn btn-primary",
                        action:function(){
                            Carregando();
                            $.ajax({
                                url:"pedido.php",
                                type:"POST",
                                data:{
                                    entrega_id,
                                    entrega_nome,
                                    entrega_ddd,
                                    entrega_telefone,
                                    cliente_telefone,
                                    loja,
                                    acao:'entregador'
                                },
                                success:function(dados){
                                    $(".popupPalco").html(dados);
                                    Carregando('none');
                                }
                            });

                        }
                    },
                    'NÃO':{
                        text:"Não",
                        btnClass:"btn btn-danger",
                        action:function(){

                        }
                    },
                }
            })

        });

        $(".finalizar").click(function(){
            $.confirm({
                title:"Finalizar Pedido",
                content:"Esta opção muda a situação do pedido como entregue ao cliente.<br>Deseja realmente finalizar o pedido?",
                columnClass:'col-12',
                buttons:{
                    sim:{
                        text:'Sim',
                        btnClass:'btn btn-danger',
                        action:function(){
                            Carregando();
                            $.ajax({
                                url:"pedido.php",
                                type:"POST",
                                data:{
                                    acao:'finalizar',
                                    pedido:'<?=$d->codigo?>',
                                },
                                success:function(dados){
                                    Carregando('none');
                                    $(".popupPalco").html(dados);
                                },
                                error:function(){
                                    console.log('erro');
                                }
                            });
                        }
                    },
                    nao:{
                        text:'Não',
                        btnClass:'btn btn-warning',
                        action:function(){
                            
                        }
                    }
                }
            })


        })
    })
</script>

  </body>
</html>