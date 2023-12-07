<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }

    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
    }
?>

<style>
    .barra_topo{
        position:absolute;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        flex-direction: column;
        top:0;
        width:100%;
        height:100px;
        background-color:#ffc63a;
        color:#c45018;
        border-bottom-right-radius:40px;
        border-bottom-left-radius:40px;
        font-family:FlameBold;
    }


    .home_corpo{
        position: absolute;
        top:100px;
        bottom:80px;
        overflow:auto;
        background-color:#fff;
        width:100%;
    }

    .home_rodape{
        position: absolute;
        background-color:#fff;
        width:100%;
        bottom:0;
        height:80px;
    }


</style>

<div class="barra_topo">
    <h2>Pagar</h2>
</div>


<div class="home_corpo">
    <div class="dados_pedido" local="pedido/pedido.php"></div>
    <div class="dados_pessoais" local="pedido/cliente.php"></div>
    <div class="dados_enderecos" local="pedido/enderecos.php"></div>
    <div class="dados_pagamento" local="pedido/pagamento.php"></div>
</div>   
<div class="home_rodape"></div>

<script>

$(function(){

    $.ajax({
        url:"rodape/rodape.php",
        success:function(dados){
            $(".home_rodape").html(dados);
        }
    });

    $.ajax({
        url:"topo/topo.php",
        success:function(dados){
            $(".barra_topo").append(dados);
        }
    });

    idUnico = localStorage.getItem("idUnico");
    codUsr = localStorage.getItem("codUsr");


    AbrirTelas = (tela, local)=>{
        $.ajax({
            url:tela,
            type:"POST",
            data:{
                idUnico,
                codUsr
            },
            success:function(dados){
                $(`.${local}`).html(dados);
            }
        });        
    }


    $("div[local]").each(function(){
        tela = $(this).attr("local");
        local = $(this).attr("class");
        AbrirTelas(tela, local);
    })
    

    //Novos testes

})

</script>