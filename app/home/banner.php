<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>
<style>
    .slider{
        position:relative;
        background:#fff;
        width:100%;        
    }
    .slider-for{
        position:relative;
        width:100%;
    }
    .slider-for img{
        margin:0;
        padding:0;
        width:100%;
        height:auto;
    }
    .barra_banner{
        position: absolute;
        margin-top:-36px;
        height:30px;
        width:100%;
    }
    .barra_banner div{
        background-color:#fff;
        width:100%;
    } 
</style>

<div class="slider">
    <div class="slider-for">

        <div style="position:relative; background:orange;">
            <img src="img/transparente.png" style="width:100%; position:relative;" />
            <div style="position:absolute; left:0, right:0; bottom:30px; top:80px; z-index:10">
                <div class="row">
                    <div class="col-12"><center><img src="img/banner.png?<?=$md5?>" style="width:70%;" /></center></div>
                    <div class="col-6" style="color:#fff; font-size:25px; text-align:right; font-family:FlameBold;">Combo<p>R$</p></div>
                    <div class="col-6" style="font-size:70px; color:#fff; font-family:FlameBold;">49<span style="font-size:25px; color:#fff; font-family:FlameBold;">99</span></div>
                </div>
            </div>
        </div>

    </div>
    <div class="d-flex justify-content-center barra_banner">
        <div></div>
        <img src="img/banner_seta.png" />
        <div></div>
    </div>
</div>



<script>

$(function(){

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