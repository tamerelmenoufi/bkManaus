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
  <div><h3>1</h3></div>
  <div><h3>2</h3></div>
  <div><h3>3</h3></div>
  <div><h3>4</h3></div>
  <div><h3>5</h3></div>
  <div><h3>6</h3></div>
</div>

<div style="height:1200px;"></div>

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