<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }

    $d1 = rand(1,9);
    $d2 = rand(0,9);
    $d3 = rand(0,9);
    $d4 = rand(0,9);

    $cod = $d1.$d2.$d3.$d4;

    $result = EnviarWapp($_POST['telefone'],"BK Manaus informe: Seu código de acesso é *{$cod}*");

    echo "{\"status\":\"success\", \"codigo\":\"{$cod}\"}";