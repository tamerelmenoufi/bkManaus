<?php


    $xml = simplexml_load_file('teste'.$_GET['n'].'.xml') or die("Erro: Não foi possível carregar o arquivo XML.");


    echo "<pre>";

    print_r($xml);

    echo "</pre>";