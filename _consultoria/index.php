<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="img/icone.png">
    <title>BK - Manaus</title>
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet"
>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBSnblPMOwEdteX5UPYXf7XUtJYcbypx6w&callback=initMap&v=weekly&language=pt&region=BR"
></script>

<link href="https://painel.bkmanaus.com.br/lib/vendor/bootstrap-5.2.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<link href="https://painel.bkmanaus.com.br/lib/vendor/jquery-confirm-v3.3.4/dist/jquery-confirm.min.css" rel="stylesheet" >


<script src="https://painel.bkmanaus.com.br/lib/vendor/jquery-3.6.0/jquery-3.6.0.min.js" ></script>
<script src="https://painel.bkmanaus.com.br/lib/vendor/jquery-confirm-v3.3.4/dist/jquery-confirm.min.js" ></script>

<link href="https://painel.bkmanaus.com.br/lib/vendor/fontawesome-free-6.1.1-web/css/all.css" rel="stylesheet">

<script src="https://painel.bkmanaus.com.br/lib/vendor/jQuery-Mask/jquery.mask.min.js" ></script>

<script src="https://painel.bkmanaus.com.br/lib/vendor/chart/chart.min.js" ></script>

<link href="https://painel.bkmanaus.com.br/lib/css/app.css" rel="stylesheet">
<script src="https://painel.bkmanaus.com.br/lib/js/app.js?20240715202344" ></script>

<link href="https://painel.bkmanaus.com.br/lib/vendor/slick/slick.css" rel="stylesheet">
<script src="https://painel.bkmanaus.com.br/lib/vendor/slick/slick.js" ></script>


<script src="https://painel.bkmanaus.com.br/lib/vendor/jquery-maskmoney/jquery.maskMoney.js" ></script>  </head>
  <body translate="no">

<div class="container">
    <h1>Relatório da Consultoria - App BK Manaus</h1>
  <?php
    $campos = " a.data as data, a.device as device, c.nome as nome, b.detalhes as detalhes";

    $intervalos = [
        ['Semana de 13/06 a 15/06', '2024-06-13 00:00:00', '2024-06-15 23:59:59'],
        ['Semana de 20/06 a 22/06', '2024-06-20 00:00:00', '2024-06-22 23:59:59'],
        ['Semana de 27/06 a 29/06', '2024-06-27 00:00:00', '2024-06-29 23:59:59'],
        ['Semana de 04/07 a 06/07', '2024-07-04 00:00:00', '2024-07-06 23:59:59'],
        ['Semana de 11/07 a 13/07', '2024-07-11 00:00:00', '2024-07-13 23:59:59'],
    ];


    echo "<table class='table table-hover'>";

    foreach($intervalos as $ind => $val){

    $query = "
    
        (SELECT a.data as data, a.device as device, c.nome as nome, b.detalhes as detalhes, '{$val[0]}' as semana, time(data) as hora, if( time(data) >= '11:00:00' and time(data) <= '23:00:00', 'green', 'red') as cor FROM app_acessos a left join vendas_tmp b on a.device = b.id_unico left join clientes c on a.cliente = c.codigo where a.data BETWEEN '{$val[1]}' and '{$val[2]}' and a.device != '' group by a.device order by detalhes desc)
    ";

    $result = mysqli_query($con, $query);


    $i = 1;
    $semana = false;
    while($d = mysqli_fetch_object($result)){

        $venda = json_decode($d->detalhes);
        $p = false;
        foreach($venda as $vi => $vd){
            if($vi){
                $p = true;
            }
        }



        if($d->semana != $semana){
            echo "<tr>
                <td colspan = '4'><h3>{$d->semana}</h3></td>
             </tr>";

             echo "<tr>
             <th>#</th>
             <th>Equipamento</th>
             <th>Cliente</th>
             <th>Venda</th>
             <th>Hora</th>
          </tr>";
            $semana = $d->semana;
            $i = 1;
        }

        echo "<tr>
                <td>{$i}</td>
                <td>{$d->device}</td>
                <td>".(($d->nome)?'<i class="fa-solid fa-user"></i>':false)."</td>
                <td>".(($p)?'<i class="fa-solid fa-bag-shopping"></i>':false)."</td>
                <td style='color:{$d->cor}'>".(($p)?$d->hora:false)."</td>
             </tr>";
        $i++;
    }
    }

    echo "</table>";
    ?>

</div>

    </body>
</html>