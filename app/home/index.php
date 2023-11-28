<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>
<style>
    .slider-for{
        position:absolute;
        top:0;
        bottom:0;
        width:100%;
    }
    .slider-for div{
        margin:0;
        padding:0;
        width:100%;
        height:460px;
        background:#ccc;
        color:#000;
        text-align:center;
    }
</style>

<div class="slider-for">
  <div style="background-image:url(img/banner.png); background-size:contain;"></div>
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

    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        autoplay: true,
        autoplaySpeed: 5000,
    });

})

	

</script>