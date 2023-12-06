<?php
include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>

<style>
    .rodape{
        position:absolute;
        bottom:0;
        width:100%;
        background-color:#fff;
        height:75px;
        z-index:1;
        border:solid 1px red;
    }
    .rodape_area{
        position:absolute;
        margin:8px;
        margin-top:15px;
        /* margin-bottom:10px; */
        padding-right:5px;
        border-radius:40px;
        background-color:#b60710;
        left:0;
        right:0;
        top:0;
        bottom:0;
        z-index:10;
    }
    .rodape_area img{
        height:70px;
        width:auto;
        cursor:pointer;
    }
    .rodape_area div{
        height:100%;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        flex-direction: column;
        cursor:pointer;
    }
    .rodape_area i{
        font-size:25px;
        color:#ffdb37;
        margin:0;
        padding:0;
    }
    .rodape_area p{
        font-family:Insanibu;
        font-size:12px;
        color:#fff;
        margin:0;
        padding:0;
    }
</style>
<div class="rodape">
    <div class="d-flex justify-content-between align-items-center rodape_area">
        <img home src="img/logo.png" />
        <div navegacao="usuarios/perfil.php">
            <i class="fa-solid fa-user"></i>
            <p>Perfil</p>
        </div>

        <div navegacao="pedido/resumo.php" class="telaPedido">
            <i class="fa-solid fa-bag-shopping"></i>
            <p>Pedido</p>
        </div>
        
        <div navegacao="pedido/resumo.php" class="telaPedido">
            <i class="fa-solid fa-bag-shopping"></i>
            <p>Pedido</p>
        </div>
        
        <div navegacao="pedido/resumo.php" class="telaPedido">
            <i class="fa-solid fa-bag-shopping"></i>
            <p>Pedido</p>
        </div>        

        <div navegacao="pedido/resumo.php">
            <i class="fa-solid fa-circle-dollar-to-slot"></i>
            <p>Pagar</p>
        </div>        
    </div>
</div>

<script>
    $(function(){

        $("img[home]").click(function(){
            Carregando();

            $.ajax({
                url:"home/index.php",
                type:"POST",
                data:{
                    historico:'.CorpoApp'
                },
                success:function(dados){
                    Carregando('none');
                    $(".CorpoApp").html(dados);
                }
            })

        });

        $("div[navegacao]").click(function(){
            Carregando();
            url = $(this).attr("navegacao");
            idUnico = localStorage.getItem("idUnico");
            $.ajax({
                url,
                type:"POST",
                data:{
                    idUnico,
                    historico:'.CorpoApp'
                },
                success:function(dados){
                    Carregando('none');
                    $(".CorpoApp").html(dados);
                }
            })

        });
    })
</script>