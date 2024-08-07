<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    if($_POST) $data = $_POST['data'];
    else $data = date("Y-m-d");

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="img/icone.png">
    <title>BK - Manaus</title>
    <?php
    include("lib/header.php");
    ?>
  </head>
  <body translate="no">

    <div class="container mt-3">

    
        <div class="row">
            <div class="col-12">
                <form action="./entregas.php" method="POST">
                <div class="input-group mb-3">
                    <input type="date" id="data" name="data" class="form-control" value="<?=$data?>" placeholder="Selecione a Data" aria-label="Selecione a Data" aria-describedby="buscarData">
                    <button class="btn btn-outline-secondary" type="submit" id="buscarData">Buscar</button>
                </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4>Controle das entregas</h4>

                <?php
                    $query1 = "select a.*, b.nome as nome_entregador from ifood a left join entregadores b on a.entregador = b.codigo where a.data like '{$data}%' and a.entregador > 0 group by a.entregador order by a.entregador, a.data";
                    $result1 = mysqli_query($con, $query1);
                    $entregador = false;
                    while($e = mysqli_fetch_object($result1)){

                        if($entregador != $e->entregador){
                ?>
                <div class="card mb-3">
                <div class="card-header">
                    <h5><?=$e->nome_entregador?></h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                <?php

                        }

                ?>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Loja</th>
                            <th>Data Pedido</th>
                            <th>Data Finalização</th>
                            <th>Tempo de Entrega</th>
                            <th>Intervalo Entregas</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                    $query = "select a.*, b.nome as nome_loja from ifood a left join lojas on a.loja = b.codigo where a.data like '{$data}%' and a.entregador = '{$e->entregador}' order by a.data asc";
                    $result = mysqli_query($con, $query);
                    $anterior = false;
                    $intervalo_entrega = false;
                    $tempo_entrega = false;
                    while($c = mysqli_fetch_object($result)){

                        $tempo_entrega = ((strtotime($c->finalizacao) - strtotime($c->data))/60);
                        $tempo_entrega_hora = str_pad(floor($tempo_entrega / 60) , 2 , '0' , STR_PAD_LEFT);
                        $tempo_entrega_minutos = str_pad(($tempo_entrega % 60) , 2 , '0' , STR_PAD_LEFT);
                        $tempo_entrega = "{$tempo_entrega_hora}:{$tempo_entrega_minutos}";


                        if($anterior) {
                            $intervalo_entrega = (abs(strtotime($c->data) - strtotime($anterior))/60);
                            $intervalo_entrega_hora = str_pad(floor($intervalo_entrega / 60) , 2 , '0' , STR_PAD_LEFT);
                            $intervalo_entrega_minutos = str_pad(($intervalo_entrega % 60) , 2 , '0' , STR_PAD_LEFT);
                            $intervalo_entrega = "{$intervalo_entrega_hora}:{$intervalo_entrega_minutos}";                            
                        }

                        if($intervalo_entrega_minutos*1 < 15 and $intervalo_entrega_hora*1 == 0) $cor2 = 'danger'; else $cor2 = 'success';
                        if($tempo_entrega_minutos*1 < 15 and $tempo_entrega_hora*1 == 0) $cor1 = 'danger'; else $cor1 = 'success';
                        $anterior = $c->data;

                ?>
                        <tr>
                            <td><?=$c->ifood?></td>
                            <td><?=$c->nome_loja?></td>
                            <td><?=$c->data?></td>
                            <td><?=$c->finalizacao?></td>
                            <td class="text-<?=$cor1?>"><?=$tempo_entrega?></td>
                            <td class="text-<?=$cor2?>"><?=$intervalo_entrega?></td>
                        </tr>
                <?php
                    }
                ?>
                    </tbody>
                </table>
                <?php
                    if($entregador != $e->entregador){
                ?>
            </li>
            </ul>
            </div>
                <?php

                    }

                    $entregador = $e->entregador;

                    }
                ?>                
            </div>
        </div>
    </div>

    <?php
        include("lib/footer.php");
    ?>

    <script>
        $(function(){
 
        })

    </script>

  </body>
</html>