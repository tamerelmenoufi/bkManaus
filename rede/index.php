<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);


    if($_GET and !$_POST) $_POST = $_GET;

    $dadosLog = print_r($_POST,true);

    if($_POST){


        $dados = json_decode($_POST);

        $operadora_id = $_POST['data']['id'];

        $content = http_build_query(array(

            'id' => $operadora_id,

        ));

        $context = stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'content' => $content,
            )
        ));

        $result = file_get_contents('https://mp.bkmanaus.com.br/ObterPagamento.php', null, $context);

        file_put_contents("logs/retorno_".date("YmdHis").".txt",$dadosLog."\n\n\n".$result);

    }