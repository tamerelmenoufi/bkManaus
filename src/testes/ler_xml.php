<?php


    $xml = simplexml_load_file('teste'.$_GET['n'].'.xml') or die("Erro: Não foi possível carregar o arquivo XML.");

    $json = json_encode($xml);




    echo "<pre>";

    var_dump($xml);

    echo "</pre>";

    echo "produtos:".($json->NFe->infNFe->det) ;


    foreach($json->NFe->infNFe->det as $i => $val){


        echo $val->prod->xProd."<br>";

    }