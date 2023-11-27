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
    include("../lib/header.php");
    ?>
    <Style>
        .semUso{
            background-color:#000;
        }
    </Style>
  </head>
  <body translate="no">

    <div class="Carregando">
        <div><i class="fa-solid fa-rotate fa-pulse"></i></div>
    </div>


    <div class="row">
        <div class="col-4 d-none d-md-block semUso"></div>
        <div class="col CorpoApp">
            
        </div>
        <div class="col-4 d-none d-md-block semUso"></div>
    </div>

    <?php
    include("../lib/footer.php");
    ?>

    <script>
        $(function(){
            // Carregando();
            $.ajax({
                url:"home/index.php",
                success:function(dados){
                    $(".CorpoApp").html(dados);
                }
            });
        })


        //Jconfirm
        jconfirm.defaults = {
            typeAnimated: true,
            type: "blue",
            smoothContent: true,
        }

    </script>

  </body>
</html>