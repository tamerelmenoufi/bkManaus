<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    $i = ((count($_SESSION['historico']))?(count($_SESSION['historico']) - 1):0);
    
    $r['pg'] = $_SESSION['historico'][$i]['local'];
    $r['tg'] = $_SESSION['historico'][$i]['destino'];
    $r['dt'] = $_SESSION['historico'][$i]['dados'];
    $r['i'] = $i;

    echo json_encode($r);

?>