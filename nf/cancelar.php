<?php

include("conn.php");


$venda_id = $_GET["id"];
$acc = $_GET["acc"];

// Seleciona os dados da venda
$stmt = $PDO->prepare('"SELECT * FROM vendas WHERE ID = ?');
$stmt->execute($venda_id);
$rowVenda = $stmt->fetch(PDO::FETCH_ASSOC);

// Seleciona os dados de configuração
$stmt = $PDO->prepare('SELECT * FROM configuracao WHERE id = ?');
$stmt->execute(1);
$rowconfig = $stmt->fetch(PDO::FETCH_ASSOC);
if($rowconfig["dados"]!=""){
    $settings = json_decode($rowconfig["dados"]);
}else{
    $settings = (object) array();
}

if($rowVenda["NF_status"]!="aprovado"){
    echo "<h1>Esta nota não pode ser cancelada</h1>";
    die;
}


// envio do cancelamento
if($_POST["ID"]==$venda_id){

    $data_nfe = $_POST;
    $data_nfe['empresa'] = array(
        "tpAmb" => $settings->tpAmb, // AMBIENTE: 1 - PRODUÇÃO / 2 - HOMOLOGACAO
        "razaosocial" => $settings->razaosocial, // RAZA0 SOCIAL DA EMPRESA
        "cnpj" => limpardados($settings->vat_no), // CNPJ DA EMPRESA
        "fantasia" => $settings->fantasia, // NOME FANTASIA
        "ie" => limpardados($settings->ie), // INSCRICAO ESTADUAL
        "im" => limpardados($settings->im), // INSCRICAO MUNICIPAL (não obrigatório)
        "cnae" => limpardados($settings->cnae), // CNAE EMPRESA obrigatorio
        "crt" => "1", // CRT
        "rua" => $settings->address, // obrigatorio
        "numero" => $settings->numero, // obrigatorio
        "bairro" => $settings->bairro, // obrigatorio
        "cidade" => $settings->nomeMunicipio, // NOME DA CIDADE
        "ccidade" => limpardados($settings->ccidade), // CODIGO DA CIDADE IBGE, buscar no google
        "cep" => limpardados($settings->postal_code),  // obrigatorio
        "siglaUF" => $settings->estado, // SIGLA DO ESTADO
        "codigoUF" => getCodigoEstado($settings->estado), // CODIGO DO ESTADO
        "fone" => limpardados($settings->phone_number),
        "tokenIBPT" => $settings->tokenIBPT, // GERAR TOKEN NO https://deolhonoimposto.ibpt.org.br/
        "CSC" => $settings->CSC,  // obrigatorio para NFC-e
        "CSCid" => limpardados($settings->CSCid), // EXEMPLO 000001 // obrigatorio para NFC-e
        "certificado_nome" => $settings->certificado, // NOME DO ARQUIVOS DO CERTIFICADO, IRÁ BUCAR NA PASTA api-nfe/certificado_digital
        "certificado_content" => file_get_contents("../certificado_digital/".$settings->certificado), // NOME DO ARQUIVOS DO CERTIFICADO, IRÁ BUCAR NA PASTA api-nfe/certificado_digital
        "certificado_senha" => $settings->certificado_senha
    );

    $fields_string = http_build_query($data_nfe);


    // Envio POST 
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $endpoint."gerador/CancelarNota.php");
    curl_setopt($ch,CURLOPT_POST, count($data_nfe, COUNT_RECURSIVE));
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //curl_setopt($ch, CURLOPT_FAILONERROR, true); // Required for HTTP error codes to be reported via our call to curl_error($ch)
    $response_server = curl_exec($ch);
    $response = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response_server));
    if (curl_errno($ch)) {
        var_dump(curl_error($ch));
    }
    curl_close($ch);

    if (isset($response->error)){
		
        echo '<h2>Erro: '.$response->error.'</h2>';
        die;

    }elseif(!$response){

        echo '<h2>Erro no servidor ao emitir</h2>';
        var_dump($response_server);
        die;

    }else{

        $venda_id_post = (string) $response->ID; 
        $status = (string) $response->status; // aprovado, reprovado, cancelado, processamento ou contingencia
        $chave = $response->chave; // numero da chave de acesso
        $xml = (string) $response->xml; // URL do XML

        sqlsrv_query($conn,"UPDATE vendas SET 
        NF_status='$status',
        NF_xml='$xml'
        WHERE ID='$venda_id_post'");
    }

    die("cancelamento");

}


?>
<body style="margin: 25px;background: #FFF;">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="./jquery.mask.min.js"></script>
<section class="content">
    <div class="row">
      <?php if(!empty($sucess)){ ?>
        <div class="col-xs-12" style="background: #4caf50; color: #FFF; width: 100%; padding: 10px; margin: 10px 0px;">
            <?=$sucess;?>
        </div>
        <?php } ?>
        <?php if(!empty($err)){ ?>
        <div class="col-xs-12"  style="background: red; color: #FFF; width: 100%; padding: 10px; margin: 10px 0px;">
            <?php foreach($err as $e){
                echo $e."<br>";
            }?>
        </div>
        <?php } ?>

        <div class="col-xs-12">
			<h3>Cancelar NFC-e #<?=$rowVenda['NF_numero'];?></h3>
            <div class="box box-primary">
                <div class="box-body mt-3">
                    <div class="row">
                        <div class="col-12">
                        <?php

                            function limpardados($txt){
                                return preg_replace("/[^0-9]/", "", $txt);
                            }

                            function getCodigoEstado($uf){

                                if($uf=="") return;
                            
                                $estados = array(
                                    35 => 'SP',
                                    41 => 'PR',
                                    42 => 'SC',
                                    43 => 'RS',
                                    50 => 'MS',
                                    11 => 'RO',
                                    12 => 'AC',
                                    13 => 'AM',
                                    14 => 'RR',
                                    15 => 'PA',
                                    16 => 'AP',
                                    17 => 'TO',
                                    21 => 'MA',
                                    24 => 'RN',
                                    25 => 'PB',
                                    26 => 'PE',
                                    27 => 'AL',
                                    28 => 'SE',
                                    29 => 'BA',
                                    31 => 'MG',
                                    33 => 'RJ',
                                    51 => 'MT',
                                    52 => 'GO',
                                    53 => 'DF',
                                    22 => 'PI',
                                    23 => 'CE',
                                    32 => 'ES',
                                    );
                            
                                    $code = array_search(strtoupper($uf), $estados);
                                    return $code;
                            }

                            echo '<script>function Clique(){  document.getElementById("btn1").disabled = true;  document.getElementById("btn1").innerHTML = "Enviando... Aguarde..."; return true; }</script>';
                    
                            echo "<form method='POST' onsubmit='return Clique();' action='./cancelar.php?id=$venda_id&nocache=".date("ymsddhiss")."' style='margin:0px 10px;'>
                            Selecione o motivo do cancelamento:<br>
                            <select name='motivo' class='form-control'>
                            <option value='ERRO PREENCIMENTO DADOS'>ERRO PREENCIMENTO DADOS</option>
                            <option value='REFAZER COM PRECO MENOR'>REFAZER COM PRECO MENOR</option>
                            <option value='ERRO IMPRESSAO'>ERRO IMPRESSAO</option>
                            <option value='PEDIDO DE TESTE'>PEDIDO DE TESTE</option>
                            <option value='CLIENTE CANCELOU A COMPRA'>CLIENTE CANCELOU A COMPRA</option>
                            </select><br><br>
                            <button id='btn1' name='submit' type='submit' class='form-control btn btn-primary' value='Cancelar Nota'>Cancelar Nota</button>
                            <input name='ID' type='hidden' value='".$venda_id."'>
                            <input name='nfe' type='hidden' value='".$rowVenda['NF_numero']."'>
                            <input name='chave' type='hidden' value='".$rowVenda['NF_chave']."'>
                            <input name='endpoint' type='hidden' value='". $endpoint ."'>
                            <input name='nocache' type='hidden' value='".date("ymsddhissss")."'>";
                            echo "</form>";
                        ?>
                     </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
                                           