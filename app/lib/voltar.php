<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    $i = ((count($_SESSION['historico']))?(count($_SESSION['historico']) -2):0);
    if(count($_SESSION['historico'])) { unset($_SESSION['historico'][($i-1)]); }else{ unset($_SESSION['historico'][0]); }
    
    $r['pg'] = $_SESSION['historico'][$i]['local'];
    $r['tg'] = $_SESSION['historico'][$i]['destino'];

    echo json_encode($r);

?>