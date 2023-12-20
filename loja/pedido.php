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

                $q = "select * from produtos where codigo ='{$v->codigo}'";
                $r = mysqli_query($con, $q);
                $P = mysqli_fetch_object($r);

                print_r($v);
                echo "<br><br>";
        ?>

                <div class="d-flex justify-content-between">
                    <div>
                        <b><?="{$v->quantidade} X {$P->produto}"?></b>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div>
                        Valor Unitário
                    </div>
                    <div>
                        <?="{$v->valor}"?>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div>
                        Valor Adicional
                    </div>
                    <div>
                        <?="{$v->adicional}"?>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div>
                        Valor Total
                    </div>
                    <div>
                        <b><?="{$v->total}"?></b>
                    </div>
                </div>
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


                if($v->tipo == 'produto'){
                    
                    if($v->regras->remocao){
                        echo "Remover do :<br>";
                        $q = "select * from itens where codigo in (".implode(",", $v->regras->remocao).")";
                        $r = mysqli_query($con, $q);
                        while($s = mysqli_fetch_object($r)){
                            echo "{$s->item}<br>";
                        }
                        // foreach($v->regras->remocao as $i1 => $v1){
                        //     echo "Remocao: {$v1}<br>";
                        // }
                    }

                    if($v->regras->inclusao){
                        echo "<hr>Inclusão:<br>";
                        $q = "select * from itens where codigo in (".implode(",", $v->regras->inclusao).")";
                        $r = mysqli_query($con, $q);
                        $qt = $v->regras->inclusao_quantidade;
                        $vl = $v->regras->inclusao_valor;                        
                        while($s = mysqli_fetch_object($r)){
                            $pnt = array_search($s->codigo, $v->regras->inclusao);
                            echo "{$qt[$pnt]} x $s->item - {$vl[$pnt]} = ".($vl[$pnt] * $qt[$pnt])."<br>";

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
                        echo "<hr>Substituição:<br>";

                        $q = "select * from itens where codigo in (".implode(",", $v->regras->substituicao).")";
                        $r = mysqli_query($con, $q);
                        $vl = $v->regras->substituicao_valor;                        
                        while($s = mysqli_fetch_object($r)){
                            $pnt = array_search($s->codigo, $v->regras->substituicao);
                            echo "$s->item - {$vl[$pnt]}<br>";

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
                        echo "Remoção:<br>";
                        foreach($v->regras->combo->remocao as $i1 => $v1){
                            echo "Remocao: {$v1->item} - {$v1->produto}<br>";
                        }                        
                    }

                    if($v->regras->combo->inclusao){
                        echo "<hr>Inclusão:<br>";
                        foreach($v->regras->combo->inclusao as $i1 => $v1){
                            $qt = $v->regras->combo->inclusao_quantidade;
                            $vl = $v->regras->combo->inclusao_valor;
                            echo "{$qt[$i1]->quantidade} / {$qt[$i1]->produto} x Inclusão {$v1->item} / {$v1->produto} - {$vl[$i1]->valor} / {$vl[$i1]->produto} = ".($vl[$i1]->valor * $qt[$i1]->quantidade)."<br>";
                            
                        }                        
                    }

                    if($v->regras->combo->substituicao){
                        echo "<hr>Substituição:<br>";
                        foreach($v->regras->combo->substituicao as $i1 => $v1){
                            $vl = $v->regras->combo->substituicao_valor;
                            echo "Substituição {$v1->item} / {$v1->produto} - {$vl[$i1]->valor} / {$vl[$i1]->produto}<br>";
                            
                        }                        
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