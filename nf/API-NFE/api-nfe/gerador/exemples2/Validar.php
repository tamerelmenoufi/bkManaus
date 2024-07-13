<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

date_default_timezone_set('America/Sao_Paulo');

require_once '../bootstrap.php';

use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;

include('./config.php'); // carrega configuracoes

$chave = $_GET['chave']; // pega chave
$xml = file_get_contents("./xml/assinadas/".$chave.".xml"); // Ambiente Linux, pega o xml de apos ser gerado

try {
    $tools = new Tools($configJson, Certificate::readPfx($content, $senhacert));
	$response = $tools->sefazValidate($xml);
	
	$stdCl = new Standardize($response);
    //nesse caso $std irá conter uma representação em stdClass do XML
    //$std = $stdCl->toStd();
    //nesse caso o $arr irá conter uma representação em array do XML
    //$arr = $stdCl->toArray();
    //nesse caso o $json irá conter uma representação em JSON do XML
    $json = $stdCl->toJson();
	
	echo $json;
		
} catch (\Exception $e) {
    //aqui você trata possiveis exceptions
    echo $e->getMessage();
} 
