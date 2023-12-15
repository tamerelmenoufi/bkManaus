<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    if(1 == 1){
?>

<div class="row g-0 p-2">
    
    <div class="card p-2">
        <h4 class="w-100 text-center">PEDIDOS PENDENTES</h4>

            <div class="d-flex justify-content-between">    
                <div class="enderecoLabel w-100" >
                    <i class="fa-solid fa-location-dot"></i>
                    <?=$d->nome?>
                </div> 
            </div> 
        
    </div>

    <div class="card p-2">
        <h4 class="w-100 text-center">PEDIDOS ANTERIORES</h4>

            <div class="d-flex justify-content-between">    
                <div class="enderecoLabel w-100" >
                    <i class="fa-solid fa-location-dot"></i>
                    <?=$d->nome?>
                </div> 
            </div> 
        
    </div>

</div>

<?php
    }else{
?>
<h3 class='w-100 text-center' style='margin-top:200px;'>Sem Pedidos!</h3><p class='w-100 text-center'>Ainda não existe nenhum produto em sua cesta de comrpas.</p>
<?php
    }
?>