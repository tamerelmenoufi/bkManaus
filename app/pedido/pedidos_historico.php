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

    $query = "select *, pix_detalhes->>'$.id' as operadora_id from vendas where (device = '{$_SESSION['idUnico']}' or cliente = '{$_SESSION['codUsr']}') and situacao = 'pendente'";
    $result = mysqli_query($con, $query);

    $q = mysqli_num_rows($result);

    if($q){
?>
    <div class="card p-2 mb-3">
        <h6 class="w-100 text-center">PEDIDOS PENDENTES</h6>
<?php
        while($d = mysqli_fetch_object($result)){
?>
            <hr>
            <h6>Pedido #<?=str_pad($d->codigo, 6, "0", STR_PAD_LEFT)?></h6>
            <div class="d-flex justify-content-between">    
                <div class="pedidosLabel w-100" >
                    <i class="fa-solid fa-dollar-sign"></i>
                    Valor do Pedido
                </div>
                <div class="valores">R$ <?=number_format($d->valor_compra,2,',',false)?></div>
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
                <div class="valores"><b>R$ <?=number_format($d->valor_total,2,',',false)?></b></div>
            </div>
            <div class="d-flex justify-content-between mt-2">    
                <button type="button" class="btn btn-danger" pedido="<?=$d->codigo?>"
                        style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                    <i class="fa-solid fa-receipt"></i> pedido
                </button>
                <div>
                    <button type="button" class="btn btn-success me-2" pix="<?=$d->codigo?>" pagamento="<?=$d->operadora_id?>"
                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                        <i class="fa-brands fa-pix"></i> PIX
                    </button>
                    <button type="button" class="btn btn-success" credito="<?=$d->codigo?>" valor_total="<?=$d->valor_total?>"
                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                        <i class="fa-regular fa-credit-card"></i> Crédito
                    </button>
                </div>
            </div>
<?php
        }
?>
    </div>
<?php
    }



if(!$_POST['novoPedido']){
    
    $query = "select * from vendas where (device = '{$_SESSION['idUnico']}' or cliente = '{$_SESSION['codUsr']}') and situacao != 'pendente' order by codigo desc ";
    $result = mysqli_query($con, $query);

    $q = mysqli_num_rows($result);

    if($q){
?>
    <div class="card p-2 mb-3">
        <h6 class="w-100 text-center">HISTÓRICO DE PEDIDOS</h6>
<?php
        while($d = mysqli_fetch_object($result)){
?>
            <hr>
            <div class="d-flex justify-content-between">    
                <div class="pedidosLabel w-100" >
                    <h6>Pedido #<?=str_pad($d->codigo, 6, "0", STR_PAD_LEFT)?></h6>
                </div>
                <div class="valores">
                    <b><?=strtoupper($d->producao)?></b>
                </div>
            </div> 
            <div class="d-flex justify-content-between">    
                <div class="pedidosLabel w-100" >
                    <i class="fa-solid fa-dollar-sign"></i>
                    Valor do Pedido
                </div>
                <div class="valores">R$ <?=number_format($d->valor_compra,2,',',false)?></div>
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
                <div class="valores"><b>R$ <?=number_format($d->valor_total,2,',',false)?></b></div>
            </div>
            <div class="d-flex justify-content-between mt-2">    
                <button type="button" class="btn btn-danger" pedido="<?=$d->codigo?>"
                        style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                    <i class="fa-solid fa-receipt"></i> pedido
                </button>
                <div>
                    <button type="button" class="btn btn-outline-secondary" disabled
                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                        <i class="fa-solid fa-clipboard-question"></i> <?=$d->situacao?>
                    </button>
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
}
?>


<script>
    $(function(){

        $(".home_valores").remove();
        $(".home_corpo").css("bottom","80px");

        $("button[pedido]").click(function(){

        
            codVenda = $(this).attr("pedido");
            idUnico = localStorage.getItem("idUnico");
            codUsr = localStorage.getItem("codUsr");

            Carregando();
            $.ajax({
                url:"pedido/pedido_editar.php",
                type:"POST",
                data:{
                    idUnico,
                    codUsr,
                    codVenda,                    
                },
                success:function(dados){
                    $(".popupPalco").html(dados);
                    $(".popupArea").css('display','flex');
                    Carregando('none');
                    // $.ajax({
                    //     url:"pedido/resumo.php",
                    //     type:"POST",
                    //     data:{
                    //         idUnico,
                    //         codUsr
                    //     },
                    //     success:function(dados){
                    //         $(`.CorpoApp`).html(dados);
                    //     }
                    // });  
                }
            });
        })


        $("button[pix]").off('click').on('click',function(){

            codVenda = $(this).attr("pix");
            pagamento = $(this).attr("pagamento");
            idUnico = localStorage.getItem("idUnico");
            codUsr = localStorage.getItem("codUsr");
            localStorage.removeItem("codVenda");

            Carregando();
            $.ajax({
                url:"pagamento/pix.php",
                type:"POST",
                data:{
                    idUnico,
                    codUsr,
                    codVenda,     
                    pagamento               
                },
                success:function(dados){
                    $(".popupPalco").html(dados);
                    $(".popupArea").css('display','flex');
                    Carregando('none');
                    // $.ajax({
                    //     url:"pedido/resumo.php",
                    //     type:"POST",
                    //     data:{
                    //         idUnico,
                    //         codUsr
                    //     },
                    //     success:function(dados){
                    //         $(`.CorpoApp`).html(dados);
                    //     }
                    // });  
                }
            });
        })

        $("button[credito]").off('click').on('click',function(){

            codVenda = $(this).attr("credito");
            valor_total = $(this).attr("valor_total");
            idUnico = localStorage.getItem("idUnico");
            codUsr = localStorage.getItem("codUsr");
            localStorage.removeItem("codVenda");

            Carregando();
            $.ajax({
                url:"pagamento/credito.php",
                type:"POST",
                data:{
                    idUnico,
                    codUsr,
                    codVenda,
                    valor_total                  
                },
                success:function(dados){
                    $(".popupPalco").html(dados);
                    $(".popupArea").css('display','flex');
                    Carregando('none');
                    // $.ajax({
                    //     url:"pedido/resumo.php",
                    //     type:"POST",
                    //     data:{
                    //         idUnico,
                    //         codUsr
                    //     },
                    //     success:function(dados){
                    //         $(`.CorpoApp`).html(dados);
                    //     }
                    // });  
                }
            });
        })


    })
</script>