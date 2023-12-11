<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    $query = "select
                    *
                from vendas_tmp 
                where id_unico = '{$_SESSION['id_unico']}'";

    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    $pos =  strripos($d->nome, " ");

?>
<style>
 
</style>

<div class="col">
    <div class="row">
            <div class="col-12">
                <div class="card mb-3" style="background-color:#fafcff; padding:20px;">
                    <p style="text-align:center">
                        <?php

                            $pedido = str_pad($d->codigo, 6, "0", STR_PAD_LEFT);


                            $PIX = new MercadoPago;
                            $retorno = $PIX->ObterPagamento($d->operadora_id);
                            $operadora_retorno = $retorno;
                            $dados = json_decode($retorno);

                            if( $d->operadora_id and
                                $d->operadora == 'mercadopago' and
                                $d->total == $dados->transaction_amount
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
                                    "transaction_amount": '.$d->total.',
                                    "description": "Pedido '.$pedido.' - Venda BKManaus (Delivery)",
                                    "payment_method_id": "pix",
                                    "payer": {
                                    "email": "'.$d->email.'",
                                    "first_name": "'.substr($d->nome, 0, ($pos-1)).'",
                                    "last_name": "'.substr($d->nome, $pos, strlen($d->nome)).'",
                                    "identification": {
                                        "type": "CPF",
                                        "number": "'.str_replace(array('.','-'),false,$d->cpf).'"
                                    },
                                    "address": {
                                        "zip_code": "'.str_replace(array('.','-'),false,$d->cep).'",
                                        "street_name": "'.$d->rua.'",
                                        "street_number": "'.$d->numero.'",
                                        "neighborhood": "'.$d->bairro.'",
                                        "city": "Manaus",
                                        "federal_unit": "AM"
                                    }
                                    }
                                }');

                                $dados = json_decode($retorno);

                                $operadora_id = $dados->id;
                                $forma_pagamento = $dados->payment_method_id;
                                $operadora_situacao = $dados->status;
                                $qrcode = $dados->point_of_interaction->transaction_data->qr_code;
                                $qrcode_img = $dados->point_of_interaction->transaction_data->qr_code_base64;
                                $api_delivery = false;

                                if($operadora_id){

                                    // //////////////////////API DELIVERY////////////////////////////

                                    // // $content = http_build_query(array(
                                    // //     'pedido' => $d->codigo,
                                    // //     'empresa' => $d->id_loja,
                                    // // ));

                                    // // $context = stream_context_create(array(
                                    // //     'http' => array(
                                    // //         'method'  => 'POST',
                                    // //         'content' => $content,
                                    // //         'header' => "Content-Type: application/x-www-form-urlencoded",
                                    // //     )
                                    // // ));

                                    // // $result = file_get_contents("http://bee.mohatron.com/pedido.php", null, $context);
                                    // // $result = json_decode($result);
                                    if($dados->status == 'approved' and $d->retirada_local != '1'){
                                        $json = '{
                                            "code": "'.$d->codigo.'",
                                            "fullCode": "bk-'.$d->codigo.'",
                                            "preparationTime": 0,
                                            "previewDeliveryTime": false,
                                            "sortByBestRoute": false,
                                            "deliveries": [
                                              {
                                                "code": "'.$d->codigo.'",
                                                "confirmation": {
                                                  "mottu": true
                                                },
                                                "name": "'.$d->nome.'",
                                                "phone": "'.trim(str_replace(array(' ','-','(',')'), false, $d->telefone)).'",
                                                "observation": "'.$d->observacoes.'",
                                                "address": {
                                                  "street": "'.$d->rua.'",
                                                  "number": "'.$d->numero.'",
                                                  "complement": "'.$d->referencia.'",
                                                  "neighborhood": "'.$d->bairro.'",
                                                  "city": "Manaus",
                                                  "state": "AM",
                                                  "zipCode": "'.trim(str_replace(array(' ','-'), false, $d->cep)).'"
                                                },
                                                "onlinePayment": true,
                                                "productValue": '.$d->total.'
                                              }
                                            ]
                                          }';

                                        $mottu = new mottu;

                                        $retorno1 = $mottu->NovoPedido($json, $d->id_mottu);
                                        $retorno = json_decode($retorno1);

                                        $api_delivery = $retorno->id;
                                    }

                                    //////////////////////API DELIVERY////////////////////////////


                                    $q = "insert into status_venda set
                                    venda = '{$d->codigo}',
                                    operadora = 'mercado_pago',
                                    tipo = 'pix',
                                    data = NOW(),
                                    retorno = '{$retorno}'";
                                    mysqli_query($con, $q);

                                    mysqli_query($con, "update vendas set
                                                                operadora_id = '{$operadora_id}',
                                                                forma_pagamento = '{$forma_pagamento}',
                                                                operadora = 'mercadopago',
                                                                operadora_situacao = '{$operadora_situacao}',
                                                                operadora_retorno = '{$retorno}'
                                                                ".(($api_delivery or $d->retirada_local == '1')?", api_delivery = '{$api_delivery}', situacao = 'p', data_finalizacao = NOW(), SEARCHING = NOW()":false)."
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
                    <h1>R$ <?=number_format($d->total,2,'.',false)?></h1>
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

        $("button[copiar]").click(function(){
            obj = $(this);
            texto = $(this).attr("copiar");
            CopyMemory(texto);
            obj.removeClass('btn-secondary');
            obj.addClass('btn-success');
            obj.children("span").text("Código PIX Copiado!");
        });

        <?php
        if($operadora_id){
        ?>
        $.ajax({
            url:"src/produtos/pagar_pix_verificar.php",
            type:"POST",
            data:{
                id:'<?=$operadora_id?>'
            },
            success:function(dados){
                $(".status_pagamento").html(dados)
            }
        });
        <?php
        }
        ?>
    })
</script>