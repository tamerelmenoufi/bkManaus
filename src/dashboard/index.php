<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['filtro'] == 'filtrar'){
        $_SESSION['dashboardDataInicial'] = $_POST['dashboardDataInicial'];
        $_SESSION['dashboardDataFinal'] = $_POST['dashboardDataFinal'];
      }elseif($_POST['filtro']){
        $_SESSION['dashboardDataInicial'] = false;
        $_SESSION['dashboardDataFinal'] = false;
      }
  
      if($_SESSION['dashboardDataInicial'] and $_SESSION['dashboardDataFinal']){
        $where = " and data between '{$_SESSION['dashboardDataInicial']} 00:00:00' and '{$_SESSION['dashboardDataFinal']} 23:59:59' ";
        $titulo = " de ".dataBr($_SESSION['dashboardDataInicial'])." a ".dataBr($_SESSION['dashboardDataFinal']);
      }else if($_SESSION['dashboardDataInicial']){
        $where = " and data like '{$_SESSION['dashboardDataInicial']}%' ";
        $titulo = " em  ".dataBr($_SESSION['dashboardDataInicial']);
      }


    $query = " SELECT
            (select count(*) from produtos where situacao = '1' and deletado != '1') as quantidade_produtos,
            (select count(*) from vendas where situacao = 'pago' {$where}) as quantidade_vendas,
            (select count(*) from entregadores where deletado != '1') as quantidade_entregadores,
            (select count(*) from vendas where situacao = 'pago' and producao = 'entregue' {$where}) as quantidade_entregue,

            (select sum(valor_total) from vendas where situacao = 'pago' {$where}) as total_vendas,
            (select sum(valor_entrega) from vendas where situacao = 'pago' {$where}) as total_entregas,
            (select sum(valor_entrega) from vendas where situacao = 'cancelado' {$where}) as total_cancelados,

            (select count(*) from ifood where 1 {$where}) as quantidade_vendas_ifood,
            (select sum(valor) from ifood where 1 {$where}) as total_vendas_ifood,
            (select count(*) from ifood where producao = 'entregue' {$where}) as quantidade_entrgue_ifood
         

    ";
    $result = mysqli_query($con,$query);

    $d = mysqli_fetch_object($result);
    
?>
<style>

</style>


<div class="m-3">


    <div class="row g-0 mb-3 mt-3">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <div class="input-group">
                <label class="input-group-text">Filtro por Período </label>
                <label class="input-group-text" for="data_inicial"> De </label>
                <input type="date" id="data_inicial" class="form-control" <?=$busca_disabled?> value="<?=$_SESSION['dashboardDataInicial']?>" >
                <label class="input-group-text" for="data_final"> A </label>
                <input type="date" id="data_final" class="form-control" value="<?=$_SESSION['dashboardDataFinal']?>" >
                <button filtro="filtrar" class="btn btn-outline-secondary" type="button">Buscar</button>
                <button filtro="limpar" class="btn btn-outline-danger" type="button">limpar</button>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-0">
        <div class="col-md-12 p-2">
            <h6>Resumo <?=(($titulo)?:'Geral')?></h6>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-warning" role="alert">
                <span>Produtos</span>
                <h1><?=number_format($d->quantidade_produtos,0,',','.')?></h1>
            </div>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-success" role="alert">
                <span>Vendas</span>
                <h1><?=number_format($d->quantidade_vendas,0,',','.')?></h1>
            </div>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-primary" role="alert">
                <span>Entregadores</span>
                <h1><?=number_format($d->quantidade_entregadores,0,',','.')?></h1>
            </div>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-secondary" role="alert">
                <span>Entregas</span>
                <h1><?=number_format($d->quantidade_entregue,0,',','.')?></h1>
            </div>
        </div>
        
    </div>
</div>


<div class="m-3">
    
    <div class="row g-0">
        <div class="col-md-12 p-2">
            <h6>Resumo Financeiro<?=(($titulo)?:' Geral')?></h6>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-success" role="alert">
                <span>Total de Vendas</span>
                <h1>R$ <?=number_format($d->total_vendas,2,',','.')?></h1>
            </div>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-warning" role="alert">
                <span>Custo de entregas</span>
                <h1>R$ <?=number_format($d->total_entregas,2,',','.')?></h1>
            </div>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-primary" role="alert">
                <span>Tickt Médio</span>
                <h1>R$ <?=number_format( ($d->total_vendas/(($d->quantidade_vendas)?:1)) ,2,',','.')?></h1>
            </div>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-danger" role="alert">
                <span>Devoluções</span>
                <h1>R$ <?=number_format($d->total_cancelados,2,',','.')?></h1>
            </div>
        </div>

        
    </div>
</div>

<div class="m-3">
    
    <div class="row g-0">
        <div class="col-md-12 p-2">
            <h6>Resumo Ifood<?=(($titulo)?:' Geral')?></h6>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-primary" role="alert">
                <span>Quantidade de Vendas</span>
                <h1><?=number_format($d->quantidade_vendas_ifood,0,',','.')?></h1>
            </div>
        </div>


        <div class="col-md-3 p-2">
            <div class="alert alert-success" role="alert">
                <span>Total das Vendas</span>
                <h1>R$ <?=number_format($d->total_vendas_ifood,2,',','.')?></h1>
            </div>
        </div>        

        <div class="col-md-3 p-2">
            <div class="alert alert-warning" role="alert">
                <span>Entregas finalizadas</span>
                <h1><?=number_format($d->quantidade_entrgue_ifood,0,',','.')?></h1>
            </div>
        </div>

        <div class="col-md-3 p-2">
            <div class="alert alert-primary" role="alert">
                <span>Tickt Médio</span>
                <h1>R$ <?=number_format($d->total_vendas_ifood/(($d->quantidade_vendas_ifood)?:1),2,',','.')?></h1>
            </div>
        </div>

        
    </div>
</div>


<script>
    $(function(){
        Carregando('none')

        $("button[filtro]").click(function(){
          filtro = $(this).attr("filtro");
          dashboardDataInicial = $("#data_inicial").val();
          dashboardDataFinal = $("#data_final").val();
          Carregando()
          $.ajax({
              url:"src/dashboard/index.php",
              type:"POST",
              data:{
                  filtro,
                  dashboardDataInicial,
                  dashboardDataFinal
              },
              success:function(dados){
                  $("#paginaHome").html(dados);
              }
          })
        })

        $("button[limpar]").click(function(){
          Carregando()
          $.ajax({
              url:"src/dashboard/index.php",
              type:"POST",
              data:{
                  filtro:'limpar',
              },
              success:function(dados){
                  $("#paginaHome").html(dados);
              }
          })
        })
        
    })
</script>