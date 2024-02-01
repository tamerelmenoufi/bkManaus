<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
    $idUnico = uniqid();
    if($_GET['s']) {
        mysqli_query($con, "update vendas_tmp set cliente = '' where id_unico = '{$_SESSION['idUnico']}'");
        $_SESSION = [];
        header("location:./");
        exit();
    }
    // $_SESSION['historico'] = [];
    // $_SESSION['historico'][0]['local'] = 'home/index.php';
    // $_SESSION['historico'][0]['destino'] = '.CorpoApp';
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
        body{
            width:100%;
            height:100%;
            padding:0;
            margin:0;
            background-color:#000;
            
        }
        .area{
            position:relative;
        }

        .Carregando{
            position:absolute;
            left:0;
            bottom:0;
            right:0;
            top:0;
            background-color:rgb(0,0,0, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            display:none;
            z-index: 9999;
        }
        .Carregando div{
            color:#fff;
            font-size: 70px;
        }


        .popupArea{
            position:absolute;
            left:0;
            bottom:0;
            right:0;
            top:0;
            background-color:rgb(0,0,0, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            display:none;
            z-index: 9988;
        }
        .popupFecha{
            position:absolute;
            right:30px;
            top:20px;
            font-size:25px;
            color:#000;
            cursor:pointer;
            z-index:2
        }        
        .popupPalco{
            position:absolute;
            padding:10px;
            padding-top:40px;
            right:8px;
            left:8px;
            top:10px;
            bottom:10px;
            background:#fff;
            border-radius:10px;
            overflow:auto;
            z-index:1
        }
        .msg{
            position:fixed;
            margin-left:15%;
            bottom:100px;
            width:70%;
            border:solid 1px green;
            background-color:#fff;
            border-radius:15px;
            height:auto;
            padding:10px;
            display:none;
            z-index:10;
        }
        .msg h3{
            color:#333;
            width:100%;
            text-align:center;
            font-family:verdana;
            padding:5px;
        }

    </Style>
  </head>
  <body translate="no">




    <div class="row g-0">
        <div class="col-5 d-none d-md-block area"></div>
        <div class="col area" style="background-color:#fff;">
            <div class="Carregando">
                <div><i class="fa-solid fa-rotate fa-pulse"></i></div>
            </div>

            <div class="popupArea">
                <i class="fa-solid fa-xmark popupFecha"></i>
                <div class="popupPalco"></div>
            </div>
            
            <div class="msg">
                <h3>Pruduto adicionado</h3>
            </div>
            
            <div class="CorpoApp area"></div>             
        </div>
        <div class="col-4 d-none d-md-block area"></div>
    </div>

    <?php
    include("../lib/footer.php");
    ?>

    <script>
        $(function(){

            idUnico = localStorage.getItem("idUnico");

            if(!idUnico){
                idUnico = '<?=$idUnico?>';
                localStorage.setItem("idUnico", idUnico);
            }

            $("body").attr("device", idUnico);


            $(".CorpoApp").css("min-height", $(window).height());


            $(".popupFecha").click(function(){
                $(".popupPalco").html('');
                $(".popupArea").css("display","none");
            })

            <?php
            if(count($_SESSION['historico'])){
            ?>
            $.ajax({
                url:"lib/refresh.php",
                dataType:"JSON",
                success:function(dados){
                    var data = $.parseJSON(dados.dt);
                    $.ajax({
                        url:dados.pg,
                        type:"POST",
                        data,
                        success:function(retorno){
                            $(`${dados.tg}`).html(retorno);
                        }
                    })
                }
              })
            <?php
            }else{
            ?>
            $.ajax({
                url:"home/index.php",
                type:"POST",
                data:{
                    idUnico,
                },
                success:function(dados){
                    $(".CorpoApp").html(dados);
                }
            });            
            <?php
            }
            ?>

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