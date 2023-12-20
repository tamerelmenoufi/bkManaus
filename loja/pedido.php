<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    if($_POST['loja']){
        $_SESSION['bkLoja'] = $_POST['loja'];
    }

    if($_POST['pedido']){
        $_SESSION['pedido'] = $_POST['pedido'];
    }


?>
<style>
    .popupPalco{
        overflow:auto;
    }
</style>

<div class="row g-0 m-3">

    <ul class="list-group">
        <?php
        $query = "select a.*, b.nome, a.delivery_detalhes->>'$.pickupCode' as entrega, a.delivery_detalhes->>'$.returnCode' as retorno from vendas a left join clientes b on a.cliente = b.codigo where a.codigo = '{$_SESSION['pedido']}'";
        $result = mysqli_query($con, $query);
        while($d = mysqli_fetch_object($result)){
        ?>
            <li class="list-group-item" pedido="<?=$d->codigo?>">
                <div class="d-flex justify-content-between">
                    <div>
                        Pedido #<?=str_pad($d->codigo, 6, "0", STR_PAD_LEFT)?>
                        <br>
                        <?=$d->nome?>
                    </div>
                    <div>
                        Entrega: <?=$d->entrega?>
                        <br>
                        Retorno: <?=$d->retorno?>
                    </div>
                </div>
            </li>
        <?php

            $pedido = json_decode($d->detalhes);

            foreach($pedido as $i => $v){
                print_r($v);
                echo "<br><br>";
                echo "Tipo:".$v->tipo."<br>";
                echo "Total:".$v->total."<br>";
                echo "Valor:".$v->valor."<br>";
                echo "Codigo:".$v->codigo."<br>";
                echo "Quantidade:".$v->quantidade."<br>";
                // echo "Categoria:".$v->categoria."<br>";
                echo "Status:".$v->status."<br>";
                echo "Adicional:".$v->adicional."<br><hr>";


                if($v->tipo == 'produto'){
                    echo "Remoção:<br>";
                    foreach($v->regras->remocao as $i1 => $v1){
                        echo "Remocao: {$v1}<br>";
                    }

                    echo "<hr>Inclusão:<br>";
                    foreach($v->regras->inclusao as $i1 => $v1){
                        $qt = $v->regras->inclusao_quantidade;
                        $vl = $v->regras->inclusao_valor;
                        echo "{$qt[$i1]} x Inclusão {$v1} - {$vl[$i1]} = ".($vl[$i1] * $qt[$i1])."<br>";
                        // echo "{$i1} x {$v1}<br>";
                    }
                }else if($v->tipo == 'combo'){

                    echo "Remoção:<br>";
                    foreach($v->regras->combo->remocao as $i1 => $v1){
                        echo "Remocao: {$v1->item} - {$v1->produto}<br>";
                    }

                    echo "<hr>Inclusão:<br>";
                    foreach($v->regras->combo->inclusao as $i1 => $v1){
                        $qt = $v->regras->combo->inclusao_quantidade;
                        $vl = $v->regras->combo->inclusao_valor;
                        echo "{$qt[$i1]->quantidade} / {$qt[$i1]->produto} x Inclusão {$v1->item} / {$v1->produto} - {$vl[$i1]->valor} / {$vl[$i1]->produto} = ".($vl[$i1]->valor * $qt[$i1]->quantidade)."<br>";
                        
                    }
                    
                }

                echo "<hr>Anotações:".$v->anotacoes."<br><br><br>";


            }

        }
        ?>
    </ul>

</div>

<script>
    $(function(){
        

    })
</script>

  </body>
</html>