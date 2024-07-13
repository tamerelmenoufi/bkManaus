<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

date_default_timezone_set('America/Sao_Paulo');

require_once '../bootstrap.php';

use NFePHP\NFe\Tools;
use NFePHP\NFe\Common\Standardize;
use NFePHP\Common\Certificate;

include('./config.php'); // carrega configuracoes

$chave = $_GET['chave']; // pega chave

$xml = file_get_contents("./xml/entradas/".$chave.".xml"); // Ambiente Linux, pega o xml de apos ser gerado

//echo "<a href='./xml/assinadas/".$chave.".xml' target='_blank'>Abrir</a><br>";
try {
	
    $tools = new Tools($configJson, Certificate::readPfx($content, $senhacert));
	//$tools->model('65');
    $response = $tools->signNFe($xml);
   
	$stdCl = new Standardize($response);
	$arr = $stdCl->toArray();
	

	$filename = "./xml/assinadas/".$chave.".xml"; // apos assinar salva arquivo
	file_put_contents($filename, trim($response)); // salva xml assinado
	chmod($filename, 0777);
	
	//echo $chave." - Assinado<BR>"; // poderia ir para outra página impprimir exemplo 
	
	header("location: 3-appEnviar.php?chave=".$chave);	
		
   
} catch (\Exception $e) {
    //aqui você trata possiveis exceptions
    echo $e->getMessage();
} 
