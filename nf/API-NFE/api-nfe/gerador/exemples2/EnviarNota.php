<?
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use NFePHP\NFe\Convert;
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;

require_once './config.php'; // carrega configuracoes

$chave = $_GET['chave']; // pega chave
$xml = file_get_contents("./xml/assinadas/".$chave.".xml"); // Ambiente Linux, pega o xml de apos ser gerado

try {
	
    //$content = conteúdo do certificado PFX
  $tools = new Tools($configJson, Certificate::readPfx($content, $senhacert));
  $tools->model('65');
  
  $idLote = substr(str_replace(',', '', number_format(microtime(true)*1000000, 0)), 0, 15);
    //envia o xml para pedir autorização ao SEFAZ

    $resp = $tools->sefazEnviaLote([$xml], $idLote);
    //transforma o xml de retorno em um stdClass
	

	$stdCl = new Standardize($resp);
    $arr = $stdCl->toArray();
 
 if ($arr['cStat'] == 103) {
	  //print_r($arr);
	  // recibo header("location: 4-appProtocolo.php?chave=".$arr['infRec']['nRec']);	
	   header("location: 4-appProtocolo.php?chave=".$chave);	
	  
    }else{
		print_r($arr);
	  //erro registrar e voltar
        //echo $arr['cStat']." - ".$arr['xMotivo'];
	
	}
	
} catch (\Exception $e) {
	
    echo $e->getMessage();
}
?>