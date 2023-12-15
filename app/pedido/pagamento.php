<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }

    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
        $where = " where codigo = '{$_SESSION['codUsr']}'";
    }

    $query = "select * from clientes where codigo = '{$_POST['codUsr']}'";

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
    .valores{
        white-space: nowrap;
    }
</style>

<div class="row g-0 p-2">
    <div class="card p-2">
        <h4 class="w-100 text-center">PAGAMENTO</h4>

            <div class="d-flex justify-content-between">    
                <div class="enderecoLabel w-100" >
                    <i class="fa-solid fa-location-dot"></i>
                    Total da compra
                </div>
                <span class="valores" total></span> 
            </div>  


            <div class="d-flex justify-content-between">    
                <div class="enderecoLabel w-100" >
                    <i class="fa-solid fa-location-dot"></i>
                    Taxa de Entrega
                </div>
                <span class="valores" taxa_entraga></span> 
            </div>

            <div class="d-flex justify-content-between">    
                <div class="enderecoLabel w-100">
                    <i class="fa-solid fa-location-dot"></i>
                    Total a Pagar
                </div>
                <span class="valores" pagar></span> 
            </div>


            <div class="d-flex justify-content-between mt-3">    
                <div class="enderecoLabel w-100 text-center pe-2">
                    <button class="btn btn-success w-100" pagamento="pix">
                        <i class="fa-brands fa-pix"></i>
                        PIX                        
                    </button>
                </div>
                <div class="enderecoLabel w-100 text-center ps-2">
                    <button class="btn btn-success w-100">
                        <i class="fa-regular fa-credit-card"></i>
                        Crédito                        
                    </button>
                </div>
            </div>

    </div>
</div>


<script>
    $(function(){

        total = ($("div[total]").attr("total"))*1;
        taxa = ($("span[valor_taxa].ativo").attr("valor_taxa"))*1;
        pagar = (total*1+taxa*1);

        $("span[total]").html('R$ ' + total.toLocaleString('pt-br', {minimumFractionDigits: 2}));
        $("span[taxa_entraga]").html('R$ ' + taxa.toLocaleString('pt-br', {minimumFractionDigits: 2}));
        $("span[pagar]").html('R$ ' + pagar.toLocaleString('pt-br', {minimumFractionDigits: 2}));

        $("button[pagamento]").click(function(){
            pagamento = $(this).attr("pagamento");
            cupom = 0;
            valor_compra = 0;
            valor_entrega = 0;
            valor_desconto = 0;
            valor_total = pagar;

            idUnico = localStorage.getItem("idUnico");
            codUsr = localStorage.getItem("codUsr");
            codVenda = localStorage.getItem("codVenda");
            Carregando();
            $.ajax({
                url:"pagamento/pix.php",
                type:"POST",
                data:{
                    pagamento,
                    cupom,
                    valor_compra,
                    valor_entrega,
                    valor_desconto,
                    valor_total,
                    idUnico,
                    codUsr,
                    codVenda,                    
                },
                success:function(dados){
                    // $(".dados_pagamento").html(dados);
                    $(".popupArea.popupPalco").html(dados);
                    $(".popupArea").css('display','flex');
                    Carregando('none');
                }
            });
        })


    })
</script>