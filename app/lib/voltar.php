<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    $i = ((count($_SESSION['historico']))?(count($_SESSION['historico']) -2):0);
    unset($_SESSION['historico'][($i-1)]);
    $r['pg'] = $_SESSION['historico'][$i]['local'];
    $r['tg'] = $_SESSION['historico'][$i]['destino'];

    echo json_encode($r);

?>