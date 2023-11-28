<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>
<style>
    .combo{
        margin:5px;
        background-color:red;
        border-radius:20px;
        height:90px;
    }
    .categorias{
        margin:5px;
        background-color:orange;
        border-radius:20px;
        height:90px;
    }
    .categorias img{
        margin:5px;
        height:80px;
    }
    .categorias span{
        margin:10px;
        /* word-break: break-word; */
        word-break: break-all;
        overflow: hidden; // Removendo barra de rolagem
        text-overflow: ellipsis; // Adicionando "..." ao final
        display: -webkit-box;
        -webkit-line-clamp: 2; // Quantidade de linhas
        -webkit-box-orient: vertical; 
    }
</style>
<div class="row g-0">
    <div class="col-12">
        <div class="combo"></div>
    </div>
    <?php
    $query = "select * from categorias where tipo = 'prd' and deletado != '1' and situacao = '1' order by ordem";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
    ?>
    <div class="col-6">
        <div class="d-flex justify-content-start categorias">
            <img src="img/logo.png" alt="">
            <span><?=$d->categoria?></span>
        </div>
    </div>
    <?php
    }
    ?>
</div>