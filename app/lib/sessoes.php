<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    print_r($_SESSION['historico']);

    $i = ((count($_SESSION['historico']))?(count($_SESSION['historico']) -1):0);
    $r['pg'] = $_SESSION['historico'][$i]['local'];
    $r['tg'] = $_SESSION['historico'][$i]['destino'];

    echo json_encode($r);

?>