<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");

    $filtro = $f_usuario = $f_meta = $f_data = false;
    if($_SESSION['relatorio']['usuario']){
        $f_usuario = " and monitor_social in( {$_SESSION['relatorio']['usuario']} ) ";
    }
    if($_SESSION['relatorio']['meta']){
        $f_meta = " and meta in( {$_SESSION['relatorio']['meta']} ) ";
    }else if($_SESSION['ProjectSeLogin']->perfil == 'crd'){
        $f_meta = " and meta in( select codigo from metas where usuario in(select codigo from usuarios where coordenador = '{$_SESSION['ProjectSeLogin']->codigo}') ) ";
    }
    if($_SESSION['relatorio']['data_inicial']){
        $f_data = " and (data between '{$_SESSION['relatorio']['data_inicial']} 00:00:00' and '".(($_SESSION['relatorio']['data_final'])?:$_SESSION['relatorio']['data_inicial'])." 23:59:59')";
    }

    $filtro = $f_usuario . $f_meta . $f_data;


    $query = "select count(*) as qt, situacao, (select count(*) from se) as total from se where monitor_social > 0 and meta > 0 {$filtro} group by situacao";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
        $D[$d->situacao] = $d->qt;
        $D['g'] = $D['g'] + $d->qt;
        $total_geral = $d->total;
    }
    $R = [
        '' => 'Não Definifo',
        'i' => 'Iniciado',
        'p' => 'Pendente',
        'c' => 'Concluído',
        'n' => 'Não encontrado',
        'f' => 'Finalizado',
    ];
?>

<style>
    .cartao{
        position:relative;
        width:99%;
        min-height:90px;
        background-color:#459adb;
        border-radius:10px;
        color:#fff;
    }
    .cartao span{
        font-size:10px;
        margin-left:10px;
    }
    .cartao p{
        font-size:25px;
        font-weight:bold;
        text-align:center;
        padding-top:0px;
        padding-bottom:15px;
    }
    .cartao div{
        position:absolute;
        right:10px;
        bottom:5px;
        width:auto;
        opacity:0;
    }
    .cartao div i{
        cursor: pointer;
        margin-left:10px;
    }
    .dados{
        background-color:green;
    }
</style>
   

    <div class="row mb-3 mt-3">
        <div class="col-md-12"><h3 style="color:#a1a1a1">Relatório Completo</h3></div>
    </div>

    <div class="row">
        <div class="col-md-10">
            <div class="row">
                <?php
                $query = "select situacao, count(*) as qt from se group by situacao";
                $result = mysqli_query($con, $query);
                $total = 0;
                while($d = mysqli_fetch_object($result)){
                ?>
                <div class="col-md-2 mb-2">
                    <div class="cartao dados">
                        <span><?=$R[$d->situacao]?></span>
                        <p><?=$d->qt?></p>
                    </div>
                </div>
                <?php
                $total = $total + $d->qt;
                }
                ?>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="cartao dados">
                <span>Geral</span>
                <p><?=$total?></p>
            </div>            
        </div>        
    </div>



<script>
    $(function(){
        Carregando('none')

    })
</script>