<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");

    $query = "SELECT * FROM dashboard where grafico = 'tabelas/resumo/{$_SESSION['filtro_relatorio_municipio']}/{$_SESSION['filtro_relatorio_tipo']}'";

    $query = "select
        (select count(*) from se) as total,
        (select count(*) from se where percentual > 0 and percentual < 100) as iniciadas,
        (select count(*) from se where percentual = 0) as pendentes,
        (select count(*) from se where percentual = 100) as concluidas,
        (select count(*) from se where beneficiario_encontrado = 'Não') as nao_encontrado
    ";

    $result = mysqli_query($con, $query);
    $Rotulos = [];
    $Quantidade = [];

    while($d = mysqli_fetch_object($result)){
        set_time_limit(90);
        $Rotulos = ['Total','Pendentes','Iniciadas','Concluídas','Não Encontrado'];
        $Quantidade = [$d->total, $d->pendentes, $d->iniciadas,$d->concluidas,$d->nao_encontrado];
    }


    // iniciados pendentes concluidos nao_encontrados
    $opc = [
        'Iniciadas' => 'iniciados',
        'Pendentes' => 'pendentes',
        'Concluídas' => 'concluidos',
        'Não Encontrado' => 'nao_encontrados',
    ];

?>
<style>
    .painel{
        display:flex;
        flex-direction:column;
        width:98%;
        height:90px;
        border-radius:9px;
        text-align:center;
        justify-content:center;
        align-items:center;
        color:#fff;
        cursor: pointer;
    }
</style>
<div class="row" style="margin:0; padding:0;">
    <div class="col-md-1"></div>
    <?php
    for($i=0;$i<count($Rotulos);$i++){
    ?>
    <div class="col-md-2">
        <div opcPop="<?=$opc[$Rotulos[$i]]?>" class="painel" style="background-color:blue;">
            <h5><?=$Rotulos[$i]?></h5>
            <h3><?=$Quantidade[$i]?></h3>
        </div>
    </div>
    <?php
    }
    ?>
    <div class="col-md-1"></div>
</div>

<script>
    $(function(){
        $("div[opcPop]").click(function(){
            opc = $(this).attr("opcPop");
            console.log(opc)
            $.dialog({
                content:"url:src/home/dashboard/tabelas/relatorios/lista_cadastros.php?opc="+opc,
                title:false,
                columnClass:'col-md-8'
            });
        });
    })
</script>