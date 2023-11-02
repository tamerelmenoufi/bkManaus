<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");
    $md5 = md5($_POST['rotulo'].$md5);
?>


<canvas id="Tipos<?= $md5 ?>" width="400" height="400"></canvas>

<script>


<?php

    $query = "SELECT * FROM dashboard where grafico = 'graficos/rural/{$_SESSION['filtro_relatorio_municipio']}'";
    $result = mysqli_query($con, $query);
    $Rotulos = [];
    $Quantidade = [];
    $d = mysqli_fetch_object($result);
    $esquema = json_decode($d->esquema);
    // print_r($esquema);
    $Rotulos = $esquema->Rotulos;
    $Quantidade = $esquema->Quantidade;
    $R = (($Rotulos)?"'".implode("','",$Rotulos)."'":0);
    $Q = (($Quantidade)?implode(",",$Quantidade):0);

?>

    const TiposCtx<?=$md5?> = document.getElementById('Tipos<?=$md5?>');

    const Tipos<?=$md5?> = new Chart(TiposCtx<?=$md5?>,
        {
            type: 'pie',
            data: {
                labels: [<?=$R?>],
                datasets: [{
                    label: [<?=$R?>],
                    data: [<?=$Q?>],
                    backgroundColor: [
                        'rgb(75, 192, 192, 0.2)',
                        'rgb(255, 159, 64, 0.2)',
                        'rgb(255, 99, 132, 0.2)',
                                            ],
                    borderColor: [
                        'rgb(75, 192, 192, 1)',
                        'rgb(255, 159, 64, 1)',
                        'rgb(255, 99, 132, 1)',
                    ],
                    borderWidth: 1,
                    rotulos: [<?=$R?>]
                }]
            }
        }
    );



</script>