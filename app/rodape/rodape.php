<?php
include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>

<style>
    .rodape{
        position:absolute;
        bottom:0;
        width:100%;
        background-color:#fff;
        height:90px;
        z-index:1;
    }
    .rodape_area{
        position:absolute;
        margin:10px;
        padding-right:30px;
        border-radius:40px;
        background-color:red;
        left:0;
        right:0;
        top:0;
        bottom:0;
        z-index:10;
    }
    .rodape_area img{
        height:70px;
        width:auto;
    }
    .rodape_area div{
        height:100%;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        flex-direction: column;
        border:solid 1px green;
    }
    .rodape_area i{
        font-size:40px;
        color:#ffdb37;
        margin:0;
        padding:0;
    }
    .rodape_area p{
        font-size:12px;
        color:#fff;
        margin:0;
        padding:0;
    }
</style>
<div class="rodape">
    <div class="d-flex justify-content-between align-items-center rodape_area">
        <img src="img/logo.png" />
        <div>
            <i class="fa-solid fa-user"></i>
            <p>Usuário</p>
        </div>
        <div>
            <i class="fa-solid fa-bag-shopping"></i>
            <p>Usuário</p>
        </div>        
        <div>
            <i class="fa-solid fa-circle-dollar-to-slot"></i>
            <p>Usuário</p>
        </div>        
    </div>
</div>