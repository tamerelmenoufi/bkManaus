<?php

$teste = true;

if($teste == true){
	error_reporting(E_ALL);
	ini_set('display_errors', 'On');
}else{
	ini_set('display_errors', 'Off');
}

require_once '../bootstrap.php';

use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\Common\Soap\SoapCurl;
use NFePHP\NFe\Make;
use NFePHP\NFe\Exception\DocumentsException;
use NFePHP\NFe\Common\Standardize;
use NFePHP\NFe\Complements;
use NFePHP\NFe\Convert;

include('./config.php');
include('./funcoes.php'); // carrega configuracoes

$nfe = new Make();
$std = new stdClass();
$error = array();

$urlxml = $data_nfe['xml'];
$urldanfe = $data_nfe['danfe'];

$numero_nf = $_REQUEST["NF"]; // NUMERO DA NNF

$versaonota = strval($dadosempresa["versao"]); // VERSAO DA NOTA
$std->versao = $versaonota; 
$std->Id = ''; 
$std->pk_nItem = null; 
$elem = $nfe->taginfNFe($std);

/**      DADOS DA NOTA        **/                     
$std->cUF = $dadosempresa["codigoUF"]; // CODIGO UF
$std->cNF = rand(1000,10000); // CDIGO DA NF
$std->natOp = ($_REQUEST['natureza_operacao'])? strval(RmvString($_REQUEST['natureza_operacao'])) : $error[] = "Natureza da operao invlida";


// MODELO: 65 NFC / 55 NF
$modelonf = $_REQUEST['modelo']; 
$impressao = $_REQUEST['impressao']; 
$serie = 1;

$std->mod = strval($modelonf); // MODELO NOTA
$std->serie = $serie ; // NUMERO DE SERIE
$std->nNF = ($numero_nf)? $numero_nf : 1; // NMERO NA NF (TEM QUE SEGUIR UMA ORDEM EXATA)
$std->dhEmi = date("c"); // data da emissao
$std->dhSaiEnt = ($_REQUEST['data_entrada_saida'])? str_replace(' ', 'T', trim($_REQUEST['data_entrada_saida'])).date("P") : null; // DATA ENTRADA/SAIDA YYYY-MM-DD HH:MM:SS *opc
$std->tpNF = (int)(RmvString($_REQUEST['operacao'])); // TIPO OPERACAO: 1 - Sada 0 - Entrada
$std->idDest = ($_REQUEST["destinatario"]) ? $_REQUEST["destinatario"] : 1; // 1 = Operao interna; 2 = Operao interestadual; 3 = Operao com exterior. 
$std->cMunFG = $dadosempresa["ccidade"];
$std->tpImp = $impressao;
/* 
TIPO DE IMPRESSAO
Para NF-e (modelo 55),  permitido utilizar os Formatos de Impresso do DANFE abaixo:
0 = Sem gerao de DANFE;
1 = DANFE normal, Retrato;
2 = DANFE normal, Paisagem;
3 = DANFE Simplificado;
4 = DANFE NFC-e;
5 = DANFE NFC-e em mensagem eletrnica (o envio de mensagem eletrnica pode ser feita de forma simultanea com a impresso do DANFE; usar o tpImp=5 quando esta for a unica forma de disponibilizao do DANFE)
*/

$std->tpEmis = RmvString($_REQUEST['emissao']); 
/*
1 - Normal - emisso normal; 
2 - Contingncia FS - emisso em contingncia com impresso do DANFE em Formulrio de Segurana; 
3 - Contingncia SCAN - emisso em contingncia no Sistema de Contingncia do Ambiente Nacional ? SCAN; 
4 - Contingncia DPEC - emisso em contingncia com envio da Declarao Prvia de Emisso em Contingncia ? DPEC; 
9 - Contingncia off-line da NFC-e;
5 - Contingncia FS-DA - emisso em contingncia com impresso do DANFE em Formulrio de Segurana para Impresso de Documento Auxiliar de Documento Fiscal Eletrnico (FS-DA).
*/

$std->cDV = 3;
$ambiente = $dadosempresa['tpAmb']; // 1=Produo; 2=Homologao OU PODE USAR VIA REQUEST: $_REQUEST['tpAmb'];
$std->tpAmb = $ambiente; 
$std->finNFe = RmvString($_REQUEST['finalidade']); // 1=NF-e normal; 2=NF-e complementar; 3=NF-e de ajuste; 4=Devoluo/Retorno. 
// $std->indFinal = ($_REQUEST['cliente']['cnpj']!="")? 0 : 1; // 0 = No; 1 = Consumidor final;
$std->indFinal = 1;
$std->indPres = RmvString($_REQUEST['pedido']['presenca']);
/*
0=No se aplica (por exemplo, Nota Fiscal complementar
ou de ajuste);
1=Operao presencial;
2=Operao no presencial, pela Internet;
3=Operao no presencial, Teleatendimento;
4=NFC-e em operao com entrega a domiclio;
9=Operao no presencial, outros. 
*/
$std->indIntermed = ($_REQUEST['pedido']['intermediario']!="")? RmvString($_REQUEST['pedido']['intermediario']) : null; // INTERMEDIARIO
$std->procEmi = '0'; // 0 = Emisso de NF-e com aplicativo do contribuinte; 
$std->verProc = "TudoNet1";
$std->dhCont = null; // Data e Hora da entrada em contingencia
$std->xJust = null; // Justificativa da entrada em contingencia
$elem = $nfe->tagide($std);


/**     REFERENCIA A NOTA                        **/  
if($_REQUEST['nfe_referenciada']){
$std->refNFe = strval($_REQUEST['nfe_referenciada']);
$elem = $nfe->tagrefNFe($std);
}


/**    DADOS DO EMITENTE DA NOTA  **/                     
$std->xNome = $dadosempresa["razaosocial"];
$std->xFant= $dadosempresa["fantasia"];
$std->IE = $dadosempresa["ie"];
$std->IEST = "";
$std->IM = $dadosempresa["im"]; // prestador de servio
$std->CNAE = $dadosempresa["cnae"];
$std->CRT = $dadosempresa["crt"];
$std->CNPJ = $dadosempresa["cnpj"]; //indicar apenas um CNPJ ou CPF
//$std->CPF;
$elem = $nfe->tagemit($std);

/**     ENDEREO DO EMITENTE DA NOTA			**/                     
$std->xLgr =  $dadosempresa["rua"];
$std->nro =  $dadosempresa["numero"];
$std->xCpl = $dadosempresa["compl"];
$std->xBairro =  $dadosempresa["bairro"];
$std->cMun =  $dadosempresa["ccidade"];
$std->xMun =  $dadosempresa["cidade"];
$std->UF =  $dadosempresa["siglaUF"];
$std->CEP =  $dadosempresa["cep"];
$std->cPais = "1058";
$std->xPais = "BRASIL";
$std->fone =  $dadosempresa["fone"];
$elem = $nfe->tagenderEmit($std);


/**  NF OBRIGATORIO --- DADOS DO COMPRADOR // para nfc nao e obrigatorio os dados do comprador **/     
if($_REQUEST['cliente']['email']){
	$std->email = strval(RmvString($_REQUEST['cliente']['email']));   // E-mail do cliente para envio da NF-e ** OPC
}elseif($_REQUEST['cliente']['email']=="" && $modelonf == 55){
	$std->email = strval($_REQUEST['cliente']['cnpj'].$_REQUEST['cliente']['cpf']."@email.com.br");   // E-mail do cliente para envio da NF-e ** OPC
}

//VALIDAES
if($_REQUEST['cliente']['cnpj']=="" && $_REQUEST['cliente']['cpf']=="" && $_REQUEST['cliente']['id_estrangeiro']=="" && $modelonf == 55){	$error[] = "CPF, CNPJ ou ID estrangeiro obrigatrio para NF"; }


if($_REQUEST['cliente']['cnpj']!=""){ // TOMADOR DA NOTA PESSOA JURIDICA

	$nomem = $_REQUEST['cliente']['razao_social'];
	$std->xNome = $nomem; //Nome completo do cliente
	$std->IE = RmvString($_REQUEST['cliente']['ie'], 2);  // (pessoa jurdica) Nmero da Inscrio Estadual
	//$std->ISUF;
	//substituto_tributario 
	//suframa
	$std->IM = NULL; 

	if(strval($_REQUEST['cliente']['ie']=="isento")){ // Isento Inscricao estadual
	$std->indIEDest = "2";
	}elseif($_REQUEST['cliente']['ie']==""){ // nao informada Inscricao estadual
	$std->indIEDest = "9";
	}else{ // Inscricao estadual normal
	$std->indIEDest = "1"; 
	}
	$std->CPF =""; 
	$std->CNPJ = RmvString($_REQUEST['cliente']['cnpj'], 2); 	// (pessoa jurdica) Nmero do CNPJ  	
	$elem = $nfe->tagdest($std);

}elseif($_REQUEST['cliente']['cpf']!=""){ // TOMADOR DA NOTA PESSOA FISICA

	$nomem = $_REQUEST['cliente']['nome_completo'];
	$std->xNome = $nomem; //Nome completo do cliente 
	$std->IE = NULL; // 
	$std->IM = NULL; 
	$std->indIEDest = "9"; 
	$std->CNPJ = "";
	$std->CPF = RmvString($_REQUEST['cliente']['cpf'], 2); 		 //Pessoa Fsica: Nmero do CPF
	$elem = $nfe->tagdest($std);

}elseif($_REQUEST['cliente']['nome_estrangeiro']!=""){ // TOMADOR DA NOTA ESTRANGEIRO

	$std->idEstrangeiro =  $_REQUEST['cliente']['id_estrangeiro']; // Nmero do passaporte ou outro documento legal para identificar pessoa estrangeira.
	$nomem = $_REQUEST['cliente']['nome_estrangeiro'];
	$std->xNome = $nomem; //Nome completo do cliente 
	$std->IE = NULL; // 
	$std->IM = NULL; 
	$std->indIEDest = NULL; 
	$std->CNPJ = "";
	$std->CPF = ""; 		 //Pessoa Fsica: Nmero do CPF
	$exterior = "sim";
	$elem = $nfe->tagdest($std);

}

 
if($_REQUEST['cliente']['endereco']!=""){

	$std->xLgr = ($_REQUEST['cliente']['endereco'])? $_REQUEST['cliente']['endereco'] : $error[] = "Endereo no foi informado";    // Endereo de entrega dos produtos
	$std->nro = RmvString($_REQUEST['cliente']['numero']);      // Nmero do endereo de entrega
	$std->xCpl = $_REQUEST['cliente']['complemento']; // Complemento do endereo de entrega
	$std->xBairro = $_REQUEST['cliente']['bairro'];      // Bairro do endereo de entrega
	$std->UF =  ($exterior)? "EX" : RmvString($_REQUEST['cliente']['uf']); // Estado do endereo de entrega
	$std->cMun = ($exterior)? "9999999" : RmvString($_REQUEST['cliente']['cidade_cod']); // codigo IBGE municipio
	$std->xMun = ($exterior)? "EXTERIOR" : RmvString($_REQUEST['cliente']['cidade']);  // Cidade do endereo de entrega
	$std->CEP = RmvString($_REQUEST['cliente']['cep'], 1);  // CEP do endereo de entrega
	$std->cPais = ($_REQUEST['cliente']['cod_pais'])? $_REQUEST['cliente']['cod_pais'] : "1058";
	$std->xPais = ($_REQUEST['cliente']['nome_pais'])? $_REQUEST['cliente']['nome_pais'] : "BRASIL";
	$std->fone = RmvString($_REQUEST['cliente']['telefone'], 1);    // Telefone do cliente
	$elem = $nfe->tagenderDest($std);
	
}

$vTotalBC = 0;
$vTotalICMS = 0;
$vTotalvICMSDesonv = 0;
$vTotalvFCP = 0.00; //incluso no layout 4.00
$vTotalvBCST = 0.00;
$vTotalvST = 0.00;
$vTotalvFCPST = 0.00; //incluso no layout 4.00
$vTotalvFCPSTRet = 0.00; //incluso no layout 4.00
$vtotalIPI  = 0.00;
$vtotalPIS  = 0.00;
$vtotalCOFINS = 0.00;
$impostototal = 0.00;
$valortotal = 0.00;
$descontototal = 0.00;
$pesototal = 0.00;
$x = 0;
$y = 0;

/**  PRODUTOS - criar loop para varios   **/ 
/*
CFOP: 
5.101 - Venda de produo do estabelecimento;
5.102 - Venda de mercadoria adquirida ou recebida de terceiros;
5.103 - Venda de produo do estabelecimento, efetuada fora do estabelecimento;
5.104 - Venda de mercadoria adquirida ou recebida de terceiros, efetuada fora do estabelecimento;
5.115 - Venda de mercadoria adquirida ou recebida de terceiros, recebida anteriormente em
consignao mercantil;
5.405 - Venda de mercadoria de terceiros, sujeita a ST, como contribuinte substitudo;
Esse cdigo ser utilizado inclusive nas hipteses em que o varejista, adquirente da
mercadoria em operaes interestaduais,  considerado substituto tributrio, efetuando o
pagamento do imposto devido por substituio tributria na entrada da mercadoria em
territrio fluminense, j que, por ocasio da sada que promove, registrada na NFC-e, atua
como substitudo (art. 4 da Resoluo SEFAZ n 537/12).
5.656 - Venda de combustvel ou lubrificante de terceiros, para consumidor final;
5.667 - Venda de combustvel ou lubrificante a consumidor ou usurio final estabelecido em outra
unidade da Federao;
5.933 - Prestao de servio tributado pelo ISSQN (Nota Fiscal conjugada);
Embora tecnicamente haja possibilidade de incluso de servios tributados pelos municpios
(ISS) na NFC-e, a sua utilizao depende de convnio firmado entre o Estado e o municpio.
Atualmente, no h nenhum convnio.
*/

foreach($_REQUEST['produtos'] as $prod){
	$y++;
	}
	
	if($_REQUEST['pedido']['desconto']!="" && $_REQUEST['pedido']['desconto']>0){	
		$descontototal = $_REQUEST['pedido']['desconto'];
	
		$descontoPorItem = number_format(($descontototal / $y), 2, '.', '');
		$descontoTotalCalculado = ($descontoPorItem * $y);
		$descontoItemFinal = number_format(($descontototal - $descontoTotalCalculado ), 2, '.', '');
	}else{
		$descontoPorItem = null;
		$descontoItemFinal = null;
	}
	
	if($_REQUEST['pedido']['outras_despesas']!="" && $_REQUEST['pedido']['outras_despesas']>0){	
		$outrostotal = $_REQUEST['pedido']['outras_despesas'];
	
		$outrosPorItem = number_format(($outrostotal / $y), 2, '.', '');
		$outrosTotalCalculado = ($outrosPorItem * $y);
		$outrosItemFinal = number_format(($outrostotal - $outrosTotalCalculado ), 2, '.', '');
	}else{
		$outrosPorItem = null;
		$outrosItemFinal = null;
	}
		
	if(RmvString($_REQUEST['pedido']['frete'])!=""  && $_REQUEST['pedido']['frete']>0){	
		$fretetotal = $_REQUEST['pedido']['frete'];	
	
		$fretePorItem = number_format(($fretetotal / $y), 2, '.', '');
		$freteTotalCalculado = ($fretePorItem * $y);
		$freteItemFinal = number_format(($fretetotal - $freteTotalCalculado ), 2, '.', '');
	}else{
		$fretePorItem = null;
		$freteItemFinal = null;
	}
	
	if($_REQUEST['transporte']['seguro']!="" && $_REQUEST['transporte']['seguro']>0){	
		$segurototal = $_REQUEST['transporte']['seguro'];	
	
		$seguroPorItem = number_format(($segurototal / $y), 2, '.', '');
		$seguroTotalCalculado = ($seguroPorItem * $y);
		$seguroItemFinal = number_format(($segurototal - $seguroTotalCalculado ), 2, '.', '');
	}else{
		$seguroPorItem = null;
		$seguroItemFinal = null;
	}
	
	foreach($_REQUEST['produtos'] as $prod){	
	
	$item = $x + 1;
	
	$frete_item = ($item!=$y)? $fretePorItem : ((!empty($fretePorItem)) ? number_format(($fretePorItem + $freteeItemFinal), 2, '.', '') : null);
	$seguro_item = ($item!=$y)? $seguroPorItem : ((!empty($seguroPorItem)) ? number_format(($seguroPorItem + $seguroItemFinal), 2, '.', '') : null);
	$desconto_item = ($item!=$y)? $descontoPorItem : ((!empty($descontoPorItem)) ? number_format(($descontoPorItem + $descontoItemFinal), 2, '.', '') : null);
	$outros_item = ($item!=$y)? $outrosPorItem : ((!empty($outrosPorItem)) ? number_format(($outrosPorItem + $outrosItemFinal), 2, '.', '') : null);
			
	$codigo = RmvString($_REQUEST['produtos'][$x]['item'], 2);		// CODIGO DO PRODUTO
	$nomeproduto = RmvString($_REQUEST['produtos'][$x]['nome']);      // NOME DO PRODUTO
	$ncm = RmvString($_REQUEST['produtos'][$x]['ncm'], 2);            // Cdigo NCM
	$cfop = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["codigo_cfop"])? RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["codigo_cfop"], 2) : '5102'; // CFOP
	$valor = RmvString($_REQUEST['produtos'][$x]['subtotal']);        // Preo unitrio do produto - sem descontos
	$quantidade = RmvString($_REQUEST['produtos'][$x]['quantidade']); // Quantidade de itens
	$un = RmvString($_REQUEST['produtos'][$x]['unidade']);            // Unidade de medida UN - Unidade  KG - Kilograma
	$ean = RmvString($_REQUEST['produtos'][$x]['ean']);               // Cdigo EAN
	$peso = ($_REQUEST['produtos'][$x]['peso'])? RmvString($_REQUEST['produtos'][$x]['peso'], 2) : 0.200;           // Peso em KG. Ex: 800 gramas = 0.800 KG
		
	$std->item = $item; //item da NFe
	$std->cProd = $codigo;
	$std->cEAN = "SEM GTIN";
	$std->xProd = $nomeproduto;
	$std->NCM = $ncm;
	$std->cBenf = ""; //incluido no layout 4.0
	$std->EXTIPI = "";
	$std->CFOP = $cfop;
	$std->uCom = $un; // untario
	$std->qCom = $quantidade; // quantidade
	$std->vUnCom = $valor; // valor unidade
	$std->vProd = $_REQUEST['produtos'][$x]['total']; // valor do produto
	$std->cEANTrib = "SEM GTIN";
	$std->uTrib = $un;
	$std->qTrib = $quantidade; // quantidade tributo
	$std->vUnTrib = $valor;
	$std->vFrete = (!empty($frete_item) && $frete_item>0)? $frete_item : '';
	$std->vSeg = (!empty($seguro_item) && $seguro_item>0)? $seguro_item : '';
	$std->vDesc = (!empty($desconto_item) && $desconto_item>0)? $desconto_item : null; // no acepta 0.00
	$std->vOutro = (!empty($outros_item) && $outros_item>0)? $outros_item : '';
	$std->indTot = 1; // Indica se valor do Item (vProd)entra no valor total da NF-e(vProd) 
	$std->xPed = "";
	$std->nItemPed = ($_REQUEST['pedido']['numero'])? $_REQUEST['pedido']['numero'] : "";
	$std->nFCI = ($_REQUEST['produtos'][$x]['nfci'])? $_REQUEST['produtos'][$x]['nfci'] : ""; // Ficha de Contedo de Importao com formatao, ex.: B01F70AF-10BF-4B1F-848C-65FF57F616FE
	$elem = $nfe->tagprod($std);
	
$valorBC = ($valor * $quantidade);
$vTotalProds += $std->vProd;
	
/* INFORMAES ADICIONAIS DO ITEM */
if($_REQUEST['produtos'][$x]['informacoes_adicionais']){
	$std->item = $item; //item da NFe
	$std->infAdProd = strval($_REQUEST['produtos'][$x]['informacoes_adicionais']);
	$elem = $nfe->taginfAdProd($std);
}


/**   INFORMAES NVE     **/  
if($_REQUEST['produtos'][$x]['nve']){
	$std->item = $item; //item da NFe
	$std->NVE = $_REQUEST['produtos'][$x]['nve'];
	$elem = $nfe->tagNVE($std);
}
	

/**  TAG Importacao  */
if(strval($_REQUEST['produtos'][$x]["ndoc_importacao"])){
	$std->item = $item;
	$std->nDI = strval($_REQUEST['produtos'][$x]["ndoc_importacao"]);
	$std->dDI = strval($_REQUEST['produtos'][$x]["ddoc_importacao"]);
	$std->xLocDesemb = $_REQUEST['produtos'][$x]["local_desembaracoo"];
	$std->UFDesemb = strval($_REQUEST['produtos'][$x]["uf_desembaraco"]);
	$std->dDesemb = strval($_REQUEST['produtos'][$x]["data_desembaraco"]);
	$std->tpViaTransp = strval($_REQUEST['produtos'][$x]["via_transporte"]);
	$std->vAFRMM = ($_REQUEST['produtos'][$x]["afrmm"]) ? strval($_REQUEST['produtos'][$x]["afrmm"]) : null;
	$std->tpIntermedio = strval($_REQUEST['produtos'][$x]["intermediacao"]);
	$std->CNPJ = ($_REQUEST['produtos'][$x]["cnpj_terceiro"]) ? strval($_REQUEST['produtos'][$x]["cnpj_terceiro"]) : null;
	$std->UFTerceiro = ($_REQUEST['produtos'][$x]["uf_terceiro"]) ? strval($_REQUEST['produtos'][$x]["uf_terceiro"]) : null;
	$std->cExportador = ($_REQUEST['produtos'][$x]["cod_exportador"]) ? strval($_REQUEST['produtos'][$x]["cod_exportador"]) : null;
	$elem = $nfe->tagDI($std);
	
	// TAG ADIES
	$std->item = $item;
	$std->nDI = strval($_REQUEST['produtos'][$x]["ndoc_importacao"]);
	$std->nAdicao = strval($_REQUEST['produtos'][$x]["adicao"]);
	$std->nSeqAdic = strval($_REQUEST['produtos'][$x]["seq_adicao"]);
	$std->cFabricante = strval($_REQUEST['produtos'][$x]["fabricante"]);
	$std->vDescDI = null;
	$std->nDraw = null;
	$elem = $nfe->tagadi($std);
}


/**       EXPORTAO   DRAWBACK        **/    
if($_REQUEST['produtos'][$x]["drawback"] && (!$_REQUEST['produtos'][$x]["reg_exportacao"] || !$_REQUEST['produtos'][$x]["nfe_exportacao"])){
	$std->item = $item; //item da NFe
	$std->nRE = null;
	$std->chNFe = null;
	$std->qExport = null;
	$std->nDraw = strval($_REQUEST['produtos'][$x]["drawback"]);
	$elem = $nfe->tagdetExport($std);
	
/**         EXPORTAO INDIRETA       **/     
} elseif($_REQUEST['produtos'][$x]["reg_exportacao"] && $_REQUEST['produtos'][$x]["nfe_exportacao"] && $_REQUEST['produtos'][$x]["drawback"]=""){
	$std->item = $item; //item da NFe
	$std->nRE = strval($_REQUEST['produtos'][$x]["reg_exportacao"]);
	$std->chNFe = strval($_REQUEST['produtos'][$x]["nfe_exportacao"]);
	$std->qExport = strval($_REQUEST['produtos'][$x]["qtd_exportacao"]);
	$elem = $nfe->tagdetExportInd($std);
}
	
	
/**    IMPOSTOS  DO PRODUTO  **/                                                                                    
/**    ICMS  **/                                                                                    
	// p.... significa alicota %
	// bc...
	// v... es valor -> para cacular usamos CalcularPorcent calula o valor baseado na base de calculo e alicota
	$std->item = $item;
	$std->orig = $_REQUEST['produtos'][$x]["impostos"]["icms"]["origem"]; 

	if($_REQUEST['produtos'][$x]["impostos"]["icms"]["situacao_tributaria"]<100){ 

		$std->CST = strval($_REQUEST['produtos'][$x]["impostos"]["icms"]["situacao_tributaria"]);
		$std->modBC = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["modBC"]!="")? strval($_REQUEST['produtos'][$x]["impostos"]["icms"]["modBC"]) : null;
		$std->vBC = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pICMS"]!="")? $valorBC : null;
		$std->pRedBC = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pRedBC"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pRedBC"],3) : null;
		$std->pICMS = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pICMS"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pICMS"],3) : null;
		$std->vICMS = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pICMS"]!="")? CalcularPorcent($valorBC, RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pICMS"],3)) : null;
		$std->pFCP = null;
		$std->vFCP = null;
		$std->vBCFCP = null;
		$std->modBCST = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["modBCST"]!="")? strval($_REQUEST['produtos'][$x]["impostos"]["icms"]["modBCST"]) : null;
		$std->pMVAST =  ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pMVAST"])!=""? RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pMVAST"],3) : null;
		$std->pRedBCST = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pRedBCST"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pRedBCST"],3) : null; // 
		$std->vBCST = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["aliquota_reducao_st"]!="")? $valorBC : null; // ?????
		$std->pICMSST = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pICMSST"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pICMSST"],3) : null;
		$std->vICMSST = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pICMSST"]!="")? CalcularPorcent($valorBC, RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pICMSST"],3)) : null;
		$std->vBCFCPST = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pFCPST"]!="")? $valorBC : null;
		$std->pFCPST = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pFCPST"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pFCPST"],3) : null;
		$std->vFCPST = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pFCPST"]!="")? CalcularPorcent($valorBC, RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pFCPST"],3)) : null;
		$std->vICMSDeson = null;
		$std->motDesICMS = null;
		$std->pRedBC = null;
		$std->vICMSOp = null;
		$std->pDif = null;
		$std->vICMSDif = null;
		$std->vBCSTRet = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["vBCSTRet"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["vBCSTRet"]) : '0.00'; // Base de CÃ¡lculo ICMS Retido na operaÃ§Ã£o anterior
		$std->pST = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pST"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pST"], 3) : '0.00'; // AlÃ­quota suportada pelo Consumidor Final
		$std->vBCFCPSTRet = null;
		$std->pFCPSTRet = null;
		$std->vFCPSTRet = null;
		$std->pRedBCEfet = null;
		$std->vBCEfet = null;
		$std->pICMSEfet = null;
		$std->vICMSEfet = null;
		$std->vICMSSubstituto = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["vICMSSubstituto"]!="")? $_REQUEST['produtos'][$x]["impostos"]["icms"]["vICMSSubstituto"] : '0.00'; // Valor do ICMS prÃ³prio do Substituto
		$std->vICMSSTRet = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["vICMSSTRet"]!="")? $_REQUEST['produtos'][$x]["impostos"]["icms"]["vICMSSTRet"] : '0.00'; // Valor do ICMS ST Retido na operaÃ§Ã£o anterior

	    $elem = $nfe->tagICMS($std);

	}else{ // SIMPLES NACIONAL

		$std->CSOSN = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["situacao_tributaria"]!="")? strval($_REQUEST['produtos'][$x]["impostos"]["icms"]["situacao_tributaria"]) : "102";
		$std->pCredSN = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pCredSN"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pCredSN"],3) : 0; // valor da porcentagem; 
		$std->vCredICMSSN = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pCredSN"]!="")? CalcularPorcent($valorBC, RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pCredSN"],3)) : '0.00';
		$std->modBCST = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["modBCST"]!="")? strval($_REQUEST['produtos'][$x]["impostos"]["icms"]["modBCST"]) : null;
		$std->pMVAST =  ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pMVAST"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pMVAST"],3) : null;
		$std->pRedBCST = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pRedBCST"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pRedBCST"],3) : null; // 
		$std->vBCST = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["aliquota_reducao_st"]!="")? $valorBC : null; // ?????
		$std->pICMSST = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pICMSST"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pICMSST"],3) : null;
		$std->vICMSST = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pICMSST"]!="")? CalcularPorcent($valorBC, RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pICMSST"],3)) : null;
		$std->vBCFCPST = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pFCPST"]!="")? $valorBC : null;
		$std->pFCPST = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pFCPST"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pFCPST"],3) : null;
		$std->vFCPST = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pFCPST"]!="")? CalcularPorcent($valorBC, RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pFCPST"],3)) : null;
		$std->vCredICMSSN = null;
		$std->vBCSTRet = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["vBCSTRet"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["vBCSTRet"]) : null; // Base de CÃ¡lculo ICMS Retido na operaÃ§Ã£o anterior
		$std->pST = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pST"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pST"], 3) : null; // AlÃ­quota suportada pelo Consumidor Final
		$std->vICMSSTRet = null;
		$std->vBCFCPSTRet = null; 
		$std->pFCPSTRet = null;
		$std->vFCPSTRet = null;
		$std->pRedBCEfet = null;
		$std->vBCEfet = null;
		$std->pICMSEfet = null;
		$std->vICMSEfet = null;
		$std->modBC = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["modBC"]!="")? strval($_REQUEST['produtos'][$x]["impostos"]["icms"]["modBC"]) : null;
		$std->vBC = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pICMS"]!="")? $valorBC : null;
		$std->pRedBC = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pRedBC"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pRedBC"],3) : null;
		$std->pICMS = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pICMS"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pICMS"],3) : null;
		$std->vICMS = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["pICMS"]!="")? CalcularPorcent($valorBC, RmvString($_REQUEST['produtos'][$x]["impostos"]["icms"]["pICMS"],3)) : null;
		$std->vICMSSubstituto = ($_REQUEST['produtos'][$x]["impostos"]["icms"]["vICMSSubstituto"]!="")? $_REQUEST['produtos'][$x]["impostos"]["icms"]["vICMSSubstituto"] : '0.00'; // Valor do ICMS prÃ³prio do Substituto
	    
		
		$elem = $nfe->tagICMSSN($std);
	}

	$vTotalICMS += (!empty($std->vICMS)) ? $std->vICMS : 0;

	/*
	 * Node com informaÃ§Ãµes da partilha do ICMS entre a UF de origem e UF de destino ou a UF definida na legislaÃ§Ã£o.
		$std->item = 1; //item da NFe
		$std->orig = 0;
		$std->CST = '90';
		$std->modBC = 0;
		$std->vBC = 1000.00;
		$std->pRedBC = null;
		$std->pICMS = 18.00;
		$std->vICMS = 180.00;
		$std->modBCST = 1000.00;
		$std->pMVAST = 40.00;
		$std->pRedBCST = null;
		$std->vBCST = 1400.00;
		$std->pICMSST = 10.00;
		$std->vICMSST = 140.00;
		$std->pBCOp = 10.00;
		$std->UFST = 'RJ';
		$nfe->tagICMSPart($std);
	 */


	/*
	// Node de informaÃ§Ã£o do ICMS Interestadual do item na NFe
	$std->item = 1; //item da NFe
	$std->vBCUFDest = 100.00;
	$std->vBCFCPUFDest = 100.00;
	$std->pFCPUFDest = 1.00;
	$std->pICMSUFDest = 18.00;
	$std->pICMSInter = 12.00;
	$std->pICMSInterPart = 80.00;
	$std->vFCPUFDest = 1.00;
	$std->vICMSUFDest = 14.44;
	$std->vICMSUFRemet = 3.56;

	$nfe->tagICMSUFDest($std);
	*/

	//$elem = $nfe->tagprod($std);
/* IPI TAG */
if($_REQUEST['produtos'][$x]["impostos"]["ipi"]["situacao_tributaria"]!="" && $_REQUEST['produtos'][$x]["impostos"]["ipi"]["situacao_tributaria"]!="-1"){
	
	$std->item = $item;
	$std->clEnq = null;
	$std->CNPJProd = ($_REQUEST['produtos'][$x]["cnpj_produtor"])? RmvString($_REQUEST['produtos'][$x]["cnpj_produtor"]) : null;
	$std->cSelo = null;
	$std->qSelo = null;
	$std->cEnq = ($_REQUEST['produtos'][$x]["impostos"]["ipi"]["codigo_enquadramento"])? strval($_REQUEST['produtos'][$x]["impostos"]["ipi"]["codigo_enquadramento"]) : "999";
	$std->CST = ($_REQUEST['produtos'][$x]["impostos"]["ipi"]["situacao_tributaria"])? strval($_REQUEST['produtos'][$x]["impostos"]["ipi"]["situacao_tributaria"]) : "99";
	$std->vBC = ($_REQUEST['produtos'][$x]["impostos"]["ipi"]["aliquota"]!="")? $valorBC : 0.00; //  base calculo - ipi
	$std->pIPI = ($_REQUEST['produtos'][$x]["impostos"]["ipi"]["aliquota"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["ipi"]["aliquota"],3) : null;  // aliquota IPI % 
	$std->vIPI = ($_REQUEST['produtos'][$x]["impostos"]["ipi"]["aliquota"]!="")? CalcularPorcent($valorBC, RmvString($_REQUEST['produtos'][$x]["impostos"]["ipi"]["aliquota"],3)) : null;
	$std->qUnid = null; // informar se for por unidade
	$std->vUnid = null; // 
	$elem = $nfe->tagIPI($std);
	
	$vtotalIPI += $std->vIPI; 
}

if($modelonf=="55"){
/* TAG PIS  */
$std->item = $item;
$std->CST = ($_REQUEST['produtos'][$x]["impostos"]["pis"]["situacao_tributaria"]!="")? strval($_REQUEST['produtos'][$x]["impostos"]["pis"]["situacao_tributaria"]) : "99";
$std->vBC = ($_REQUEST['produtos'][$x]["impostos"]["pis"]["aliquota"]!="")? $valorBC : 0.00; //  base calculo - PIS
$std->pPIS = ($_REQUEST['produtos'][$x]["impostos"]["pis"]["aliquota"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["pis"]["aliquota"],3) : null;  // aliquota PIS % 
$std->vPIS = ($_REQUEST['produtos'][$x]["impostos"]["pis"]["aliquota"]!="")? CalcularPorcent($valorBC, RmvString($_REQUEST['produtos'][$x]["impostos"]["pis"]["aliquota"],3)) : 0.00; // VALOR PIS
$std->qBCProd = null;// em valor
$std->vAliqProd = null; // em valor
$elem = $nfe->tagPIS($std);

$vtotalPIS += $std->vPIS; 
	
/*   TAG CONFIS */
$std->item = $item;
$std->CST = ($_REQUEST['produtos'][$x]["impostos"]["cofins"]["situacao_tributaria"]!="")? strval($_REQUEST['produtos'][$x]["impostos"]["cofins"]["situacao_tributaria"]) : "99";
$std->vBC = ($_REQUEST['produtos'][$x]["impostos"]["cofins"]["aliquota"]!="")? $valorBC : 0.00; //  base calculo - CONFIS
$std->pCOFINS = ($_REQUEST['produtos'][$x]["impostos"]["cofins"]["aliquota"]!="")? RmvString($_REQUEST['produtos'][$x]["impostos"]["cofins"]["aliquota"],3) : null;  // aliquota CONFIS % 
$std->vCOFINS = ($_REQUEST['produtos'][$x]["impostos"]["cofins"]["aliquota"]!="")? CalcularPorcent($valorBC, RmvString($_REQUEST['produtos'][$x]["impostos"]["cofins"]["aliquota"],3)) : 0.00; // VALOR CONFIS
$std->qBCProd = null;
$std->vAliqProd = null;
$elem = $nfe->tagCOFINS($std);

$vtotalCOFINS += $std->vCOFINS; 

}

/**   IMPOSTOS DE IMPORTACAO      **/  
if($_REQUEST['produtos'][$x]["impostos"]["importacao"]["aliquota"]){
	$std->item = $item; //item da NFe
	$std->vBC = $_REQUEST['produtos'][$x]["total"];
	$std->vDespAdu = $_REQUEST['pedido']["despesas_aduaneiras"];
	$std->vII = ($_REQUEST['produtos'][$x]["total"]/100) * $_REQUEST['produtos'][$x]["impostos"]["importacao"]["aliquota"];
	$std->vIOF = ($_REQUEST['produtos'][$x]["impostos"]["importacao"]["iof"])? $_REQUEST['produtos'][$x]["impostos"]["importacao"]["iof"] : null;
	$elem = $nfe->tagII($std);
}
	
/*        IMPOSTO SERVIOS DE QUALQUER NATUREZA                                                                             
$std->item = 1; //item da NFe
$std->vBC = 1000.00;
$std->vAliq = 5.00;
$std->vISSQN = 50.00;
$std->cMunFG = '3518800';
$std->cListServ = '12.23';
$std->vDeducao = null;
$std->vOutro = null;
$std->vDescIncond = null;
$std->vDescCond = null;
$std->vISSRet = null;
$std->indISS = 2;
$std->cServico = '123';
$std->cMun = '3518800';
$std->cPais = '1058';
$std->nProcesso = null;
$std->indIncentivo = 2;

$elem = $nfe->tagISSQN($std);
*/

$valortotal = $valortotal + ($valor * $quantidade);
$descontototal = $descontototal + $desconto;
$pesototal = $pesototal + $peso;
	
// calcular todos os impostos	

/**         DADOS TOTAIS DA NOTA                **/
$impostos = DadosImpostos($dadosempresa["tokenIBPT"], $dadosempresa["cnpj"], $ncm, $dadosempresa["siglaUF"], '0', $nomeproduto, $un, $valor, 0, 3);
$impostototal = $impostototal + $impostos;
/* Tributos incidentes no Produto ou Servio do item da NFe */

$std->vTotTrib = $std->vICMS + $std->vIPI + $std->vPIS + $std->vCOFINS;
$elem = $nfe->tagimposto($std);
$x++;

$vTotalBC += $valorBC;

} // ate aqui o lopp dos produtos

/**          TOTAL DE IMPOSTOS    **/                     
$std->vBC = $vTotalBC;
$std->vICMS = $vTotalICMS;
$std->vICMSDesonv = 0.00;
$std->vFCP = 0.00; //incluso no layout 4.00
$std->vBCST = 0.00;
$std->vST = 0.00;
$std->vFCPST = 0.00; //incluso no layout 4.00
$std->vFCPSTRet = 0.00; //incluso no layout 4.00
$std->vII = 0.00;
$std->vIPI = $vtotalIPI;
$std->vIPIDevol = 0.00; //incluso no layout 4.00
$std->vPIS = $vtotalPIS;
$std->vCOFINS = $vtotalCOFINS;
//$std->vIOF = 0.00;
//$std->vISS = 0.00;
$std->vProd = $vTotalProds;
$std->vSeg = ($_REQUEST['transporte']['seguro']!="")? $_REQUEST['transporte']['seguro'] : 0.00;
$std->vDesc = ($descontototal>0)? $descontototal : null;  // Total do desconto
$std->vFrete = $fretetotal;
$std->vSeg = $segurototal;
$std->vOutro = $outrostotal;
//$std->vTotTrib = $impostototal;
$std->vNF = number_format((($std->vProd + $std->vFrete + $std->vSeg + $std->vOutro) - $descontototal), 2, '.', '');
$std->vTotTrib = $vTotalICMS + $vtotalIPI + $vtotalPIS + $vtotalCOFINS;
$vTotTrib = ($std->vTotTrib > 0)? $std->vTotTrib : $impostototal;

$elem = $nfe->tagICMSTot($std);

/**          FRETE              **/                     
$std->modFrete = RmvString($_REQUEST['pedido']['modalidade_frete']); // Modalidade do frete 
$elem = $nfe->tagtransp($std);

/**     VOLUMES                                         **/ 
if($_REQUEST['transporte']['volume']!="" && $modelonf == 55){
	$std->item = ($_REQUEST['transporte']['numeracao'])? $_REQUEST['transporte']['numeracao'] : 1; //indicativo do numero do volume
	$std->qVol = ($_REQUEST['transporte']['volume'])? $_REQUEST['transporte']['volume'] : 1;
	$std->esp = $_REQUEST['transporte']['especie']; // CAIXA ...
	$std->marca = $_REQUEST['transporte']['marca'];
	$std->nVol = NULL; //$_REQUEST['transporte'][''];
	$std->pesoL = ($_REQUEST['transporte']['peso_bruto'])? $_REQUEST['transporte']['peso_bruto'] : 0.000;
	$std->pesoB = ($_REQUEST['transporte']['peso_liquido'])? $_REQUEST['transporte']['peso_liquido'] : 0.000;
	$elem = $nfe->tagvol($std);
}

/**        LACRES                                                               **/  
if($_REQUEST['transporte']['lacres'] && $modelonf == 55){
	$std->item = ($_REQUEST['transporte']['numeracao'])? $_REQUEST['transporte']['numeracao'] : 1; //indicativo do numero do volume
	$std->nLacre = strval($_REQUEST['transporte']['lacres']);
	$elem = $nfe->taglacres($std);
}


/**       TRANSPORTADORA             **/  
if($_REQUEST['transporte']['cnpj']){
	$std->CNPJ = strval(RmvString($_REQUEST['transporte']['cnpj']));
	$std->CPF = null;
	$std->xNome = strval($_REQUEST['transporte']['razao_social']);
	$std->IE = strval($_REQUEST['transporte']['ie']);
	$std->xEnder = strval($_REQUEST['transporte']['endereco']);
	$std->xMun = strval($_REQUEST['transporte']['cidade']);
	$std->UF = strval($_REQUEST['transporte']['uf']);	
	$elem = $nfe->tagtransporta($std);
		
}elseif($_REQUEST['transporte']['cpf']){ 
	$std->CPF = strval(RmvString($_REQUEST['transporte']['cpf']));
	$std->CNPJ = null;
	$std->xNome = strval($_REQUEST['transporte']['nome_completo']);
	$std->IE = null;
	$std->xEnder = strval($_REQUEST['transporte']['endereco']);
	$std->xMun = strval($_REQUEST['transporte']['cidade']);
	$std->UF = strval($_REQUEST['transporte']['uf']);
	$elem = $nfe->tagtransporta($std);
}

/**     TRANSPORTADORA VEICULO       **/  
if($_REQUEST['transporte']['placa']){
	$std->placa =  strval($_REQUEST['transporte']['placa']);
	$std->UF = strval($_REQUEST['transporte']['uf_veiculo']);
	$std->RNTC = strval($_REQUEST['transporte']['rntc']);
	$elem = $nfe->tagveicTransp($std);
}

/*                                                                                         
$std->vServ = 240.00;
$std->vBCRet = 240.00;
$std->pICMSRet = 1.00;
$std->vICMSRet = 2.40;
$std->CFOP = '5353';
$std->cMunFG = '3518800';
$elem = $nfe->tagveicTransp($std);
 **/

if($_REQUEST['fatura']['numero']  && $modelonf == 55){
	$std->nFat = strval($_REQUEST['fatura']['numero']);
	$std->vOrig = $_REQUEST['fatura']['valor'];
	$std->vDesc = $_REQUEST['fatura']['desconto'];
	$std->vLiq = $_REQUEST['fatura']['valor_liquido'];
	$elem = $nfe->tagfat($std);
}

/**     DUPLICATA       0000000/00A, 0000000/00B e assim por diante.   **/     
if($_REQUEST['duplicatas']['vencimento'] && $modelonf == 55){
	$std->nDup = strval($numero_nf."/01");
	$std->dVenc = strval($_REQUEST['duplicatas']['vencimento']);
	$std->vDup = $_REQUEST['duplicatas']['valor'];
	$elem = $nfe->tagdup($std);
}

/**             PAGAMENTO             **/

/*
FORMAS DE PAGAMENTO 
01 - dinheiro
02 
03- cartao
*/

$pg = 0;
if($_REQUEST['pedido']['forma_pagamento']){

	$std->tpIntegra = 2; // - 1 TEF, 2 POS
	$std->vTroco = $_REQUEST['pedido']['troco']; // TROCO
	$elem = $nfe->tagpag($std);  
	
	if(is_array($_REQUEST['pedido']['forma_pagamento'])){
	
		// loop pagamentos
		foreach($_REQUEST['pedido']['forma_pagamento'] as $pagamento){
			$std->tPag = strval($pagamento);
			$std->vPag = $_REQUEST['pedido']['valor_pagamento'][$pg];
			if($_REQUEST['pedido']['forma_pagamento']==03 || $_REQUEST['pedido']['forma_pagamento']==04){
				$std->CNPJ = strval($_REQUEST['pedido']['cnpj_credenciadora'][$pg]); //Informar o CNPJ da Credenciadora de carto de credito / debito.
				$std->tBand = strval($_REQUEST['pedido']['bandeira'][$pg]);
				$std->cAut = strval($_REQUEST['pedido']['autorizacao'][$pg]);
			}
			if($pagamento=="99"){ 
				//$std->xPag = "Outros"; 
			} else {
				//$std->xPag = null;
			}
			$elem = $nfe->tagdetPag($std); // modelo 4.00
			$pg++;
		}
  
	} else { // No  array
  
		$std->tPag = strval($_REQUEST['pedido']['forma_pagamento']);
		$std->vPag = $_REQUEST['pedido']['valor_pagamento'];
		if($_REQUEST['pedido']['forma_pagamento']==03 || $_REQUEST['pedido']['forma_pagamento']==04){
		$std->CNPJ = strval($_REQUEST['pedido']['cnpj_credenciadora']); //Informar o CNPJ da Credenciadora de carto de crdito / dbito.
		$std->tBand = strval($_REQUEST['pedido']['bandeira']);
		$std->cAut = strval($_REQUEST['pedido']['autorizacao']);
		}
		if($_REQUEST['pedido']['forma_pagamento']=="99"){ 
			//$std->xPag = "Outros"; 
		} else {
			//$std->xPag = null;
		}
		$elem = $nfe->tagdetPag($std); // modelo 4.00		

	}

}

/**           INFORMAES ADICIONAIS                   **/                     
$std->infAdFisco = ($_REQUEST['pedido']['informacoes_fisco'])? $_REQUEST['pedido']['informacoes_fisco'] : "";
//$std->infCpl = 'Valor aproximado de tributos '.toMoney($impostos).' ('.(int)(((($impostos-$valortotal)/$valortotal)*100)+100).'%) - Fonte IBPT | ';
$std->infCpl  = $_REQUEST['pedido']['informacoes_complementares'];
$elem = $nfe->taginfAdic($std);

/*                                                                                            
$std->xCampo = 'email';
$std->xTexto = 'algum@mail.com';
$elem = $nfe->tagobsCont($std);
**/

/*                                                                                            
$std->xCampo = 'Info';
$std->xTexto = 'alguma coisa';
$elem = $nfe->tagobsFisco($std);
*/

/**    EXPORTAO TAG            **/     
if($_REQUEST['exportacao']['uf_embarque']){
$std->UFSaidaPais = strval($_REQUEST['exportacao']['uf_embarque']);
$std->xLocExporta = strval($_REQUEST['exportacao']['local_embarque']);
$std->xLocDespacho = strval($_REQUEST['exportacao']['local_despacho']);
$elem = $nfe->tagexporta($std);
}


/**  TECNICO RESPONSAVEL * */
if($_REQUEST['tecnico']['cnpj']!=""){
$std->CNPJ = strval($_REQUEST['tecnico']['cnpj']); //CNPJ da pessoa jurÃ­dica responsÃ¡vel pelo sistema utilizado na emissÃ£o do documento fiscal eletrÃ´nico
$std->xContato= strval($_REQUEST['tecnico']['contato']); //Nome da pessoa a ser contatada
$std->email = strval($_REQUEST['tecnico']['email']); //E-mail da pessoa jurÃ­dica a ser contatada
$std->fone = strval($_REQUEST['tecnico']['fone']); //Telefone da pessoa jurÃ­dica/fÃ­sica a ser contatada
$std->CSRT = strval($_REQUEST['tecnico']['csrt']); //CÃ³digo de SeguranÃ§a do ResponsÃ¡vel TÃ©cnico
$std->idCSRT = strval($_REQUEST['tecnico']['idcsrt']); //Identificador do CSRT
$elem = $nfe->taginfRespTec($std);
}

/**  Dados do intermediador * */
if($_REQUEST['intermediador']['cnpj']!=""){
$std->CNPJ = strval($_REQUEST['intermediador']['cnpj']); //CNPJ do intermediador: Mercado livre, 
$std->idCadIntTran = strval($_REQUEST['intermediador']['idcadastro']); //Nome da pessoa a ser contatada
$elem = $nfe->tagIntermed($std);
}

$elem = $nfe->taginfNFeSupl($std);

$result = $nfe->montaNFe();

$xml1 = $nfe->getXML();

$chave = $nfe->getChave();

$modelo = $nfe->getModelo();

var_dump($nfe->getErrors()); // debug de errros

// Pasta principal onde vai ficar os XML
$pasta = "./xml/";

header('Content-type: text/json');

if (!empty($error))
{
// reporta o erro para o usurio
$erros = array($error);
echo json_encode(array("error" => "Erro ao emitir nota", "log" => $erros));
die;
	
}
else
{		
	
	
	/*
	*  	VERIFICAR O CERTIFICADO DIGITAL SE ESTÃ CORRETO
	*/
	
	try { 
		
		$tools = new Tools($configJson, Certificate::readPfx($content, $senhacert));
		$tools->model($modelo);
	  
	  } catch (\Exception $e) {
		   
	  echo json_encode(array("error" => "Certificado: ".$e->getMessage()));
	  die;
	  
	  }
	  
	  /*
	  *  	FIM CERTIFICADO
	  */

	/*
	*  	SALVA ENTRADA
	*/
	
	$filename = $pasta."entradas/".$chave.".xml"; // apos assinar salva arquivo
	file_put_contents($filename, trim($xml1)); // salva xml assinado
	chmod($filename, 0775);
	
	/*
	*  	FIM SALVA ENTRADA
	*/
	
		
	/*
	*  	ASSINAR 
	*/	
	try { 
		
	  	$response_assina = $tools->signNFe($xml1);

		$stdCl = new Standardize($response_assina);
		$arr = $stdCl->toArray();

		$filename_assina = $pasta."assinadas/".$chave.".xml"; // apos assinar salva arquivo
		file_put_contents($filename_assina, trim($response_assina)); // salva xml assinado
		chmod($filename_assina, 0775);
	
	} catch (\Exception $e) {
  
		echo json_encode(array("error" => "Assina: ".str_replace("{http://www.portalfiscal.inf.br/nfe}", "", $e->getMessage())));
		die;
	}
	/*
	*  FIM ASSINAR 
	*/	

	// MODO TESTE, VALIDAMOS E RETORNAMOS
	if($_REQUEST["teste"]=="ok"){
		echo json_encode(array("update" => 0, "teste" => "ok", "chave"  => $chave));
		die;
	}


	/*
	*  	ENVIO PARA O SEFAZ
	*/

			
		try {
		
			$xml_assinado = file_get_contents($pasta."assinadas/".$chave.".xml"); 

			$idLote = substr(str_replace(',', '', number_format(microtime(true)*1000000, 0)), 0, 15);
			$modEnvio = 1; // 1 - sincrono (somente 1 nota) / 0 - acyntrocno, nÃ£o recebe a resposta na hora

			$response_envio = $tools->sefazEnviaLote([$xml_assinado], $idLote, $modEnvio);
		
			$stdCl = new Standardize($response_envio);
			$arr_envio = $stdCl->toArray();
			// 103- asincrono
			// 104- sincrono
			if ($arr_envio['cStat'] == 103 || $arr_envio['cStat'] == 104) { // OK ENVIO
					
				$recibo_envio = $arr_envio['infRec']['nRec'];
			
			} else {
				
				echo json_encode(array("error" => "Envio: ".$arr_envio['xMotivo']." (".$arr_envio['cStat'].")"));
				die;
			
			} 	
			
		} catch (\Exception $e) {	
			
			echo json_encode(array("error" => "Envio: ".$e->getMessage()));
			die;
				
		}

		// delay para evitar notas em contingÃªncia
		if($modelonf=="65"){
			sleep(3);
		}else{
			sleep(5);
		}

		$response_protocolo = $tools->sefazConsultaRecibo($recibo_envio, $ambiente);
	
		$stdCl_prot = new Standardize($response_protocolo);
		$std_prot = $stdCl_prot->toArray();

		if($std_prot['protNFe']['infProt']['cStat']==104 || $std_prot['protNFe']['infProt']['cStat']==100){ // tudo ok
			
			try {
				
				$response_protocolo = $tools->sefazConsultaChave($chave, $ambiente); // CONSULTA COM CHAVE
		
			} catch (\Exception $e) {
					
				echo json_encode( array("error" => $e->getMessage()) );	
				die;
			}

			try {

				$resposta_addprot = Complements::toAuthorize($xml_assinado, $response_protocolo); // checa a autorizao
		
			} catch (\Exception $e) {
					
				echo json_encode( array("error" => $e->getMessage()) );	
				die;
			}

			$stdCl2 = new Standardize($resposta_addprot);
			$arr = $stdCl2->toArray();
			
			if($arr['protNFe']['infProt']['cStat']==100){ // AUTORIZADO O USO DA NOTA FISCAL
	
				$filename = $pasta."autorizadas/".$chave.".xml"; // Aps assinar salva arquivo
				file_put_contents($filename, trim($resposta_addprot)); // Salva xml assinado
				chmod($filename, 0775);
		
				$data = array();
				$data['status']  = "aprovado";
				$data['nfe']  = strval($numero_nf);
				$data['serie']  = strval($serie);
				$data['recibo']  = strval($recibo_envio);
				$data['chave']  = strval($chave);
				$data['xml']  = $urlxml."autorizadas/".$chave.".xml";
				$data['danfe']  = $urldanfe."?chave=".$chave;
				$data['log']  = $arr['protNFe']['infProt']['xMotivo'];

				echo json_encode($data);
			
			} else {
	
				echo json_encode(array("error" => "Error de AutorizaÃ§Ã£o: ". $arr['protNFe']['infProt']['xMotivo']." (".$arr['protNFe']['infProt']['cStat'].")"));
				die;
			}


		}else{
						
			echo json_encode(array("error" => "Consuta Sefaz: ".$std_prot['protNFe']['infProt']['xMotivo']." (".$std_prot['protNFe']['infProt']['cStat'].")"));
		}
		/*
		*  FIM PROTOCOLO
		*/
	
} // FIM SEM ERRO