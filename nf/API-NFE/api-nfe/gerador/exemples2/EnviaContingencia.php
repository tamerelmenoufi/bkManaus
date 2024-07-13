<?

use NFePHP\NFe\Convert;
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;

try {
    //esses são os dados quando da entrada em contingência que devem ser mantidos
    //em arquivo ou em base de dados para uso sempre quando a classe Tools for instanciada
    //é uma string json
    //NOTA: esse json pode ser criado com Contingency::class
    $contingency = '{
        "motive":"SEFAZ fora do AR",
        "timestamp":1484747583,
        "type":"SVCAN",
        "tpEmis":6
    }';

    //instancia a classe tools
    $tools = new Tools($configJson, Certificate::readPfx($content, 'senha'));

    //indica que estamos em modo de contingência, se nada for passado estaremos em modo normal
    $tools->contingency->load($contingency);

    //envia o xml para pedir autorização ao SEFAZ
    //a variável $xml é uma string contêm o XML da NFe que será enviada 
    $idLote = str_pad($nfemit->id, 15, '0', STR_PAD_LEFT);
    $indSinc = 0; //usar método assincrono preferencial !! Evite usar não funciona em todos os estados
    $compactar = false; //Evite usar não funciona em todos os estados
    $retxmls = []; //nessa variável serão retornados os XML já ajustados para mode de contingência
    $resp = $this->tools->sefazEnviaLote([$xml], $idLote, $indSinc, $compactar, $retxmls);
    //os xml contidos no array da variável $retxmls[] e devem ser gravados em substituição aos documentos originais
    
    //transformas a resposta da SEFAZ em um stdClass
    $st = new Standardize();
    $std = $st->toStd($resp);
    if ($std->cStat != 103) {
        //erro registrar e voltar
        return "[$std->cStat] $std->xMotivo";
    }
    $recibo = $std->infRec->nRec;
    //esse recibo deve ser guardado para a próxima operação que é a consulta do recibo
    //NOTA: se o xml foi enviado em contingência a consulta do recibo também deverá ser feita nesse mesmo modo.

    header('Content-type: text/xml; charset=UTF-8');
    echo $resp;
} catch (\Exception $e) {
    echo str_replace("\n", "<br/>", $e->getMessage());
}



?>