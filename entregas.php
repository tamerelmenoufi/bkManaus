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
                    <input type="date" id="data" name="data" class="form-control" value="<?=$_POST['data']?>" placeholder="Selecione a Data" aria-label="Selecione a Data" aria-describedby="buscarData">
                    <button class="btn btn-outline-secondary" type="submit" id="buscarData">Buscar</button>
                </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4>Controle das entregas</h4>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Loja</th>
                            <th>Entregador</th>
                            <th>Data Pedido</th>
                            <th>Data Finalização</th>
                            <th>Tempo de Entrega</th>
                            <th>Intervalo Entregas</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                    $query = "select * from ifood where data like '{$data}%' and entregador > 0 order by entregador, data";
                    $result = mysqli_query($con, $query);
                    $anterior = false;
                    while($c = mysqli_fetch_object($result)){

                        $tempo_entrega = ((strtotime($c->finalizacao) - strtotime($c->data))/60);
                        $tempo_entrega_hora = floor($tempo_entrega / 60);
                        $tempo_entrega_minutos = ($tempo_entrega % 60);
                        $tempo_entrega = "{$tempo_entrega_hora}:{$tempo_entrega_minutos}";


                        if($anterior) {
                            $intervalo_entrega = (abs(strtotime($c->data) - strtotime($anterior))/60);
                            $intervalo_entrega_hora = floor($intervalo_entrega / 60);
                            $intervalo_entrega_minutos = ($intervalo_entrega % 60);
                            $intervalo_entrega = "{$intervalo_entrega_hora}:{$intervalo_entrega_minutos}";                            
                        }
                        $anterior = $c->data;

                ?>
                        <tr>
                            <td><?=$c->ifood?></td>
                            <td><?=$c->loja?></td>
                            <td><?=$c->entregador?></td>
                            <td><?=$c->data?></td>
                            <td><?=$c->finalizacao?></td>
                            <td><?=$tempo_entrega?></td>
                            <td><?=$intervalo_entrega?></td>
                        </tr>
                <?php
                    }
                ?>
                    </tbody>
                </table>
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