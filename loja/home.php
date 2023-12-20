<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    if($_POST['loja']){
        $_SESSION['bkLoja'] = $_POST['loja'];
    }

    $query = "select * from lojas where codigo = '{$_SESSION['bkLoja']}'";
    $result = mysqli_query($con, $query);
    $l = mysqli_fetch_object($result);

?>
<style>
    .barra_topo{
        position:absolute;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        flex-direction: column;
        top:0;
        width:100%;
        height:100px;
        background-color:#f4000a;
        color:#670600;
        border-bottom-right-radius:40px;
        border-bottom-left-radius:40px;
        font-family:FlameBold;
    }
    .barra_topo h2{
        color:#f6e13a;
    }
    .home_corpo{
        position: absolute;
        top:100px;
        bottom:0px;
        overflow:auto;
        background-color:#fff;
        left:0;
        right:0;
    }
    .fechar{
        color:#fff;
        font-size:14px;
        cursor:pointer;
        position:absolute;
        right:20px;
        top:15px;
    }
    li[pedido]{
        cursor:pointer;
        font-size:12px;
    }
</style>
<div class="barra_topo">
    <span class="fechar"><i class="fa-solid fa-right-from-bracket"></i> Sair</span>
    <h2><?=$l->nome?></h2>
</div>

<div class="home_corpo">
    <div class="row g-0 m-3">

        <ul class="list-group">
            <?php
            $query = "select a.*, b.nome, a.delivery_detalhes->>'$.pickupCode' as entrega, a.delivery_detalhes->>'$.returnCode' as retorno from vendas a left join clientes b on a.cliente = b.codigo where a.delivery_id = '{$l->mottu}' and a.situacao = 'pago' order by a.data desc";
            $result = mysqli_query($con, $query);
            while($d = mysqli_fetch_object($result)){
            ?>
                <li class="list-group-item" pedido="<?=$d->codigo?>">
                    <div class="d-flex justify-content-between">
                        <div>
                            Pedido #<?=str_pad($d->codigo, 6, "0", STR_PAD_LEFT)?>
                            <br>
                            <?=$d->nome?>
                        </div>
                        <div>
                            Entrega: <?=$d->entrega?>
                            <br>
                            Retorno: <?=$d->retorno?>
                        </div>
                    </div>
                </li>
            <?php
            }
            ?>
        </ul>

    </div>
</div>

<script>
    $(function(){
        $(".fechar").click(function(){
            $.confirm({
                title:"Desconectar",
                content:"Deseja realmente desconectar do sistema?",
                type:'red',
                columnClass:'col-12',
                buttons:{
                    sim:{
                        text:"Sim",
                        btnClass:'btn btn-danger',
                        action:function(){
                            localStorage.removeItem("loja");
                            window.location.href='./';
                        }
                    },
                    nao:{
                        text:"Não",
                        btnClass:'btn btn-warning',
                        action:function(){
                            
                        }
                    }
                }
            })
        })


        $("li[pedido]").click(function(){
            pedido = $(this).attr("pedido");
            loja = localStorage.getItem("loja");
            console.log(pedido)
            console.log(loja)
            Carregando();
            $.ajax({
                url:"pedido.php",
                type:"POST",
                data:{
                    pedido,
                    loja
                },
                success:function(dados){
                    Carregando();
                    $(".popupPalco").html(dados);
                    $(".popupArea").css("display","block");
                },
                error:function(){
                    console.log('erro');
                }
            });
        })

    })
</script>

  </body>
</html>