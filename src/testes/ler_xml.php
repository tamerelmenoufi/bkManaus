<?php


    $xml = simplexml_load_file('teste'.$_GET['n'].'.xml') or die("Erro: Não foi possível carregar o arquivo XML.");

    $json = json_encode($xml);




    echo "<pre>";

    print_r($json);

    echo "</pre>";