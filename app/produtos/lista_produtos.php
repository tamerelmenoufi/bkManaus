<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>
<style>
    .home_corpo{
        position: absolute;
        top:0;
        bottom:90px;
        overflow:auto;
        background-color:#fff;
        width:100%;
    }

    .home_rodape{
        position: absolute;
        background-color:#fff;
        width:100%;
        bottom:0;
        height:90px;
    }
</style>

<div class="home_corpo">

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


})

	

</script>