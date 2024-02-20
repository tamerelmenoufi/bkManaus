<?php
    $app = true;
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        mysqli_query($con, "insert into vendas_tmp set id_unico = '{$_POST['idUnico']}', cliente='{$_POST['codUsr']}', detalhes='{}'");
    }
?>
<style>
    .home_corpo{
        position: absolute;
        top:0;
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

<div class="home_corpo">
    <div class="home_promocao"></div>
    <div class="home_categorias"></div>
</div>
<div class="home_rodape"></div>

<script>

$(function(){

    $.ajax({
        url:"home/banner.php",
        success:function(dados){
            $(".home_promocao").html(dados);
        }
    });


    $.ajax({
        url:"home/categorias.php",
        success:function(dados){
            $(".home_categorias").html(dados);
        }
    });

    
    $.ajax({
        url:"rodape/rodape.php",
        success:function(dados){
            $(".home_rodape").html(dados);
        }
    });



})

	

</script>