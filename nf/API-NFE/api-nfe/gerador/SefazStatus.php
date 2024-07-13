<?php
//error_reporting(E_ALL);
ini_set('display_errors', 'Off');

require_once '../bootstrap.php';
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\Common\Soap\SoapCurl;
use NFePHP\NFe\Common\Standardize;

require_once './config.php'; // carrega configuracoes

if($_GET['modelo']) { $modelo = strval($_GET['modelo']); }else { $modelo = "55"; }
if($_GET['ambiente']) { $ambiente = (int)($_GET['ambiente']); }else { $ambiente = 2; } // 2 - HOMOLOGAÇÃO 

	
$tools = new Tools($configJson, Certificate::readPfx($content, $senhacert));
//seta o modelo para 55
$tools->model($modelo);

//sempre que ativar a contingência pela primeira vez essa informação deverá ser 
//gravada na base de dados ou em um arquivo para uso posterior, até que a mesma seja 
//desativada pelo usuário, essa informação não é persistida automaticamente e depende 
//de ser gravada pelo ERP

//NOTA: esse retorno da função é um JSON

//$contingencia = $tools->contingeny->activate('SP', 'Teste apenas');
//e se necessário carregada novamente quando a classe for instanciada,
//obtendo a string da contingência em json e passando para a classe


//$tools->contingency->load($contingencia);

//Se não for passada a sigla do estado, o status será obtido com o modo de
//contingência, se este estiver ativo ou seja SVCRS ou SVCAN, usando a sigla 
//contida no config.json
//$response = $tools->sefazStatus();
//Se for passada a sigla do estado, o status será buscado diretamente 
//no autorizador indcado pela sigla do estado, dessa forma ignorando
//a contingência

$estado = strval($dadosempresa["siglaUF"]);
$response = $tools->sefazStatus($estado, $ambiente);

$stdCl = new Standardize($response);
    //nesse caso $std irá conter uma representação em stdClass do XML
    //$std = $stdCl->toStd();
    //nesse caso o $arr irá conter uma representação em array do XML
    //$arr = $stdCl->toArray();
    //nesse caso o $json irá conter uma representação em JSON do XML
    $json = $stdCl->toJson();
	
	echo $json;
	
//header('Content-type: text/xml; charset=UTF-8');
//echo $response;