<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }

    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
        $where = " where codigo = '{$_SESSION['codUsr']}'";
    }

    $query = "select * from clientes where codigo = '{$_POST['codUsr']}'";

    $result = mysqli_query($con, $query);

    $d = mysqli_fetch_object($result);

?>

<style>
    .enderecoLabel{
        white-space: nowrap;
        overflow: hidden; /* "overflow" value must be different from "visible" */
        text-overflow: ellipsis;
        color:#333;
        font-size:14px;
        cursor:pointer;
    }
</style>

<div class="row g-0 p-2">
    <div class="card p-2">
        <h4 class="w-100 text-center">Dados do Cliente</h4>

            <div class="d-flex justify-content-between">    
                <div class="enderecoLabel w-100" >
                    <i class="fa-solid fa-location-dot"></i>
                    <?=$d->nome?>
                </div> 
            </div>  
            <div class="d-flex justify-content-between">    
                <div class="enderecoLabel w-50" >
                    <i class="fa-solid fa-location-dot"></i>
                    <?=$d->cpf?>
                </div> 
                <div class="enderecoLabel w-50" >
                    <i class="fa-solid fa-location-dot"></i>
                    <?=$d->telefone?>
                </div> 
            </div>  

        
    </div>
</div>


<script>
    $(function(){


    })
</script>