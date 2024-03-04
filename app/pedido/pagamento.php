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
    .loja_fechada{
        display:none!important;
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


            <div class="d-flex justify-content-between mt-3 pagamentos">    
                <div class="enderecoLabel w-100 text-center pe-2">
                    <button class="btn btn-success w-100" pagamento="pix">
                        <i class="fa-brands fa-pix"></i>
                        PIX                        
                    </button>
                </div>
                <div class="enderecoLabel w-100 text-center ps-2">
                    <button class="btn btn-success w-100" pagamento="credito">
                        <i class="fa-regular fa-credit-card"></i>
                        Crédito                        
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-3 loja_fechada">  
                <div class="alert alert-danger w-100 text-center" role="alert">
                    Loja Fechada
                </div>
            </div>

    </div>
</div>


<script>
    $(function(){

        total = ($("div[total]").attr("total"))*1;
        taxa = ($("span[valor_taxa].ativo").attr("valor_taxa"))*1;
        codTaxa = ($("span[codigo_taxa].ativo").attr("codigo_taxa"));
        loja = ($("span[codigo_taxa].ativo").attr("loja"));
        distancia = ($("span[distancia].ativo").attr("distancia"))*1;
        pagar = (total*1+taxa*1);

        $("span[total]").html('R$ ' + total.toLocaleString('pt-br', {minimumFractionDigits: 2}));
        $("span[taxa_entraga]").html('R$ ' + taxa.toLocaleString('pt-br', {minimumFractionDigits: 2}));
        $("span[pagar]").html('R$ ' + pagar.toLocaleString('pt-br', {minimumFractionDigits: 2}));

        if(!taxa){
            $(".pagamentos").remove();
            $(".loja_fechada").removeClass("loja_fechada");
        }

        $("button[pagamento]").click(function(){
            
            pagamento = $(this).attr("pagamento");

            total = ($("div[total]").attr("total"))*1;
            taxa = ($("span[valor_taxa].ativo").attr("valor_taxa"))*1;
            codTaxa = ($("span[codigo_taxa].ativo").attr("codigo_taxa"));
            loja = ($("span[codigo_taxa].ativo").attr("loja"));
            distancia = ($("span[distancia].ativo").attr("distancia"))*1;
            pagar = (total*1+taxa*1);
            
            cupom = 0;
            valor_cupom = 0;
            valor_compra = total;
            valor_entrega = taxa;
            codigo_entrega = codTaxa;
            valor_desconto = 0;
            valor_total = pagar;

            idUnico = localStorage.getItem("idUnico");
            codUsr = localStorage.getItem("codUsr");
            // codVenda = localStorage.getItem("codVenda");
            localStorage.removeItem("codVenda");

            // alert(codVenda);

            // return false;

            if(distancia > 7999){
                $.alert({
                    title:"Limite de Distância",
                    content:`Infelizmente o seu endereço está fora da nossa área de entregas. Logo estaremos entregando para todos os bairros de Manaus.`,
                    type:"red"
                });
                return false;
            }


            $.confirm({
                title:"Confirmação de pagamento",
                content:"Esta operação finaliza a sua compra e gera a cobrança.<br>Deseja prosseguir?",
                buttons:{
                    sim:{
                        text:'Sim',
                        btnClass:'btn btn-success',
                        action:function(){
                            
                            Carregando();
                            $.ajax({
                                url:`pagamento/${pagamento}.php`,
                                type:"POST",
                                data:{
                                    pagamento,
                                    cupom,
                                    valor_cupom,
                                    valor_compra,
                                    valor_entrega,
                                    codigo_entrega,
                                    loja,
                                    valor_desconto,
                                    valor_total,
                                    idUnico,
                                    codUsr,
                                    // codVenda,                    
                                },
                                success:function(dados){
                                    $(".popupPalco").html(dados);
                                    $(".popupArea").css('display','flex');
                                    Carregando('none');
                                    $.ajax({
                                        url:"pedido/resumo.php",
                                        type:"POST",
                                        data:{
                                            idUnico,
                                            codUsr
                                        },
                                        success:function(dados){
                                            $(`.CorpoApp`).html(dados);
                                        }
                                    });  
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