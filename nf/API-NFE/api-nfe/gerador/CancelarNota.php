<?php
//error_reporting(E_ALL);
ini_set('display_errors', 'Off');

require_once '../bootstrap.php';
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;
use NFePHP\NFe\Convert;

require_once './config.php'; // carrega configuracoes

header('Content-type: text/json');

$chave = $_REQUEST['chave'];
$motivo = $_REQUEST['motivo'];

try {

    $filename_au = "./xml/autorizadas/".$chave.".xml"; // apos assinar salva arquivo
    $myXMLData = file_get_contents($filename_au);
    $xml=simplexml_load_string($myXMLData) or die(json_encode(array("error" => 'Nota não encontrada.')));

    $cnpj = $xml->infNFe->emit->CNPJ;
	  $modelo = (int) $xml->infNFe->ide->mod;

   	$tools = new Tools($configJson, Certificate::readPfx($content, $senhacert));
	  $tools->model($modelo);

        $xJust = ($motivo)? $motivo : 'Desistencia do comprador';
        $nProt = strval($xml->protNFe->infProt->nProt);
	
          // CANCELA A NOTA FISCAL
          $response = $tools->sefazCancela($chave, $xJust, $nProt);
    
              $stdCl = new Standardize($response);
              $std = $stdCl->toStd(); 
			
            $cStat = $std->retEvento->infEvento->cStat;
            $xMotivo = $std->retEvento->infEvento->xMotivo;
			
            if($cStat == 101 || $cStat == 135 || $cStat == 155){
					
             // $xml = Complements::toAuthorize(, $response);
              $xml = Complements::cancelRegister($myXMLData, $response);

              $filename = "./xml/canceladas/".$chave.".xml"; 

              file_put_contents($filename, trim($xml)); // Carrega o xml assinado.
              chmod($filename, 0777);
                
            /*
            Neste ponto, já esta cancelada a nota.
            Examplo de retorno para o seu erp
            
            */
			       $data = array();
              $data['status']  = "cancelado";
              $data['ID']  = $_REQUEST['ID'];
              $data['nfe']  = strval($_REQUEST['nfe']);
              $data['chave']  = strval($chave);
              $data['xml']  = "/api-nfe/gerador/xml/canceladas/".$chave.".xml";

              echo json_encode($data);
              //exit; 
          
        } else {
       
				    echo json_encode(array("error" => $std->xMotivo." (".$std->cStat."), ". $xMotivo." (".$cStat.")"));
        		die;
					
				}
   
} catch (\Exception $e) {
   
    echo json_encode(array("error" => $e->getMessage()));
    die;
}
