<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");

    if($_POST['campo']) $_SESSION['s_campo'] = $_POST['campo'];
    if($_POST['valor']) $_SESSION['s_valor'] = $_POST['valor'];
    if($_POST['json']) $_SESSION['s_json'] = $_POST['json'];
    if($_POST['campo'] and !$_POST['json']) $_SESSION['s_json'] = false;
    if($_POST['campo'] and !$_POST['valor']) $_SESSION['s_valor'] = false;
    if($_POST['titulo']) $_SESSION['s_titulo'] = $_POST['titulo'];
    if($_POST['campo']) $_SESSION['sem_metas'] = $_POST['sem_metas'];


    $filtro = $f_usuario = $f_meta = $f_data = $f_campo = false;
    if($_SESSION['relatorio']['usuario']){
        $f_usuario = " and a.monitor_social in( {$_SESSION['relatorio']['usuario']} ) ";
    }
    if($_SESSION['relatorio']['meta']){
        $f_meta = " and a.meta in( {$_SESSION['relatorio']['meta']} ) ";
    }else if($_SESSION['ProjectSeLogin']->perfil == 'crd'){
        $f_meta = " and a.meta in( select codigo from metas where usuario in(select codigo from usuarios where coordenador = '{$_SESSION['ProjectSeLogin']->codigo}') ) ";
    }

    if($_SESSION['relatorio']['data_inicial']){
        $f_data = " and (a.data between '{$_SESSION['relatorio']['data_inicial']} 00:00:00' and '".(($_SESSION['relatorio']['data_final'])?:$_SESSION['relatorio']['data_inicial'])." 23:59:59')";
    }
    if($_SESSION['s_campo'] and $_SESSION['s_json']){
        $f_campo = " and {$_SESSION['s_campo']} like '%\"{$_SESSION['s_valor']}\"%' ";
    }else if($_SESSION['s_campo']){
        $f_campo = " and {$_SESSION['s_campo']} = '{$_SESSION['s_valor']}' ";
    }

    if($_SESSION['sem_metas']){
        $where = " (a.monitor_social = 0 or a.meta = 0) ";
    }else{
        $where = " a.monitor_social > 0 and a.meta > 0 ";
    }

    $filtro = $f_usuario . $f_meta . $f_data . $f_campo;
?>

<style>
    .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }
    .grafico<?=$md5?>{
        margin-right:20px;
        width:30px;
        height:30px;
    }
    .percentual{
        margin-right:20px;
        font-size:12px;
    }

</style>
<h5 class="Titulo<?=$md5?>"><?=$_SESSION['s_titulo']?></h5>
<ul class="list-group">
<?php
    $query = "select a.* from se a where {$where} {$filtro} limit 1000";
    $result = mysqli_query($con, $query);
    $t = 0;
    while($s = mysqli_fetch_object($result)){
?>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <span><?=$s->nome?></span>
        <div class="d-flex justify-content-between align-items-center">
            <span class="percentual"><?=number_format($s->percentual,0)?>%</span>
            <canvas class="grafico<?=$md5?>" id="Tipos<?= $s->codigo ?>" pct="<?=number_format($s->percentual,0)?>"></canvas>
            <?php
            if($_SESSION['ProjectSeLogin']->perfil != 'sup'){
            ?>
            <button 
                cod="<?=$s->codigo?>" 
                class="btn btn-warning btn-sm"
                data-bs-toggle="offcanvas"
                href="#offcanvasDireita"
                role="button"
                aria-controls="offcanvasDireita"  
            >
                <i class="fa fa-edit"></i>
            </button>
            <?php
            }
            ?>
        </div>
    </li>
<?php
    }

?>
</ul>
<script>
    $(function(){

        Carregando('none');

        $("button[cod]").off('click').on('click',function(){


            cod = $(this).attr("cod");
            Carregando();
            $.ajax({
                url:"src/se/se.php",
                type:"POST",
                data:{
                    cod,
                    origem:'1'
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })

        })


        const Graficos<?=$md5?> = (pct, local)=>{

            let r = (100 - pct)

            if(pct >= 70){
                cor = 'rgb(9, 112, 4, 0.2)'
            }else if(pct >= 40){
                cor = 'rgb(255, 132, 0, 0.2)'
            }else if(pct >= 0){
                cor = 'rgb(255, 0, 0, 0.2)'
            }

            const TiposCtx<?=$md5?> = document.getElementById(local);
            
            const Tipos<?=$md5?> = new Chart(TiposCtx<?=$md5?>,
                {
                    type: 'pie',
                    data: {
                        labels: ['Preenchido', 'Não preendhido'],
                        datasets: [{
                            label: ['Preenchido', 'Não preendhido'],
                            data: [pct, r],
                            backgroundColor: [
                                cor,
                                'rgb(144, 144, 144, 0.2)',
                            ],
                            borderColor: [
                                cor,
                                'rgb(144, 144, 144, 0.2)',
                            ],
                            borderWidth: 1,
                            rotulos: false
                        }]
                    },
                    options:{
                        responsive: false,
                        plugins: {
                            legend:{
                                display: false,
                            },
                            title: {
                                display: false,
                                text: 'Preenchimento'
                            },
                            tooltip:{
                                enabled: false
                            },
                        }
                    }
                }
            );
        }

        $(".grafico<?=$md5?>").each(function(){
            local = $(this).attr("id")
            pct = $(this).attr("pct")
            Graficos<?=$md5?>(pct, local)
        })


    })
</script>