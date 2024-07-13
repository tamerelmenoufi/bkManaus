<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

date_default_timezone_set('America/Sao_Paulo');

require_once '../bootstrap.php';

use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;

include('./config.php'); // carrega configuracoes

   $chave = $_GET['chave'];
   $ambiente = 2;
   $mod = 65;
	

    $certificate = Certificate::readPfx($content, $senhacert);
    $tools = new Tools($configJson, $certificate);
    $tools->model('65');

    $response = $tools->sefazConsultaChave($chave, 2);
	//$response = $tools->sefazConsultaRecibo($chave, 2)
	
	$stdCl = new Standardize($response);
	 $std = $stdCl->toStd();
	
	if($std->cStat==100){ // tudo ok
	

try {
	
	$xml = file_get_contents("./xml/assinadas/".$chave.".xml"); // Ambiente Linux, pega o xml de apos ser gerado
	
    $resposta = Complements::toAuthorize($xml, $response);
	
	$resposta2 = Complements::addNFeProtocol($resposta, $response);
    
	if($arr['cStat']==100){ // autorizada
	
	$filename = "./xml/autorizadas/".$chave.".xml"; // apos assinar salva arquivo
	file_put_contents($filename, trim($resposta2)); // salva xml assinado
	chmod($filename, 0777);
	
	}
	
	echo "<a href='../../nfe-impressao/gerador/appNFC.php?chave=".$chave."'>Imprimir</a>";
    
	//echo $resposta;
	
	
} catch (\Exception $e) {
    echo "Erro: " . $e->getMessage();
}

}else{
	// tratar error
	print_r($std);
}
