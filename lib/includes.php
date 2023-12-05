<?php
    session_start();
    include("/appinc/connect.php");
    include("fn.php");
    $con = AppConnect('bk_manaus');
    $conApi = AppConnect('information_schema');
    $md5 = md5(date("YmdHis"));

    $urlPainel = 'https://painel.bkmanaus.com.br/';

    if($_POST['historico']){
        $pagina = $_SERVER["PHP_SELF"];
        $destino = $_POST['historico'];
        $i = ((count($_SESSION['historico']))?(count($_SESSION['historico']) -1):0);
        if($_SESSION['historico'][$i] != $pagina){
            $_SESSION['historico'][] = $pagina;
        } 
        unset($_POST['historico']);
    }