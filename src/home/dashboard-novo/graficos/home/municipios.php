<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");
    $md5 = md5($_POST['rotulo'].$md5);
?>

<canvas id="Tipos<?= $md5 ?>" width="400" height="400"></canvas>

<script>


<?php

$query = "SELECT * FROM dashboard where grafico = 'graficos/municipios'";
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
        type: 'bar',
        data: {
            labels: [<?=$R?>],
            datasets: [
                {
                    label: [<?=$R?>],
                    data: [<?=$Q?>],
                    backgroundColor: 'rgb(255, 159, 64, 0.2)',
                    // [
                    //     'rgba(255, 99, 132, 0.2)',
                    //     'rgba(54, 162, 235, 0.2)',
                    //     'rgba(255, 206, 86, 0.2)',
                    // ],
                    borderColor: 'rgb(255, 159, 64, 1)',
                    // [
                    //     'rgba(255, 99, 132, 1)',
                    //     'rgba(54, 162, 235, 1)',
                    //     'rgba(255, 206, 86, 1)',
                    // ],
                    borderWidth: 1,
                    rotulos: [<?=$R?>]
                }
        ]
        },
        options: {
            indexAxis: 'y',
            elements: {
                bar: {
                    borderWidth: 2,
                }
            },
            responsive: true,
            plugins: {
                legend: false/*{
    position: 'right',
  }*/,
                title: {
                    display: false,
                    text: 'Pesquisa de satisfação'
                },


                tooltip: {
                    callbacks: {
                        title: function (context) {
                            indx = context[0].parsed.y;
                            return context[0].dataset.rotulos[indx];
                        },
                        label: function (context) {
                            indx = context.parsed.y;
                            var label = ' ' + context.dataset.label[indx] || '';

                            if (label) {
                                label += ' : ';
                            }
                            if (context.parsed.y !== null) {
                                label += context.parsed.x + ' Registro(s)';
                            }
                            return label;
                        }
                    }
                }

            }
        },
    }
);



</script>