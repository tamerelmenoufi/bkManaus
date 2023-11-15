<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);

    $q = "select a.*, (select icon from produtos where a.codigo = categoria and icon != '' order by rand() limit 1) as icon from categorias a where a.situacao = '1' and a.deletado != '1' order by a.ordem desc";
    $r = mysqli_query($con, $q);
    
    $p = [];
    while($d = mysqli_fetch_object($r)){
        $p[] = $d;
    }
    
    echo json_encode($p);

    