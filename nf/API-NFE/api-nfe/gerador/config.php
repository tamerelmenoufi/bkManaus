<?php

// TIME ZONE - http://php.net/manual/en/timezones.america.php
date_default_timezone_set('America/Sao_Paulo');

$urlxml = $_REQUEST["xml"]; // ex: 'https://{muda isso aqui}seusite.com/api/gerador/xml/autorizadas/'
$urldanfe = $_REQUEST["danfe"];// URL PARA GERADOR DANGE

// DADOS DO EMITENTE DA NOTA FISCAL, OU SEJA A EMPRESA QUE VAI EMITIR

$dadosempresa = [
    "atualizacao" => "2021-01-01 18:01:21",
    "tpAmb" => (int) $_REQUEST["empresa"]["tpAmb"], // AMBIENTE 1 - PRODUÇÃO / 2 - HOMOLOGACAO
    "razaosocial" => $_REQUEST["empresa"]["razaosocial"], // RAZA0 SOCIAL DA EMPRESA
    "cnpj" => $_REQUEST["empresa"]["cnpj"], // CNPJ DA EMPRESA
	"fantasia" => $_REQUEST["empresa"]["fantasia"], // NOME FANTASIA
	"ie" => $_REQUEST["empresa"]["ie"], // INSCRICAO ESTADUAL
	"im" => $_REQUEST["empresa"]["im"], // INSCRICAO MUNICIPAL
	"cnae" => $_REQUEST["empresa"]["cnae"], // obrigatorio
	"crt" => $_REQUEST["empresa"]["crt"], 
	"rua" => $_REQUEST["empresa"]["rua"], // obrigatorio
	"numero" => $_REQUEST["empresa"]["numero"], // obrigatorio
	"bairro" => $_REQUEST["empresa"]["bairro"], // obrigatorio
    "cidade" => $_REQUEST["empresa"]["cidade"], // NOME DA CIDADE
	"ccidade" => $_REQUEST["empresa"]["ccidade"], // CODIGO DA CIDADE IBGE, buscar no google
	"cep" => $_REQUEST["empresa"]["cep"],  // obrigatorio
	"siglaUF" => $_REQUEST["empresa"]["siglaUF"], // SIGLA DO ESTADO
	"codigoUF" => $_REQUEST["empresa"]["codigoUF"], // CODIGO DO ESTADO
	"fone" => $_REQUEST["empresa"]["fone"],
    "tokenIBPT" => $_REQUEST["empresa"]["tokenIBPT"], // GERAR TOKEN NO https://deolhonoimposto.ibpt.org.br/
    "CSC" => $_REQUEST["empresa"]["CSC"],  // obrigatorio para NFC-e
    "CSCid" => $_REQUEST["empresa"]["CSCid"], // EXEMPLO 000001 // obrigatorio para NFC-e
    "schemes" => "PL_009_V4",
	"versao" => '4.00',
    "proxyConf" => [
        "proxyIp" => "",
        "proxyPort" => "",
        "proxyUser" => "",
        "proxyPass" => ""
    ]   
];

//monta o config.json
$configJson = json_encode($dadosempresa);

//carrega o conteudo do certificado.
$content = file_get_contents('../certificado_digital/'.$_REQUEST["empresa"]["certificado_nome"]); // ENDEREÇO PARA O CERTIFICADO DIGITAL
$senhacert = $_REQUEST["empresa"]["certificado_senha"]; // SENHA DO CERTIFICADO