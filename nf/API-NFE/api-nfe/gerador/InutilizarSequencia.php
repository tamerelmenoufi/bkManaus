<?php
//error_reporting(E_ALL);
ini_set('display_errors', 'Off');

require_once '../bootstrap.php';
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;

require_once './config.php'; // carrega configuracoes

header('Content-type: text/json');

$nSerie = $_REQUEST['serie'];
$nIni = $_REQUEST['inicial'];
$nFin = $_REQUEST['final'];
$xJust = ($_REQUEST['motivo']=="") ? $_REQUEST['motivo'] : 'Erro de digitação dos numeros sequencias das notas';

try {

    $response = $tools->sefazInutiliza($nSerie, $nIni, $nFin, $xJust);
    $stdCl = new Standardize($response);
    $arr = $stdCl->toArray();

    echo json_encode(array("status" => true, "info" => $arr));
    die;

} catch (\Exception $e) {
   
    echo json_encode(array("status" => false, "error" => $e->getMessage()));
    die;

}
