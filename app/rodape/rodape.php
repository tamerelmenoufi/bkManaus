<?php
include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>

<style>
    .rodape{
        position:absolute;
        bottom:0;
        width:100%;
        background-color:#fff;
        height:110px;
        z-index:1;
    }
    .rodape_area{
        position:absolute;
        margin:10px;
        border-radius:40px;
        background-color:red;
        left:0;
        right:0;
        top:0;
        bottom:0;
        z-index:10;
    }
    .rodape_area img{
        height:90px;
        width:auto;
    }
</style>
<div class="rodape">
    <div class="rodape_area">
        <img src="img/logo.png" />
    </div>
</div>