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


    echo $query = "select count(*) as qt, situacao from se where monitor_social > 0 and meta > 0 {$filtro} group by situacao";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
        $D[$d->situacao] = $d->qt;
        $D['g'] = $D['g'] + $d->qt;
    }
    $R = [
        '' => 'Não Definifo',
        'i' => 'Iniciado',
        'p' => 'Pendente',
        'c' => 'Concluído',
        'n' => 'Não encontrado',
    ]
?>

<style>
    .cartao{
        position:relative;
        width:99%;
        min-height:120px;
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
</style>

    <div class="row mb-3 mt-3">
        <div class="col-md-12"><h3 style="color:#a1a1a1">Relatório Quantitativo</h3></div>
    </div>
    <div class="row">

        <div class="col-md-2 mb-3">
            <div class="cartao">
                <span>Toatl Geral</span>
                <p><?=($D['g']*1)?></p>
                <div>
                    <i acao='editarXXX' filtro='' class="fa-solid fa-up-right-from-square"></i>
                    <i acao='downloadXXX' filtro='' class="fa-solid fa-file-arrow-down"></i>
                </div>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="cartao">
                <span>Pesquisas Iniciadas</span>
                <p><?=($D['i']*1)?></p>
                <div>
                    <i acao='editarXXX' filtro='i' class="fa-solid fa-up-right-from-square"></i>
                    <i acao='downloadXXX' filtro='i' class="fa-solid fa-file-arrow-down"></i>
                </div>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="cartao">
                <span>Pesquisas Pendentes</span>
                <p><?=($D['p']*1)?></p>
                <div>
                    <i acao='editarXXX' filtro='p' class="fa-solid fa-up-right-from-square"></i>
                    <i acao='downloadXXX' filtro='p' class="fa-solid fa-file-arrow-down"></i>
                </div>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="cartao">
                <span>Pesquisas Concluídas</span>
                <p><?=($D['c']*1)?></p>
                <div>
                    <i acao='editarXXXX' filtro='c' class="fa-solid fa-up-right-from-square"></i>
                    <i acao='downloadXXX' filtro='c' class="fa-solid fa-file-arrow-down"></i>
                </div>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="cartao">
                <span>Beneficiários não encontrados</span>
                <p><?=($D['n']*1)?></p>
                <div>
                    <i acao='editarXXX' filtro='n' class="fa-solid fa-up-right-from-square"></i>
                    <i acao='downloadXXX' filtro='n' class="fa-solid fa-file-arrow-down"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="cartao">
                <span>Situação não identificada</span>
                <p><?=($D['']*1)?></p>
                <div>
                    <i acao='editarXXX' filtro='n' class="fa-solid fa-up-right-from-square"></i>
                    <i acao='downloadXXX' filtro='n' class="fa-solid fa-file-arrow-down"></i>
                </div>
            </div>
        </div>
    </div>

<script>
    $(function(){

        $(".cartao").mouseover(function(){
            // $(this).children(".cartao div").css('opacity',1)
        }).mouseout(function(){
            // $(this).children(".cartao div").css('opacity',0)
        })

    })
</script>