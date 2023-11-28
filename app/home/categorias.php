<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>
<style>
    .combo{
        margin:5px;
        background-color:red;
        border-radius:20px;
    }
    .categorias{
        margin:5px;
        background-color:orange;
        border-radius:20px;
    }
    
</style>
<div class="row g-0">
    <div class="col-12">
        <div class="combo"></div>
    </div>
    <?php
    for($i=0;$i<6;$i++){
    ?>
    <div class="col-6">
        <div class="categorias"></div>
    </div>
    <?php
    }
    ?>
</div>