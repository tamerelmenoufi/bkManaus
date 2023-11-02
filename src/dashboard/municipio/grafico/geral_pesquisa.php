<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");
    $md5 = md5($_POST['rotulo'].$md5);
    $mun = $_SESSION['filtro_relatorio_municipio'];

    //VERIFICAR AS QUANTIDADE POR SITUAÇÃO
    $i = 0; $iu = 0; $iuc = 0; $ir = 0; //Inicidos
    $p = 0; $pu = 0; $puc = 0; $pr = 0; //Pendentes
    $c = 0; $cu = 0; $cuc = 0; $cr = 0; //Concluídos
    $n = 0; $nu = 0; $nuc = 0; $nr = 0; //Não Encontrados

    // foreach($_SESSION['municipios']['quantidade'] as $indice => $valores){ //Lista os municipios
        foreach($_SESSION['municipios']['quantidade'][$mun] as $indice1 => $valores1){ //Lista as zonas
            foreach($valores1 as $indice2 => $valores2){ //Lista as zonas urbanas
                foreach($valores2 as $indice3 => $valores3){ //Lista os Bairros
                    //Obter as quantidades por situacao
                    $i += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['i']);
                    $p += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['p']);
                    $c += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['c']);
                    $n += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['n']);

                    if($indice1 == 'Urbano'){
                    //Obter as quantidades por situacao por zona Urbana
                    $iu += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['i']);
                    $pu += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['p']);
                    $cu += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['c']);
                    $nu += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['n']);

                    $iuz[$indice2] += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['i']);
                    $puz[$indice2] += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['p']);
                    $cuz[$indice2] += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['c']);
                    $nuz[$indice2] += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['n']);

                    }

                    if($indice1 == 'Rural'){
                    //Obter as quantidades por situacao por zona Rural
                    $ir += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['i']);
                    $pr += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['p']);
                    $cr += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['c']);
                    $nr += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['n']);
                    }

                    ///////////CONDIÇÔES PARA A CAPITAL - CODIGO 66 ////////////////////////
                    if($indice == 66){

                        $iuc += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['i']);
                        $puc += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['p']);
                        $cuc += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['c']);
                        $nuc += ($_SESSION['municipios']['quantidade'][$mun][$indice1][$indice2][$indice3]['n']);

                    }




                }
            }
        }
    // }

    // echo "Geral Iniciados: ".$i."<br>";
    // echo "Geral Pendentes: ".$p."<br>";
    // echo "Geral Concluídos: ".$c."<br>";
    // echo "Geral Não Encontrados: ".$n."<br><hr>";

    // echo "Geral Urbano Iniciados: ".$iu."<br>";
    // echo "Geral Urbano Pendentes: ".$pu."<br>";
    // echo "Geral Urbano Concluídos: ".$cu."<br>";
    // echo "Geral Urbano Não Encontrados: ".$nu."<br><hr>";

    // echo "Geral Rural Iniciados: ".$ir."<br>";
    // echo "Geral Rural Pendentes: ".$pr."<br>";
    // echo "Geral Rural Concluídos: ".$cr."<br>";
    // echo "Geral Rural Não Encontrados: ".$nr."<br><hr>";

?>
<style>
    .icone{
        margin-right:5px;
        cursor:pointer;
    }
</style>

<canvas id="Tipos<?= $md5 ?>" width="400" height="400" style="margin:10px;"></canvas>

<ul class="list-group" style="font-size:10px;">
  <li class="list-group-item d-flex justify-content-between align-items-center">
    <span>
        <i acao='editar' filtro='i' class="fa-solid fa-up-right-from-square icone"></i>
        <i acao='download' filtro='i' class="fa-solid fa-file-arrow-down icone"></i>
        Pesquisas Iniciadas
    </span>
    <span class="badge bg-primary rounded-pill"><?=$i?></span>
  </li>
  <li class="list-group-item d-flex justify-content-between align-items-center">
    <span>
        <i acao='editar' filtro='p' class="fa-solid fa-up-right-from-square icone"></i>
        <i acao='download' filtro='p' class="fa-solid fa-file-arrow-down icone"></i>
        Pesquisas Pendente
    </span>
    <span class="badge bg-primary rounded-pill"><?=$p?></span>
  </li>
  <li class="list-group-item d-flex justify-content-between align-items-center">
    <span>
        <i acao='editar' filtro='c' class="fa-solid fa-up-right-from-square icone"></i>
        <i acao='download' filtro='c' class="fa-solid fa-file-arrow-down icone"></i>
        Pesquisas Concluída
    </span>
    <span class="badge bg-primary rounded-pill"><?=$c?></span>
  </li>
  <li class="list-group-item d-flex justify-content-between align-items-center">
    <span>
        <i acao='editar' filtro='n' class="fa-solid fa-up-right-from-square icone"></i>
        <i acao='download' filtro='n' class="fa-solid fa-file-arrow-down icone"></i>
        Beneficiários Não Enontrados
    </span>
    <span class="badge bg-primary rounded-pill"><?=$n?></span>
  </li>
</ul>

<script>


<?php

    $Rotulos = ['Pesquisas Iniciadas','Pesquisas Pendentes','Pesquisas Concluídas','Beneficiários Não Enontrados'];
    $Quantidade = [$i, $p, $c, $n];
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
            },
            options:{
                plugins: {
                    legend: {
                        display: false,
                    }
                }
            }
        }
    );



</script>