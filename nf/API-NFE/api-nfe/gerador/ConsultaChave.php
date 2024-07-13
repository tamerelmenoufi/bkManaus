<?php
//error_reporting(E_ALL);
ini_set('display_errors', 'Off');

require_once '../bootstrap.php';

use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;

include('./config.php'); // carrega configuracoes

$chave = $_GET['chave'];

try {

    $filename_au = "./xml/entrada/".$chave.".xml";
    $myXMLData = file_get_contents($filename_au);
    $xml=simplexml_load_string($myXMLData) or die(json_encode(array("error" => 'Nota não encontrada.')));

    $cnpj = $xml->infNFe->emit->CNPJ;
	$modelo = $xml->infNFe->ide->mod;
    $ambiente = $xml->infNFe->ide->tpAmb;

    $tools = new Tools($configJson, Certificate::readPfx($content, $senhacert));
    $tools->model($modelo);

    $response = $tools->sefazConsultaChave($chave, $ambiente);

    //você pode padronizar os dados de retorno atraves da classe abaixo
    //de forma a facilitar a extração dos dados do XML
    //NOTA: mas lembre-se que esse XML muitas vezes será necessário, 
    //      quando houver a necessidade de protocolos
    $stdCl = new Standardize($response);
    //nesse caso $std irá conter uma representação em stdClass do XML
    $std = $stdCl->toStd();
    //nesse caso o $arr irá conter uma representação em array do XML
    $arr = $stdCl->toArray();
    //nesse caso o $json irá conter uma representação em JSON do XML
    $json = $stdCl->toJson();

	echo json_encode($arr);
    die;
	
} catch (\Exception $e) {
    die(json_encode(array("error" => $e->getMessage())));
}