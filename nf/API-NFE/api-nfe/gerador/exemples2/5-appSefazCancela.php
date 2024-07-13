<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Common\Complements;


require_once './config.php'; // carrega configuracoes


try {
    $tools = new Tools($configJson, Certificate::readPfx($content, $senhacert));
    $tools->model('55');
    
    $chave = $_GET['chave'];
    $xJust = 'Desistencia do comprador no momento da retirada';
    $nProt = '135170001136479';
    $response = $tools->sefazCancela($chave, $xJust, $nProt);
    
    //você pode padronizar os dados de retorno atraves da classe abaixo
    //de forma a facilitar a extração dos dados do XML
    //NOTA: mas lembre-se que esse XML muitas vezes será necessário, 
    //      quando houver a necessidade de protocolos
    $stdCl = new Standardize($response);
    //nesse caso $std irá conter uma representação em stdClass do XML retornado
    $std = $stdCl->toStd();
    //nesse caso o $arr irá conter uma representação em array do XML retornado
    $arr = $stdCl->toArray();
    //nesse caso o $json irá conter uma representação em JSON do XML retornado
    $json = $stdCl->toJson();
    
	echo $json;
    //verifique se o evento foi processado
    if ($std->cStat != 128) {
        //houve alguma falha e o evento não foi processado
        //TRATAR
    } else {
        $cStat = $std->retEvento->infEvento->cStat;
        if ($cStat == '101' || $cStat == '135' || $cStat == '155' ) {
            //SUCESSO PROTOCOLAR A SOLICITAÇÂO ANTES DE GUARDAR
            $xml = Complements::toAuthorize($tools->lastRequest, $response);
			
			echo $xml;
            //grave o XML protocolado e prossiga com outras tarefas de seu aplicativo
        } else {
            //houve alguma falha no evento 
            //TRATAR
        }
    }    
} catch (\Exception $e) {
    echo $e->getMessage();
    //TRATAR
}