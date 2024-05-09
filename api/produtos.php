<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);

    $q = "select * from produtos where situacao = '1' and deletado != '1' /*and valor > 0 and icon != ''*/ ".(($_POST['categoria'])?" and categoria = '{$_POST['categoria']}'":false);
    $r = mysqli_query($con, $q);
    
    $p = [];
    while($d = mysqli_fetch_object($r)){
        $p[] = $d;
    }
    
    echo json_encode($p);

    