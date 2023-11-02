<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");
    $url = false;

    $query = "SELECT
                    a.municipio as cod_municipio,
                    a.bairro_comunidade as cod_bairro,
                    m.municipio,
                    b.descricao,
                    a.local,
                    a.zona_urbana,
                    a.situacao,
                    count(*) quantidade
                FROM se a
                    left join municipios m on a.municipio = m.codigo
                    left join bairros_comunidades b on a.bairro_comunidade = b.codigo
                group by
                        a.municipio,
                        a.local,
                        a.zona_urbana,
                        a.bairro_comunidade,
                        a.situacao";

    $result = mysqli_query($con,$query);
    $_SESSION['municipios'] = [];
    while($d = mysqli_fetch_object($result)){
        $_SESSION['municipios']['quantidade'][$d->cod_municipio][$d->local][$d->zona_urbana][$d->cod_bairro][$d->situacao] = $d->quantidade;
        $_SESSION['municipios']['nome'][$d->cod_municipio] = $d->municipio;
        $_SESSION['bairro']['nome'][$d->cod_bairro] = $d->descricao;
    }

    if($_SESSION['filtro_relatorio_municipio'] == 66){
        // echo "Geral: de ".$_SESSION['filtro_relatorio_municipio'].': '.array_multisum($_SESSION['municipios']['quantidade'][$_SESSION['filtro_relatorio_municipio']])."<br>";
        // foreach($_SESSION['municipios']['quantidade'][$_SESSION['filtro_relatorio_municipio']]['Urbano'] as $indice => $valores){
        //     echo "Geral: de ".$_SESSION['filtro_relatorio_municipio'].'Urbano - '.$indice.': '.array_multisum($_SESSION['municipios']['quantidade'][$_SESSION['filtro_relatorio_municipio']]['Urbano'][$indice])."<br>";
        // }
        $url = 'src/dashboard/capital.php';
    }else if($_SESSION['filtro_relatorio_municipio']){
        // echo "Geral: de ".$_SESSION['filtro_relatorio_municipio'].': '.array_multisum($_SESSION['municipios']['quantidade'][$_SESSION['filtro_relatorio_municipio']])."<br>";
        // echo "Geral: de ".$_SESSION['filtro_relatorio_municipio'].'Urbano: '.array_multisum($_SESSION['municipios']['quantidade']['Urbano']['0'][$_SESSION['filtro_relatorio_municipio']])."<br>";
        // echo "Geral: de ".$_SESSION['filtro_relatorio_municipio'].'Rural: '.array_multisum($_SESSION['municipios']['quantidade']['Rural']['0'][$_SESSION['filtro_relatorio_municipio']])."<br>";
        $url = 'src/dashboard/municipio.php';
    }else{
        $url = 'src/dashboard/geral.php';
    }

    // foreach($_SESSION['bairro']['nome'] as $indice => $valor){

    //     echo $indice.' em '.$valor."<br>";

    // }

?>
<style>
    .AreaDashboardTop{
        position:absolute;
        left:20px;
        right:20px;
        z-index:10;
    }
</style>
<div class="AreaDashboardTop">
    <div class="row mb-3 mt-3">
        <div class="col-md-1"></div>
        <div class="col-md-10" style="text-align:right">
            <a
            class="btn btn-warning"
            href="./print.php?u=<?=base64_encode("src/dashboard/geral.php")?>"
            target='relatorio'
            >Imprimir</a>

            <button
            filtrar
            class="btn btn-primary"
            data-bs-toggle="offcanvas"
            href="#offcanvasDireita"
            role="button"
            aria-controls="offcanvasDireita"
            >Filtrar</button>
        </div>
        <div class="col-md-1"></div>
    </div>
</div>

<div ResultadoDashboard style="position:relative"></div>
<script>
    $(function(){
        Carregando('none')
        <?php
        if($url){
        ?>
        $.ajax({
            url:"<?=$url?>",
            success:function(dados){
                $("div[ResultadoDashboard]").html(dados);
            }
        });
        <?php
        }
        ?>

    $("button[filtrar]").click(function(){
        Carregando();
        $.ajax({
            url:"src/dashboard/filtro.php",
            success:function(dados){
                $(".LateralDireita").html(dados);
            }
        })
    });

    $(document).off('click').on('click', 'i[acao]', function(){
        opc = $(this).attr('acao');
        filtro = $(this).attr('filtro');
        $.ajax({
            url:"src/dashboard/tabelas/geral.php",
            type:'POST',
            data:{
                filtro,
                opc,
            },
            success:function(dados){
                $.dialog({
                    content:dados,
                    title:`Relatório`,
                    columnClass:'col-md-offset-2 col-md-8'
                })
            }
        });

        // $.alert('Relatório sem resultados de pesquisa!');
    })




    })
</script>