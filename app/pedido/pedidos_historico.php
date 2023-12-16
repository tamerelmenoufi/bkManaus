<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

?>
<style>
    .pedidosLabel{
        white-space: nowrap;
        overflow: hidden; /* "overflow" value must be different from "visible" */
        text-overflow: ellipsis;
        color:#333;
        font-size:12px;
        cursor:pointer;
    }
    .valores{
        white-space: nowrap;
        font-size:12px;
    }
    .mais{
        color:blue;
    }
    .menos{
        color:red;
    }
</style>
<div class="row g-0 p-2">


<?php

    $query = "select * from vendas where (device = '{$_SESSION['idUnico']}' or cliente = '{$_SESSION['codUsr']}') and situacao = 'pendente'";
    $result = mysqli_query($con, $query);

    $q = mysqli_num_rows($result);

    if($q){
?>
    <div class="card p-2 mb-3">
        <h4 class="w-100 text-center">PEDIDOS PENDENTES</h4>
<?php
        while($d = mysqli_fetch_object($result)){
?>
            <hr>
            <h6>Pedido <?=$d->codigo?></h6>
            <div class="d-flex justify-content-between">    
                <div class="pedidosLabel w-100" >
                    <i class="fa-solid fa-dollar-sign"></i>
                    Valor do Pedido
                </div>
                <div class="valores">$R <?=number_format($d->valor_compra,2,',',false)?></div>
            </div> 
            <div class="d-flex justify-content-between">    
                <div class="pedidosLabel w-100" >
                    <i class="fa-solid fa-dollar-sign"></i>
                    Taxa de Enterga
                </div>
                <div class="valores"><i class="fa-solid fa-plus mais"></i> $R <?=number_format($d->valor_entrega,2,',',false)?></div>
            </div> 
            <div class="d-flex justify-content-between">    
                <div class="pedidosLabel w-100" >
                    <i class="fa-solid fa-dollar-sign"></i>
                    Desconto Cupom
                </div>
                <div class="valores"><i class="fa-solid fa-minus menos"></i> $R <?=number_format($d->valor_desconto,2,',',false)?></div>
            </div>
            <div class="d-flex justify-content-between">    
                <div class="pedidosLabel w-100" >
                    <i class="fa-solid fa-dollar-sign"></i>
                    Total
                </div>
                <div class="valores"><b>$R <?=number_format($d->valor_total,2,',',false)?></b></div>
            </div>
            <div class="d-flex justify-content-between mt-2">    
                <button type="button" class="btn btn-primary"
                        style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                    <i class="fa-solid fa-receipt"></i> pedido
                </button>
                <button type="button" class="btn btn-primary"
                        style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                    <i class="fa-brands fa-pix"></i> PIX
                </button>
                <button type="button" class="btn btn-primary"
                        style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                    <i class="fa-regular fa-credit-card"></i> Crédito
                </button>
            </div>
<?php
        }
?>
    </div>
<?php
    }




    $query = "select * from vendas where device = '{$_SESSION['idUnico']}' and situacao != 'pendente' order by situacao ";
    $result = mysqli_query($con, $query);

    $q1 = mysqli_num_rows($result);

    if($q1){
?>
    <div class="card p-2 mb-3">
        <h4 class="w-100 text-center">PEDIDOS PENDENTES</h4>
<?php
        while($d = mysqli_fetch_object($result)){
?>

            <div class="d-flex justify-content-between">    
                <div class="enderecoLabel w-100" >
                    <i class="fa-solid fa-location-dot"></i>
                    <?=$d->nome?>
                </div> 
            </div> 

<?php
        }
?>
    </div>
<?php
    }
?>


</div>

<?php
    $q = ($q + $q1);
    if(!$q){
?>
<h3 class='w-100 text-center' style='margin-top:200px;'>Sem Pedidos!</h3><p class='w-100 text-center'>Ainda não existe nenhum produto em sua cesta de comrpas.</p>
<?php
    }
?>