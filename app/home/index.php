<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>
<style>
    .home_promocao{
        position: relative;
        background-color:#fff;
        width:100%;
        border: solid 1px blue;
    }
    .home_rodape{
        position: relative;
        background-color:#fff;
        width:100%;
        margin-top:100px;
        border: solid 1px blue;
    }
</style>

<div class="home_promocao"></div>
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
        url:"rodape/rodape.php",
        success:function(dados){
            $(".home_rodape").html(dados);
        }
    });



})

	

</script>