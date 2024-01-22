<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['acao'] == 'verificar'){

        $query = "select * from entregadores where codigo = '{$_POST['entregador']}' and situacao = '1' and deletado != '1'";
        $result = mysqli_query($con,$query);
        if(mysqli_num_rows($result)){
            $retorno = [
                'status' => true
            ];
        }else{
            $retorno = [
                'status' => false
            ];
        }
        echo json_encode($retorno);
        exit();
    }

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
            z-index:1
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
            
            <div class="CorpoApp area"></div>             
        </div>
        <div class="col-4 d-none d-md-block area"></div>
    </div>

    <?php
    include("../lib/footer.php");
    ?>

    <script>
        $(function(){

            loja = localStorage.getItem("Dloja");
            entregador = localStorage.getItem("Dentregador");

            if(entregador){
                Carregando();
                $.ajax({
                    url:"index.php",
                    type:"POST",
                    dataType:"JSON",
                    data:{
                        entregador,
                        acao:"verificar"
                    },
                    success:function(dados){

                        if(dados.status == true){
                            
                            $("body").attr("entregador", entregador);
                            $.ajax({
                                url:"home.php",
                                type:"POST",
                                data:{
                                    entregador,
                                    loja,
                                },
                                success:function(dados){
                                    Carregando('none');
                                    $(".CorpoApp").html(dados);
                                }
                            });
                            
                        }else{
                            localStorage.removeItem("Dloja");
                            localStorage.removeItem("Dentregador");
                            window.location.href='./';
                            Carregando('none');
                        }
                        // $(".CorpoApp").html(dados);
                    },
                    error:function(){
                        $.alert(dados.status)
                        Carregando('none');
                    }
                }); 

            }else{
                Carregando();
                localStorage.removeItem("Dloja");
                localStorage.removeItem("Dentregador");
                $.ajax({
                    url:"entregadores.php",
                    success:function(dados){
                        Carregando('none');
                        $(".CorpoApp").html(dados);
                    }
                }); 
            }

            $(".CorpoApp").css("min-height", $(window).height());


            $(".popupFecha").click(function(){
                $(".popupPalco").html('');
                $(".popupArea").css("display","none");
            })

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