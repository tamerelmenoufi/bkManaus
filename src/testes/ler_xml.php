<?php


    $xml = simplexml_load_file('teste.xml') or die("Erro: Não foi possível carregar o arquivo XML.");


    echo "<pre>";

    print_r($xml);

    echo "</pre>";