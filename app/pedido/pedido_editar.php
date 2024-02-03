<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }
    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
    }

    if($_POST['codVenda']){
        $_SESSION['codVenda'] = $_POST['codVenda'];
    }

    $query = "select * from vendas where codigo = '{$_SESSION['codVenda']}'";
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
        <h6 class="w-100 text-center">RESUMO DO PEDIDO</h6>
                <?php

                    foreach(json_decode($d->detalhes) as $i => $dados){

                        $pd = mysqli_fetch_object(mysqli_query($con, "select * from produtos where codigo = '{$dados->codigo}'"));
                        if($dados->status){
                ?>
            <div class="d-flex justify-content-between">    
                <div class="enderecoLabel w-70" codigo="<?=$c->codigo?>">
                    <i class="fa-solid fa-location-dot"></i>
                    <?=$dados->quantidade?> x <?=$pd->produto?>
                </div> 
                <div class="d-flex justify-content-between enderecoLabel">
                    <!-- <div>R$ <?=number_format($dados->total,2,',',false)?></div> -->
                    <div class="w-100 text-end">R$ <?=number_format($dados->total*$dados->quantidade,2,',',false)?></div>
                </div>
            </div>    
            <?php
                $total = ($total + ($dados->total*$dados->quantidade));

                        }
                    }
            ?>
            <div class="w-100 text-end enderecoLabel mt-3">Compra R$ <?=number_format($d->valor_compra,2,',',false)?></div>
            <div class="w-100 text-end enderecoLabel">Entrega R$ <?=number_format($d->valor_entrega,2,',',false)?></div>
            <div class="w-100 text-end enderecoLabel">Desconto R$ <?=number_format($d->valor_desconto,2,',',false)?></div>
            <div class="w-100 text-end enderecoLabel"><b>TOTAL R$ <?=number_format($d->valor_total,2,',',false)?></b></div>


            <div class="d-flex justify-content-between mt-3">
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


    </div>
</div>



<script>

$(function(){

    Tempo = false;

    idUnico = localStorage.getItem("idUnico");
    codUsr = localStorage.getItem("codUsr");

})

</script>