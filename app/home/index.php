<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>
<style>
    .home_promocao{
        position: absolute;
        top:0;
        bottom:90px;
        overflow:auto;
        background-color:#fff;
        width:100%;
        border: solid 1px blue;
    }


    * {
        scrollbar-width: thin;
        scrollbar-color: black transparent;
    }

    *::-webkit-scrollbar {
        width: 3px;
        height: 3px; /* A altura só é vista quando a rolagem é horizontal */
    }

    *::-webkit-scrollbar-track {
        background: transparent;
        padding: 2px;
    }

    *::-webkit-scrollbar-thumb {
        background-color: #000;
    }



    .home_rodape{
        position: absolute;
        background-color:#fff;
        width:100%;
        bottom:0;
        height:90px;
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