<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);

    $q = "select a.*, concat('BK ',categoria) as categoria, (select icon from produtos where a.codigo = b.categoria order by rand() limit 1) as icon from categorias where situacao = '1' and deletado != '1' order by ordem desc";
    $r = mysqli_query($con, $q);
    
    $p = [];
    while($d = mysqli_fetch_object($r)){
        $p[] = $d;
    }
    
    echo json_encode($p);

    