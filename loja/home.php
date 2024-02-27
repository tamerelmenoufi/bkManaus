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
        bottom:55px;
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
    /* li[pedido]{
        cursor:pointer;
        font-size:12px;
    }
    .bg-secondary-subtle{
        background-color:#e2e3e5;
    }
    .bg-success-subtle{
        background-color:#d1e7dd;
    } */
    .legenda{
        position:absolute;
        bottom:0px;
        right:0px;
        left:0px;
        height:55px;
    }
    .entregadores{
        position:absolute;
        bottom:10px;
        right:20px;
    }
    .ifood{
        position:absolute;
        left:20px;
        top:10px;
        height:40px;
        width:auto;
        cursor:pointer;
    }
</style>
<div class="barra_topo">
    <img class="ifood" src="<?=$urlPainel?>/img/ifood.png?<?=date("YmdHis")?>" />
    <span class="fechar"><i class="fa-solid fa-right-from-bracket"></i> Sair</span>
    <h2><?=$l->nome?></h2>
</div>

<div class="home_corpo">
    <!-- <div class="row g-0 m-3">

        <ul class="list-group">
            <?php
            $query = "select a.*, if(a.producao = 'pendente',0,1) as ordem, b.nome, a.delivery_detalhes->>'$.pickupCode' as entrega, a.delivery_detalhes->>'$.returnCode' as retorno from vendas a left join clientes b on a.cliente = b.codigo where /*a.delivery_id = '{$l->mottu}' and*/ a.situacao = 'pago' and loja = '{$_SESSION['bkLoja']}' order by a.producao asc, a.data desc";
            $result = mysqli_query($con, $query);
            while($d = mysqli_fetch_object($result)){

            $delivery = json_decode($d->delivery_detalhes);

                if(!$d->producao or $d->producao == 'pendente'){
                    $bg = 'bg-secondary';
                }elseif($d->producao == 'producao'){
                    $bg = 'bg-warning';
                }elseif($d->producao == 'entrega'){
                    $bg = 'bg-info';
                }elseif($d->producao == 'entregue'){
                    $bg = 'bg-success';
                }

            ?>
                <li class="list-group-item <?=$bg?>" pedido="<?=$d->codigo?>">
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
                    <?php
                    if($delivery->deliveryMan->name){
                    ?>
                    <div class="d-flex justify-content-between mt-2 mb-2">
                        <div><b><i class="fa-solid fa-motorcycle"></i> Dados de Entrega</b></div>
                        <div>
                            <b><?=strtoupper($d->producao)?></b>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between dados">
                        <div>
                            <i class="fa-solid fa-person-biking"></i> Nome
                        </div>
                        <div>
                            <?=$delivery->deliveryMan->name?>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between dados">
                        <div>
                            <i class="fa-solid fa-mobile-screen-button"></i> Telefone
                        </div>
                        <div>
                            <?="({$delivery->deliveryMan->ddd}) {$delivery->deliveryMan->phone}"?>
                        </div>
                    </div>                    
                    <?php
                    }
                    ?>
                </li>
            <?php
            }
            ?>
        </ul>

    </div> -->
</div>
<div class="d-flex justify-content-between legenda">
    <div class="d-flex flex-column justify-content-between w-50">
        <div class="d-flex justify-content-between" style="font-size:12px">
            <div class="ms-3 p-1"><object style="width:10px; height:10px; border-radius:2px;" class="bg-secondary"></object> Pendente</div>
            <div class="p-1"><object style="width:10px; height:10px; border-radius:2px;" class="bg-warning"></object> Produção</div>
        </div>
        <div class="d-flex justify-content-between" style="font-size:12px">
            <div class="ms-3 p-1"><object style="width:10px; height:10px; border-radius:2px;" class="bg-info"></object> Entrega</div>
            <div class="p-1"><object style="width:10px; height:10px; border-radius:2px;" class="bg-success"></object> Entregue</div>
        </div>
    </div>
    <div class="d-flex justify-content-end w-50 pe-3">
        <div class="p-2">
            <button class="btn btn-primary entregadores" loja="<?=$_SESSION['bkLoja']?>"><i class="fa-solid fa-person-biking"></i> Entregadores</button>
        </div>
        
    </div>
</div>  

<script>
    $(function(){

        $.ajax({
            url:"lista.php",
            type:"POST",
            success:function(dados){
                $(".home_corpo").html(dados);
            }
        }); 

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

        // $("li[pedido]").click(function(){
        //     pedido = $(this).attr("pedido");
        //     loja = localStorage.getItem("loja");
        //     Carregando();
        //     $.ajax({
        //         url:"pedido.php",
        //         type:"POST",
        //         data:{
        //             pedido,
        //             loja
        //         },
        //         success:function(dados){
        //             Carregando('none');
        //             $(".popupPalco").html(dados);
        //             $(".popupArea").css("display","block");
        //         },
        //         error:function(){
        //             console.log('erro');
        //         }
        //     });
        // })

        $(".entregadores").click(function(){
            loja = localStorage.getItem("loja");
            Carregando();
            $.ajax({
                url:"entregadores/index.php",
                type:"POST",
                data:{
                    loja
                },
                success:function(dados){
                    Carregando('none');
                    $(".popupPalco").html(dados);
                    $(".popupArea").css("display","block");
                },
                error:function(){
                    console.log('erro');
                }
            });
        })


        $(".ifood").click(function(){
            Carregando();
            $.ajax({
                url:"ifood/index.php",
                type:"POST",
                data:{
                },
                success:function(dados){
                    Carregando('none');
                    $(".popupPalco").html(dados);
                    $(".popupArea").css("display","block");
                },
                error:function(){
                    console.log('erro');
                }
            });
        })

        // atualizacao = setTimeout(() => {
        //     $.ajax({
        //         url:"home.php",
        //         type:"POST",
        //         data:{
        //             loja,
        //         },
        //         success:function(dados){
        //             $(".CorpoApp").html(dados);
        //         }
        //     }); 
        // }, 10000);

        // $(".popupFecha").click(function(){
        //     clearTimeout(atualizacao);
        // })

    })
</script>

  </body>
</html>