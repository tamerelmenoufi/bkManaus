<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    if($_POST['loja']){
        $_SESSION['bkLoja'] = $_POST['loja'];
    }

    $query = "select * from lojas where codigo = '{$_SESSION['bkLoja']}' and situacao = '1' and deletado != '1'";
    $result = mysqli_query($con, $query);
    $l = mysqli_fetch_object($result);

?>
<style>

    li[pedido], li[entrega], li[entregaBLQ]{
        cursor:pointer;
        font-size:12px;
    }
    .bg-secondary-subtle{
        background-color:#e2e3e5;
    }
    .bg-success-subtle{
        background-color:#d1e7dd;
    }

</style>


    <div class="row g-0 m-3">

        <ul class="list-group">
            <?php
           $query = "
            (select
            'entrega' as tipo,
            a.codigo,	
            '' as device,	
            '' as detalhes,	
            a.ifood,
            '' as loja,	
            '' as cliente,	
            '' as endereco,	
            '' as pagamento,	
            '' as pix_detalhes,	
            '' as cartao_detalhes,	
            '' as delivery,	
            '' as delivery_id,	
            if(b.nome != '',b.nome,a.entregador) as delivery_detalhes,	
            a.data,	
            '' as cupom,	
            '' as valor_compra,	
            '' as valor_entrega,	
            '' as valor_desconto,	
            '' as valor_total,
            a.producao,
            'pago' as situacao,
            IF(a.producao = 'pendente', 0, IF(a.producao = 'producao', 1, if(a.producao = 'entrega', 2, 3))) as ordem,	
            '' as nome,
            '' as entrega,	
            '' as retorno
                    from ifood a left join entregadores b on a.entregador = b.codigo where a.loja = '{$_SESSION['bkLoja']}' and (a.producao != 'entregue' or data >= NOW() - INTERVAL 1 DAY) )

                        union

            (select 
                    'pedido' as tipo, 
                    a.*, 
                    IF(a.producao = 'pendente', 0, IF(a.producao = 'producao', 1, if(a.producao = 'entrega', 2, 3))) as ordem,
                    b.nome, 
                    a.delivery_detalhes->>'$.pickupCode' as entrega, 
                    a.delivery_detalhes->>'$.returnCode' as retorno 
                    
                from vendas a 
                    left join clientes b on a.cliente = b.codigo 
                where /*a.delivery_id = '{$l->mottu}' and*/ 
                    a.situacao = 'pago' and 
                    loja = '{$_SESSION['bkLoja']}' and (a.producao != 'entregue' or data >= NOW() - INTERVAL 1 DAY)
                    /*and data >= NOW() - INTERVAL 1 DAY*/) 
                order by ordem asc, data desc";

            $result = mysqli_query($con, $query);
            while($d = mysqli_fetch_object($result)){

            $delivery = json_decode($d->delivery_detalhes);

            if($d->pagamento == 'ifood'){
                $ifood = json_decode($d->ifood);
                $d->nome = $ifood->cliente->nome;
                $d->codigo_ifood = $ifood->codigo;
            }

            if(!$d->producao or $d->producao == 'pendente'){
                $bg = 'bg-secondary';
            }elseif($d->producao == 'producao'){
                $bg = 'bg-warning';
            }elseif($d->producao == 'entrega'){
                $bg = 'bg-info';
            }elseif($d->producao == 'entregue'){
                $bg = 'bg-success';
            }


                if($d->tipo == 'pedido'){
            ?>
                <li class="list-group-item <?=$bg?>" pedido="<?=$d->codigo?>">
                    <div class="d-flex justify-content-between">
                        <div>
                            Pedido #<?=str_pad((($d->codigo_ifood)?:$d->codigo), 6, "0", STR_PAD_LEFT).(($ifood)?' (ifood) ':false)?>
                            <br>
                            <?=$d->nome?>
                        </div>
                        <div>
                            Data: <?=dataBr($d->data)?>
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
                }else{
            ?>                    
                <li class="list-group-item <?=$bg?>" entrega<?=(($d->producao == 'entregue')?'BLQ':false)?>="<?=$d->codigo?>">
                    <div class="d-flex justify-content-between">
                        <div>
                            Pedido #<?=str_pad($d->ifood, 6, "0", STR_PAD_LEFT).' (ifood) '?>
                        </div>
                        <div>
                            Data: <?=dataBr($d->data)?>
                        </div>
                        <div>
                            Situação: <?=strtoupper($d->producao)?>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-2 mb-2">
                        <div><b><i class="fa-solid fa-motorcycle"></i> Dados de Entrega</b></div>
                        <div>
                            <b><?=strtoupper($d->producao)?></b>
                        </div>
                    </div>
                    <?php
                    if(!$d->delivery_detalhes){
                    ?>
                    <div class="d-flex justify-content-center dados">
                        <div>
                            <i class="fa-solid fa-bag-shopping"></i> <b>RETIRADA NA LOJA</b> 
                        </div>
                    </div>   
                    <?php
                    }else if($d->delivery_detalhes == '1'){
                    ?>
                    <div class="d-flex justify-content-center dados">
                        <div>
                            <i class="fa-solid fa-bag-shopping"></i> <b>RETIRADA PELO PARCEIRO</b> 
                        </div>
                    </div>                  
                    <?php
                    }else{
                    ?>
                    <div class="d-flex justify-content-start dados">
                        <div>
                            <i class="fa-solid fa-person-biking"></i> <b>Entregador: </b> 
                        </div>
                        <div class="ms-2">
                            <?=$d->delivery_detalhes?>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </li>
            <?php
                }
            }
            ?>
        </ul>

    </div>


<script>
    $(function(){


        $("li[pedido]").click(function(){
            pedido = $(this).attr("pedido");
            loja = localStorage.getItem("loja");

            Carregando();
            $.ajax({
                url:"pedido.php",
                type:"POST",
                data:{
                    pedido,
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

        $("li[entrega]").click(function(){
            codigo = $(this).attr("entrega");
            loja = localStorage.getItem("loja");

            // $.alert('Em Desenvolvimento')

            Carregando();
            $.ajax({
                url:"ifood_n/index.php",
                type:"POST",
                data:{
                    codigo,
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


        atualizacao = setTimeout(() => {
            $.ajax({
                url:"lista.php",
                type:"POST",
                data:{
                    loja,
                },
                success:function(dados){
                    $(".home_corpo").html(dados);
                }
            }); 
        }, 10000);

    })
</script>

  </body>
</html>