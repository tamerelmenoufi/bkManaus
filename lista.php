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
                <?php
                    $query = "select * from categorias where situacao = '1' and deletado != '1' order by ordem";
                    $result = mysqli_query($con, $query);
                    while($c = mysqli_fetch_object($result)){
                        $query1 = "select * from produtos where categoria = '{$c->codigo}' and situacao = '1' and deletado != '1' order by produto";
                        $result1 = mysqli_query($con, $query1);
                        while($p = mysqli_fetch_object($result1)){
                            echo $d->produto."<br>";
                        }
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