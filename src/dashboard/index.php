<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    // echo $query = "update produtos set 
    //                                 valor = '3.44'
    //                         where categoria = 2
    // ";
    // sisLog($query);
    
?>
<style>

</style>
<div class="row mb-3 mt-3">
    <div class="col-md-12">
        DashBoard
    </div>
</div>


<div class="m-3">
    
    <div class="row g-0">
        <div class="col-md-12 p-2">
            <h6>Resumo <?=(($_SESSION['dashboardDataInicial'] and $_SESSION['dashboardDataFinal'])? "de ".dataBr($_SESSION['dashboardDataInicial'])." a ".dataBr($_SESSION['dashboardDataFinal']):'Geral')?></h6>
        </div>
        <div class="col-md-2 p-2">
            <div class="alert alert-secondary" role="alert">
                <span>Planilhas Importadas</span>
                <h1><?=$v->planilhas?></h1>
            </div>
        </div>
        <div class="col-md-2 p-2">
            <div class="alert alert-primary" role="alert">
                <span>Quantidade Vendas</span>
                <h1><?=$v->vendas?></h1>
            </div>
        </div>

        <div class="col-md-2 p-2">
            <div class="alert alert-warning" role="alert">
                <span>Quantidade Devolução</span>
                <h1><?=$v->devolucao?></h1>
            </div>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-danger" role="alert">
                <span>Total Devolução</span>
                <h1>R$ <?=number_format($v->pagamento_devolucao,2,',','.')?></h1>
            </div>
        </div>


        <div class="col-md-3 p-2">
            <div class="alert alert-success" role="alert">
                <span>Total Vendas</span>
                <h1>R$ <?=number_format($v->pagamento_produto,2,',','.')?></h1>
            </div>
        </div>


        
    </div>
</div>




<script>
    $(function(){
        Carregando('none')
        
    })
</script>