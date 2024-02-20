<?php
    $app = true;
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['acao'] == 'pagar'){

        require "{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/vendor/rede/Transacao.php";

        require "{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/vendor/rede/Consulta.php";

        file_put_contents('cartao.txt', $retorno);
        $cartao_detalhes = $retorno;
        $retorno = json_decode($retorno);

        if($retorno->authorization->status == 'Approved'){
            $c = explode("-", $_POST['reference']);

            //Se tiver pagamento pix pendente executar o script de cancelamento aqui 
            $q = "select a.pix_detalhes->>'$.id' as operadora_id, b.telefone as Ctelefone, c.telefone as Ltelefone from vendas a left join clientes b on a.cliente = b.codigo left join lojas c on a.loja = c.codigo where a.codigo = '{$c[0]}'";
            $r = mysqli_query($con, $q);
            $v = mysqli_fetch_object($r);
            
            $q = "update vendas set
                                    pagamento = 'credito',
                                    cartao_detalhes = '".(($cartao_detalhes)?:'{}')."',
                                    delivery = '',
                                    situacao = 'pago'
                                where codigo = '{$c[0]}'
            ";
            mysqli_query($con, $q);  

            $delivery_detalhes = false;
            if($v->operadora_id){
                $PIX = new MercadoPago;
                $delivery_detalhes = $PIX->CancelarPagamento($v->operadora_id); //////////////  
                $q = "update vendas set delivery_detalhes = '{$delivery_detalhes}' where codigo = '{$c[0]}'";
                mysqli_query($con, $q); 
            }


            $pedido = str_pad($c[0], 6, "0", STR_PAD_LEFT);
            $mensagem = "*BK Manaus Informa* - O pagamento do pedido *#{$pedido}* foi confirmado por Cartão de Crédito. Pedido enviado para a loja e está em produção.";
            EnviarWapp($v->Ctelefone,$mensagem);
            $mensagem = "Vou te informar o andamento por aqui, mas você pode acompanhar seu pedido *#{$pedido}* também pelo linque {$urlApp}.";
            EnviarWapp($v->Ctelefone,$mensagem);

            $mensagem = "*BK Manaus Informa* - Pedido *#{$pedido}* autorizado, aguardando início de produção.";
            EnviarWapp($v->Ltelefone,$mensagem);

            $mensagem = "Gerencie pelo painel da loja acessando {$urlLoja}";
            EnviarWapp($v->Ltelefone,$mensagem);

        }

        $retorno = [
            'status' => $retorno->authorization->status
        ];
        echo json_encode($retorno);
        exit();
    }

    if($_POST){
        $lista = [];
        foreach($_POST as $i => $v){
            $lista[] = "{$i}:'$v'";
        }
        $listaPost = implode(', ', $lista);
    }
    

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }
    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
    }

    if($_POST['pagamento'] and !$_POST['codVenda']){

        $query = "select
                        a.*,
                        b.nome as Cnome,
                        b.cpf as Ccpf,
                        b.telefone as Ctelefone,
                        b.email as Cemail,
                        c.codigo as endereco,
                        c.cep as Ecep,
                        c.logradouro as Elogradouro,
                        c.numero as Enumero,
                        c.complemento as Ecomplemento,
                        c.ponto_referencia as Eponto_referencia,
                        c.bairro as Ebairro,
                        c.localidade as Elocalidade,
                        c.uf as Euf
                    from vendas_tmp a 
                    left join clientes b on a.cliente = b.codigo
                    left join enderecos c on (a.cliente = c.cliente and c.padrao = '1')
                    where a.id_unico = '{$_SESSION['idUnico']}'";

        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);

        $q = "insert into vendas set 
                                    device = '{$d->id_unico}',
                                    loja = '{$_POST['loja']}',
                                    cliente = '{$d->cliente}',
                                    endereco = '{$d->endereco}',
                                    detalhes = '{$d->detalhes}', 
                                    pagamento = '{$_POST['pagamento']}',
                                    data = NOW(),
                                    delivery_id = '{$_POST['codigo_entrega']}',
                                    cupom = '{$_POST['cupom']}',
                                    valor_compra = '{$_POST['valor_compra']}',
                                    valor_entrega = '{$_POST['valor_entrega']}',
                                    valor_desconto = '{$_POST['valor_desconto']}',
                                    valor_total = '{$_POST['valor_total']}',
                                    situacao = 'pendente'
                    ";
        mysqli_query($con, $q);
        $_POST['codVenda'] = mysqli_insert_id($con);

        mysqli_query($con, "update vendas_tmp set detalhes = '{}' where id_unico = '{$_SESSION['idUnico']}'");
        $pedido = str_pad($_POST['codVenda'], 6, "0", STR_PAD_LEFT);
        $mensagem = "*BK Manaus Informa* - Sua solicitação de pagamento para do pedido *#{$pedido}* com cartão de crédito foi registrada. Aguardando confirmação.";
        EnviarWapp($d->Ctelefone,$mensagem);

    }

    // print_r($v);

?>
<style>
    .PedidoTopoTitulo{
        position:fixed;
        left:0px;
        top:0px;
        width:100%;
        height:60px;
        background:#f5ebdc;
        padding-left:70px;
        padding-top:15px;
        z-index:1;
    }
    .card small{
        font-size:12px;
        text-align:left;
    }
    .card input{
        border:solid 1px #ccc;
        border-radius:3px;
        background-color:#eee;
        color:#333;
        font-size:20px;
        text-align:center;
        margin-bottom:5px;
        width:100%;
        text-transform:uppercase;
    }

    .alertas{
        width:100%;
        text-align:center;
        background-color:#ffffff;
        border:solid 1px #fd3e00;
        color:#ff7d52;
        text-align:center !important;
        border-radius:7px;
        font-size:11px !important;
        font-weight:normal !important;
        padding:5px;
        margin-top:10px;
        margin-bottom:10px;
        display:<?=(($d->tentativas_pagamento < 3)?'block':'none')?>;
    }

</style>

<div class="card mb-3" style="background-color:#fafcff; padding:20px;">
    <div class="row">
            <div class="col-12">
                <div class="card text-white bg-danger mb-3" style="padding:20px;">

                    <small>Nome</small>
                    <input type="text" id="cartao_nome" placeholder="NOME NO CARTÃO" value='' />
                    <small>Número</small>
                    <input inputmode="numeric" maxlength='19' type="text" id="cartao_numero" placeholder="0000 0000 0000 0000" value='' />
                    <div class="row">
                        <div class="col-4">
                            <small>MM</small>
                            <input inputmode="numeric" maxlength='2' type="text" id="cartao_validade_mes" placeholder="00" value='' />
                        </div>
                        <div class="col-4">
                            <small>AAAA</small>
                            <input inputmode="numeric" maxlength='4' type="text" id="cartao_validade_ano" placeholder="0000" value='' />
                        </div>
                        <div class="col-4">
                            <small>CVV</small>
                            <input inputmode="numeric" maxlength='4' type="text" id="cartao_ccv" placeholder="0000" value='' />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <small>BANDEIRAS</small>
                            <div class="row">
                                <div class="col">
                                    <h2>
                                        <i class="fa-brands fa-cc-mastercard"></i>
                                        <i class="fa-brands fa-cc-visa"></i>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-secondary btn-block btn-lg" id="Pagar" hom="1" tentativas="<?=$d->tentativas_pagamento?>" loja="<?=$d->id_loja?>">
                    <i class="fa fa-calculator" aria-hidden="true"></i>
                    PAGAR R$ <?=number_format($_POST['valor_total'], 2, ',','.')?>
                </button>

                <div class="alertas animate__animated animate__fadeIn animate__infinite animate__slower">
                    Atenção, você possui <span tentativa><?=$d->tentativas_pagamento?></span> tentativa(s)!
                </div>


            </div>
        </div>
    </div>
</div>
<script>
    $(function(){

        $("#cartao_numero").mask("9999 9999 9999 9999");
        $("#cartao_validade_mes").mask("99");
        $("#cartao_validade_ano").mask("9999");
        $("#cartao_ccv").mask("9999");

        $("#Pagar").click(function(){

            kind = 'credit';
            reference = '<?="{$_POST['codVenda']}-".date("His")?>';
            amount = '<?=number_format($_POST['valor_total'],2,".",false)?>';
            cardholderName = $("#cartao_nome").val();
            cardNumber = $("#cartao_numero").val();
            expirationMonth = $("#cartao_validade_mes").val();
            expirationYear = $("#cartao_validade_ano").val();
            securityCode = $("#cartao_ccv").val();
            // tentativas = $(this).attr("tentativas");
            // loja = '<?=$_POST['codigo_entrega']?>';
            // captcha = '<?=$_POST['captcha']?>';

            // homologacao = $(this).attr("hom");

            // if(tentativas == 0){
            //     msg = '<div style="color:red"><center><h2><i class="fa-solid fa-ban"></i></h2>Você passou de três tentativas de pagamento com cartão de crédito. Favor selecionar outra forma de pagamento!</center></div>';
            //     $.alert(msg);
            //     return false;
            // }

            if(
                    !kind
                ||  !reference
                ||  !amount
                ||  !cardholderName
                ||  !cardNumber
                ||  !expirationMonth
                ||  !expirationYear
                ||  !securityCode

            ){
                $.alert('Preenche os dados do cartão corretamente!');
                return false;
            }
            Carregando();
            $.ajax({
                url:"pagamento/credito.php",
                type:"POST",
                data:{
                    kind,
                    reference,
                    amount,
                    cardholderName,
                    cardNumber,
                    expirationMonth,
                    expirationYear,
                    securityCode,
                    // loja,
                    // captcha,
                    // hom:homologacao,
                    acao:'pagar'
                },
                success:function(dados){
                    Carregando('none');
                    let retorno = JSON.parse(dados);
                    if (retorno.status == 'Approved') {
                        $.alert({
                            content:'Seu pagamento foi realizado com sucesso!',
                            title:"Mensagem de Aprovação",
                            type:"green",
                            buttons:{
                                'ok':{
                                    text:"ok",
                                    btnClass:'btn btn-success',
                                    action:function(){
                                        window.location.href='./';
                                    }
                                }
                            }
                        });
                    }else{
                        $.alert({
                            content:'Ocorreu um erro na tentativa de seu pagamento!',
                            title:"Mensagem de Recusão",
                            type:"red",
                            buttons:{
                                'ok':{
                                    text:"ok",
                                    btnClass:'btn btn-danger',
                                    action:function(){
                                        window.location.href='./';
                                    }
                                }
                            }
                        });
                    }
                }
            });

        });

    })
</script>