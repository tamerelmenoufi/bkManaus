<?php
    session_start();
    include("/appinc/connect.php");
    include("fn.php");
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/vendor/mottu/classes.php");
    include("AppWapp.php");
    $con = AppConnect('bk_manaus');
    $conApi = AppConnect('information_schema');
    $md5 = md5(date("YmdHis"));

    $urlPainel = 'https://painel.bkmanaus.com.br/';

    if($_POST['historico']){
        $pagina = str_replace("/bkManaus/app/", false, $_SERVER["PHP_SELF"]);
        $destino = $_POST['historico'];
        $i = ((count($_SESSION['historico']))?(count($_SESSION['historico']) -1):0);
        if($_SESSION['historico'][$i]['local'] != $pagina){
            $j = (($_SESSION['historico'][$i]['local'])?($i+1):0);
            $_SESSION['historico'][$j]['local'] = $pagina;
            $_SESSION['historico'][$j]['destino'] = $_POST['historico'];
            unset($_POST['historico']);
            $_SESSION['historico'][$j]['dados'] = json_encode($_POST);
        }else{
            unset($_POST['historico']);
        }
    }