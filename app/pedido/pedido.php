<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }

    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
        $where = " where codigo = '{$_SESSION['codUsr']}'";
    }

    $query = "select * from vendas_tmp where id_unico = '{$_POST['idUnico']}'";

    $result = mysqli_query($con, $query);

    $d = mysqli_fetch_object($result);

?>

<style>
    .enderecoLabel{
        white-space: nowrap;
        overflow: hidden; /* "overflow" value must be different from "visible" */
        text-overflow: ellipsis;
        color:#333;
        font-size:14px;
        cursor:pointer;
    }
</style>

<div class="row g-0 p-2 mt-3">
    <div class="card p-2">
        <h4 class="w-100 text-center">Resumo do Pedido</h4>
        
            
                <?php

                    foreach(json_decode($d->detalhes) as $i => $dados){

                        $pd = mysqli_fetch_object(mysqli_query($con, "select * from produtos where codigo = '{$dados->codigo}'"));
                        if($dados->status){
                ?>
            <div class="d-flex justify-content-between">    
                <div class="enderecoLabel w-50" codigo="<?=$c->codigo?>">
                    <i class="fa-solid fa-location-dot"></i>
                <?php
                            echo $pd->produto;
                            $total = ($total + ($dados->total*$dados->quantidade));

                ?>
                </div> 
                <div class="d-flex justify-content-between w-50">
                    <div>R$ <?=number_format($dados->total,2,',',false)?></div>
                    <div> x <?=$dados->quantidade?></div>
                    <div>R$ <?=number_format($dados->total*$dados->quantidade,2,',',false)?></div>
                </div>
            </div>    
            <?php

                        }
                    }
                    echo " Total: ". $total."<br>";
            ?>
        
    </div>
</div>


<script>
    $(function(){


    })
</script>