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
    ]
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
    .geral{
        background-color:#fc8b06;
    }

</style>
<?php
    if(
        $_SESSION['ProjectSeLogin']->perfil == 'adm' or
        $_SESSION['ProjectSeLogin']->perfil == 'crd'
    ){
?>
<div class="alert alert-dark" role="alert">
    Sugestão para Cadastro de novos Beneficiados 
    <button lista_novos class="btn btn-warning btn-sm"><i class="fa fa-users"></i> Visualizar Lista</button>
</div>
<?php
    }
?>    
    <div class="row mb-3 mt-3">
        <div class="col-md-12"><h3 style="color:#a1a1a1">Relatório Quantitativo das Metas</h3></div>
    </div>

    <div class="row">
        <!-- <div class="col-md-1 mb-3">
            <div class="cartao geral">
                <span>Toatl Geral</span>
                <p><?=$total_geral?></p>
                <div>
                    <i acao='editarXXX' filtro='' class="fa-solid fa-up-right-from-square"></i>
                    <i acao='downloadXXX' filtro='' class="fa-solid fa-file-arrow-down"></i>
                </div>
            </div>
        </div> -->
        <div class="col-md-10">
            <div class="row">

                <div class="col-md-2 mb-3">
                    <div class="cartao">
                        <span>Em Metas</span>
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
        </div>
        <div class="col-md-2 mb-3">
            <div class="cartao geral">
                <span>Finalizados</span>
                <p><?=($D['f']*1)?></p>
                <div>
                    <i acao='editarXXX' filtro='' class="fa-solid fa-up-right-from-square"></i>
                    <i acao='downloadXXX' filtro='' class="fa-solid fa-file-arrow-down"></i>
                </div>
            </div>
        </div>
    </div>

    

<?php

/*
?>

    <div class="card mb-3">
    <h5 class="card-header">Relatório de atividades nas metas</h5>
    <div class="card-body">
        <ul class="list-group">
    <?php
        $rotulo = [
            'g' => 'Geral em Metas',
            'n' => 'Novos',
            'c' => 'Concluídas',
            'p' => 'Pendentes',
            'i' => 'Iniciadas',
            'f' => 'Finalizadas',
            '' => 'Situação não definida'
        ];
        arsort($D);
        foreach($D as $ind => $val){
            $p = number_format($val*100/$total_geral, 4,',',false);
    ?>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-5"><?=($rotulo[$ind])?></div>
                    <div class="col-7">
                        <div class="progress">
                            <div class="progress-bar" style="width:<?=$p?>%" role="progressbar" aria-valuenow="<?=$p?>" aria-valuemin="0" aria-valuemax="100"><?=$p?>%</div>
                        </div>
                    </div>
                    <!-- <div class="col-2">
                            <button 
                                class="btn btn-info btn-sm w-100 d-flex justify-content-between"
                                campo="<?=$d['campo']?>"
                                valor="<?=$ind?>" 
                                json="<?=$d['tipo']?>"
                                rotulo_titulo="<?=$d['rotulo']?>"
                                rotulo_campo="<?=$d['legenda'][$ind]?>"
                                data-bs-toggleX="offcanvas"
                                hrefX="#offcanvasDireita"
                                roleX="button"
                                aria-controlsX="offcanvasDireita"                          
                            >
                                <i class="fa-solid fa-arrow-up-1-9"></i><span><?=$val?> <i class="fa-solid fa-up-right-from-square"></i></span>
                            </button>                    
                    </div> -->
                </div>
            </li>
    <?php
        }
    ?>
        </ul>
    </div>
    </div>
    <?php

//*/
?>

<script>
    $(function(){

        $(".cartao").mouseover(function(){
            // $(this).children(".cartao div").css('opacity',1)
        }).mouseout(function(){
            // $(this).children(".cartao div").css('opacity',0)
        })

        $("button[lista_novos]").click(function(){
            Carregando();
            $.ajax({
                url:"src/relatorios/telas/novos_beneficiados.php",
                type:"POST",
                success:function(dados){

                    $(".popUpBeneficiados div").html(dados);
                    $(".popUpBeneficiados h3").html(`Lista de Novos Beneficiados`);
                    $(".popUpBeneficiados").css("display","block");
                    Carregando('none');

                }
            })
        })

    })
</script>