<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\Common\Soap\SoapCurl;

include("config.php"); // carrega configuracoe

$tools = new Tools($configJson, Certificate::readPfx($content, $senhacert));


//Somente para modelo 55, o modelo 65 evidentemente não possue 
//esse tipo de serviço
$tools->model('55');

//coloque a UF e escolha entre 
//CNPJ
//IE
//CPF
//pelo menos um dos três deverá ser indicado
//essa busca não funciona se não houver a disponibilidade do serviço na SEFAZ
$uf = 'RN';
$cnpj = '19650485000197';
$iest = '';
$cpf = '';
$response = $tools->sefazCadastro($uf, $cnpj, $iest, $cpf);

header('Content-type: text/xml; charset=UTF-8');
echo $response;

