<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

?>

<div class="row g-0 p-2">


<?php

    $query = "select * from vendas where device = '{$_SESSION['idUnico']}' and situacao = 'pendente'";
    $result = mysqli_query($con, $query);

    $q = mysqli_num_rows($result);

    if($q){
?>
    <div class="card p-2 mb-3">
        <h4 class="w-100 text-center">PEDIDOS PENDENTES</h4>
<?php
        while($d = mysqli_fetch_object($result)){
?>

            <div class="d-flex justify-content-between">    
                <div class="enderecoLabel w-100" >
                    <i class="fa-solid fa-location-dot"></i>
                    <?=$d->nome?>
                </div> 
            </div> 

<?php
        }
?>
    </div>
<?php
    }




    $query = "select * from vendas where device = '{$_SESSION['idUnico']}' and situacao != 'pendente' order by situacao ";
    $result = mysqli_query($con, $query);

    $q = mysqli_num_rows($result);

    if($q){
?>
    <div class="card p-2 mb-3">
        <h4 class="w-100 text-center">PEDIDOS PENDENTES</h4>
<?php
        while($d = mysqli_fetch_object($result)){
?>

            <div class="d-flex justify-content-between">    
                <div class="enderecoLabel w-100" >
                    <i class="fa-solid fa-location-dot"></i>
                    <?=$d->nome?>
                </div> 
            </div> 

<?php
        }
?>
    </div>
<?php
    }
?>


</div>

<?php
    if(!$q){
?>
<h3 class='w-100 text-center' style='margin-top:200px;'>Sem Pedidos!</h3><p class='w-100 text-center'>Ainda não existe nenhum produto em sua cesta de comrpas.</p>
<?php
    }
?>