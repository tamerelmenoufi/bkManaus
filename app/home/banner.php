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
        margin-top:-40px;
        height:40px;
        width:100%;
    }
    .barra_banner div{
        background-color:#fff;
        width:100%;
    } 
</style>

<div class="slider">
    <div class="slider-for">
        <div style="position:relative; background:red;">
            <img src="img/transparente.png" style="width:100%;" />
        </div>
        
        <div style="position:relative; background:orange;">
            <img src="img/transparente.png" style="width:100%;" />
        </div>

        <div style="position:relative; background:green;">
            <img src="img/transparente.png" style="width:100%;" />
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