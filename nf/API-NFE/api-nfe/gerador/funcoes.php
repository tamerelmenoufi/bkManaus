<?php

function is_connected()
{
    $connected = @fsockopen("www.example.com", 80); 
                                        //website, port  (try 80 or 443)
    if ($connected){
        $is_conn = true; //action when connected
        fclose($connected);
    }else{
        $is_conn = false; //action in connection failure
    }
    return $is_conn;

}



function rip_tags($string) {
   
    // ----- remove HTML TAGs -----
    $string = preg_replace ('/<[^>]*>/', ' ', $string);
   
    // ----- remove control characters -----
    $string = str_replace("\r", '', $string);    // --- replace with empty space
    $string = str_replace("\n", ' ', $string);   // --- replace with space
    $string = str_replace("\t", ' ', $string);   // --- replace with space
   
    // ----- remove multiple spaces -----
    $string = trim(preg_replace('/ {2,}/', ' ', $string));
   
    return $string;

}

function RmvString($string, $tipo = "") {
	
	if($tipo==1){
		// remover espacos
		$string2 = trim(str_replace(" ", "", $string));
	}elseif($tipo==2){
		// remover pontos
		$string2 = trim(str_replace(".", "", $string));
	}else{
	 $string2 = $string;
	}
	
    $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º',"'");
    $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C','','','','','','','','','','','','','','','','','','','','','','',"");
    return str_replace($what, $by, $string2);
}

function unhtmlentities($string) 
{
    // replace numeric entities
    $string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
    $string = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $string);
    // replace literal entities
    $trans_tbl = get_html_translation_table(HTML_ENTITIES);
    $trans_tbl = array_flip($trans_tbl);
    return strtr($string, $trans_tbl);
}

function DadosImpostos($token, $cnpj, $ncm, $uf, $ex, $descricao, $un, $valor, $gtin, $dadostipo){
	
if($token=="" || $cnpj=="" || $ncm=="" || $uf=="" || $descricao =="" || $un =="" || $valor == ""){
    return 0;
}

// $dadostipo - 1 impostos
$url = "https://apidoni.ibpt.org.br/api/v1/produtos?token=".$token."&cnpj=".$cnpj."&codigo=".$ncm."&uf=".$uf."&ex=".$ex."&descricao=".urlencode($descricao)."&unidadeMedida=".$un."&valor=".$valor."&gtin=".urlencode($gtin);
   
$json_file = file_get_contents($url);
$json_array = json_decode($json_file , true);

if($dadostipo==1){	
$ValorTributoEstadual = $json_array['ValorTributoEstadual'];
$ValorTributoImportado = $json_array['ValorTributoImportado'];
$ValorTributoMunicipal = $json_array['ValorTributoMunicipal'];
$ValorTributoNacional = $json_array['ValorTributoNacional'];
return $ValorTributoEstadual + $ValorTributoMunicipal + $ValorTributoNacional;
}

if($dadostipo==2){	
$ValorTributoEstadual = $json_array['ValorTributoEstadual'];
return $ValorTributoEstadual;
}
	
}

function toMoney($val,$symbol='R$',$r=2)
{
	
	 return 'R$ '.number_format($val, 2, ',', '.');
}

function tonormal($val,$r=2)
{
	
	 return number_format($val, 2, '.', ',');
}

function tonormalReal($val,$r=2)
{
	
	 return number_format($val, 2, ',', '.');
}


function GetPagamento($id){
	
if($id == 01){ return "Dinheiro"; }
if($id == 02){ return "Cheque"; }
if($id == 03){ return "Cartão de Crédito"; }
if($id == 04){ return "Cartão de Débito"; }
if($id == 05){ return "Crédito Loja"; }
if($id == 10){ return "Vale Alimentação"; }
if($id == 11){ return "Vale Refeição"; }
if($id == 12){ return "Vale Presente"; }
if($id == 13){ return "Vale Combustível"; }
if($id == 99){ return "Outros"; }

}


function CalcularPorcent($valor, $porcentagem){

    return (($valor/100) * $porcentagem);

}
