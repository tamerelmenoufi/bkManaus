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
    li[pedido], .dados{
        cursor:pointer;
        font-size:12px;
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
                <div class="d-flex justify-content-between dados">
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

                // print_r($v);
                // echo "<br><br>";
        ?>
            <li class="list-group-item" pedido="<?=$d->codigo?>">

                <div class="d-flex justify-content-between dados">
                    <div>
                        <b><?="{$v->quantidade} X ".(($v->tipo == 'combo')?'Combo ':false)."{$P->produto}"?></b>
                    </div>
                </div>
                <div class="d-flex justify-content-between dados">
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


                if(
                    $v->regras->remocao or 
                    $v->regras->inclusao or 
                    $v->regras->substituicao or
                    $v->regras->combo->remocao or
                    $v->regras->combo->inclusa or 
                    $v->regras->combo->substituicao
                ){
            ?>
                <div class="alert alert-dark dados" role="alert">
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
                        
                            <div><b>Remover de <?=$P->produto?></b></div>
                            <?=implode(", ", $lista)?><br>

            <?php
                        // echo "Remover do :<br>";

                        // foreach($v->regras->remocao as $i1 => $v1){
                        //     echo "Remocao: {$v1}<br>";
                        // }
                    }

                    if($v->regras->inclusao){
                        echo "<div><b>Incluir em {$P->produto}</b></div>";
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
                                <div>
                                    <b><?=number_format($vl[$pnt],2,',',false)?></b>
                                </div>
                                <div>
                                    <b><?=number_format($vl[$pnt] * $qt[$pnt],2,',',false)?></b>
                                </div>
                                
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
                        echo "<div><b>Substituir {$P->produto}</b></div>";

                        $q = "select * from itens where codigo in (".implode(",", $v->regras->substituicao).")";
                        $r = mysqli_query($con, $q);
                        $vl = $v->regras->substituicao_valor;                        
                        while($s = mysqli_fetch_object($r)){
                            $pnt = array_search($s->codigo, $v->regras->substituicao);
                            // echo "$s->item - {$vl[$pnt]}<br>";
                ?>
                            <div class="d-flex justify-content-between dados">
                                <div>
                                    <?="{$s->item}"?>
                                </div>
                                <div>
                                    <b><?=number_format($vl[$pnt],2,',',false)?></b>
                                </div>                                
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
                            <div><b>Remover de <?=$produto?></b></div>
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
                                    echo "<div><b>Incluir em {$s->produto}</b></div>";
                                    $produto = $s->produto;
                                }
                        ?>
                            <div class="d-flex justify-content-between dados">
                                <div>
                                    <?="{$lista[$i1][$s->codigo]['quantidade']} x $s->item"?>
                                </div>
                                <div>
                                    <b><?=number_format($lista[$i1][$s->codigo]['valor'],2,',',false)?></b>
                                </div>
                                <div>
                                    <b><?=number_format($lista[$i1][$s->codigo]['valor'] * $lista[$i1][$s->codigo]['quantidade'],2,',',false)?></b>
                                </div>
                                
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
                                    echo "<div><b>Subistituir {$s->produto}</b></div>";
                                    $produto = $s->produto;
                                }
                        ?>
                            <div class="d-flex justify-content-between dados">
                                <div>
                                    <?="{$s->item}"?>
                                </div>
                                <div>
                                    <b><?=number_format($lista[$i1][$s->codigo]['valor'],2,',',false)?></b>
                                </div>
                            </div>
                        <?php
                            }
                        }


                    }
 
                    
                }

                echo "<p><b>".$v->anotacoes."</b></p>";

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