<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    if($_POST['pagamento']){

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
                    where a.id_unico = '{$_SESSION['id_unico']}'";

        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);



        mysqli_query($con, "insert into vendas set 
                                                    device = '{$d->id_unico}',
                                                    cliente = '{$d->cliente}',
                                                    endereco = '{$d->endereco}',
                                                    detalhes = '{$d->detalhes}', 
                                                    pagamento = '{$_POST['pagamento']}',
                                                    data = NOW(),
                                                    data = '{$_POST['cupom']}',
                                                    valor_compra = '<?=$_POST['valor_compra']?>',
                                                    valor_entrega = '<?=$_POST['valor_entrega']?>',
                                                    valor_desconto = '<?=$_POST['valor_desconto']?>',
                                                    valor_total = '<?=$_POST['valor_desconto']?>',
                                                    situacao = 'pendente'
                    ");
        $codigo = mysqli_insert_id($con);
        $_SESSION['codVenda'] = $codigo;

    }else if($_POST['codVenda']){
        $_SESSION['codVenda'] = $_POST['codVenda'];
    }


    $v = mysqli_fetch_object(mysqli_query($con, "select * from vendas where codigo = '{$_SESSION['codVenda']}'"));


    $pos =  strripos($d->Cnome, " ");

?>
<style>
 
</style>

<div class="col">
    <div class="row">
            <div class="col-12">
                <div class="card mb-3" style="background-color:#fafcff; padding:20px;">
                    <p style="text-align:center">
                        <?php

                            $pedido = str_pad($v->codigo, 6, "0", STR_PAD_LEFT);


                            $PIX = new MercadoPago;
                            $retorno = $PIX->ObterPagamento($d->operadora_id); //////////////
                            $operadora_retorno = $retorno;
                            $dados = json_decode($retorno);

                            if( $d->operadora_id and
                                $d->operadora == 'mercadopago' and
                                $v->valor_total == $dados->transaction_amount
                                ){

                                // echo "<pre>";
                                // print_r($dados);
                                // echo "</pre>";
                                $operadora_id = $dados->id;


                                $forma_pagamento = $dados->payment_method_id;
                                $operadora_situacao = $dados->status;
                                $qrcode = $dados->point_of_interaction->transaction_data->qr_code;
                                $qrcode_img = $dados->point_of_interaction->transaction_data->qr_code_base64;

                            }else{

                                //AQUI É A GERAÇÃO DA COBRANÇA PIX

                                $PIX = new MercadoPago;
                                // "transaction_amount": '.$d->total.',
                                // "transaction_amount": 2.11,
                                $retorno = $PIX->Transacao('{
                                    "transaction_amount": '.$v->valor_total.',
                                    "description": "Pedido '.$pedido.' - Venda BKManaus (Delivery)",
                                    "payment_method_id": "pix",
                                    "payer": {
                                    "email": "'.$d->Cemail.'",
                                    "first_name": "'.substr($d->Cnome, 0, ($pos-1)).'",
                                    "last_name": "'.substr($d->Cnome, $pos, strlen($d->Cnome)).'",
                                    "identification": {
                                        "type": "CPF",
                                        "number": "'.str_replace(array('.','-'),false,$d->Ccpf).'"
                                    },
                                    "address": {
                                        "zip_code": "'.str_replace(array('.','-'),false,$d->Ccep).'",
                                        "street_name": "'.$d->Clogradouro.'",
                                        "street_number": "'.$d->Cnumero.'",
                                        "neighborhood": "'.$d->Cbairro.'",
                                        "city": "Manaus",
                                        "federal_unit": "AM"
                                    }
                                    }
                                }');

                                $operadora_retorno = $retorno;
                                $dados = json_decode($retorno);

                                $operadora_id = $dados->id;
                                $forma_pagamento = $dados->payment_method_id;
                                $operadora_situacao = $dados->status;
                                $qrcode = $dados->point_of_interaction->transaction_data->qr_code;
                                $qrcode_img = $dados->point_of_interaction->transaction_data->qr_code_base64;
                                $api_delivery = false;

                                if($operadora_id){

                                    if($dados->status == 'approved' and $d->retirada_local != '1'){
                                        $json = '{
                                            "code": "'.$v->codigo.'",
                                            "fullCode": "bk-'.$v->codigo.'",
                                            "preparationTime": 0,
                                            "previewDeliveryTime": false,
                                            "sortByBestRoute": false,
                                            "deliveries": [
                                              {
                                                "code": "'.$v->codigo.'",
                                                "confirmation": {
                                                  "mottu": true
                                                },
                                                "name": "'.$d->Cnome.'",
                                                "phone": "'.trim(str_replace(array(' ','-','(',')'), false, $d->Ctelefone)).'",
                                                "observation": "'.$d->Ccomplemento.'",
                                                "address": {
                                                  "street": "'.$d->Clogradouro.'",
                                                  "number": "'.$d->Cnumero.'",
                                                  "complement": "'.$d->Cponto_referencia.'",
                                                  "neighborhood": "'.$d->Cbairro.'",
                                                  "city": "Manaus",
                                                  "state": "AM",
                                                  "zipCode": "'.trim(str_replace(array(' ','-'), false, $d->Ccep)).'"
                                                },
                                                "onlinePayment": true,
                                                "productValue": '.$v->valor_total.'
                                              }
                                            ]
                                          }';

                                        $mottu = new mottu;

                                        $retorno1 = $mottu->NovoPedido($json, $d->id_mottu);
                                        $retorno = json_decode($retorno1);

                                        $api_delivery = $retorno->id;
                                    }

                                    //////////////////////API DELIVERY////////////////////////////


                                    mysqli_query($con, "update vendas set
                                                                pagamento = 'pix',
                                                                pix_detalhes = '{$retorno}',
                                                                delivery = 'mottu',
                                                                delivery_detalhes = '{$retorno1}'
                                                                ".(($api_delivery or $d->retirada_local == '1')?", situacao = 'pago'":false)."
                                                        where codigo = '{$d->codigo}'
                                                ");

                                }
                            }

                            // $qrcode = '12e44a26-e3b4-445f-a799-1199df32fa1e';
                            // $operadora_id = 23997683882;

                        ?>
                        Utilize o QrCode para pagar a sua conta ou copie o códio PIX abaixo.
                    </p>
                    <div style="padding:20px;">
                        <img src="data:image/png;base64,<?=$qrcode_img?>" style="width:100%">
                        <!-- <img src="img/qrteste.png" style="width:100%"> -->
                        <div class="status_pagamento"></div>
                    </div>
                    Total a Pagar:
                    <h1>R$ <?=number_format($v->valor_total,2,'.',false)?></h1>
                    <p style="text-align:center; font-size:12px;">Clique no botão abaixo para copiar o Código PIX de sua compra.</p>
                    <!-- <p style="text-align:center; font-size:16px;"><?=$qrcode?></p> -->
                    <button copiar="<?=$qrcode?>" class="btn btn-secondary btn-lg btn-block"><i class="fa-solid fa-copy"></i> <span>Copiar Código PIX</span></button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){

        codVenda = '<?=$codigo?>';
        if(codVenda){
            localStorage.setItem("codVenda", codVenda);
        }

        $("button[copiar]").click(function(){
            obj = $(this);
            texto = $(this).attr("copiar");
            CopyMemory(texto);
            obj.removeClass('btn-secondary');
            obj.addClass('btn-success');
            obj.children("span").text("Código PIX Copiado!");
        });


    })
</script>