<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
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
            <div class="col">
                <h4>Lista de produtos</h4>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th>Produto</th>
                            <th>Valor</th>
                            <th>Valor no Combo</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                    $query = "select * from categorias where situacao = '1' and deletado != '1' order by ordem";
                    $result = mysqli_query($con, $query);
                    while($c = mysqli_fetch_object($result)){
                        $query1 = "select * from produtos where categoria = '{$c->codigo}' and situacao = '1' and deletado != '1' order by produto";
                        $result1 = mysqli_query($con, $query1);
                        while($p = mysqli_fetch_object($result1)){
                ?>
                        <tr>
                            <td><?=$c->categoria?></td>
                            <td><?=$p->produto?></td>
                            <td>R$ <?=(($c->codigo == 8)?number_format(CalculaValorCombo($p->codigo),2,",",false):number_format($p->valor,2,",",false))?></td>
                            <td><?=(($c->codigo == 8)?'-':'R$ '.number_format($p->valor_combo,2,",",false))?></td>
                        </tr>
                <?php
                        }
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