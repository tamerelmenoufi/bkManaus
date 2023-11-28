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
        <div class="d-flex justify-content-between categorias">
            <img src="" alt="">
            <?=$d->categoria?>
        </div>
    </div>
    <?php
    }
    ?>
</div>