<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");

    $query = "SELECT * FROM dashboard where grafico = 'tabelas/resumo'";
    $result = mysqli_query($con, $query);
    $Rotulos = [];
    $Quantidade = [];
    $d = mysqli_fetch_object($result);
    $esquema = json_decode($d->esquema);
    $Rotulos = $esquema->Rotulos;
    $Quantidade = $esquema->Quantidade;

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