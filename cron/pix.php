<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    $query = "select 
                    a.*, 
                    a.pix_detalhes->>'$.id' as operadora_id,
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
                    c.uf as Euf,
                    d.telefone as Ltelefone
                from vendas a

                left join clientes b on a.cliente = b.codigo
                left join lojas d on a.loja = d.codigo
                left join enderecos c on (a.cliente = c.cliente and c.padrao = '1')                
            where a.situacao = 'pendente'";

    $result = mysqli_query($con, $query);
    while($v = mysqli_fetch_object($result)){

        $PIX = new MercadoPago;
        $retorno = $PIX->ObterPagamento($v->operadora_id);
        $operadora_retorno = $retorno;
        $dados = json_decode($retorno);

        if($dados->status == 'approved'){
            
            // //////////////////////API DELIVERY////////////////////////////
            // if($dados->status == 'approved'){
            //     $json = '{
            //         "code": "'.$v->codigo.'",
            //         "fullCode": "bk-'.$v->codigo.'",
            //         "preparationTime": 0,
            //         "previewDeliveryTime": false,
            //         "sortByBestRoute": false,
            //         "deliveries": [
            //           {
            //             "code": "'.$v->codigo.'",
            //             "confirmation": {
            //               "mottu": true
            //             },
            //             "name": "'.$v->Cnome.'",
            //             "phone": "'.trim(str_replace(array(' ','-','(',')'), false, $v->Ctelefone)).'",
            //             "observation": "'.$v->Ccomplemento.'",
            //             "address": {
            //               "street": "'.$v->Clogradouro.'",
            //               "number": "'.$v->Cnumero.'",
            //               "complement": "'.$v->Cponto_referencia.'",
            //               "neighborhood": "'.$v->Cbairro.'",
            //               "city": "Manaus",
            //               "state": "AM",
            //               "zipCode": "'.trim(str_replace(array(' ','-'), false, $v->Ccep)).'"
            //             },
            //             "onlinePayment": true,
            //             "productValue": '.$v->valor_total.'
            //           }
            //         ]
            //       }';

            //     $mottu = new mottu;

            //     $retorno1 = $mottu->NovoPedido($json, $v->delivery_id);
            //     $retorno1 = json_decode($retorno1);

            //     $api_delivery = $retorno1->id;
            // }
            // //////////////////////API DELIVERY////////////////////////////

            $pedido = str_pad($v->codigo, 6, "0", STR_PAD_LEFT);
            $mensagem = "*BK Manaus Informa* - O pagamento do pedido *#{$pedido}* foi confirmado por PIX. Pedido enviado para a loja e está em produção.";
            EnviarWapp($v->Ctelefone,$mensagem);
            $mensagem = "Vou te informar o andamento por aqui, mas você pode acompanhar seu pedido *#{$pedido}* também pelo linque {$urlApp}.";
            EnviarWapp($v->Ctelefone,$mensagem);

            $mensagem = "*BK Manaus Informa* - Pedido *#{$pedido}* autorizado, aguardando início de produção.";
            EnviarWapp($v->Ltelefone,$mensagem);

        }

        $q = "update vendas set
                                    pagamento = 'pix',
                                    pix_detalhes = '".(($retorno)?:'{}')."',
                                    delivery = 'mottu',
                                    delivery_detalhes = '".(($retorno1)?:'{}')."',
                                    situacao = '".SituacaoPIX($dados->status)."'
                            where codigo = '{$v->codigo}'
                    ";
        
        mysqli_query($con, $q);

    }