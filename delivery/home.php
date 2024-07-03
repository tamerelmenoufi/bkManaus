<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    if($_POST['entregador']){
        $_SESSION['DbkEntregador'] = $_POST['entregador'];
    }
    if($_POST['loja']){
        $_SESSION['DbkLoja'] = $_POST['loja'];
    }

    if($_POST['acao'] == 'entrega'){

        $query = "update ifood set pedido = '{$_POST['cod_entrega']}', finalizacao = NOW(), producao = 'entregue', situacao = 'concluido' where codigo = '{$_POST['entrega']}'";
        mysqli_query($con, $query);
        // entrega,
        // cod_entrega:name,
        // acao:'entrega'

    }
    

    $query = "select * from entregadores where codigo = '{$_SESSION['DbkEntregador']}'";
    $result = mysqli_query($con, $query);
    $e = mysqli_fetch_object($result);

    if($e->situacao == '0'){
        header("location:./");
        exit();
    }

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
    li[pedido]{
        cursor:pointer;
        font-size:12px;
    }
    li[entrega]{
        cursor:pointer;
        font-size:12px;
    }
    li[entregaBLQ]{
        cursor:pointer;
        font-size:12px;
    }
    .bg-secondary-subtle{
        background-color:#e2e3e5;
    }
    .bg-success-subtle{
        background-color:#d1e7dd;
    }
    .entregadores{
        position:absolute;
        bottom:10px;
        right:20px;
    }
</style>
<div class="barra_topo">
    <span class="fechar"><i class="fa-solid fa-right-from-bracket"></i> Sair</span>
    <h2><?=$e->nome?></h2>
</div>

<div class="home_corpo">
    <div class="row g-0 m-3">

        <ul class="list-group">
            <?php
            $query = "(select 
                            'pedido' as tipo,
                            a.*, 
                            if(a.producao = 'pendente',0,1) as ordem, 
                            b.nome, 
                            a.delivery_detalhes->>'$.pickupCode' as entrega, 
                            a.delivery_detalhes->>'$.returnCode' as retorno,
                            c.codigo as endereco,
                            c.cep as Ecep,
                            c.logradouro as Elogradouro,
                            c.numero as Enumero,
                            c.complemento as Ecomplemento,
                            c.ponto_referencia as Eponto_referencia,
                            c.bairro as Ebairro,
                            c.localidade as Elocalidade,
                            c.uf as Euf
                    from vendas a 
                    left join clientes b on a.cliente = b.codigo 
                    left join enderecos c on (a.cliente = c.cliente and c.padrao = '1')
                    where /*a.delivery_id = '{$l->mottu}' and*/ a.situacao = 'pago' and loja = '{$_SESSION['DbkLoja']}' and delivery_detalhes->>'$.deliveryMan.id' = '{$_SESSION['DbkEntregador']}' order by ordem asc, a.data desc) UNION (select
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
            b.nome as delivery_detalhes,
            a.data,
            '' as cupom,	
            '' as valor_compra,	
            '' as valor_entrega,	
            '' as valor_desconto,	
            '' as valor_total,
            a.producao,	
            'pago' as situacao,	
            '' as ordem,
            '' as nome,	
            '' as entrega,	
            '' as retorno,
            '' as endereco,	
            '' as Ecep,
            '' as Elogradouro,	
            '' as Enumero,
            '' as Ecomplemento,	
            '' as Eponto_referencia,	
            '' as Ebairro,
            '' as Elocalidade,	
            '' as Euf
                    from ifood a left join entregadores b on a.entregador = b.codigo where a.loja = '{$_SESSION['DbkLoja']}' and a.entregador = '{$_SESSION['DbkEntregador']}')";
            $result = mysqli_query($con, $query);
            while($d = mysqli_fetch_object($result)){

            $delivery = json_decode($d->delivery_detalhes);


            if($d->pagamento == 'ifood'){
                $ifood = json_decode($d->ifood);
                $d->codigo_ifood = $ifood->codigo;
                $d->nome = $ifood->cliente->nome;
                $d->codigo_ifood = $ifood->codigo;
                            
                $d->Elogradouro = $ifood->endereco->logradouro;
                $d->Enumero = $ifood->endereco->numero;
                $d->Ebairro = $ifood->endereco->bairro;
                $d->Ecomplemento = $ifood->endereco->complemento;
                $d->Eponto_referencia = $ifood->endereco->ponto_referencia;
            }


            if($d->tipo == 'pedido'){
            ?>
                <li class="list-group-item <?=(($d->producao != 'entregue')?'bg-secondary-subtle':'bg-success-subtle')?>" pedido="<?=$d->codigo?>">
                    <div class="d-flex justify-content-between">
                        <div>
                            Pedido #<?=str_pad((($d->codigo_ifood)?:$d->codigo), 6, "0", STR_PAD_LEFT).(($d->codigo_ifood)?' (ifood)':false)?>
                            <br>
                            <?=$d->nome?>
                        </div>
                        <div>
                            Entrega: ****<?=$d->entregaX?>
                            <br>
                            Retorno: <?=$d->retorno?>
                        </div>
                    </div>
                    <?php
                    if($delivery->deliveryMan->name){
                    ?>
                    <div class="d-flex justify-content-between mt-2 mb-2">
                        <div><b><i class="fa-solid fa-motorcycle"></i> Dados da Entrega</b></div>
                        <div>
                            <b><?=strtoupper($d->producao)?></b>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start dados">
                        <div>
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div class="ms-1">
                            <?=$d->Elogradouro?>, <?=$d->Enumero.(($d->Ecomplemento)?", {$d->Ecomplemento}":false).(($d->Eponto_referencia)?" ({$d->Eponto_referencia})":false)?>
                        </div>
                    </div>                  
                    <?php
                    }
                    ?>
                </li>
            <?php
            }else{
            ?>
            <li class="list-group-item <?=(($d->producao != 'entregue')?'bg-secondary-subtle':'bg-success-subtle')?>" entrega<?=(($d->producao != 'entregue')?false:'BLQ')?>="<?=$d->codigo?>" ifood="<?=$d->ifood?>">
                    <div class="d-flex justify-content-between">
                        <div>
                            Ifood #<?=str_pad($d->ifood, 6, "0", STR_PAD_LEFT)?>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <b>Situação</b>
                        </div>
                        <div>
                            <b><?=strtoupper($d->producao)?></b>
                        </div>
                    </div>                    
                </li>
            <?php
            }
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
                            localStorage.removeItem("Dloja");
                            localStorage.removeItem("Dentregador");
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
            loja = localStorage.getItem("Dloja");
            entregador = localStorage.getItem("Dentregador");
            Carregando();
            $.ajax({
                url:"pedido.php",
                type:"POST",
                data:{
                    pedido,
                    loja,
                    entregador
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
            entrega = $(this).attr("entrega");
            ifood = $(this).attr("ifood");

            
            $.confirm({
                title: 'Confirmação de Entrega',
                content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Digito o código para a entrega ifood <b>#'+ ifood +'</b></label>' +
                '<input type="text" inputmode="numeric" placeholder="Digite o código" class="name form-control" required />' +
                '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Submit',
                        btnClass: 'btn-blue',
                        action: function () {
                            var name = this.$content.find('.name').val();
                            if(!name){
                                $.alert('O código de entrega é Obrigatório');
                                return false;
                            }
                            if(name.length != 4){
                                $.alert('O código de entrega está errado ou incompleto!');
                                return false;
                            }
                            
                            $.ajax({
                                url:"home.php",
                                type:"POST",
                                data:{
                                    entrega,
                                    cod_entrega:name,
                                    acao:'entrega'
                                },
                                success:function(dados){
                                    $(".CorpoApp").html(dados);
                                }
                            });
                        }
                    },
                    cancel: function () {
                        //close
                    },
                },
                onContentReady: function () {
                    // bind to events
                    $(".name").mask("9999");
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });


        })



        $(".entregadores").click(function(){
            loja = localStorage.getItem("Dloja");
            entregador = localStorage.getItem("Dentregador");
            Carregando();
            $.ajax({
                url:"entregadores/index.php",
                type:"POST",
                data:{
                    loja,
                    entregador
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
            loja = localStorage.getItem("Dloja");
            entregador = localStorage.getItem("Dentregador");
            $.ajax({
                url:"home.php",
                type:"POST",
                data:{
                    loja,
                    entregador,
                },
                success:function(dados){
                    $(".CorpoApp").html(dados);
                }
            }); 
        }, 10000);

        // $(".popupFecha").click(function(){
        //     clearTimeout(atualizacao);
        // })

    })
</script>

  </body>
</html>